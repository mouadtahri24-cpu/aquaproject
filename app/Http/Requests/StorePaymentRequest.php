<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest {
    public function authorize(): bool {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array {
        return [
            'swimmer_id' => 'required|exists:swimmers,id',
            'month' => 'required|date_format:Y-m|unique:payments,month,NULL,id,swimmer_id,' . $this->swimmer_id,
            'amount_expected' => 'required|numeric|min:0|max:100000',
            'amount_paid' => 'required|numeric|min:0|max:100000|lte:amount_expected',
            'status' => [
                'required',
                Rule::in(['Paid', 'Partial', 'Pending', 'Late']),
            ],
        ];
    }

    public function messages(): array {
        return [
            'swimmer_id.required' => 'Le nageur est obligatoire.',
            'swimmer_id.exists' => 'Le nageur sélectionné n\'existe pas.',
            'month.required' => 'Le mois est obligatoire.',
            'month.date_format' => 'Le mois doit être au format YYYY-MM.',
            'month.unique' => 'Un paiement existe déjà pour ce nageur ce mois-ci.',
            'amount_expected.required' => 'Le montant attendu est obligatoire.',
            'amount_expected.numeric' => 'Le montant attendu doit être un nombre.',
            'amount_expected.min' => 'Le montant attendu ne peut pas être négatif.',
            'amount_paid.required' => 'Le montant payé est obligatoire.',
            'amount_paid.numeric' => 'Le montant payé doit être un nombre.',
            'amount_paid.lte' => 'Le montant payé ne doit pas dépasser le montant attendu.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être Paid, Partial, Pending ou Late.',
        ];
    }
}
