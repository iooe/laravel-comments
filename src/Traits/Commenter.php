<?php

namespace tizis\laraComments\Traits;

use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Entity\CommentVotes;

/**
 * Add this trait to your User model so
 * that you can retrieve the comments for a user.
 */
trait Commenter
{
    /**
     * Returns all comments that this user has made.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'commenter_id');
    }

    /**
     * Returns all comments that this user has made with recursion and commenter eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentsWithChildrenAndCommenter(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // 'allChildrenWithCommenter.commenter' needs for eager loading of first level Comment::class
        return $this->hasMany(Comment::class, 'commenter_id')
            ->with('allChildrenWithCommenter', 'allChildrenWithCommenter.commenter', 'commenter');
    }

    /**
     * Return all commenter votes relations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentsVotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommentVotes::class, 'commenter_id');
    }
}
