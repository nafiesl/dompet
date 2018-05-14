<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_transaction_has_name_link_method()
    {
        $transaction = factory(Transaction::class)->create();

        $this->assertEquals(
            link_to_route('transactions.show', $transaction->name, [$transaction], [
                'title' => trans(
                    'app.show_detail_title',
                    ['name' => $transaction->name, 'type' => trans('transaction.transaction')]
                ),
            ]), $transaction->nameLink()
        );
    }

    /** @test */
    public function a_transaction_has_belongs_to_creator_relation()
    {
        $transaction = factory(Transaction::class)->make();

        $this->assertInstanceOf(User::class, $transaction->creator);
        $this->assertEquals($transaction->creator_id, $transaction->creator->id);
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
        $this->assertEquals('31', $transaction->date_only);
    }

    /** @test */
    public function a_transaction_has_for_user_scope()
    {
        $transactionOwner = $this->createUser();
        $transaction = factory(Transaction::class)->create([
            'creator_id' => $transactionOwner->id,
        ]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertCount(1, Transaction::forUser($transactionOwner)->get());
    }
}
