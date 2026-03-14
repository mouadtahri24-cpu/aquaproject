<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'swimmer_id' => $this->swimmer_id,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'is_absent' => $this->is_absent,
            'is_present' => $this->is_present,
            'reason' => $this->reason,
            'swimmer' => new SwimmerResource($this->whenLoaded('swimmer')),
            'session' => new SessionResource($this->whenLoaded('session')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
