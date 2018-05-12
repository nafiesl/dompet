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
        $transactions = Transaction::where(function ($query) {
            $query->where('name', 'like', '%'.request('q').'%');
        })->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('transactions.index', compact('transactions', 'editableTransaction'));
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

        $this->validate($request, [
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);

        $newTransaction = $request->only('name', 'description');
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

        $this->validate($request, [
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);

        $routeParam = request()->only('page', 'q');

        $transaction->update($request->only('name', 'description'));

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

        $routeParam = request()->only('page', 'q');

        if (request('transaction_id') == $transaction->id && $transaction->delete()) {
            return redirect()->route('transactions.index', $routeParam);
        }

        return back();
    }
}
