<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Yearly transaction summary report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $year = $this->getYearQuery($request->get('year'));
        $data = $this->getYearlyTransactionSummary($year, auth()->id());

        return view('reports.index', compact('year', 'data'));
    }

    /**
     * Get correct year from query string.
     *
     * @param  int|string  $yearQuery
     * @return int|string
     */
    private function getYearQuery($yearQuery)
    {
        return in_array($yearQuery, getYears()) ? $yearQuery : date('Y');
    }

    /**
     * Get transaction yearly report data.
     *
     * @param  int|string  $year
     * @return \Illuminate\Support\Collection
     */
    private function getYearlyTransactionSummary($year, $userId)
    {
        $rawQuery = 'MONTH(date) as month';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportsData = DB::table('transactions')->select(DB::raw($rawQuery))
            ->where(DB::raw('YEAR(date)'), $year)
            ->where('creator_id', $userId)
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('date', 'asc')
            ->get();

        $reports = [];
        foreach ($reportsData as $report) {
            $key = str_pad($report->month, 2, '0', STR_PAD_LEFT);
            $reports[$key] = $report;
            $reports[$key]->difference = $report->income - $report->spending;
        }

        return collect($reports);
    }
}
