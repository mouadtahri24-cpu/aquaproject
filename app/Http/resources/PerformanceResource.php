<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerformanceResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'swimmer_id' => $this->swimmer_id,
            'event_id' => $this->event_id,
            'session_id' => $this->session_id,
            'month' => $this->month,
            'time_seconds' => (float) $this->time_seconds,
            'formatted_time' => $this->formatted_time,
            'is_personal_record' => $this->is_personal_record,
            'record_label' => $this->record_label,
            'notes' => $this->notes,
            'progression' => $this->progression,
            'swimmer' => new SwimmerResource($this->whenLoaded('swimmer')),
            'event' => new EventResource($this->whenLoaded('event')),
            'session' => new SessionResource($this->whenLoaded('session')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
