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
        $year = $request->get('year', date('Y'));
        $data = $this->getYearlyTransactionSummary($year);

        return view('reports.index', compact('year', 'data'));
    }

    /**
     * Get transaction yearly report data.
     *
     * @param  string  $year
     * @return \Illuminate\Support\Collection
     */
    private function getYearlyTransactionSummary($year)
    {
        $rawQuery = 'MONTH(date) as month';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportsData = DB::table('transactions')->select(DB::raw($rawQuery))
            ->where(DB::raw('YEAR(date)'), $year)
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
