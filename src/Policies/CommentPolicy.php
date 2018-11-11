<?php

namespace tizis\laraComments\Policies;

use tizis\laraComments\Entity\Comment;

class CommentPolicy
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

    /**
     * @param $user
     * @param Comment $comment
     * @return bool
     */
    public function reply($user, Comment $comment): bool
    {
        return true;
        //return $user->id !== $comment->commenter_id;
    }
}