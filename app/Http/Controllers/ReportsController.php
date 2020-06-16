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
        $partnerId = $request->get('partner_id');
        $partners = $this->getPartnerList();
        $year = $this->getYearQuery($request->get('year'));
        $data = $this->getYearlyTransactionSummary($year, auth()->id(), $partnerId);

        return view('reports.index', compact('year', 'data', 'partners', 'partnerId'));
    }

    /**
     * Get correct year from query string.
     *
     * @param  int|string  $yearQuery
     * @return int|string
     */
    private function getYearQuery($yearQuery)
    {
        return in_array($yearQuery, get_years()) ? $yearQuery : date('Y');
    }

    /**
     * Get transaction yearly report data.
     *
     * @param  int|string  $year
     * @param  int  $userId
     * @param  int|null  $partnerId
     * @return \Illuminate\Support\Collection
     */
    private function getYearlyTransactionSummary($year, $userId, $partnerId = null)
    {
        $rawQuery = 'MONTH(date) as month';
        $rawQuery .= ', YEAR(date) as year';
        $rawQuery .= ', count(`id`) as count';
        $rawQuery .= ', sum(if(in_out = 1, amount, 0)) AS income';
        $rawQuery .= ', sum(if(in_out = 0, amount, 0)) AS spending';

        $reportQuery = DB::table('transactions')->select(DB::raw($rawQuery))
            ->where(DB::raw('YEAR(date)'), $year)
            ->where('creator_id', $userId);

        if ($partnerId) {
            $reportQuery->where('partner_id', $partnerId);
        }

        $reportsData = $reportQuery->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
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
