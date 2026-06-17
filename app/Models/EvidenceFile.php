<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenceFile extends Model
{
    protected $fillable = [
        'vulnerability_id', 'uploaded_by', 'original_name', 'stored_path', 'mime_type', 'size',
    ];

    public function vulnerability()
    {
        return $this->belongsTo(Vulnerability::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
