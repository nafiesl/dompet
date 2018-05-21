<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'creator_id'];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
