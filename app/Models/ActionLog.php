<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

class ActionLog extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = ['tenant_id', 'user_id', 'action', 'description'];

    // Un log appartiene a un utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}