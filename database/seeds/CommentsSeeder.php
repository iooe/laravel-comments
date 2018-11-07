<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

use App\Entity\Comment;

class CommentsSeeder extends Seeder
{
    public function run()
    {
        factory(Comment::class, 10)->create()->each(function(Comment $comment) {

        });
    }
}