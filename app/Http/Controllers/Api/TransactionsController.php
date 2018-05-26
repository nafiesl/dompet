<?php

namespace App\Http\Controllers\Api;

use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\Transaction as TransactionResource;

class TransactionsController extends Controller
{
    public function index()
    {
        $yearMonth = $this->getYearMonth();

        return new TransactionCollection(
            $this->getTansactionsForUser(auth()->user(), $yearMonth)
        );
    }

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

        $responseMessage = __('transaction.income_added');

        if ($newTransaction['in_out'] == 0) {
            $responseMessage = __('transaction.spending_added');
        }

        $responseData = [
            'message' => $responseMessage,
            'data'    => new TransactionResource($transaction),
        ];
        return response()->json($responseData, 201);
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

        return response()->json([
            'message' => __('transaction.updated'),
            'data'    => new TransactionResource($transaction),
        ]);
    }
}
