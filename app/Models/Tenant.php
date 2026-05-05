<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'slug', 'vat_number'];

    // Relazioni
    public function users() {
        return $this->hasMany(User::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }
}