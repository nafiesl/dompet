<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_user_can_register()
    {
        $this->postJson(route('api.register'), [
            'name'                  => 'User Name',
            'email'                 => 'user@example.net',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->seeStatusCode(200);

        $this->seeInDatabase('users', [
            'name'  => 'User Name',
            'email' => 'user@example.net',
        ]);
    }
}
