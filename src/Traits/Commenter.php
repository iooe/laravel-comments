<?php

namespace tizis\laraComments\Traits;

use tizis\laraComments\Entity\Comment;

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
    public function comments()
    {
        return $this->hasMany(Comment::class, 'commenter_id');
    }

    /**
     * Returns all comments that this user has made with recursion and commenter eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentsWithChildrenAndCommenter()
    {
        return $this->hasMany(Comment::class, 'commenter_id')
            ->with(['allChildren']);
    }
}
