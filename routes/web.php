<?php

use App\Models\User;

use App\Models\Room;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;

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
    //returns just the paginated items
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
 * Important note we should not use RAW so much because it could prevent queries from working 
 * if you switch databases but eloquent will always work on diffrent databases
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


/**
 * CONDITIONAL CLAUSES AND CHUNKING Results
 * 
 * You were able to get a grasp of Chunk when and why to use it 
 * Tou were also able to grasp the concept of when satement
 */
Route::get('/condional-clauses-chunking-results', function(){
    $room_id = 2;

   $reservations = DB::table('reservations')
   ->when($room_id, function ($query, $room_id) {
       return $query->where('room_id', $room_id);
   })
   ->get();

   
   $sort = 'room_size';
   $sortby = DB::table('rooms')
   ->when($sort, function ($query, $sort) {
       return $query->orderBy($sort);
   })
   ->get();

   
   //chunking records
    $count = 0;

    // Count comments where user_id = 2
    $chunk = DB::table('comments')
            ->orderBy('id')
            ->chunk(2, function ($comments) use (&$count) {
                foreach ($comments as $comment) {
                    if ($comment->user_id == 1) {
                        $count++;
                    }
                }
                // To stop chunking early (optional):
                // if ($count >= 10) return false;
            });

    $chunky = DB::table('comments')
              ->where('user_id', 1)
              ->orderBy('id')
              ->chunk(2, function ($comments) {
                    $ids = $comments->pluck('id')->toArray();
                    DB::table('comments')
                    ->whereIn('id', $ids)
                    ->update(['comments' => "Updated by Emmanuel Software"]);
                });

   dump($chunky);
});


/**
 * DATABASE JOINS USING LARAVEL QUERY BUILDER
 */

Route::get('/join-clause', function (){

});

/**
 * JOINS BASIC TO ADVANCED
 */
Route::get('/joins-basic-to-advanced', function(){
    $join = DB::table('reservations')
                ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
                ->join('users', 'reservations.user_id', '=', 'users.id')
                //    ->where('rooms.id', '>=', 2)
                //    ->where('users.id', '>', 1)
                //alternate syntax same result
                ->where([['rooms.id', '>=', 2], ['users.id', '>', 1]])
                ->get();

    $altSyntax = DB::table('reservations')
                    ->join('rooms', function($join){
                                $join->on('reservations.room_id', '=', 'rooms.id')
                                ->where('rooms.id', '>=', 2);
                    })
                    ->join('users', function($join){
                                $join->on('reservations.user_id', '=', 'users.id')
                                ->where('users.id', '>', 1);
                    })
                    ->get();

    $rooms = DB::table('rooms')
                ->where('id', '>=', 2);
    $users = DB::table('users')
                ->where('id', '>', 1);
    $diffSyntax = DB::table('reservations')
                    ->joinSub($rooms, 'rooms', function($join){
                        $join->on('reservations.room_id', '=', 'rooms.id');
                    })
                    ->joinSub($users, 'users', function($join){
                        $join->on('reservations.user_id', '=', 'users.id');
                    })
                    ->get();

        $leftJoin = DB::table('rooms')
                ->leftJoin('reservations', 'rooms.id', '=', 'reservations.room_id')
                ->leftJoin('cities', 'reservations.city_id', '=', 'cities.id')
                ->selectRaw('room_size, price ,count(reservations.id) as reservations_count, cities.cities')
                ->groupBy('room_size', 'price', 'cities.cities')
                ->orderByRaw('count(reservations.id) DESC')
                ->get();

        $crossJoin = DB::table('rooms')
                    ->crossJoin('cities')
                    ->leftJoin('reservations', function($join){
                        $join->on('rooms.id', '=', 'reservations.room_id')
                        ->on('cities.id', '=', 'reservations.city_id'); 
                    })
                    ->selectRaw('room_size, COUNT(reservations.id) as reservation_count, cities.cities')
                    ->groupBy('rooms.room_size','cities.cities')
                    ->orderByRaw('room_size DESC')
                    ->get();

    dump($crossJoin);
});

/**
 * UNIONS
 * These combine the results set of the result set of multiple tables 
 */
Route::get('/union', function (){
    $users   = DB::table('users')
               ->select('name');

    $results = DB::table('cities')
              ->select('cities')
              ->union($users)
              ->get();

    $comments = DB::table('comments')
                ->select('ratings as rating_or_room_id', 'id', DB::raw('"comments" as type_of_activity'))
                ->where('user_id', 2);
    
    $reservations = DB::table('reservations')
                ->select('room_id as rating_or_room_id', 'id', DB::raw('"reservations" as type_of_activity'))
                ->union($comments)
                ->where('user_id', 2)
                ->get();

        $orders = DB::table('orders')
                    ->select('client_id', 'order_date as date', 'order_amount as amount', DB::raw('"order" as type'))
                    ->where('user_id', 2);

        $reunds = DB::table('refunds')
                ->select('client_id', 'refund_date as date', 'refund_amount as amount', DB::raw('"refund" as type'))
                ->union($comments)
                ->where('user_id', 2)
                ->get();

    dump($reservations);
});

/**
 * UPDATING RECORDS
 * used the update helper, increments, decrements, 
 */
Route::get('/updating-records', function(){
     $insert = DB::table('comments')
              ->insert([
                ['comments' => 'test created by me', 'ratings' => 5, 'user_id' => 3, 'created_at' => now(), 'updated_at' => now()->addSeconds(12000)],
                ['comments' => 'test created by mandrakes', 'ratings' => 3, 'user_id' => 2, 'created_at' => now(), 'updated_at' => now()->addSeconds(180000)]
              ]);

    // $id = DB::table('users')->insertGetId(
    //       ['email' => 'dexter@cartoonnetwork.com', 'name' => 'Dexter Labs', 'created_at' => now(), 'updated_at' => now()->addSeconds(180000), 'password' => 'HoYGhost']
    //       );

    // $affected = DB::table('users')
    //             ->where('id', 6)
    //             ->update(['name' => 'Dexter Laboratory']);

    // $affected = DB::table('users')
    //             ->where('id', 1)
    //             ->update(['meta->settings->site_language' => 'fn']);

    // $affected = DB::table('rooms')
    //             ->increment('price', 100);

    $affected = DB::table('rooms')
                ->decrement('price', 200, ['description' => 'test description']);
});

/**
 * DELETING RECORDS
 * truncate set the table to start from ID of one and delete does not affect the table
 */
Route::get('/', function(){
     $deleted = DB::table('cities')->delete();
    //resets the database table to 
    // $deleted = DB::table('cities')->truncate();
    // $deleted = DB::table('users')->where('votes', '>', 100)->delete();

    dump($deleted);
});

/**
 * PESSIMISTIC LOCKING AND SHARED LOCK
 * lockForUpdate method. A "for update" lock prevents the selected records from being modified or from being selected with another shared lock:
 * A shared lock prevents the selected rows from being modified until your transaction is committed:
 */
Route::get('/pessimistic-locking', function(){
    $sharedLock = DB::table('users')
    ->where('votes', '>', 100)
    ->sharedLock()
    ->get();

    $lockforUpdate = DB::table('users')
    ->where('votes', '>', 100)
    ->lockForUpdate()
    ->get();
})
