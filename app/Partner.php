<?php

namespace App;

use App\Traits\Models\ForUser;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use ForUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'creator_id'];

    /**
     * Partner belongs to user creator relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Partner has many transactions relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get partner name label attribute.
     *
     * @return string
     */
    public function getNameLabelAttribute()
    {
        return '<span class="badge badge-pill badge-secondary">'.$this->name.'</span>';
    }
}
