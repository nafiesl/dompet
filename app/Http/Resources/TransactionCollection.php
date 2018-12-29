<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
    {
        $incomeTotal = $this->getIncomeTransactionTotal();
        $spendingTotal = $this->getSpendingTransactionTotal();
        $difference = $incomeTotal - $spendingTotal;
        $transactions = $this->resource;
        $startBalance = 0;
        $endBalance = 0;
        if ($transactions->last()) {
            $startBalance = balance(Carbon::parse($transactions->last()->date)->subDay()->format('Y-m-d'));
        }
        if ($transactions->first()) {
            $endBalance = balance($transactions->first()->date);
        }

        return [
            'stats' => [
                'start_balance'  => formatNumber($startBalance),
                'income_total'   => formatNumber($incomeTotal),
                'spending_total' => formatNumber($spendingTotal),
                'difference'     => formatNumber($difference),
                'end_balance'    => formatNumber($endBalance),
            ],
        ];
    }

    private function getIncomeTransactionTotal()
    {
        return $this->resource->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : 0;
        });
    }

    private function getSpendingTransactionTotal()
    {
        return $this->resource->sum(function ($transaction) {
            return $transaction->in_out ? 0 : $transaction->amount;
        });
    }
}
