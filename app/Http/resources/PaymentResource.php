<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'swimmer_id' => $this->swimmer_id,
            'month' => $this->month,
            'amount_expected' => (float) $this->amount_expected,
            'amount_paid' => (float) $this->amount_paid,
            'amount_due' => (float) $this->amount_due,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'percentage_paid' => $this->percentage_paid,
            'is_overdue' => $this->isOverdue(),
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),
            'swimmer' => new SwimmerResource($this->whenLoaded('swimmer')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
