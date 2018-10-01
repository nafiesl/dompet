<?php

namespace Tests\Unit\Models;

use App\User;
use App\Partner;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PartnerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_partner_has_belongs_to_creator_relation()
    {
        $partner = factory(Partner::class)->make();

        $this->assertInstanceOf(User::class, $partner->creator);
        $this->assertEquals($partner->creator_id, $partner->creator->id);
    }
}
