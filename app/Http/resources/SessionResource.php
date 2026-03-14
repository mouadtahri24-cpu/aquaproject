<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'coach_id' => $this->coach_id,
            'session_date' => $this->session_date?->format('Y-m-d'),
            'formatted_date' => $this->formatted_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'formatted_time' => $this->formatted_time,
            'type' => $this->type,
            'objective' => $this->objective,
            'attendance_rate' => round($this->getAttendanceRate(), 2) . '%',
            'present_count' => $this->getPresentCount(),
            'absent_count' => $this->getAbsentCount(),
            'group' => new GroupResource($this->whenLoaded('group')),
            'coach' => new UserResource($this->whenLoaded('coach')),
            'attendances_count' => $this->whenLoaded('attendances', $this->attendances->count()),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
