<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class LogoutUser
{
    public function execute()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}