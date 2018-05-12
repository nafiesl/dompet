<?php

namespace App\Http\Controllers;

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
        $date = request('date', date('Y-m-d'));
        $transactions = Transaction::where(function ($query) use ($date) {
            $query->where('date', $date);
        })->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('transactions.index', compact('transactions', 'editableTransaction', 'date'));
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
            'description' => 'required|max:255',
        ]);
        $newTransaction['creator_id'] = auth()->id();

        Transaction::create($newTransaction);

        return redirect()->route('transactions.index');
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
            'date'        => 'required|date|date_format:Y-m-d',
            'amount'      => 'required|max:60',
            'description' => 'required|max:255',
        ]);

        $routeParam = request()->only('page', 'date');

        $transaction->update($transactionData);

        return redirect()->route('transactions.index', $routeParam);
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

        $routeParam = request()->only('page', 'date');

        if (request('transaction_id') == $transaction->id && $transaction->delete()) {
            return redirect()->route('transactions.index', $routeParam);
        }

        return back();
    }
}
