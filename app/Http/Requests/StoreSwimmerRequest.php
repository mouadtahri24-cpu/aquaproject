<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreSwimmerRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(6)->toDateString(), // Minimum 6 ans
                'after_or_equal:' . now()->subYears(18)->toDateString(),  // Maximum 17 ans (mineur)
            ],
            'parent_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
            'level' => 'sometimes|string|in:Débutant,Intermédiaire,Avancé',
            'status' => 'sometimes|string|in:active,inactive,suspended',
        ];
    }

    public function messages(): array {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'birth_date.required' => 'La date de naissance est obligatoire.',
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'birth_date.before_or_equal' => 'Le nageur doit avoir au moins 6 ans.',
            'birth_date.after_or_equal' => 'Le nageur doit être mineur (moins de 18 ans).',
            'parent_id.required' => 'Le parent est obligatoire.',
            'parent_id.exists' => 'Le parent sélectionné n\'existe pas.',
            'group_id.required' => 'Le groupe est obligatoire.',
            'group_id.exists' => 'Le groupe sélectionné n\'existe pas.',
            'level.in' => 'Le niveau doit être Débutant, Intermédiaire ou Avancé.',
            'status.in' => 'Le statut doit être active, inactive ou suspended.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            // Vérifier que le parent existe et a le rôle 'parent'
            $parent = \App\Models\User::find($this->parent_id);
            if ($parent && !$parent->isParent()) {
                $validator->errors()->add('parent_id', 'L\'utilisateur sélectionné doit être un parent.');
            }

            // Vérifier que le groupe a la bonne catégorie d'âge
            $group = \App\Models\Group::find($this->group_id);
            if ($group) {
                $swimmerAge = Carbon::parse($this->birth_date)->age;
                if ($swimmerAge < $group->min_age || $swimmerAge > $group->max_age) {
                    $validator->errors()->add(
                        'group_id',
                        "L'âge du nageur ({$swimmerAge} ans) ne correspond pas à la catégorie du groupe ({$group->category_label})."
                    );
                }
            }
        });
    }
}
