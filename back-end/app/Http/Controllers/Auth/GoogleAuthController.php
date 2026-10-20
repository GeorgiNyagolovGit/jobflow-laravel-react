<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $this->ensureConfigured();

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $this->ensureConfigured();
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();

            if (! $email) {
                return $this->redirectToFrontend('login?error=google_email_missing');
            }

            $user = User::query()
                ->where('google_id', $googleUser->getId())
                ->orWhere('email', $email)
                ->first();

            if (! $user) {
                $user = User::query()->create([
                    'name' => $googleUser->getName() ?: Str::before($email, '@'),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(64)),
                    'email_verified_at' => now(),
                ]);

                $candidateRole = Role::query()->where('name', 'candidate')->first();

                if ($candidateRole) {
                    $user->roles()->syncWithoutDetaching([$candidateRole->id]);
                }
            } elseif (! $user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            if (! $user->is_active) {
                return $this->redirectToFrontend('login?error=account_disabled');
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return $this->redirectToFrontend('auth/google/callback');
        } catch (Throwable) {
            return $this->redirectToFrontend('login?error=google_auth_failed');
        }
    }

    private function redirectToFrontend(string $path): RedirectResponse
    {
        return redirect(rtrim((string) config('app.frontend_url'), '/').'/'.$path);
    }

    private function ensureConfigured(): void
    {
        abort_unless(
            config('services.google.client_id')
                && config('services.google.client_secret')
                && config('services.google.redirect'),
            503,
            'Google sign-in is not configured.'
        );
    }
}
