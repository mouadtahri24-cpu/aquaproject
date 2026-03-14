<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id' => $this->sender_id,
            'content' => $this->content,
            'is_read' => $this->is_read,
            'read_status' => $this->read_status,
            'formatted_date' => $this->formatted_date,
            'formatted_time' => $this->formatted_time,
            'is_today' => $this->is_today,
            'sender' => new UserResource($this->whenLoaded('sender')),
            'read_at' => $this->read_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
