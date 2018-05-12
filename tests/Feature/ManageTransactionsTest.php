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
        $transaction = factory(Transaction::class)->create();

        $this->loginAsUser();
        $this->visit(route('transactions.index'));
        $this->see($transaction->amount);
    }

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $date = '2017-01-01';
        $this->loginAsUser();
        $this->visit(route('transactions.index'));

        $this->click(__('transaction.add_income'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-income']));

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
        $this->visit(route('transactions.index'));

        $this->click(__('transaction.add_spending'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-spending']));

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
        $this->loginAsUser();
        $date = date('Y-m-d');
        $transaction = factory(Transaction::class)->create(['amount' => 99.99, 'date' => $date]);

        $this->visit(route('transactions.index', ['date' => $date]));
        $this->click('edit-transaction-'.$transaction->id);
        $this->seePageIs(route('transactions.index', ['action' => 'edit', 'date' => $date, 'id' => $transaction->id]));

        $this->submitForm(__('transaction.update'), [
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
