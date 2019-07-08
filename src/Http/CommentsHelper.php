<?php

namespace tizis\laraComments\Http;

use Illuminate\Support\Carbon;
use tizis\laraComments\Contracts\Comment as CommentInterface;
use tizis\laraComments\Contracts\ICommentable;
use tizis\laraComments\Http\Resources\CommentResource;
use tizis\laraComments\UseCases\CommentService;

class CommentsHelper
{
    /**
     * Alias to CommentService::getNewestComments
     *
     * @param int $take
     * @param null $commentable_type
     * @return mixed
     */
    public static function getNewestComments(int $take = 10, $commentable_type = null)
    {
        return CommentResource::collection(CommentService::getNewestComments($take, $commentable_type));
    }

    /**
     * Alias to CommentService::getUserRating with cache facade
     *
     * @param int $commenterId
     * @param Carbon|null $cacheTtl
     * @return int
     */
    public static function getCommenterRating(int $commenterId, Carbon $cacheTtl = null): int
    {
        $cacheKey = 'lara-comments:helper:getUserRating:' . $commenterId;

        if (\Cache::has($cacheKey) && $cacheTtl !== null) {
            return (int)\Cache::get($cacheKey);
        }

        $rating = CommentService::getUserRatingWithoutCaching($commenterId);

        if ($cacheTtl !== null) {
            \Cache::put($cacheKey, $rating, $cacheTtl);
        }

        return $rating;
    }

    /**
     * Alias to CommentService::moveCommentTo
     *
     * @param CommentInterface $comment
     * @param ICommentable $newCommentableAssociate
     */
    public static function moveCommentTo(CommentInterface $comment, ICommentable $newCommentableAssociate): void
    {
        CommentService::moveCommentTo($comment, $newCommentableAssociate);
    }
}