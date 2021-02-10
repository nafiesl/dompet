<?php

namespace App;

use App\Partner;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    const TYPE_DEBT = 1;
    const TYPE_RECEIVABLE = 0;

    protected $fillable = [
        'partner_id', 'type_id', 'amount', 'description', 'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
