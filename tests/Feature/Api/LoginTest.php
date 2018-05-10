<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_login_via_api()
    {
        $user = $this->createUser(['password' => bcrypt('testing')]);
        $this->postJson(route('api.login'), [
            'email'    => $user->email,
            'password' => 'testing',
        ]);

        $this->seeJson([
            'name'      => $user->name,
            'email'     => $user->email,
            'api_token' => $user->api_token,
        ]);
    }

    /** @test */
    public function invalid_user_api_login_returns_proper_message()
    {
        $this->postJson(route('api.login'), [
            'email'    => '',
            'password' => '',
        ]);

        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'email'    => [__('validation.required')],
                'password' => [__('validation.required')],
            ],
        ]);

        $this->postJson(route('api.login'), [
            'email'    => 'member@example.net',
            'password' => '',
        ]);

        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors'  => ['password' => [__('validation.required')]],
        ]);

        $this->postJson(route('api.login'), [
            'email'    => 'member@example.net',
            'password' => 'testing',
        ]);

        $this->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors'  => ['email' => [__('auth.failed')]],
        ]);
    }
}
