<?php

namespace App;

use App\Partner;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    const TYPE_DEBT = 1;
    const TYPE_RECEIVABLE = 2;

    protected $fillable = [
        'partner_id', 'type_id', 'amount', 'description', 'creator_id',
        'start_date', 'end_date', 'planned_payment_count',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function getTypeAttribute()
    {
        if ($this->type_id == Loan::TYPE_DEBT) {
            return __('loan.types.debt');
        }

        return __('loan.types.receivable');
    }

    public function getAmountStringAttribute()
    {
        return number_format($this->amount, 2);
    }
}
