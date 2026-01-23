<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function addressOwner(): HasOneThrough
    {
        return $this->hasOneThrough(
        Address::class,  //model trying to be accessed
        User::class,    //intermediate model
        'country_id',  //foreign key on user model
        'user_id',    //foreign key on Address model
        'id',        //local key on country model
        'id'        //local key on user model
         )->with('user');
    }


    public function citizenAddresses()
    {
        return $this->citizens()->with('address')->get();
    }


    public function allAddresses()
    {
        return Address::whereIn('user_id', function($query) {
            $query->select('id')
                ->from('users')
                ->where('country_id', $this->id);
        })->with('user')->get();

        //utilizes relationships whereHas
        //     return Address::whereHas('user', function($query) {
        //     $query->where('country_id', $this->id);
        // })->with('user')->get();
    }
}
