<?php

namespace tizis\laraComments\Contracts;

use tizis\laraComments\Entity\Comment;

interface ICommentPolicy
{
    public function edit($user, Comment $comment);
    public function delete($user, Comment $comment);
}