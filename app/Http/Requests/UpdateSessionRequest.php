<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSessionRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'group_id' => 'sometimes|exists:groups,id',
            'coach_id' => 'sometimes|exists:users,id',
            'session_date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'type' => [
                'sometimes',
                Rule::in(['Entrainement', 'Competition']),
            ],
            'objective' => 'sometimes|nullable|string|max:1000',
        ];
    }

    public function messages(): array {
        return [
            'group_id.exists' => 'Le groupe sélectionné n\'existe pas.',
            'coach_id.exists' => 'Le coach sélectionné n\'existe pas.',
            'session_date.date' => 'La date doit être valide.',
            'start_time.date_format' => 'L\'heure de début doit être au format HH:mm.',
            'end_time.date_format' => 'L\'heure de fin doit être au format HH:mm.',
            'end_time.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'type.in' => 'Le type doit être Entrainement ou Competition.',
            'objective.max' => 'L\'objectif ne doit pas dépasser 1000 caractères.',
        ];
    }
}
