<?php

namespace tizis\laraComments\UseCases;

use tizis\laraComments\Contracts\ICommentable;
use tizis\laraComments\Entity\Comment;

class CommentService
{

    /**
     * @param $message
     * @return mixed
     */
    public static function htmlFilter($message)
    {
        $message = '<p>' . str_replace("\n", '</p><p>', $message) . '</p>';
        return clean($message, [
            'HTML.Allowed' => config('comments.purifier.HTML_Allowed'),
            'AutoFormat.RemoveEmpty' => true
        ]);
    }

    /**
     * @param $model
     * @return bool
     */
    public static function isCommentable($model): bool
    {
        return $model instanceof ICommentable;
    }

    /**
     * @param string $message
     * @return Comment
     */
    public function updateComment(Comment $comment, string $message): Comment
    {
        $comment->update([
            'comment' => $message
        ]);

        return $comment;
    }

    /**
     * @param $user
     * @param ICommentable $model
     * @param string $message
     * @param null $parent
     * @return Comment
     */
    public function createComment($user, ICommentable $model, string $message, $parent = null): Comment
    {

        $comment = new Comment();
        $comment->commenter()->associate($user);
        $comment->commentable()->associate($model);

        if ($parent !== null) {
            $comment->parent()->associate($parent);
        }

        $comment->comment = $message;
        $comment->save();

        return $comment;
    }

    /**
     * @param Comment $comment
     * @throws \Exception
     */
    public function deleteComment(Comment $comment): void
    {
        if (!$comment->children()->exists()) {
            $comment->delete();
        } else {
            throw new \DomainException('Comment has replies');
        }
    }

    /**
     * @param Comment $comment
     * @return int
     */
    public function ratingRecalculation(Comment $comment): int
    {
        $rating = 0;
        foreach ($comment->votes as $vote) {
            $rating = $vote->commenter_vote === 0 ? $rating - 1 : $rating + 1;
        }
        $comment->rating = $rating;
        $comment->save();
        return $rating;
    }
}
