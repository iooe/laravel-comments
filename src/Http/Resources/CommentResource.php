<?php

namespace tizis\laraComments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'comment' => $this->comment,
            'created_at' => $this->created_at->timestamp,
            'commenter_id' => $this->commenter_id,
            'rating' => $this->rating,
            'commenter' => [
                'id' => $this->commenter->id,
                'avatar' => $this->getAvatar(),
                'name' => $this->commenter->name,
                'email' => $this->commenter->email
            ],
            'children' => self::collection($this->children)
        ];
    }

    protected function getAvatar()
    {
        if (method_exists($this->commenter, 'getAvatar')) {
            return $this->commenter->getAvatar();
        }
        return null;
    }
}