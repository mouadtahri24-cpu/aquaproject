<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'amount_expected' => 'sometimes|numeric|min:0|max:100000',
            'amount_paid' => 'sometimes|numeric|min:0|max:100000|lte:amount_expected',
            'status' => [
                'sometimes',
                Rule::in(['Paid', 'Partial', 'Pending', 'Late']),
            ],
        ];
    }

    public function messages(): array {
        return [
            'amount_expected.numeric' => 'Le montant attendu doit être un nombre.',
            'amount_expected.min' => 'Le montant attendu ne peut pas être négatif.',
            'amount_paid.numeric' => 'Le montant payé doit être un nombre.',
            'amount_paid.lte' => 'Le montant payé ne doit pas dépasser le montant attendu.',
            'status.in' => 'Le statut doit être Paid, Partial, Pending ou Late.',
        ];
    }
}
