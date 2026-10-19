<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function edit()
    {
        return response()->json([
            'message' => 'Account settings.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        $request->user()->update([
            'password' => $data['password'],
        ]);

        return response()->json([
            'message' => 'Password changed successfully.',
        ]);
    }
}