<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DocumentRevision extends Model
{
    use HasUuids;

    protected $fillable = [
        'document_id', 'version_number', 'file_path', 
        'status', 'comment', 'uploaded_by', 'approved_at', 'rejection_reason'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relazioni
    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function uploader() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}