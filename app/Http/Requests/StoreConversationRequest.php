<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check();
    }

    public function rules(): array {
        return [
            'participant_b_id' => 'required|exists:users,id|different:id',
        ];
    }

    public function messages(): array {
        return [
            'participant_b_id.required' => 'L\'autre participant est obligatoire.',
            'participant_b_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
            'participant_b_id.different' => 'Vous ne pouvez pas converser avec vous-même.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $currentUser = auth()->user();
            $otherUser = \App\Models\User::find($this->participant_b_id);
            
            // Interdire les conversations Parent-Parent
            if ($currentUser->isParent() && $otherUser && $otherUser->isParent()) {
                $validator->errors()->add(
                    'participant_b_id',
                    'Les parents ne peuvent pas converser entre eux. Veuillez contacter un administrateur ou un coach.'
                );
            }

            // Vérifier si une conversation existe déjà
            $existingConversation = \App\Models\Conversation::where(function ($q) use ($currentUser, $otherUser) {
                $q->where([
                    'participant_a_id' => $currentUser->id,
                    'participant_b_id' => $otherUser->id,
                ])->orWhere([
                    'participant_a_id' => $otherUser->id,
                    'participant_b_id' => $currentUser->id,
                ]);
            })->first();

            if ($existingConversation) {
                $validator->errors()->add(
                    'participant_b_id',
                    'Une conversation existe déjà avec cet utilisateur.'
                );
            }
        });
    }
}
