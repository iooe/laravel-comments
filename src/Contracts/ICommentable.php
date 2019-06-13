<?php

namespace tizis\laraComments\Contracts;

use Illuminate\Database\Eloquent\Builder;
use tizis\laraComments\Entity\Comment;

/**
 * Commentable model Interface
 *
 * Interface ICommentable
 * @package tizis\laraComments\Contracts
 */
interface ICommentable
{
    public function isCommentable();

    public function comments();

    public function scopeWithCommentsCount(Builder $query);

}