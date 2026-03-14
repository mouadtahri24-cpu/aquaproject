<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isCoach());
    }

    public function rules(): array {
        return [
            'session_id' => 'required|exists:sessions,id',
            'swimmer_id' => 'required|exists:swimmers,id',
            'status' => [
                'required',
                Rule::in(['Present', 'Absent', 'Justifie', 'Retard']),
            ],
            'reason' => 'sometimes|nullable|string|max:500',
        ];
    }

    public function messages(): array {
        return [
            'session_id.required' => 'La séance est obligatoire.',
            'session_id.exists' => 'La séance sélectionnée n\'existe pas.',
            'swimmer_id.required' => 'Le nageur est obligatoire.',
            'swimmer_id.exists' => 'Le nageur sélectionné n\'existe pas.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être Present, Absent, Justifie ou Retard.',
            'reason.max' => 'La raison ne doit pas dépasser 500 caractères.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            // Vérifier que le nageur appartient au groupe de la séance
            $session = \App\Models\Session::find($this->session_id);
            $swimmer = \App\Models\Swimmer::find($this->swimmer_id);
            
            if ($session && $swimmer && $session->group_id !== $swimmer->group_id) {
                $validator->errors()->add(
                    'swimmer_id',
                    'Le nageur ne fait pas partie du groupe de cette séance.'
                );
            }
        });
    }
}
