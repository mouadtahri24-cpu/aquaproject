<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource {
    public function toArray(Request $request): array {
        $currentUser = auth()->user();
        
        return [
            'id' => $this->id,
            'participant_a_id' => $this->participant_a_id,
            'participant_b_id' => $this->participant_b_id,
            'is_parent_to_parent' => $this->is_parent_to_parent,
            'other_participant' => new UserResource($this->getOtherParticipant($currentUser->id)),
            'participant_a' => new UserResource($this->whenLoaded('participantA')),
            'participant_b' => new UserResource($this->whenLoaded('participantB')),
            'last_message' => $this->last_message ? new MessageResource($this->last_message) : null,
            'last_message_date' => $this->formatted_last_message_date,
            'unread_count' => $this->getUnreadCountAttribute($currentUser->id),
            'messages_count' => $this->whenLoaded('messages', $this->messages->count()),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
