<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'url',
        'imageable_id', //will contain Id of either a post or user
        'imageable_type' //will contain the class name of the parent model either App\Models\Post or App\Models\User
    ];

    /**
     * Get the parent imageable model (user or post).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
