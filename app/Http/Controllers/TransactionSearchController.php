<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionSearchController extends Controller
{
    public function index(Request $request)
    {
        $transactions = collect([]);
        $searchQuery = $request->get('search_query');
        if ($searchQuery) {
            $transactionQuery = Transaction::orderBy('date', 'desc');
            $transactionQuery->where('description', 'like', '%'.$searchQuery.'%');
            $transactions = $transactionQuery->with('category', 'partner', 'loan')->limit(100)->get();
        }

        return view('transaction_search.index', compact('searchQuery', 'transactions'));
    }
}
