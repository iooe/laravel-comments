<?php

namespace tizis\laraComments\Events;

use Illuminate\Queue\SerializesModels;
use tizis\laraComments\Entity\Comment;

class CommentUpdated
{
    use SerializesModels;

    public $comment;

    /**
     * CommentUpdated constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
