<?php

use App\Models\User;
use App\Enums\UserRole;

test('user have roles', function () {
    $user = User::factory()->create();
    expect($user->role)->toBe(UserRole::USER);
    expect($user->is_admin)->toBeFalse();
    expect($user->is_user)->toBeTrue();
    expect($user->is_guest)->toBeFalse();

    $user = User::factory()->admin()->create();
    expect($user->role)->toBe(UserRole::ADMIN);
    expect($user->is_admin)->toBeTrue();
    expect($user->is_user)->toBeFalse();
    expect($user->is_guest)->toBeFalse();

    $user = User::factory()->guest()->create();
    expect($user->role)->toBe(UserRole::GUEST);
    expect($user->is_admin)->toBeFalse();
    expect($user->is_user)->toBeFalse();
    expect($user->is_guest)->toBeTrue();
});
