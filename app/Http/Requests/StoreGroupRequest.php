<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'level' => 'required|string|in:Débutant,Intermédiaire,Avancé',
            'schedule_label' => 'sometimes|nullable|string|max:255',
            'coach_id' => 'required|exists:users,id',
            'age_category' => [
                'required',
                Rule::in(['benjamin', 'cadet', 'junior']),
            ],
            'monthly_fee' => 'required|numeric|min:0|max:10000',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'Le nom du groupe est obligatoire.',
            'name.max' => 'Le nom du groupe ne doit pas dépasser 255 caractères.',
            'level.required' => 'Le niveau est obligatoire.',
            'level.in' => 'Le niveau doit être Débutant, Intermédiaire ou Avancé.',
            'coach_id.required' => 'Le coach est obligatoire.',
            'coach_id.exists' => 'Le coach sélectionné n\'existe pas.',
            'age_category.required' => 'La catégorie d\'âge est obligatoire.',
            'age_category.in' => 'La catégorie d\'âge doit être benjamin (6-9), cadet (10-12) ou junior (13-17).',
            'monthly_fee.required' => 'Les frais mensuels sont obligatoires.',
            'monthly_fee.numeric' => 'Les frais doivent être un nombre.',
            'monthly_fee.min' => 'Les frais ne peuvent pas être négatifs.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            // Vérifier que le coach a le rôle 'coach'
            $coach = \App\Models\User::find($this->coach_id);
            if ($coach && !$coach->isCoach()) {
                $validator->errors()->add('coach_id', 'L\'utilisateur sélectionné doit être un coach.');
            }
        });
    }
}
