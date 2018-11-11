<?php

namespace tizis\laraComments\UseCases;

use tizis\laraComments\Contracts\ICommentable;

class CommentService
{
    public static function htmlFilter($message) {
        $message = '<p>'.str_replace("\n", '</p><p>', $message).'</p>';
        return  clean($message, [
            'HTML.Allowed' => config('comments.purifier.HTML_Allowed'),
            'AutoFormat.RemoveEmpty' => true
        ]);
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
