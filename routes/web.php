<?php

use App\Models\User;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    //using default db connection set in .env
    // $user   = DB::select('select * from users where id = ?', [2]);
    //using sqlite for db connection
    // $users  = DB::connection('sqlite')->select("select * from users");

    // dump($user, $users);

    //example of using DB facade for quering database 
    $user  = DB::select('select * from users where name like ?', ["%Hilpert%"]);
    $users = DB::select('select * from users where id = ? and name = ?', [2, "Maybell Fisher"]);
    //where we do the same thing but we bind it using the parameters we are searching for
    $userDiffSyntax = DB::select('select * from users where id = :identifier and name = :username', ['identifier' => 3, 'username' => "Maybell Fisher"]);

    // dump($userDiffSyntax);

    //inserting a record 
    // $userInsrt = DB::insert('insert into users (name,email,password) values (?,?,?)', ['Emmanuel Software', 'emmanuelsofuwa2@gmail.com', 'skiiii']);

    //update a record using DB facade
    // $userUp    = DB::update('update users set email = "emmanuelsofuwa2@update.com" where email = :email', ['email' => 'emmanuelsofuwa2@gmail.com']);

    //delete a record 
    // $delUser   = DB::delete('delete from users where email =:email' , ['email' => 'emmanuelsofuwa2@update.com']);

    // $userInsrt = DB::insert('insert into users (name,email,password) values (:name,:email,:password)', ['name' =>'Emmanuel\'s cheese', 'email' => 'emmanuelsofuwa2@gmail.com', 'password' => 'skiiii']);

    //eloquent orm returns a collection
    $orm = User::all();

    //Query Builder returns a collection
    $builder = DB::table('users')->select('name','email')->get();

    //DB Facade does not return a collection
    $facade = DB::select('select * from users');

    dump($orm, $builder, $facade);
});

