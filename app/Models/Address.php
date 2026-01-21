<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'number',
        'street',
        'user_id',
    ];

    /**
     * relationship between user and address
     *
     * @return void
     */
    public function user(): BelongsTo
    {
        // 2nd and 3rd arguments are optional except you are not following the convention
        //2nd argument foreign key
        // 3rd argument refrenced key
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
