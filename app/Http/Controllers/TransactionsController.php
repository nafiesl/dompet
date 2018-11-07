<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\Transactions\CreateRequest;
use App\Http\Requests\Transactions\UpdateRequest;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the transaction.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $editableTransaction = null;
        $yearMonth = $this->getYearMonth();
        $year = request('year', date('Y'));
        $month = request('month', date('m'));

        $transactions = $this->getTansactions($yearMonth);

        $categories = $this->getCategoryList();
        $partners = $this->getPartnerList();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableTransaction = Transaction::find(request('id'));
        }

        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        return view('transactions.index', compact(
            'transactions', 'editableTransaction',
            'yearMonth', 'month', 'year', 'categories',
            'incomeTotal', 'spendingTotal', 'partners'
        ));
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \App\Http\Requests\Transactions\CreateRequest  $transactionCreateForm
     * @return \Illuminate\Routing\Redirector
     */
    public function store(CreateRequest $transactionCreateForm)
    {
        $transaction = $transactionCreateForm->save();

        if ($transaction['in_out']) {
            flash(__('transaction.income_added'), 'success');
        } else {
            flash(__('transaction.spending_added'), 'success');
        }

        return redirect()->route('transactions.index', [
            'month' => $transaction->month, 'year' => $transaction->year,
        ]);
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \App\Http\Requests\Transactions\UpdateRequest  $transactionUpateForm
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Routing\Redirector
     */
    public function update(UpdateRequest $transactionUpateForm, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction = $transactionUpateForm->save();

        flash(__('transaction.updated'), 'success');

        return redirect()->route('transactions.index', [
            'month'       => $transaction->month,
            'year'        => $transaction->year,
            'category_id' => $transactionUpateForm->get('queried_category_id'),
            'query'       => $transactionUpateForm->get('query'),
        ]);
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        request()->validate(['transaction_id' => 'required']);

        if (request('transaction_id') == $transaction->id && $transaction->delete()) {
            flash(__('transaction.deleted'), 'warning');

            return redirect()->route('transactions.index', [
                'month' => $transaction->month, 'year' => $transaction->year,
            ]);
        }

        flash(__('transaction.undeleted'), 'error');

        return back();
    }
}
