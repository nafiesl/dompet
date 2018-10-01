<?php

namespace App\Http\Controllers;

use App\Partner;
use App\Category;
use App\Transaction;
use Illuminate\Http\Request;

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

        $categories = Category::pluck('name', 'id');
        $partners = Partner::pluck('name', 'id');

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Transaction);

        $newTransaction = $request->validate([
            'date'        => 'required|date|date_format:Y-m-d',
            'amount'      => 'required|max:60',
            'in_out'      => 'required|boolean',
            'description' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id,creator_id,'.auth()->id(),
        ]);
        $newTransaction['creator_id'] = auth()->id();

        $transaction = Transaction::create($newTransaction);

        if ($newTransaction['in_out']) {
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transactionData = $this->validate($request, [
            'in_out'      => 'required|boolean',
            'date'        => 'required|date|date_format:Y-m-d',
            'amount'      => 'required|max:60',
            'description' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id,creator_id,'.auth()->id(),
        ]);

        $transaction->update($transactionData);

        flash(__('transaction.updated'), 'success');

        return redirect()->route('transactions.index', [
            'month'       => $transaction->month,
            'year'        => $transaction->year,
            'category_id' => $request->get('queried_category_id'),
            'query'       => $request->get('query'),
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

        $this->validate(request(), [
            'transaction_id' => 'required',
        ]);

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
