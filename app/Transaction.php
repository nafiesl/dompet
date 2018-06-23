<?php

namespace App;

use Carbon\Carbon;
use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use ForUser;

    protected $fillable = [
        'date', 'amount', 'in_out', 'description',
        'category_id', 'creator_id',
    ];

    public function nameLink()
    {
        return link_to_route('transactions.show', $this->name, [$this], [
            'title' => trans(
                'app.show_detail_title',
                ['name' => $this->name, 'type' => trans('transaction.transaction')]
            ),
        ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getTypeAttribute()
    {
        return $this->in_out ? __('transaction.income') : __('transaction.spending');
    }

    public function getDateOnlyAttribute()
    {
        return substr($this->date, -2);
    }

    public function getMonthAttribute()
    {
        return Carbon::parse($this->date)->format('m');
    }

    public function getYearAttribute()
    {
        return Carbon::parse($this->date)->format('Y');
    }

    public function getAmountStringAttribute()
    {
        $amountString = number_format($this->amount, 2);

        if ($this->in_out == 0) {
            $amountString = '- '.$amountString;
        }

        return $amountString;
    }
}
