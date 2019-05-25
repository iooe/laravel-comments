<?php

namespace tizis\laraComments\UseCases;

use DB;
use tizis\laraComments\Contracts\Comment as CommentInterface;
use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Entity\CommentVotes;

class VoteService
{
    /**
     * @param $user
     * @param Comment $comment
     * @param int $vote
     * @throws \Throwable
     */
    public function make($user, CommentInterface $comment, int $vote): void
    {
        DB::transaction(function () use ($user, $comment, $vote) {
            $oldVoteEntity = $comment->votes()->where('commenter_id', $user->id)->first();

            if ($oldVoteEntity) {
                $offset = $this->offset($oldVoteEntity->commenter_vote, $vote);

                if ($oldVoteEntity && $offset) {
                    $this->remove($oldVoteEntity);
                }

                if ($oldVoteEntity && !$offset) {
                    $this->update($oldVoteEntity, $vote);
                }

            } else {
                $this->store($comment, $user, $vote);
            }

        });
    }

    /**
     * @param $oldVote
     * @param int $newVote
     * @return bool
     */
    private function offset($oldVote, int $newVote): bool
    {
        return ($oldVote === 0 && $newVote === 1) || ($oldVote === 1 && $newVote === 0);
    }

    /**
     * @param CommentVotes $vote
     * @throws \Exception
     */
    private function remove(CommentVotes $vote): void
    {
        $vote->delete();
    }

    /**
     * @param CommentVotes $vote
     * @param int $updatedVote
     */
    private function update(CommentVotes $vote, int $updatedVote): void
    {
        $vote->update([
            'commenter_vote' => $updatedVote
        ]);
    }

    /**
     * @param Comment $comment
     * @param $user
     * @param int $vote
     */
    private function store(CommentInterface $comment, $user, int $vote): void
    {
        $comment->votes()->save(new CommentVotes([
                'commenter_id' => $user->id,
                'commenter_vote' => $vote
            ]
        ));
    }
}
