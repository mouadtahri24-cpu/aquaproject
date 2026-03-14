<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnnouncementRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isCoach());
    }

    public function rules(): array {
        return [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|min:10|max:5000',
            'type' => [
                'sometimes',
                Rule::in(['info', 'urgent', 'event', 'announcement']),
            ],
            'is_published' => 'sometimes|boolean',
            'expires_at' => 'sometimes|nullable|date|after:now',
        ];
    }

    public function messages(): array {
        return [
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'content.min' => 'Le contenu doit contenir au moins 10 caractères.',
            'content.max' => 'Le contenu ne doit pas dépasser 5000 caractères.',
            'type.in' => 'Le type doit être info, urgent, event ou announcement.',
            'expires_at.date' => 'La date d\'expiration doit être valide.',
            'expires_at.after' => 'La date d\'expiration doit être dans le futur.',
        ];
    }
}
