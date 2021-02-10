<?php

namespace Tests\Unit\Models;

use App\Loan;
use App\Partner;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_loan_has_belongs_to_creator_relation()
    {
        $loan = factory(Loan::class)->make();

        $this->assertInstanceOf(User::class, $loan->creator);
        $this->assertEquals($loan->creator_id, $loan->creator->id);
    }

    /** @test */
    public function a_loan_has_belongs_to_partner_relation()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->make(['partner_id' => $partner->id]);

        $this->assertInstanceOf(Partner::class, $loan->partner);
        $this->assertEquals($loan->partner_id, $loan->partner->id);
    }
}
