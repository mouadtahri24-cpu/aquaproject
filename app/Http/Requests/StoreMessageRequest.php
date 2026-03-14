<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check();
    }

    public function rules(): array {
        return [
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|min:1|max:2000',
        ];
    }

    public function messages(): array {
        return [
            'conversation_id.required' => 'La conversation est obligatoire.',
            'conversation_id.exists' => 'La conversation sélectionnée n\'existe pas.',
            'content.required' => 'Le message ne peut pas être vide.',
            'content.min' => 'Le message doit contenir au moins 1 caractère.',
            'content.max' => 'Le message ne doit pas dépasser 2000 caractères.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $conversation = \App\Models\Conversation::find($this->conversation_id);
            $currentUser = auth()->user();

            // Vérifier que l'utilisateur est participant à la conversation
            if ($conversation && !$conversation->hasUser($currentUser->id)) {
                $validator->errors()->add(
                    'conversation_id',
                    'Vous ne pouvez pas envoyer de messages dans cette conversation.'
                );
            }
        });
    }
}
