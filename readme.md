
add trait and implements interface

use tizis\laraComments\Contracts\ICommentable;
use tizis\laraComments\Traits\Commentable;

class Ranobe extends Model implements ICommentable
{
    use Commentable;


get views

php artisan vendor:publish --provider="tizis\laraComments\Providers\ServiceProvider" --tag=views


create model
<?php

namespace App\Entity;

use tizis\laraComments\Entity\Comment as laraComment;

class Comment extends laraComment
{
