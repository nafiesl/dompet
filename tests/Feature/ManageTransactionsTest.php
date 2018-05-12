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
    public function user_can_create_a_transaction()
    {
        $this->loginAsUser();
        $this->visit(route('transactions.index'));

        $this->click(trans('transaction.create'));
        $this->seePageIs(route('transactions.index', ['action' => 'create']));

        $this->submitForm(trans('transaction.create'), [
            'amount'      => 99.99,
            'date'        => '2017-01-01',
            'description' => 'Transaction 1 description',
        ]);

        $this->seePageIs(route('transactions.index'));

        $this->seeInDatabase('transactions', [
            'amount'      => 99.99,
            'date'        => '2017-01-01',
            'description' => 'Transaction 1 description',
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

        $this->submitForm(trans('transaction.update'), [
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

        $this->visit(route('transactions.index', [$transaction->id]));
        $this->click('del-transaction-'.$transaction->id);
        $this->seePageIs(route('transactions.index', ['action' => 'delete', 'id' => $transaction->id]));

        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);

        $this->press(trans('app.delete_confirm_button'));

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
