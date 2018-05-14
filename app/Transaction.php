<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['date', 'amount', 'in_out', 'description', 'creator_id'];

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

    public function getTypeAttribute()
    {
        return $this->in_out ? __('transaction.income') : __('transaction.spending');
    }

    public function getAmountStringAttribute()
    {
        $amountString = number_format($this->amount, 2);

        if ($this->in_out == 0) {
            $amountString = '- '.$amountString;
        }

        return $amountString;
    }

    public function scopeForUser($query, User $user)
    {
        $query->where('creator_id', $user->id);
    }
}
