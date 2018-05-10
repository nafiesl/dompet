<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_get_their_profile_from_api()
    {
        $user = $this->createUser(['password' => bcrypt('testing')]);

        $this->getJson(route('api.user'), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeJson([
            'name'  => $user->name,
            'email' => $user->email,
        ]);

        $this->dontSeeJson([
            'api_token' => $user->api_token,
        ]);
    }
}
