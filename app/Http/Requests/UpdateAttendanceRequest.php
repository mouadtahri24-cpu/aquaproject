<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isCoach());
    }

    public function rules(): array {
        return [
            'status' => [
                'sometimes',
                Rule::in(['Present', 'Absent', 'Justifie', 'Retard']),
            ],
            'reason' => 'sometimes|nullable|string|max:500',
        ];
    }

    public function messages(): array {
        return [
            'status.in' => 'Le statut doit être Present, Absent, Justifie ou Retard.',
            'reason.max' => 'La raison ne doit pas dépasser 500 caractères.',
        ];
    }
}
