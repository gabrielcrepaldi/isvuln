<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NvdController extends Controller
{
    public function lookup(Request $request): JsonResponse
    {
        $cveId = strtoupper(trim($request->query('cve_id', '')));

        if (!preg_match('/^CVE-\d{4}-\d{4,}$/', $cveId)) {
            return response()->json(['error' => __('vuln.nvd_bad_format')], 422);
        }

        $response = Http::timeout(10)
            ->get('https://services.nvd.nist.gov/rest/json/cves/2.0', [
                'cveId' => $cveId,
            ]);

        if (!$response->successful()) {
            return response()->json(['error' => __('vuln.nvd_api_failed')], 502);
        }

        $data = $response->json();
        $vulnerabilities = $data['vulnerabilities'] ?? [];

        if (empty($vulnerabilities)) {
            return response()->json(['error' => __('vuln.nvd_not_found')], 404);
        }

        $cve = $vulnerabilities[0]['cve'];

        // Extract English description
        $description = '';
        foreach ($cve['descriptions'] ?? [] as $desc) {
            if ($desc['lang'] === 'en') {
                $description = $desc['value'];
                break;
            }
        }

        // Extract CVSS score — prefer CVSSv3.1, fall back to v3.0, then v2
        $cvssScore = null;
        $cvssVersion = null;
        $metrics = $cve['metrics'] ?? [];

        if (!empty($metrics['cvssMetricV31'])) {
            $cvssScore = $metrics['cvssMetricV31'][0]['cvssData']['baseScore'];
            $cvssVersion = '3.1';
        } elseif (!empty($metrics['cvssMetricV30'])) {
            $cvssScore = $metrics['cvssMetricV30'][0]['cvssData']['baseScore'];
            $cvssVersion = '3.0';
        } elseif (!empty($metrics['cvssMetricV2'])) {
            $cvssScore = $metrics['cvssMetricV2'][0]['cvssData']['baseScore'];
            $cvssVersion = '2.0';
        }

        $severity = $this->scoreToSeverity($cvssScore);

        return response()->json([
            'cve_id'       => $cveId,
            'description'  => $description,
            'severity'     => $severity,
            'cvss_score'   => $cvssScore,
            'cvss_version' => $cvssVersion,
        ]);
    }

    private function scoreToSeverity(?float $score): string
    {
        if ($score === null) return 'info';
        if ($score >= 9.0)   return 'critical';
        if ($score >= 7.0)   return 'high';
        if ($score >= 4.0)   return 'medium';
        if ($score > 0.0)    return 'low';
        return 'info';
    }
}
