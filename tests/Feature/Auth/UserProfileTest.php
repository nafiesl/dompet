<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_visit_their_profile_page()
    {
        $user = $this->loginAsUser();
        $this->visitRoute('profile.show');
        $this->see($user->name);
        $this->see($user->email);
    }
}
