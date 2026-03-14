<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerformanceRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isCoach());
    }

    public function rules(): array {
        return [
            'session_id' => 'sometimes|nullable|exists:sessions,id',
            'month' => 'sometimes|date_format:Y-m',
            'time_seconds' => 'sometimes|numeric|min:0.01|max:9999.99',
            'notes' => 'sometimes|nullable|string|max:1000',
            'is_personal_record' => 'sometimes|boolean',
        ];
    }

    public function messages(): array {
        return [
            'session_id.exists' => 'La séance sélectionnée n\'existe pas.',
            'month.date_format' => 'Le mois doit être au format YYYY-MM.',
            'time_seconds.numeric' => 'Le temps doit être un nombre.',
            'time_seconds.min' => 'Le temps doit être supérieur à 0.',
            'notes.max' => 'Les notes ne doivent pas dépasser 1000 caractères.',
        ];
    }
}
