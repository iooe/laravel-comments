<?php

namespace tizis\laraComments\Contracts;

use tizis\laraComments\Entity\Comment;

/**
 * Comment auth policy
 *
 * Interface ICommentPolicy
 * @package tizis\laraComments\Contracts
 */
interface ICommentPolicy
{
    public function edit($user, Comment $comment);

    public function delete($user, Comment $comment);

    public function reply($user, Comment $comment);
}