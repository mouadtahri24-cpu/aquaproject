<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSessionRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'group_id' => 'required|exists:groups,id',
            'coach_id' => 'required|exists:users,id',
            'session_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => [
                'required',
                Rule::in(['Entrainement', 'Competition']),
            ],
            'objective' => 'sometimes|nullable|string|max:1000',
        ];
    }

    public function messages(): array {
        return [
            'group_id.required' => 'Le groupe est obligatoire.',
            'group_id.exists' => 'Le groupe sélectionné n\'existe pas.',
            'coach_id.required' => 'Le coach est obligatoire.',
            'coach_id.exists' => 'Le coach sélectionné n\'existe pas.',
            'session_date.required' => 'La date de la séance est obligatoire.',
            'session_date.date' => 'La date doit être valide.',
            'session_date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'start_time.required' => 'L\'heure de début est obligatoire.',
            'start_time.date_format' => 'L\'heure de début doit être au format HH:mm.',
            'end_time.required' => 'L\'heure de fin est obligatoire.',
            'end_time.date_format' => 'L\'heure de fin doit être au format HH:mm.',
            'end_time.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'type.required' => 'Le type de séance est obligatoire.',
            'type.in' => 'Le type doit être Entrainement ou Competition.',
            'objective.max' => 'L\'objectif ne doit pas dépasser 1000 caractères.',
        ];
    }
}
