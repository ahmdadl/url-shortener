<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class AutoGuestLogin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            // Check if session already has a guest user ID
            if (!$request->session()->has('guest_user_id')) {
                $guest = User::create([
                    'name' => 'Guest-' . Str::random(6),
                    'email' => Str::uuid() . '@guest.local',
                    'password' => bcrypt(Str::random(16)), // random unusable password
                    'role' => UserRole::GUEST,
                ]);

                $request->session()->put('guest_user_id', $guest->id);

                Auth::login($guest);
            } else {
                $guest = User::find($request->session()->get('guest_user_id'));

                if ($guest) {
                    Auth::login($guest);
                }
            }
        }

        return $next($request);
    }
}
