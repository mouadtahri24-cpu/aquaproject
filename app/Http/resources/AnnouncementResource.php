<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'type_icon' => $this->type_icon,
            'type_color' => $this->type_color,
            'is_published' => $this->is_published,
            'is_active' => $this->is_active,
            'is_expired' => $this->is_expired,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'formatted_date' => $this->formatted_date,
            'expires_at' => $this->expires_at?->format('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
