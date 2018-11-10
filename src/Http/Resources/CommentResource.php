<?php

namespace tizis\laraComments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            'children' => self::collection($this->children),
            'commenter' => [
                'name' => $this->commenter->name,
                'email' => $this->commenter->email
            ]
        ];
    }
}