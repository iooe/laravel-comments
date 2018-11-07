php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=views



<?php

namespace App\Entity;

use tizis\laraComments\Entity\Comment as laraComment;

class Comment extends laraComment
{
