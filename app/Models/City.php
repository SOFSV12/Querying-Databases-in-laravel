<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;

    protected $fillable = [
        'cities'
    ];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'city_room', 'city_id', 'room_id', 'id', 'id')->withTimestamps();
    }
}
