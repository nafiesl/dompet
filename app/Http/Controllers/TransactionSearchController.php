<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionSearchController extends Controller
{
    public function index(Request $request)
    {
        $defaultStartDate = auth()->user()->account_start_date ?: date('Y-m').'-01';
        $startDate = request('start_date', $defaultStartDate);
        $endDate = request('end_date', date('Y-m-t'));

        $transactions = collect([]);
        $searchQuery = $request->get('search_query');
        if ($searchQuery) {
            $transactionQuery = Transaction::orderBy('date', 'desc');
            $transactionQuery->where('description', 'like', '%'.$searchQuery.'%');
            $transactionQuery->whereBetween('date', [$startDate, $endDate]);
            $transactions = $transactionQuery->with('category', 'partner', 'loan')->limit(100)->get();
        }

        return view('transaction_search.index', compact('searchQuery', 'transactions', 'startDate', 'endDate'));
    }
}
