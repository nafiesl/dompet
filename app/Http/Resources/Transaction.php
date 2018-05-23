<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Transaction extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $transaction = $this->resource;

        return [
            'date'           => $transaction->date,
            'amount'         => $transaction->amount_string,
            'description'    => $transaction->description,
            'category'       => optional($transaction->category)->name,
            'category_color' => optional($transaction->category)->color,
        ];
    }
}
