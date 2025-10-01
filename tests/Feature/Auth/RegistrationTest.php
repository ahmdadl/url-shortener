<?php

declare(strict_types=1);

use App\Enums\UserRole;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('registration screen can be rendered', function () {
    $response = get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    assertAuthenticated();
    $user = auth()->user();
    expect($user->role)->toBe(UserRole::USER);
    $response->assertRedirect(route('dashboard', absolute: false));
});
