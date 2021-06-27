<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionSearchController extends Controller
{
    public function index(Request $request)
    {
        $startDate = date('Y-m').'-01';
        $endDate = date('Y-m-t');
        $transactions = collect([]);
        $searchQuery = $request->get('search_query');
        if ($searchQuery) {
            $transactionQuery = Transaction::orderBy('date', 'desc');
            $transactionQuery->where('description', 'like', '%'.$searchQuery.'%');
            $transactions = $transactionQuery->with('category', 'partner', 'loan')->limit(100)->get();

            $defaultStartDate = auth()->user()->account_start_date;
            $startDate = $defaultStartDate ?: $startDate;
        }

        return view('transaction_search.index', compact('searchQuery', 'transactions', 'startDate', 'endDate'));
    }
}
