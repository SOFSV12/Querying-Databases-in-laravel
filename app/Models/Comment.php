<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'comments',
        'ratings',
        'user_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * get the country which the comment belongs to
     *
     * @return HasOneThrough
     */
    public function countryOwner():HasOneThrough
    {
        return $this->hasOneThrough(
            Address::class, //final model which we want to access
            User::class,    //intermediate model
            'user_id',      //foreign key on users table??
            'user_id',      //foreign key on final model
            'id',           //local key
            'id',           //local key of intermidiate table users
            );
    }
}
