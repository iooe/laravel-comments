<?php

namespace tizis\laraComments\Tests\Models;

use tizis\laraComments\Traits\Commenter;
use Illuminate\Foundation\Auth\User;

class SomeUser extends User
{
    use Commenter;

    protected $table = 'users';
}