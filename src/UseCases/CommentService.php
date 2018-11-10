<?php

namespace tizis\laraComments\UseCases;

use tizis\laraComments\Contracts\ICommentable;

class CommentService
{
    public static function htmlFilter($message) {
        return  clean($message, ['HTML.Allowed' => config('comments.purifier.HTML_Allowed')]);
    }

    public static function classExists($class): bool
    {
        return class_exists($class);
    }

    public static function isCommentable($model): bool
    {
        return $model instanceof ICommentable;
    }
}
