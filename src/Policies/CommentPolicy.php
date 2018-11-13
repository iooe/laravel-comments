<?php

namespace tizis\laraComments\Policies;

use tizis\laraComments\Contracts\ICommentPolicy;
use tizis\laraComments\Entity\Comment;

class CommentPolicy implements ICommentPolicy
{
    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function delete($user, Comment $comment): bool
    {
        return $user->id === $comment->commenter_id;
    }

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function edit($user, Comment $comment): bool
    {
        return $user->id === $comment->commenter_id;
    }
}