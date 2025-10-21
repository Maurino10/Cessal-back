<?php

namespace App\Http\Requests\Cessions;

use Illuminate\Foundation\Http\FormRequest;

class CessionBorrowerQuotaRequest extends FormRequest
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
            'granted_amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'granted_amount.required' => 'Le montant accordé est obligatoire.',
            'granted_amount.numeric' => 'Le montant accordé doit être un nombre.',
            'granted_amount.min' => 'Le montant accordé doit être positif.',
        ];
    }
}
