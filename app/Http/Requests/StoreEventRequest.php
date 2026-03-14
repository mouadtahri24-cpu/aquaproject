<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'distance' => 'sometimes|nullable|integer|min:10|max:10000',
            'stroke' => [
                'sometimes',
                'nullable',
                Rule::in(['Crawl', 'Dos', 'Brasse', 'Papillon']),
            ],
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'Le nom de l\'épreuve est obligatoire.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'distance.integer' => 'La distance doit être un nombre entier.',
            'distance.min' => 'La distance doit être au moins 10 mètres.',
            'distance.max' => 'La distance ne doit pas dépasser 10000 mètres.',
            'stroke.in' => 'La nage doit être Crawl, Dos, Brasse ou Papillon.',
        ];
    }
}
