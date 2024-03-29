<?php

namespace Tests\Unit\Models;

use App\Category;
use App\Loan;
use App\Partner;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_transaction_has_belongs_to_creator_relation()
    {
        $transaction = factory(Transaction::class)->make();

        $this->assertInstanceOf(User::class, $transaction->creator);
        $this->assertEquals($transaction->creator_id, $transaction->creator->id);
    }

    /** @test */
    public function a_transaction_has_belongs_to_category_relation()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->make(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $transaction->category);
        $this->assertEquals($transaction->category_id, $transaction->category->id);
    }

    /** @test */
    public function a_transaction_has_belongs_to_partner_relation()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->make(['partner_id' => $partner->id]);

        $this->assertInstanceOf(Partner::class, $transaction->partner);
        $this->assertEquals($transaction->partner_id, $transaction->partner->id);
    }

    /** @test */
    public function a_transaction_has_belongs_to_loan_relation()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->make(['creator_id' => $user->id, 'loan_id' => $loan->id]);

        $this->assertInstanceOf(Loan::class, $transaction->loan);
        $this->assertEquals($transaction->loan_id, $transaction->loan->id);
    }

    /** @test */
    public function a_transaction_has_type_attribute()
    {
        $transaction = factory(Transaction::class)->make(['in_out' => 1]);
        $this->assertEquals(__('transaction.income'), $transaction->type);

        $transaction->in_out = 0;
        $this->assertEquals(__('transaction.spending'), $transaction->type);
    }

    /** @test */
    public function a_transaction_has_amount_string_attribute()
    {
        $amount = 1099.00;

        $transaction = factory(Transaction::class)->make([
            'in_out' => 1,
            'amount' => $amount,
        ]);
        $this->assertEquals(number_format($amount, 2), $transaction->amount_string);

        $transaction->in_out = 0;
        $this->assertEquals('- '.number_format($amount, 2), $transaction->amount_string);
    }

    /** @test */
    public function a_transaction_has_year_month_and_date_only_attribute()
    {
        $transaction = factory(Transaction::class)->make(['date' => '2017-01-31']);

        $this->assertEquals('2017', $transaction->year);
        $this->assertEquals('01', $transaction->month);
        $this->assertEquals(Carbon::parse('2017-01-31')->isoFormat('MMM'), $transaction->month_name);
        $this->assertEquals('31', $transaction->date_only);
    }

    /** @test */
    public function a_transaction_has_day_name_attribute()
    {
        $date = '2017-01-31';
        $transaction = factory(Transaction::class)->make(['date' => $date]);

        $this->assertEquals(Carbon::parse($date)->isoFormat('dddd'), $transaction->day_name);

        $transaction = factory(Transaction::class)->make(['date' => null]);
        $this->assertEquals(null, $transaction->day_name);
    }

    /** @test */
    public function a_transaction_has_change_day_name_minggu_to_ahad_attribute()
    {
        $date = '2017-01-29';
        $transaction = factory(Transaction::class)->make(['date' => $date]);

        $this->assertEquals('Ahad', $transaction->day_name);
    }

    /** @test */
    public function a_transaction_has_for_user_scope()
    {
        $transactionOwner = $this->loginAsUser();
        $transaction = factory(Transaction::class)->create([
            'creator_id' => $transactionOwner->id,
        ]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertCount(1, Transaction::all());
    }
}
