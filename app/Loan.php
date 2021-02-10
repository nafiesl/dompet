<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['name', 'description', 'creator_id'];

    public function getNameLinkAttribute()
    {
        return link_to_route('loans.show', $this->name, [$this], [
            'title' => __(
                'app.show_detail_title',
                ['name' => $this->name, 'type' => __('loan.loan')]
            ),
        ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
