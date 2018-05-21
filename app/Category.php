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

    public function scopeForUser($query, User $user)
    {
        $query->where('creator_id', $user->id);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
