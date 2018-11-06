<?php

namespace tizis\laraComments\Events;

use Illuminate\Queue\SerializesModels;
use tizis\laraComments\Entity\Comment;

class CommentDeleted
{
    use SerializesModels;

    public $comment;

    /**
     * CommentDeleted constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
