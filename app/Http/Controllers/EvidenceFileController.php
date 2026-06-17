<?php

namespace App\Http\Controllers;

use App\Models\EvidenceFile;
use App\Models\Vulnerability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvidenceFileController extends Controller
{
    /**
     * Server-detected MIME types that are permitted, keyed alongside the
     * extension whitelist enforced by the validation rule below.
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'text/plain',
        'text/csv',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip',
        'application/vnd.tcpdump.pcap',
        'application/octet-stream', // some systems report pcap as this
    ];

    public function store(Request $request, Vulnerability $vulnerability)
    {
        $this->authorize('update', $vulnerability);

        // Fix 3: restrict by extension (client) AND server-detected MIME.
        $request->validate([
            'evidence' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,pdf,txt,log,csv,docx,zip,pcap',
        ]);

        $file = $request->file('evidence');

        // Server-detected MIME — never trust getClientMimeType().
        $mimeType = $file->getMimeType();

        if (! in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            return back()->withErrors(['evidence' => 'File type not permitted.']);
        }

        // Fix 2: never use the client filename for storage; randomize it.
        $extension    = strtolower($file->getClientOriginalExtension());
        $originalName = $file->getClientOriginalName();
        $newFilename  = Str::uuid() . '.' . $extension;

        // Capture metadata before storing.
        $size = $file->getSize();

        // Fix 1: store outside the web root via the local disk (storage/app).
        Storage::disk('local')->putFileAs('evidence', $file, $newFilename);

        EvidenceFile::create([
            'vulnerability_id' => $vulnerability->id,
            'uploaded_by'      => Auth::id(),
            'original_name'    => $originalName,   // display only
            'stored_path'      => 'evidence/' . $newFilename,
            'mime_type'        => $mimeType,
            'size'             => $size,
        ]);

        return back()->with('success', 'Evidence uploaded.');
    }

    /**
     * Fix 4: serve files only through this authenticated, authorized route.
     */
    public function download(EvidenceFile $evidenceFile)
    {
        $this->authorize('view', $evidenceFile->vulnerability);

        // basename() strips any path-traversal attempt from the stored path.
        $path = 'evidence/' . basename($evidenceFile->stored_path);

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $path,
            $evidenceFile->original_name
        );
    }

    public function destroy(EvidenceFile $evidenceFile)
    {
        $this->authorize('update', $evidenceFile->vulnerability);

        // Fix 6: delete via Storage facade; basename() blocks traversal.
        Storage::disk('local')->delete('evidence/' . basename($evidenceFile->stored_path));

        $evidenceFile->delete();

        return back()->with('success', 'Evidence deleted.');
    }
}
