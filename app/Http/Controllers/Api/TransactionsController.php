<?php

namespace App\Http\Controllers\Api;

use App\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCollection;

class TransactionsController extends Controller
{
    public function index()
    {
        $yearMonth = $this->getYearMonth();

        $transactionQuery = Transaction::forUser(auth()->user());
        $transactionQuery->where('date', 'like', $yearMonth.'%');
        $transactions = $transactionQuery->orderBy('date')->with('category')->get();

        return new TransactionCollection($transactions);
    }
}
