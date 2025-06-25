<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class AuthService extends Service
{
    public function getAuthUser()
    {
        return Auth::user();
    }

    public function autoLogin($user)
    {
        Auth::login($user);
    }

    public function IsSuperUser()
    {
        $user = $this->getAuthUser()->id;
        $user = User::find($user);

        return $user && $user->isOwnerSystem();
    }
}
