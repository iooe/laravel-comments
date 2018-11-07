<?php
//namespace tizis\laraComments\Database\Seeds;

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DemoSeeder extends Seeder
{
    public function run()
    {
         factory(config('comments.testing.seeding.commentable'), 50)->create()->each(function (Faker $faker) {
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
    }
}