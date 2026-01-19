<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //a model may fire the same events
    //retrieved, creating, created, updated, updating, saving, saved,
    //deleting, deleted, restored, restoring

     use HasFactory, SoftDeletes;

    //  protected $dispatchesEvents = [
    //     'saved'   => 'class to handle saved event',
    //     'deleted' => 'class to handle deleted event',
    //  ];

    protected $fillable = [
        'comments',
        'ratings',
        'user_id',
    ];
    //is the list of attributes that CANNOT be mass assigned.
    protected $guarded = [];

    //create global query scope, which would be applied on all queries
    protected static function booted()
    {
        // static::addGlobalScope('ratings', function(Builder $builder){
        //     $builder->where('ratings', '<', 3);
        // });

        static::retrieved(function($comments){
            echo $comments->comments;
        });

    }

    /**
     * query Scope
     * used for frequently accessed queries helps keep code DRY
     *
     * @param [type] $query
     * @param integer $value
     * @return void
     */
    public function scopeRating($query, int $value = 4) {
        return $query->where('ratings', '>', $value);
    }

    /**
     * Accesors In Laravel
     * naming convention get<Column Name>Attribute
     * manipulate model properties when accessing columns
     * getRatings and ratings do the exact same thing
     */
    // protected function getRatingsAttribute($value)
    // {
    //     return $value + 10;
    // }

    protected function ratings(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => $value * 20,
        );
    }

    /**
     * Mutators
     * These change the attribute before saving
     * @param [type] $value
     * @return void
     */
    public function setRatingsAttribute($value)
    {
        $this->attributes['ratings'] = $value * 20;
    }

    protected function casts(): array
    {
        return [
            'ratings' => 'float'
        ];
    }


}
