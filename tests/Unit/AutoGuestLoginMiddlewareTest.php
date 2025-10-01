<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    Session::start(); // Needed for CSRF/session usage
});

it('creates a guest user if not logged in', function () {
    get('/')
        ->assertStatus(200);

    $user = Auth::user();

    expect($user)->not()->toBeNull()
        ->and($user->is_guest)->toBeTrue();
});

it('reuses the same guest user on subsequent requests', function () {
    get('/'); // first request creates guest
    $guestId = Auth::id();

    get('/'); // second request should reuse
    $sameGuestId = Auth::id();

    expect($guestId)->toBe($sameGuestId);
});

it('does nothing if a real user is logged in', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/')
        ->assertStatus(200);

    $currentUser = Auth::user();

    expect($currentUser->id)->toBe($user->id)
        ->and($currentUser->is_guest)->toBeFalse();
});
