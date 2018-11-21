<?php

namespace tizis\laraComments\Contracts;

/**
 * Comment auth policy
 *
 * Interface ICommentPolicy
 * @package tizis\laraComments\Contracts
 */
interface ICommentPolicy
{
    public function edit($user, $comment);

    public function delete($user, $comment);

    public function reply($user, $comment);

    public function vote($user, $comment);
}