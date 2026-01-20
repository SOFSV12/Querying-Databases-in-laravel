<?php

use App\Http\Controllers\testController;
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
Route::get('/eloquent', function() {
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


    //you can also pass an array to the select method
    return User::select(['users.*'])->get();
});


/**
 * Models and helper functions
 * these include some aggregate function
 * and Query Scopes
 */
Route::get('/introduction-to-models', function() {
    //return a single user
        //can accept an array can accept a single id
        // $user = User::find([1,2,3])->toArray();

        //first method
        // $first = User::where('email', 'like', '%@%')->first();

        //firstOr (Sometimes you may wish to perform some other action if no results are found)
        // $firstOr = User::where('email', 'like', '%@yahoo%')->firstOr(
        //     function(){
        //         User::create([
        //             'email'    => 'emmanuelsofuwa@yahoo.com',
        //             'password' => 'mail1223%$',
        //             'name'     => 'Emmanuel Sofuwa'
        //         ]);
        //     }
        // );

        // $findorfail = User::findOrFail(100); //same as firstOrFail()

        //aggreagte functions
        $commentsMax = Comment::max('ratings');// sum(), count(), min()

        //fetch all comments all() and get() same thing
        $commAll = Comment::all();
        $commGet = Comment::get();
        $withoutGlobalScope = Comment::withoutGlobalScope('ratings')->get();
        $localScope = Comment::Rating()->get()->toArray();

        dump($commAll, $commGet, $withoutGlobalScope, $localScope);
});


/**
 * Lesson 2
 * Collections and Additional operations on Eloquent
 */
Route::get('/additional-operations', function() {
    //returns a collection instance
    $result = Comment::all()->toArray();
    //count
    $count = Comment::all()->count(); //Comment::count();
    //retursn data as JSON
    $json = Comment::all()->toJson();

    $fetchComments = Comment::all();

    $reject = $fetchComments->reject(function($comment){
        return $comment->ratings < 4;
    });

    $map = $fetchComments->map(function(object $comment){
      return  $comment->ratings * 5;
    });

    dump($map);
});

/**
 * Lesson 3
 * Inserting data
 */
Route::get('inserts', function() {
    //inserting Records using the new keyword or instanciating the model
    $comment = new Comment();
    $comment->user_id = 2;
    $comment->ratings = 4;
    $comment->comments = "Comment Content";
    $comment->save();


    $methodTwo = Comment::create([
        'user_id' => 1,
        'ratings' => 3,
        'comments' => "Testing"
    ]);

    dump($comment,$methodTwo);
});


/**
 *  UPDATE OPERATIONS
 */
Route::get('updates', function() {
    //updating records
    $update = Comment::find(1)->update(['comments' => "I Love Cheese"]);
    //check docs also

    dump($update);
});

/**
 * DELETE OPERATIONS
 */
Route::get('deletes', function() {
    // $comment = Comment::find('3')->delete();
    // $destroy = Comment::destroy([1,5,7,8]);
    // $conditional = Comment::where('ratings', '>=', 2)->delete();
    // $includesTrashedRecords = Comment::withTrashed()->get();
    // $onlyTrashed = Comment::onlyTrashed()->get();
    // $restore     = Comment::withTrashed()->find(10)->restore();
    //find only works for records which have sofy delete column empty
    $forceDelete = Comment::withTrashed()->find(9)->forceDelete();
    dump($forceDelete);
});


/**
 * Attribute Casting
 */
Route::get('/casts', function() {
    // you can cast using the cast protected function casts() in your model to cast attribute to certain types works just as
    //an accessor, please find another way which this can also be done

    return User::select(['users.*', 'last_commented_at' => Comment::selectRaw('Max(created_at)')
    ->whereColumn('user_id', 'users.id')])->withCasts([
        'last_commented_at' => 'datetime:m-d'
    ])->get();

    //When using a subquery in a SELECT clause, it must return exactly one column and at most one row.
    //same thing
    return User::select([
                'users.*',
                'last_commented_at' => Comment::select('created_at')
                    ->whereColumn('users.id', 'comments.user_id')
                    ->latest()
                    ->take(1),
            ])->withCasts([
                'last_commented_at' => 'datetime:m-d',
            ])->get();

});









