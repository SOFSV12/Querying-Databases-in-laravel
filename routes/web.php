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
Route::get('/where-clause-query-one', function(){
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

/**
 * WHERE CLAUSE PART 2 
 * HERE I learnt that WhereColumn is quite powerful used to compare column in different tables 
 * and whereRaw permits you to write raw sql, used the laravel documentation very helpful
 */

Route::get('/where-clause-query-two', function() {
    $reservations = DB::table('reservations')->select('check_in', 'user_id', 'room_id')->get();

    //we have whereBetween and whereNotBetween
    $roomSize = DB::table('rooms')->whereBetween('room_size', [5, 100])->get();

    //we have whereNotIn and whereIn
    $excludeRecords = DB::table('rooms')->whereNotIn('id', [1,4,5,7,9,11,15,6,10,3])->get();

    //below are other helper methods that are provided by laravel for querying
    // whereNull('column')  whereNotNull
    // whereDate('created_at', '2020-05-13')
    // whereMonth('created_at', '5')
    // whereDay('created_at', '13')
    // whereYear('created_at', '2020')
    // whereTime('created_at', '=', '12:25:10')
    // whereColumn('column1', '>', 'column2')
    // whereColumn([
    //     ['first_name', '=', 'last_name'],
    //     ['updated_at', '>', 'created_at']
    // ]

    $users = DB::table('users')->whereExists(function($query){
        $query->select('id')->from('reservations')
        ->whereRaw('reservations.user_id = users.id')
        //whereColumn can do what where Raw wants to acheive
        // ->whereColumn('reservations.user_id', 'users.id') 
        ->where('check_in', '=', '2025-12-29')
        ->limit(5);
    })->get();


    dump($users);
});

/**
 * SQL CLAUSE PART 3
 * you can creat a json columb which stores an array and use a specialized method called
 * whereJsonContains to traverse the properties of the column
 */
Route::get('/where-clause-query-three', function(){
    $filterMeta = DB::table('users')
    ->whereJsonContains('meta->settings->site_language', 'en')
    ->whereJsonContains('meta->skills', 'oop')
    ->get();

    dump($filterMeta);
});

/**
 * lesson for pagination 
 * items()
 * provides you only the items with out the bulk of what paginate will retur'
 * you also have simplePaginate()
 */

Route::get('/paginate', function(){
    //  $comments = DB::table('comments')->paginate(3);
    //we also have a simple paginate method
    $comments = DB::table('comments')->simplePaginate(3);
    dump($comments->items());
});

/**
 * FULLtext search was implemeted on a column and was used
 * fulltext search is more effective compared to where
 */
Route::get('/fulltext-search', function(){
    //we create a full text index which would help to easily search our comments column in colum table
    // $results = DB::statement('ALTER TABLE comments ADD FULLTEXT fulltext_index(comments)');
    $word='+qui -est';
    $wordSearch = DB::table('comments')
    ->whereRaw('MATCH(comments) AGAINST (:search IN BOOLEAN MODE)', [$word])->get();

    //this is slower compared to fulltext search 
    $wordSearchTwo = DB::table('comments')->where('comments', 'like', '%qui%')->get();
    dump($wordSearch, $wordSearchTwo);
});

/**
 * RAW SQL STATEMENTS
 */
Route::get('/raw-sql-statements', function(){
    $comments = DB::table('comments')
    //select and DB::raw combination do the same thing with selectRaw 
    // ->select(DB::raw('count(user_id) as number_of_comments, users.name'))
    ->selectRaw('count(user_id) as number_of_comments, users.name')
    ->join('users', 'users.id' ,'=', 'comments.user_id')
    ->groupBy('user_id')
    ->get();

    // other raw queries include
    // whereRaw  /orWhereRaw
    // havingRaw  /orHavingRaw
    //orderByRaw
    //groupByRaw

    $results = DB::table('comments')
    ->orderByRaw('updated_at - created_at DESC')->get();

    //same result diffrent syntax RAW enables you to write RAW SQL Expressions
    $nameLength = DB::table('users')
    ->selectRaw('LENGTH(name) as name_length, name')
    // ->orderByDesc('name_length')
    ->orderByRaw('LENGTH(name) DESC')
    ->get();

    dump($nameLength);
});


/**
 * SQL ORDER, 
 * GROUP BY, - groups thingd together based on what is common
 * SKIP, OFFSET  - do the same thing
 * TAKE, LIMIT - do the same thing
 */
Route::get('/sql-order-skip-take-group-by', function(){
     $users = DB::table('users')
    ->orderBy('name', 'DESC')
    ->pluck('name');

    //other available methods are
    //inRandomOrder(), latest(), first(), limit() 

    $result = DB::table('comments')
    ->selectRaw('count(id) as number_of_five_star_comments, ratings')
    ->groupBy('ratings')
    ->having('ratings' ,'=', 5)
    ->get();

    // $skip = DB::table('comments')
    // ->skip(5)
    // ->take(5)
    // ->get();

    $skip = DB::table('comments')
    ->offset(5)
    ->limit(5)
    ->get();

    dump($skip);
});

