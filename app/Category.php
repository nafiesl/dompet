<?php

namespace App;

use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ForUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'color', 'creator_id'];

    /**
     * Category belongs to user creator relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category has many transactions relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get category name label attribute.
     *
     * @return string
     */
    public function getNameLabelAttribute()
    {
        return '<span class="badge" style="background-color: '.$this->color.'">'.$this->name.'</span>';
    }
}
