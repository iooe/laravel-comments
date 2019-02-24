<?php

namespace tizis\laraComments\UseCases;

use tizis\laraComments\Contracts\ICommentable;
use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Http\Requests\GetRequest;

class CommentService
{
    /**
     * @param int $take
     * @param null $commentable_type
     * @return mixed
     */
    public static function getNewestComments($take = 10, $commentable_type = null)
    {
        return Comment::take($take)
            ->when($commentable_type !== null, function ($q) use ($commentable_type) {
                return $q->where('commentable_type', $commentable_type);
            })
            ->with(['commentable'])
            ->orderBy('id', 'desc')
            ->get();
    }

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
     * @param $modelPath
     * @return bool
     */
    public static function modelIsExists($modelPath): bool
    {
        return class_exists($modelPath);
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
     * @param GetRequest $request
     * @return array
     */
    public static function orderByRequestAdapter(GetRequest $request): array
    {
        $order_direction = $request->order_direction === 'asc' || $request->order_direction === 'desc'
            ? $request->order_direction : 'asc';

        $order_by = $request->order_by === 'rating' ? $request->order_by : 'id';

        return ['column' => $order_by, 'direction' => $order_direction];
    }


    /**
     * @param string $message
     * @return Comment
     */
    public static function updateComment(Comment $comment, string $message): Comment
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
    public static function createComment($user, ICommentable $model, string $message, $parent = null): Comment
    {

        $comment = new Comment();
        $comment->commenter()->associate($user);
        $comment->commentable()->associate($model);

        if ($parent !== null) {
            $comment->parent()->associate($parent);
        }

        $comment->rating = 0;
        $comment->comment = $message;
        $comment->save();

        return $comment;
    }

    /**
     * @param Comment $comment
     * @throws \Exception
     */
    public static function deleteComment(Comment $comment): void
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
    public static function ratingRecalculation(Comment $comment): int
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
