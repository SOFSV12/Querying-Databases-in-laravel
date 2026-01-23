<?php

use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $country = Country::find(2);

    //using All Addresses provides us with all the matching addresses of the user
    $addresses = $country->allAddresses();
    dump($addresses);

    // below is a more manual to fetch the user information
    // $user_id =[];
    // foreach($addresses as $address){
    //     // echo $address->user_id ." ". "<br/>";
    //     $user_id[] = $address->user_id;

    // }
    // $users = User::whereIn('id', $user_id)->get();
    // dump($users->toArray());


    foreach($addresses as $address) {
        echo "Address: " . $address->street . "<br/>";
        echo "User: " . $address->user->name . "<br/>";  // ← User data attached!
        echo "Email: " . $address->user->email . "<br/>";
    }


    //has One through will only fetch you the first instance of that has one through relationship
    echo "<br/>";
    echo "<br/>";
    echo $country->addressOwner->street ." ". "<br/>"; // fetch address
    echo $country->addressOwner->user->name; // fetch user with this address
    // return view('welcome');
});
