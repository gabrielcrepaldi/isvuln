<?php

namespace App\Http\Controllers;

use App\Models\EvidenceFile;
use App\Models\Vulnerability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvidenceFileController extends Controller
{
    public function store(Request $request, Vulnerability $vulnerability)
    {
        $this->authorize('update', $vulnerability);

        // 🚩 VULN POINT #2: only validating that a file exists, nothing else
        $request->validate([
            'evidence' => 'required|file',
        ]);

        $file = $request->file('evidence');

        // 🚩 VULN POINT #1: using the user-supplied original filename directly
        $originalName = $file->getClientOriginalName();

        // 🚩 VULN POINT #3: storing in a public, web-accessible directory
        $destination = public_path('uploads/evidence');
        $file->move($destination, $originalName);

        EvidenceFile::create([
            'vulnerability_id' => $vulnerability->id,
            'uploaded_by'      => Auth::id(),
            'original_name'    => $originalName,
            'stored_path'      => 'uploads/evidence/' . $originalName,
            // 🚩 VULN POINT #4: trusting the client-reported MIME type
            'mime_type'        => $file->getClientMimeType(),
            'size'             => $file->getSize(),
        ]);

        return back()->with('success', 'Evidence uploaded.');
    }

    public function destroy(EvidenceFile $evidenceFile)
    {
        $this->authorize('update', $evidenceFile->vulnerability);

        // 🚩 VULN POINT #5: deleting based on DB path without verifying it's inside the intended dir
        $fullPath = public_path($evidenceFile->stored_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $evidenceFile->delete();

        return back()->with('success', 'Evidence deleted.');
    }
}
