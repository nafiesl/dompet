<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_login_and_logout()
    {
        $user = factory(User::class)->create(['name' => 'Nama Member', 'email' => 'email@mail.com']);

        $this->visit(route('login'));

        $this->submitForm(trans('auth.login'), [
            'email'    => 'email@mail.com',
            'password' => 'secret',
        ]);

        $this->see(trans('auth.welcome', ['name' => $user->name]));
        $this->seePageIs(route('home'));
        $this->seeIsAuthenticated();

        $this->press(trans('auth.logout'));

        $this->seePageIs(route('welcome'));
    }

    /** @test */
    public function member_invalid_login()
    {
        $this->visit(route('login'));

        $this->submitForm(trans('auth.login'), [
            'email'    => 'email@mail.com',
            'password' => 'member',
        ]);

        $this->seePageIs(route('login'));
        $this->dontSeeIsAuthenticated();
    }

    /** @test */
    public function user_cannot_login_if_they_are_in_inactive_status()
    {
        $user = factory(User::class)->create([
            'email'     => 'email@mail.com',
            'is_active' => 0,
        ]);

        $this->visit(route('login'));

        $this->submitForm(trans('auth.login'), [
            'email'    => 'email@mail.com',
            'password' => 'secret',
        ]);

        $this->see(trans('auth.user_inactive'));
        $this->seePageIs(route('login'));
        $this->dontSeeIsAuthenticated();
    }

    /** @test */
    public function unauthenticated_users_are_redirects_to_login_page()
    {
        $this->visit(route('home'));
        $this->seePageIs(route('login'));
    }
}
