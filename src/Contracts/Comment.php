<?php

namespace tizis\laraComments\Contracts;

interface Comment
{
    /**
     * Returns all comments that this comment is the parent of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children();

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeParentless($query);

    /**
     * The user who posted the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commenter();

    /**
     * The model that was commented upon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable();

    /**
     * Returns the comment to which this comment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent();


    public function rating();

    public function votesCount();

    public function votes();
}
