<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    //the property below can be used to override the default table which model maps to
    protected $table = 'rooms';
    //protected $primaryKey = 'room_id'
    //public $timestamps =false;
    //protected $connection = 'sqlite'

    protected $fillable = [
        'room_number',
        'room_size',
        'price',
        'description',
    ];

    public function city(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_room', 'room_id', 'city_id', 'id', 'id')->withTimestamps();
    }
}

