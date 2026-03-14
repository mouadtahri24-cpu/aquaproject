<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'coach_id' => $this->coach_id,
            'type' => $this->type,
            'type_icon' => $this->type_icon,
            'type_color' => $this->type_color,
            'title' => $this->title,
            'body' => $this->body,
            'preview' => $this->getPreview(100),
            'is_read' => $this->is_read,
            'status_label' => $this->status_label,
            'full_message' => $this->full_message,
            'formatted_date' => $this->formatted_date,
            'user' => new UserResource($this->whenLoaded('user')),
            'coach' => new UserResource($this->whenLoaded('coach')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
