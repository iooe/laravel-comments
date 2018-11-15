<?php

namespace tizis\laraComments\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use tizis\laraComments\Events\CommentCreated;
use tizis\laraComments\Events\CommentDeleted;
use tizis\laraComments\Events\CommentUpdated;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CommentCreated::class,
        'updated' => CommentUpdated::class,
        'deleted' => CommentDeleted::class,
    ];

    protected $with = ['children', 'commenter'];

    protected $dates = ['deleted_at'];

    /**
     * Returns all comments that this comment is the parent of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'child_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeParentless($query)
    {
        return $query->doesntHave('parent');
    }

    /**
     * The user who posted the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commenter()
    {
        return $this->belongsTo(config('comments.commenter'));
    }

    /**
     * The model that was commented upon.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Returns the comment to which this comment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'child_id');
    }
}
