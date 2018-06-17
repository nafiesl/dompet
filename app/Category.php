<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'color', 'creator_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('forUser', function (Builder $builder) {
            $builder->where('creator_id', auth()->id());
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getNameLabelAttribute()
    {
        return '<span class="badge" style="background-color: '.$this->color.'">'.$this->name.'</span>';
    }
}
