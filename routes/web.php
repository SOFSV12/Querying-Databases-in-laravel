<?php

use App\Models\Country;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $country = Country::find(2);


    /**
     * using the relationships setup between country and user, user and comments
     * check Country model for comments method
     */

    dump($country->comments->toArray());

    /**
     * check comment method for regular setup if relationships do not exist
    */
    foreach($country->comments as $comment)
    {
            echo $comment->title . "<br> " . $comment->text . "<br> " . $comment->user_id . "<br> <br> ";
    }

    //fetching all comments that belong to a country
    dump($country->userComments());

    foreach($country->userComments() as $comment)
    {
        echo "username   :  " . $comment->user->name . "<br> <br> ";
    }
});
