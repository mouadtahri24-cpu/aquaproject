<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SwimmerResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->age,
            'age_category' => $this->age_category,
            'is_minor' => $this->is_minor,
            'status' => $this->status,
            'level' => $this->level,
            'parent' => new UserResource($this->whenLoaded('parent')),
            'group' => new GroupResource($this->whenLoaded('group')),
            'attendance_rate' => round($this->getAttendanceRate(), 2) . '%',
            'current_month_payment' => $this->getCurrentMonthPaymentStatus(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
