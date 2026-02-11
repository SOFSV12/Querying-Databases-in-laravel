<?php

use App\Models\Post;
use App\Models\Image;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    //fetch post image
    $post = Post::find(1);
    $image = $post->image;

    //fecth parent model, will return either a post or a user instance
    $image = Image::find(1);
    $imageable = $image->imageable;

});
