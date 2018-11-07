<?php

namespace tizis\laraComments\Tests\Models;

use tizis\laraComments\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Commentable;

    protected $guarded = [];
}