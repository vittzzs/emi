<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait SecuritySystemTrait
{
    public function isOwnerSystem(): bool
    {
        return $this->id == config('owner-system.user.id') || $this->hasRole('super usuario');
    }

    public static function getSuperUser()
    {
        return self::find(config('owner-system.user.id'));
    }

    public static function scopeNoOwnerUser(Builder $query): void
    {
        $superUserId = self::getSuperUser()->id;

        if (Auth::id() !== $superUserId) {
            $query->whereNot('id', $superUserId);
        }
    }
}
