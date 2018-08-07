<?php

namespace Tests\Feature\Transactions;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_transaction_list_in_transaction_index_page()
    {
        $user = $this->loginAsUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);

        $this->visit(route('transactions.index'));
        $this->see($transaction->amount);
    }

    /** @test */
    public function user_can_see_transaction_list_by_selected_month_and_year()
    {
        $user = $this->loginAsUser();
        $lastMonth = today()->subDays(31);
        $lastMonthNumber = $lastMonth->format('m');
        $lastMonthYear = $lastMonth->format('Y');
        $lastMonthDate = $lastMonth->format('Y-m-d');
        $lastMonthTransaction = factory(Transaction::class)->create([
            'date'        => $lastMonthDate,
            'description' => 'Last month Transaction',
            'creator_id'  => $user->id,
        ]);

        $this->visit(route('transactions.index'));
        $this->dontSee($lastMonthTransaction->description);

        $this->visit(route('transactions.index', ['month' => $lastMonthNumber, 'year' => $lastMonthYear]));
        $this->see($lastMonthTransaction->description);
    }

    /** @test */
    public function transaction_list_for_this_month_by_default()
    {
        $user = $this->loginAsUser();
        $thisMonthTransaction = factory(Transaction::class)->create([
            'date'        => today()->format('Y-m-d'),
            'description' => 'Today Transaction',
            'creator_id'  => $user->id,
        ]);
        $lastMonthDate = today()->subDays(31)->format('Y-m-d');
        $lastMonthTransaction = factory(Transaction::class)->create([
            'date'        => $lastMonthDate,
            'description' => 'Last month transaction',
            'creator_id'  => $user->id,
        ]);

        $this->visit(route('transactions.index'));
        $this->see($thisMonthTransaction->description);
        $this->dontSee($lastMonthTransaction->description);
    }
}
