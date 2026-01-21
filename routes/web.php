<?php

use App\Models\City;
use App\Models\User;
use App\Models\Address;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


});

/**
 * setuo a relatonship between User and Address Model
 * User hasOne Address
 * Address belongs to one User
 */
Route::get('/one-to-one', function(){
    $user   = User::find(1);
    $number = $user->address->number;
    $street = $user->address->street;

    $add = Address::find(1);
    $userName = $add->user->name;
});

/**
 * One to Many Relationship
 * setup a relationship between User and Comment Model
 * User hasMany Comments
 * Comments Belong to User
 * Important thing to note is to hydrate the child with the parent for,
 * Inverse data retrival you need to use chapter0ne() mthod either in model or onr runtime
 */
Route::get('/one-to-many', function(){
    $userc = User::find(1);
    $userCom = $userc->comments->toArray();
    //note this is wrong $userc->comments->comments, $userCom returns a collection instance and not just an attribute
    dump($userCom);
});

/**
 * Many To Many Relationship
 * typical example is a user can have many roles, and a role can belong to many Users
 * A relationships was set between rooms and cities
 * City BelongsToMany Rooms
 * Room BelongsToMany  Cities
 * Important things to note you always need a pivot table, additional methods can be chained
 * to your relationships to return timestamps withTimeStamps() and other additional columns using withPivot()
 * You can also query your pivot table and create custom pivot Model, naming convention of table is done in alphapetcal
 * order, pivot function can replace in relationship definition using as
 */
Route::get('/many-to-many', function (){
    //many to many query between rooms and cities
    $city = City::find(1);
    $cityRooms = $city->rooms->toArray();
    dump($cityRooms, $city->cities);

    $room = Room::find(6);
    // dump($room->city->toArray());
    foreach($room->city as $cityRoom){
        echo $cityRoom->cities.  " has a Room ID of " . $cityRoom->pivot->room_id  .  ", and a City ID of " . $cityRoom->pivot->city_id ." created at " .
        $cityRoom->pivot->created_at . "<br>" ;
    }
});












