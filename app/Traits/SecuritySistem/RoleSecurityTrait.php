<?php

namespace App\Traits\SecuritySistem;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait RoleSecurityTrait
{
    public static function scopeNoOwnerRole(Builder $query): void
    {
        $auth = User::find(Auth::id());
        if (! $auth->hasRole('super usuario')) {
            $query->whereNot('name', 'super usuario');
        }
    }
}
