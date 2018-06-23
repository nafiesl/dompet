<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait ForUser
{
    public static function bootForUser()
    {
        static::addGlobalScope('forUser', function (Builder $builder) {
            $builder->where('creator_id', auth()->id());
        });
    }
}
