<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\Models\Article $resource
 */
class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title,
            'excerpt' => $this->resource->excerpt,
            'content' => $this->resource->content,
            'created_at' => $this->resource->created_at,
            'favorites_count' => $this->whenCounted('favorites'),
            'favorited' => $this->whenExistsLoaded('favorited'),
            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
