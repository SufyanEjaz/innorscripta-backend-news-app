<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category ? $this->category : '',
            'author' => $this->author,
            'published_at' => !empty($this->published_at) ? $this->published_at : '',
            'source' => $this->source ? $this->source->name : '',
            'description' => $this->description,
            'url' => $this->url,
            'image' => $this->url_to_image,
            'content' => $this->content,
        ];
    }
}