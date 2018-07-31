<?php

namespace Tests\Feature;

use App\Category;
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

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-income', 'month' => $month, 'year' => $year]));

        $this->submitForm(__('transaction.add_income'), [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);

        $this->seePageIs(route('transactions.index', ['month' => $month, 'year' => $year]));
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out'      => 1, // 0:spending, 1:income
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function user_can_create_a_spending_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $this->loginAsUser();
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_spending'));
        $this->seePageIs(route('transactions.index', ['action' => 'add-spending', 'month' => $month, 'year' => $year]));

        $this->submitForm(__('transaction.add_spending'), [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Spending description',
        ]);

        $this->seePageIs(route('transactions.index', ['month' => $month, 'year' => $year]));
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
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $transaction = factory(Transaction::class)->create([
            'in_out'     => 0,
            'amount'     => 99.99,
            'date'       => $date,
            'creator_id' => $user->id,
        ]);
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));
        $this->click('edit-transaction-'.$transaction->id);
        $this->seePageIs(route('transactions.index', [
            'action' => 'edit', 'id'   => $transaction->id,
            'month'  => $month, 'year' => $year,
        ]));

        $this->submitForm(__('transaction.update'), [
            'in_out'      => 1,
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
        ]);

        $this->seePageIs(route('transactions.index', ['month' => $transaction->month, 'year' => $transaction->year]));
        $this->see(__('transaction.updated'));

        $this->seeInDatabase('transactions', [
            'amount'      => 99.99,
            'date'        => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_transaction()
    {
        $user = $this->loginAsUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);

        $this->visit(route('transactions.index', ['action' => 'edit', 'id' => $transaction->id]));
        $this->click('del-transaction-'.$transaction->id);
        $this->seePageIs(route('transactions.index', ['action' => 'delete', 'id' => $transaction->id]));

        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->seePageIs(route('transactions.index', ['month' => $transaction->month, 'year' => $transaction->year]));
        $this->see(__('transaction.deleted'));

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
