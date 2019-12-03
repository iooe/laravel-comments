<?php

namespace tizis\laraComments\Contracts;

use Illuminate\Database\Eloquent\Builder;

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