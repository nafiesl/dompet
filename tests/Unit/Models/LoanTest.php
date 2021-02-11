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

    /** @test */
    public function a_loan_has_type_attribute()
    {
        $loan = factory(Loan::class)->make(['type_id' => Loan::TYPE_DEBT]);
        $this->assertEquals(__('loan.types.debt'), $loan->type);

        $loan->type_id = Loan::TYPE_RECEIVABLE;
        $this->assertEquals(__('loan.types.receivable'), $loan->type);
    }

    /** @test */
    public function a_loan_has_amount_string_attribute()
    {
        $amount = 1099.00;

        $loan = factory(Loan::class)->make([
            'amount' => $amount,
        ]);
        $this->assertEquals(number_format($amount, 2), $loan->amount_string);
    }
}
