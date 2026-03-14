<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateSwimmerRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'birth_date' => 'sometimes|date|before_or_equal:' . now()->subYears(6)->toDateString(),
            'parent_id' => 'sometimes|exists:users,id',
            'group_id' => 'sometimes|exists:groups,id',
            'level' => 'sometimes|string|in:Débutant,Intermédiaire,Avancé',
            'status' => 'sometimes|string|in:active,inactive,suspended',
        ];
    }

    public function messages(): array {
        return [
            'first_name.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
            'last_name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'parent_id.exists' => 'Le parent sélectionné n\'existe pas.',
            'group_id.exists' => 'Le groupe sélectionné n\'existe pas.',
            'level.in' => 'Le niveau doit être Débutant, Intermédiaire ou Avancé.',
            'status.in' => 'Le statut doit être active, inactive ou suspended.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            if ($this->has('group_id')) {
                $swimmer = $this->route('swimmer');
                $group = \App\Models\Group::find($this->group_id);
                if ($group) {
                    $swimmerAge = $swimmer->age;
                    if ($swimmerAge < $group->min_age || $swimmerAge > $group->max_age) {
                        $validator->errors()->add(
                            'group_id',
                            "L'âge du nageur ({$swimmerAge} ans) ne correspond pas à la catégorie du groupe."
                        );
                    }
                }
            }
        });
    }
}
