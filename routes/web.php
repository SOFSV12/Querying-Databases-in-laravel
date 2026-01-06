<?php

use App\Models\User;

use App\Models\Room;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    
});






/**
 * LESSON 1
 * GETTING RESULTS FROM THE DATABASE: SELECT 
 * everything that was worked on using select, didtinct, aggregate functions under query builder
 */

Route::get('/select-statement-query', function(){
/*
    Below is a Query Builder which, selects certain property for each item returned in the collection,
    Query Builder returns a collection
    */
    // $builder = DB::table('users')->select('name','email')->get();

    // //return all records from the table
    // $builderFetchAll = DB::table('users')->get();

    // //return a specifc property from a collection
    // $builderFetchNameOnly = DB::table('users')->pluck('name');

    // //return the first record where you locate the string max
    // $fetchUser = DB::table('users')->where('name', 'like', 'DR%')->first();

    // //return the first record which is a match
    // $fetchMatchingUser = DB::table('users')->where('name', 'Annie McCullough')->get();

    // //get a specific value 
    // $fetchMatchingUserValue = DB::table('users')->where('name', 'Annie McCullough')->value('email');

    // $find = DB::table('users')->find(2);

    // $find2 = DB::table('users')->where('id', 2)->value('name');

    // dump($builder, $builderFetchAll, $builderFetchNameOnly, $fetchUser, $fetchMatchingUser, $fetchMatchingUserValue, $find, $find2);

    /*
        Querying Comments table using LARAVEL QUERY BUILDER
        observe below we can alias a column being selected for example
        {columnname}  as      {alias}
        'comments     as     content'
        we also used aggregate functions for sql, min,max,avg,you can find diffrent helper function in laravel docs

    */
        // $comments = DB::table('comments')->select('comments as content')->get();
        $comments = DB::table('comments')->select('email')->distinct()->get();
        $commentCount = DB::table('comments')->count();
        $sum = DB::table('users')->sum('id');
        $max = DB::table('users')->max('id');
        $min = DB::table('users')->min('id');
        $avg = DB::table('users')->avg('id');
        $userIsreal = DB::table('users')->where('id', 3)->exists();
        $userIsNotreal = DB::table('users')->where('id', 5)->doesntExist();

        dump($commentCount, $sum, $max, $min, $avg, $userIsreal, $userIsNotreal);
});

/**
 * LESSON 2 
 * SQL CLAUSE PART 1
 * how to use where clause with laravel query builder, how to use where clause with an anonymous function
 * how to use where clause with a diffrent syntax such as 
 * where->(some operation)->where(another operation)
 * same with
 * where->([[first operation], [second operation]])
 * Both interpreted as where this and this 
*/
Route::get('/where-clause-query', function(){
    $rooms = DB::table('rooms')->get();

    $lessThan = DB::table('rooms')->where('price', '<', 300)->get();

    /*
    Multiple Conditions
    when we do where([['price', '>', 300], ['room_number', '>', 5]])
    we say the first condition And condition 2 
    */

    $conditions = DB::table('rooms')->where([
        ['price', '>', 300], 
        ['room_number', '>', 5]
        ])->get();
    
    $conditionAlternateSyntax = DB::table('rooms')->where('price', '>', 300)->where('room_number', '>', 5)->get();

    /*orWhere clasue example*/
    $condition = DB::table('rooms')->where('price', '<', 300)->orWhere('room_number', '>', 5)->get();

    /*using anonymous function*/
    $anonymous = DB::table('rooms')->where('price', '<', 300)
    ->orWhere(function($query){
        $query->where([['room_size', '<=' , 30], ['description', 'like' , '%Ducimus%']]);
    })->get();

    dump($conditions,$conditionAlternateSyntax);
});


