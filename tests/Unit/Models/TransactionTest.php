<?php

namespace Tests\Unit\Models;

use App\User;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase as TestCase;

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
}
