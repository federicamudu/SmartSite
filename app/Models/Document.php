<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant; // Il nostro Trait!

class Document extends Model
{
    use HasUuids, BelongsToTenant; // Magia attivata.

    protected $fillable = ['code', 'title', 'description', 'created_by'];

    // Relazioni
    public function revisions() {
        return $this->hasMany(DocumentRevision::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}