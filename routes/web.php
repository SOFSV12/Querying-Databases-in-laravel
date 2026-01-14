<?php

use App\Models\Room;

use App\Models\User;
use App\Models\Comment;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     
});


/**
 * LESSON 1
 * Introduction to laravel ORM - ELOQUENT
 */
Route::get('/eloquent', function(){
    $queryBuilder = DB::table('rooms')
                    ->where('room_size', '>', 3)
                    ->get();

    $eloquent = Room::where('room_size', '>', 3)
                ->get();
    
    $fetchAll = Room::all();

    $getAll   = Room::get();

    // $userSelect = User::select('name', 'email')
    //             ->addSelect([
    //             'worst_rating' => Comment::selectRaw('MIN(ratings)')
    //             ->where('ratings', '>', 2)
    //             ->whereColumn('user_id', 'users.id')
    //             ])
    //             ->get()
    //             ->toArray();

    //add select works with select 
    $userSelect = User::select('name', 'email')
                  ->addSelect(['worst_rating' => Comment::select('ratings')
                  ->whereColumn('user_id', 'users.id')
                  ->orderBy('ratings', 'asc')
                  ->limit(1)])
                  ->get()
                  ->toArray();
    
    $result = User::orderByDesc(
                Reservation::select('check_in')
                ->whereColumn('user_id', 'users.id')
                ->orderBy('check_in', 'DESC')
                ->take(1))
                ->select('id', 'name')
                ->get()
                ->toArray();

    $ids = [];
    //use less memeory than get() and chunk() but chunk() takes longer 
    //bigger chunk size, is less time a query takes but more memory used
    $chunk = Reservation::chunk(2, function($res) use (&$ids){
        // foreach ($res as $reservation) {
        //     $ids[] = $reservation->id;
        // }

        foreach(Room::cursor() as $reservation){
            echo $reservation->id;
        }
    });

    //please be aware of cusor() method and lazy method
    //lazy is similar to chunk
    //cursor the cursor method may be used to significantly reduce your application's memory consumption when iterating through tens of thousands of Eloquent model records, with only one query

    dump($ids);
});


