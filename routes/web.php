<?php

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    //fetch post Comments
    // $postComments = Post::find(1);
    
    // foreach($postComments->comments as $postComment ){
    //     echo $postComment->body . "<br>";
    // }

 
    // $comment = Comment::find(1);
    
    // //fetches the parent of the individual comment 
    // $commentable = $comment->commentable;

    // dd($commentable);
    
    $posts = Post::with('comments')->get();

    foreach ($posts as $post) {
        foreach ($post->comments as $comment) {
            echo $comment->commentable->title . "<br>" . "<br>";
        }
    } 

});
