<?php

namespace App\Http\Controllers;

use App\Partner;
use App\Category;
use App\Transaction;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getYearMonth()
    {
        $date = request('date');
        $year = request('year', date('Y'));
        $month = request('month', date('m'));
        $yearMonth = $year.'-'.$month;

        $explodedYearMonth = explode('-', $yearMonth);

        if (count($explodedYearMonth) == 2 && checkdate($explodedYearMonth[1], '01', $explodedYearMonth[0])) {
            if (checkdate($explodedYearMonth[1], $date, $explodedYearMonth[0])) {
                return $explodedYearMonth[0].'-'.$explodedYearMonth[1].'-'.$date;
            }

            return $explodedYearMonth[0].'-'.$explodedYearMonth[1];
        }

        return date('Y-m');
    }

    protected function getTansactions($yearMonth)
    {
        $transactionQuery = Transaction::query();
        $transactionQuery->where('date', 'like', $yearMonth.'%');
        $transactionQuery->where('description', 'like', '%'.request('query').'%');

        if ($categoryId = request('category_id')) {
            $transactionQuery->where('category_id', $categoryId);
        }

        if ($partnerId = request('partner_id')) {
            $transactionQuery->where('partner_id', $partnerId);
        }

        return $transactionQuery->orderBy('date', 'desc')->with('category')->get();
    }

    /**
     * Get income total of a transaction listing.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $transactions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getIncomeTotal($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : 0;
        });
    }

    /**
     * Get spending total of a transaction listing.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $transactions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSpendingTotal($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->in_out ? 0 : $transaction->amount;
        });
    }

    /**
     * Get partner list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPartnerList()
    {
        return Partner::orderBy('name')->pluck('name', 'id');
    }

    /**
     * Get category list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getCategoryList()
    {
        return Category::orderBy('name')->pluck('name', 'id');
    }
}
