<?php

namespace tizis\laraComments\UseCases;

use DB;
use tizis\laraComments\Contracts\Comment as CommentInterface;
use tizis\laraComments\Contracts\Vote;

class VoteService
{
    /**
     * @param $user
     * @param CommentInterface $comment
     * @param int $vote
     * @throws \Throwable
     */
    public function make($user, CommentInterface $comment, int $vote): void
    {
        DB::transaction(function () use ($user, $comment, $vote) {
            $oldVoteEntity = $comment->votes()->where('commenter_id', $user->id)->first();

            if (!$oldVoteEntity) {
                $this->store($comment, $user, $vote);
                return;
            }

            if ($this->isUselessValue($oldVoteEntity->commenter_vote, $vote)) {
                $this->remove($oldVoteEntity);
                return;
            }

            $this->update($oldVoteEntity, $vote);
        });
    }

    /**
     * @param $oldVote
     * @param int $newVote
     * @return bool
     */
    private function isUselessValue($oldVote, int $newVote): bool
    {
        return ($oldVote === 0 && $newVote === 1) || ($oldVote === 1 && $newVote === 0);
    }

    /**
     * @param Vote $vote
     * @throws \Exception
     */
    private function remove(Vote $vote): void
    {
        $vote->delete();
    }

    /**
     * @param Vote $vote
     * @param int $updatedVote
     */
    private function update(Vote $vote, int $updatedVote): void
    {
        $vote->updateCommenterVote($updatedVote);
    }

    /**
     * @param Comment $comment
     * @param $user
     * @param int $vote
     */
    private function store(CommentInterface $comment, $user, int $vote): void
    {
        $comment->addNewVoteIntoRatingRecords($user->id, $vote);
    }
}
