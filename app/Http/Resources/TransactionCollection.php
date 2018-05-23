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
        return ['total' => $this->getTransactionTotal()];
    }

    private function getTransactionTotal()
    {
        $total = $this->resource->sum(function ($transaction) {
            return $transaction->in_out ? $transaction->amount : -$transaction->amount;
        });
        $total = number_format($total, 2);

        return str_replace('-', '- ', $total);
    }
}
