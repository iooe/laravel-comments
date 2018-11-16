<?php

namespace tizis\laraComments\Policies;

use tizis\laraComments\Contracts\ICommentPolicy;
use tizis\laraComments\Entity\Comment;

class CommentPolicy implements ICommentPolicy
{
    /**
     * @param $user
     * @param $comment
     * @return bool
     */
    public function delete($user, $comment): bool
    {
        return $user->id === $comment->commenter_id;
    }

    /**
     * @param $user
     * @param $comment
     * @return bool
     */
    public function edit($user, $comment): bool
    {
        return $user->id === $comment->commenter_id;
    }

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function reply($user, $comment): bool
    {
        return true;
    }

    /**
     * @param $user
     * @param $comment
     * @return bool
     */
    public function vote($user, $comment): bool
    {
        return $user->id !== $comment->commenter_id;
    }
}