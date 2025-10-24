<?php

namespace App\Http\Requests\Cessions;

use Illuminate\Foundation\Http\FormRequest;

class CessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero_dossier' => 'required|string',
            'date_contrat' => 'required|date',
            'request_subject' => 'required|string',
            'reimbursed_amount' => 'required|numeric|min:0',
            'date_cession' => 'nullable|date',
            'tpi' => 'required|exists:tpi,id',
            'user' => 'required|exists:users,id',
        ];
    }

    public function messages(): array {
        return [
            'numero_dossier.required' => 'Le numéro du dossier est obligatoire.',
            'numero_dossier.string' => 'Le numéro du dossier doit être une chaîne de caractères.',

            'date_contrat.required' => 'La date du contrat est obligatoire.',
            'date_contrat.date' => 'La date du contrat doit être une date valide.',

            'request_subject.required' => 'L’objet de la demande est obligatoire.',
            'request_subject.string' => 'L’objet de la demande doit être une chaîne de caractères.',

            'reimbursed_amount.required' => 'Le montant demandé est obligatoire.',
            'reimbursed_amount.numeric' => 'Le montant demandé doit être un nombre.',
            'reimbursed_amount.min' => 'Le montant demandé doit être positif.',

            'date_cession.date' => 'La date de cession doit être une date valide.',

            'id_tpi.required' => 'Le tribunal est obligatoire.',
            'id_tpi.exists' => 'Le tribunal sélectionné n’existe pas.',

            'id_user.required' => 'Le greffier est obligatoire.',
            'id_user.exists' => 'Le greffier sélectionné n’existe pas.',
                   
        ];
    }
}
