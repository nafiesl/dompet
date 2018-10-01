<?php

namespace Tests\Unit\Models;

use App\User;
use App\Partner;
use Tests\TestCase;
use App\Transaction;
use Illuminate\Database\Eloquent\Collection;
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

    /** @test */
    public function a_partner_has_for_user_scope()
    {
        $partnerOwner = $this->loginAsUser();
        $partner = factory(Partner::class)->create([
            'creator_id' => $partnerOwner->id,
        ]);
        $othersPartner = factory(Partner::class)->create();

        $this->assertCount(1, Partner::all());
    }

    /** @test */
    public function a_partner_has_many_transactions_relation()
    {
        $partnerOwner = $this->loginAsUser();
        $partner = factory(Partner::class)->create([
            'creator_id' => $partnerOwner->id,
        ]);
        $transaction = factory(Transaction::class)->create([
            'partner_id' => $partner->id,
            'creator_id' => $partnerOwner->id,
        ]);

        $this->assertInstanceOf(Collection::class, $partner->transactions);
        $this->assertInstanceOf(Transaction::class, $partner->transactions->first());
    }
}
