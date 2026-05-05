<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Trait per Modelli che Appartengono a un Tenant
 * 
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void addGlobalScope(string $identifier, \Closure $scope)
 * @method static void creating(\Closure $callback)
 */
trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        // 1. Il Filtro
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $builder->where('tenant_id', $user->tenant_id);
            }
        });

        // 2. L'Inserimento
        static::creating(function ($model) {
            if (Auth::check() && empty($model->tenant_id)) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $model->tenant_id = $user->tenant_id;
            }
        });
    }
}