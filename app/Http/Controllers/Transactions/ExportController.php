<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function csv(Request $request)
    {
        $yearMonth = $this->getYearMonth();
        $transactions = $this->getTansactions($yearMonth);

        $output = implode("; ", [
            __('app.date'),
            __('app.description'),
            __('transaction.in_out'),
            __('transaction.amount'),
            __('category.category'),
            __('partner.partner'),
        ]);
        $output .= "\n";

        foreach ($transactions as $transaction) {
            $output .= implode("; ", [
                $transaction->date,
                $transaction->description,
                $transaction->in_out,
                $transaction->amount,
                optional($transaction->category)->name,
                optional($transaction->partner)->name,
            ]);
            $output .= "\n";
        }

        return Response::make(rtrim($output, "\n"), 200, [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=transactions_'.date('YmdHis').'.csv',
        ]);
    }
}
