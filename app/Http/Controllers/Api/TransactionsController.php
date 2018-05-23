<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCollection;

class TransactionsController extends Controller
{
    public function index()
    {
        $yearMonth = $this->getYearMonth();

        return new TransactionCollection(
            $this->getTansactionsForUser(auth()->user(), $yearMonth)
        );
    }
}
