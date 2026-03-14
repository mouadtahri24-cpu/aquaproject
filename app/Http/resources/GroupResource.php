<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'schedule_label' => $this->schedule_label,
            'age_category' => $this->age_category,
            'category_label' => $this->category_label,
            'age_range' => $this->age_range,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'monthly_fee' => (float) $this->monthly_fee,
            'is_active' => $this->is_active,
            'coach' => new UserResource($this->whenLoaded('coach')),
            'swimmers_count' => $this->whenLoaded('swimmers', $this->swimmers->count()),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
