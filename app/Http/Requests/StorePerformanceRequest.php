<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isCoach());
    }

    public function rules(): array {
        return [
            'swimmer_id' => 'required|exists:swimmers,id',
            'event_id' => 'required|exists:events,id',
            'session_id' => 'sometimes|nullable|exists:sessions,id',
            'month' => 'required|date_format:Y-m',
            'time_seconds' => 'required|numeric|min:0.01|max:9999.99',
            'notes' => 'sometimes|nullable|string|max:1000',
            'is_personal_record' => 'sometimes|boolean',
        ];
    }

    public function messages(): array {
        return [
            'swimmer_id.required' => 'Le nageur est obligatoire.',
            'swimmer_id.exists' => 'Le nageur sélectionné n\'existe pas.',
            'event_id.required' => 'L\'épreuve est obligatoire.',
            'event_id.exists' => 'L\'épreuve sélectionnée n\'existe pas.',
            'session_id.exists' => 'La séance sélectionnée n\'existe pas.',
            'month.required' => 'Le mois est obligatoire.',
            'month.date_format' => 'Le mois doit être au format YYYY-MM.',
            'time_seconds.required' => 'Le temps est obligatoire.',
            'time_seconds.numeric' => 'Le temps doit être un nombre.',
            'time_seconds.min' => 'Le temps doit être supérieur à 0.',
            'notes.max' => 'Les notes ne doivent pas dépasser 1000 caractères.',
        ];
    }
}
