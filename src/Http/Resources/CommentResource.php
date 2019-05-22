<?php

namespace tizis\laraComments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use tizis\laraComments\Contracts\ICommentPreprocessor;

class CommentResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'comment' => self::comment($this->comment),
            'created_at' => $this->created_at->timestamp,
            'commenter_id' => $this->commenter_id,
            'rating' => $this->rating,
            'commenter' => self::user($this->commenter),
            'children' => self::collection($this->children)
        ];
    }

    /**
     * @param string $comment
     * @return string
     */
    protected static function comment(string $comment): string
    {
        $config = config('comments.api.get.preprocessor.comment');

        if (!\class_exists($config)) {
            return $comment;
        }

        $preprocessor = new $config;

        if ($preprocessor instanceof ICommentPreprocessor) {
            $comment = $preprocessor->process($comment);
        }

        return $comment;
    }

    protected static function user($user)
    {
        $config = config('comments.api.get.preprocessor.user');
        $default = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];

        if (!\class_exists($config)) {
            return $default;
        }

        $preprocessor = new $config;

        if ($preprocessor instanceof ICommentPreprocessor) {
            return $preprocessor->process($user);
        }

        return $default;
    }
}