<?php

namespace tizis\laraComments\Events;

use Illuminate\Queue\SerializesModels;
use tizis\laraComments\Entity\Comment;

class CommentCreated
{
    use SerializesModels;

    public $comment;

    /**
     * CommentCreated constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
