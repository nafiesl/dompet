<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Services\Transactions\CsvTransformer;

class ExportController extends Controller
{
    public function csv(Request $request)
    {
        $yearMonth = $this->getYearMonth();
        $transactions = $this->getTansactions($yearMonth);
        $output = (new CsvTransformer($transactions))->toString();

        return Response::make(rtrim($output, "\n"), 200, [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=transactions_'.date('YmdHis').'.csv',
        ]);
    }
}
