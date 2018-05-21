<?php

namespace App\Http\Controllers;

use App\Category;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $editableTransaction = null;
        $yearMonth = $this->getYearMonth();
        $year = request('year', date('Y'));
        $month = request('month', date('m'));
        $transactionQuery = Transaction::forUser(auth()->user());
        $transactionQuery->where('date', 'like', $yearMonth.'%');
        $transactionQuery->where('description', 'like', '%'.request('query').'%');
        $categories = Category::forUser(auth()->user())->pluck('name', 'id');

        $transactions = $transactionQuery->orderBy('date')->get();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('transactions.index', compact(
            'transactions', 'editableTransaction',
            'yearMonth', 'month', 'year', 'categories'
        ));
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
            'month' => $transaction->month, 'year' => $transaction->year,
        ]);
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
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

    protected function getYearMonth()
    {
        $date = request('date');
        $year = request('year', date('Y'));
        $month = request('month', date('m'));
        $yearMonth = $year.'-'.$month;

        $explodedYearMonth = explode('-', $yearMonth);

        if (count($explodedYearMonth) == 2 && checkdate($explodedYearMonth[1], '01', $explodedYearMonth[0])) {
            if (checkdate($explodedYearMonth[1], $date, $explodedYearMonth[0])) {
                return $explodedYearMonth[0].'-'.$explodedYearMonth[1].'-'.$date;
            }
            return $explodedYearMonth[0].'-'.$explodedYearMonth[1];
        }

        return date('Y-m');
    }
}
