<?php

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user   = User::find(1);
    $number = $user->address->number;
    $street = $user->address->street;

    $add = Address::find(1);
    $userName = $add->user->name;

    $userc = User::find(1);
    $userCom = $userc->comments->toArray();
    //note this is wrong $userc->comments->comments, $userCom returns a collection instance and not just an attribute
    dump($userCom);
});











