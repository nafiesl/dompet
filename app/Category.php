<?php

namespace App;

use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use ForUser;

    protected $fillable = ['name', 'description', 'color', 'creator_id'];

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
