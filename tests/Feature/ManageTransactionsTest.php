<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageTransactionsTest extends TestCase
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
    public function user_can_see_transaction_list_by_selected_date()
    {
        $user = $this->loginAsUser();
        $yesterday = today()->subDay()->format('Y-m-d');
        $yesterdayTransaction = factory(Transaction::class)->create([
            'date'        => $yesterday,
            'description' => 'Yesterday Transaction',
            'creator_id'  => $user->id,
        ]);

        $this->visit(route('transactions.index'));
        $this->dontSee($yesterdayTransaction->description);

        $this->visit(route('transactions.index', ['date' => $yesterday]));
        $this->see($yesterdayTransaction->description);
    }

    /** @test */
    public function transaction_list_for_today_by_default()
    {
        $user = $this->loginAsUser();
        $todayTransaction = factory(Transaction::class)->create([
            'date'        => today()->format('Y-m-d'),
            'description' => 'Today Transaction',
            'creator_id'  => $user->id,
        ]);
        $yesterday = today()->subDay()->format('Y-m-d');
        $yesterdayTransaction = factory(Transaction::class)->create([
            'date'        => $yesterday,
            'description' => 'Yesterday Transaction',
            'creator_id'  => $user->id,
        ]);

        $this->visit(route('transactions.index'));
        $this->see($todayTransaction->description);
        $this->dontSee($yesterdayTransaction->description);
    }

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $date = '2017-01-01';
        $this->loginAsUser();
        $this->visit(route('transactions.index', ['date' => $date]));

        $this->click(__('transaction.add_income'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-income', 'date' => $date]));

        $this->submitForm(__('transaction.add_income'), [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Income description',
        ]);

        $this->seePageIs(route('transactions.index', ['date' => $date]));
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out'      => 1, // 0:spending, 1:income
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Income description',
        ]);
    }

    /** @test */
    public function user_can_create_a_spending_transaction()
    {
        $date = '2017-01-01';
        $this->loginAsUser();
        $this->visit(route('transactions.index', ['date' => $date]));

        $this->click(__('transaction.add_spending'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-spending', 'date' => $date]));

        $this->submitForm(__('transaction.add_spending'), [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Spending description',
        ]);

        $this->seePageIs(route('transactions.index', ['date' => $date]));
        $this->see(__('transaction.spending_added'));

        $this->seeInDatabase('transactions', [
            'in_out'      => 0, // 0:spending, 1:income
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Spending description',
        ]);
    }

    /** @test */
    public function user_can_edit_a_transaction_within_search_query()
    {
        $date = date('Y-m-d');
        $user = $this->loginAsUser();
        $transaction = factory(Transaction::class)->create([
            'in_out'     => 0,
            'amount'     => 99.99,
            'date'       => $date,
            'creator_id' => $user->id,
        ]);

        $this->visit(route('transactions.index', ['date' => $date]));
        $this->click('edit-transaction-'.$transaction->id);
        $this->seePageIs(route('transactions.index', ['action' => 'edit', 'date' => $date, 'id' => $transaction->id]));

        $this->submitForm(__('transaction.update'), [
            'in_out'      => 1,
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Transaction 1 description',
        ]);

        $this->seePageIs(route('transactions.index', ['date' => $date]));

        $this->seeInDatabase('transactions', [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Transaction 1 description',
        ]);
    }

    /** @test */
    public function user_can_delete_a_transaction()
    {
        $this->loginAsUser();
        $transaction = factory(Transaction::class)->create();

        $this->visit(route('transactions.index', ['action' => 'edit', 'id' => $transaction->id]));
        $this->click('del-transaction-'.$transaction->id);
        $this->seePageIs(route('transactions.index', ['action' => 'delete', 'id' => $transaction->id]));

        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->see(__('transaction.deleted'));

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
