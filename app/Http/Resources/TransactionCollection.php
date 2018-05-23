<?php

namespace App\Http\Resources;

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

        return [
            'stats' => [
                'income_total'   => formatNumber($incomeTotal),
                'spending_total' => formatNumber($spendingTotal),
                'difference'     => formatNumber($difference),
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
