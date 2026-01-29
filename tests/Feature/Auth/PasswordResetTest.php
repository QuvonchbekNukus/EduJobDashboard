<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_routes_are_disabled(): void
    {
        $this->get('/forgot-password')->assertStatus(404);
        $this->post('/forgot-password')->assertStatus(404);
        $this->get('/reset-password/any-token')->assertStatus(404);
        $this->post('/reset-password')->assertStatus(404);
    }
}
