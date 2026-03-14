<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'name' => 'sometimes|string|max:255',
            'level' => 'sometimes|string|in:Débutant,Intermédiaire,Avancé',
            'schedule_label' => 'sometimes|nullable|string|max:255',
            'coach_id' => 'sometimes|exists:users,id',
            'age_category' => [
                'sometimes',
                Rule::in(['benjamin', 'cadet', 'junior']),
            ],
            'monthly_fee' => 'sometimes|numeric|min:0|max:10000',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array {
        return [
            'name.max' => 'Le nom du groupe ne doit pas dépasser 255 caractères.',
            'level.in' => 'Le niveau doit être Débutant, Intermédiaire ou Avancé.',
            'coach_id.exists' => 'Le coach sélectionné n\'existe pas.',
            'age_category.in' => 'La catégorie d\'âge doit être benjamin, cadet ou junior.',
            'monthly_fee.numeric' => 'Les frais doivent être un nombre.',
            'monthly_fee.min' => 'Les frais ne peuvent pas être négatifs.',
        ];
    }
}
