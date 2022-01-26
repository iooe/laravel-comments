<?php

namespace tizis\laraComments\Contracts;

interface Vote
{
    public function commenter();
    public function updateCommenterVote($updatedVote):void;
}
