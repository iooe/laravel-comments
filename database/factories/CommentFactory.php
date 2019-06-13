<?php

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Faker\Generator as Faker;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Entity\Comment::class, function (Faker $faker) {

    $post = Post::orderByRaw('RAND()')->first();
    $user = User::orderByRaw('RAND()')->first();

    $commentableId = $post->id;
    $childId = null;
    if (random_int(0, 1)) {
        $parentComment = Comment::orderByRaw('RAND()')->first();
        $childId = $parentComment->id;
        $commentableId = $parentComment->commentable_id;
    }
    return [
        'commenter_id' => $user->id,
        'commentable_type' => Post::class,
        'commentable_id' => $commentableId,
        'comment' => $faker->text,
        'child_id' => $childId,
    ];
});
