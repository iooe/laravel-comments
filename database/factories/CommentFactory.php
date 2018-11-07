<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(config('comments.testing.seeding.commentable'), function (Faker $faker) {

    $user = config('comments.testing.seeding.commenter')::orderByRaw('RAND()')->first();
    $post = config('comments.testing.seeding.commentable')::orderByRaw('RAND()')->first();

    return [
        'commenter_id' => $user->id,
        'commentable_type' => config('comments.testing.seeding.commentable'),
        'commentable_id' => $post->id,
        'comment' => $faker->macAddress,
        'child_id' => null,
    ];
});