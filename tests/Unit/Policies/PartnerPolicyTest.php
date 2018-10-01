<?php

namespace Tests\Unit\Policies;

use App\Partner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PartnerPolicyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_partner()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Partner));
    }

    /** @test */
    public function user_can_view_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create();
        $this->assertTrue($user->can('view', $partner));
    }

    /** @test */
    public function user_can_update_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create();
        $this->assertTrue($user->can('update', $partner));
    }

    /** @test */
    public function user_can_delete_partner()
    {
        $user = $this->createUser();
        $partner = factory(Partner::class)->create();
        $this->assertTrue($user->can('delete', $partner));
    }
}
