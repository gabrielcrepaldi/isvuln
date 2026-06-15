<?php

namespace App\Http\Controllers;

use App\Models\Vulnerability;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class VirusTotalController extends Controller
{
    public function scan(Vulnerability $vulnerability): JsonResponse
    {
        $this->authorize('view', $vulnerability);

        $target = trim($vulnerability->target ?? '');

        if ($target === '') {
            return response()->json(['error' => __('vuln.vt_no_target')], 422);
        }

        $apiKey = config('services.virustotal.key');

        if (empty($apiKey)) {
            return response()->json(['error' => __('vuln.vt_no_key')], 500);
        }

        // VirusTotal URL identifier: base64url-encode the URL, strip "=" padding.
        $urlId = rtrim(strtr(base64_encode($target), '+/', '-_'), '=');

        $response = Http::timeout(15)
            ->withHeaders(['x-apikey' => $apiKey])
            ->get("https://www.virustotal.com/api/v3/urls/{$urlId}");

        // 404 means the URL has never been submitted to VirusTotal.
        if ($response->status() === 404) {
            return response()->json(['error' => __('vuln.vt_not_analyzed')], 404);
        }

        if (!$response->successful()) {
            return response()->json(['error' => __('vuln.vt_api_failed')], 502);
        }

        $attributes = $response->json('data.attributes', []);
        $stats = $attributes['last_analysis_stats'] ?? [];

        $malicious  = (int) ($stats['malicious'] ?? 0);
        $suspicious = (int) ($stats['suspicious'] ?? 0);
        $harmless   = (int) ($stats['harmless'] ?? 0);
        $undetected = (int) ($stats['undetected'] ?? 0);
        $total      = $malicious + $suspicious + $harmless + $undetected;

        $verdict = $this->verdict($malicious, $suspicious);

        $lastAnalysisDate = $attributes['last_analysis_date'] ?? null;

        return response()->json([
            'verdict'            => $verdict,
            'malicious'          => $malicious,
            'suspicious'         => $suspicious,
            'harmless'           => $harmless,
            'undetected'         => $undetected,
            'total'              => $total,
            'reputation'         => (int) ($attributes['reputation'] ?? 0),
            'last_analysis_date' => $lastAnalysisDate
                ? date('Y-m-d H:i', (int) $lastAnalysisDate)
                : null,
        ]);
    }

    private function verdict(int $malicious, int $suspicious): string
    {
        if ($malicious >= 3) {
            return 'malicious';
        }

        if ($malicious >= 1 || $suspicious >= 3) {
            return 'suspicious';
        }

        return 'clean';
    }
}
