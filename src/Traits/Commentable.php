<?php

namespace tizis\laraComments\Traits;

use tizis\laraComments\Entity\Comment;
use Illuminate\Database\Eloquent\Builder;

/**
 * Add this trait to any model that you want to be able to
 * comment upon or get comments for.
 */
trait Commentable
{
    /**
     * @return bool
     */

    public function isCommentable(): bool
    {
        return true;
    }

    /**
     * Returns all comments for this model.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @param Builder $query
     */
    public function scopeWithCommentsCount(Builder $query)
    {
        return $query->withCount('comments');
    }
}
