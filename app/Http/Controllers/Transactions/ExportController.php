<?php

namespace App\Http\Controllers\Transactions;

use App\Category;
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

    public function byCategory(Category $category)
    {
        $year = request('year', date('Y'));
        $startDate = request('start_date', date('Y-m').'-01');
        $endDate = request('end_date', date('Y-m-d'));
        $transactions = $this->getCategoryTransactions($category, [
            'partner_id' => request('partner_id'),
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'query'      => request('query'),
        ]);
        $output = (new CsvTransformer($transactions))->toString();

        return Response::make(rtrim($output, "\n"), 200, [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=transactions_'.date('YmdHis').'.csv',
        ]);
    }
}
