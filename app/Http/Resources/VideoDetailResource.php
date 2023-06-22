<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'thumbnail' => $this->thumbnail,
            'url_video' => $this->url_video,
            'user_id' => $this->user_id,
            'level' => $this->level,
            'duration' => $this->duration,
            'commentable' => $this->commentable,
        ];
    }
}
