<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Country extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get associated Citizens
     *
     * @return HasMany
     */
    public function citizens(): HasMany
    {
        return $this->hasMany(User::class, 'country_id', 'id');
    }

    public function comment() :HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, User::class);
    }

    public function Comments()
    {
        return $this->through('citizens')->has('comment');
    }

    public function userComments()
    {
        //🧱 Pure database-level logic
        // return Comment::whereIn('user_id', function ($query) {
        //     $query->select('id')
        //     ->from('users')
        //     ->where('country_id', $this->id);
        // })->with('user')->get();


        //utilizing a relationship
        return Comment::whereHas('user', function ($query) {
            $query->select('id')
            ->from('users')
            ->where('country_id', $this->id);
        })->with('user')->get();
    }




}
