<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
class EvidenceFile extends Model
{
	use Auditable;
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
