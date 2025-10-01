<?php

namespace Tests;

use App\Enums\UserRole;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function assertGuest($guard = null)
    {
        if (!auth()->user()) {
            return parent::assertGuest($guard);
        }

        $this->assertEquals(auth()->user()?->role, UserRole::GUEST);
    }
}
