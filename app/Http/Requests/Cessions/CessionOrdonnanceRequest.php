<?php

namespace App\Http\Requests\Cessions;

use Illuminate\Foundation\Http\FormRequest;

class CessionOrdonnanceRequest extends FormRequest
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
            'numero_ordonnance' => 'required|string|min:6|unique:cession_ordonnance',
        ];
    }

    public function messages(): array {
        return [
            'numero_ordonnance.required' => 'Le numéro d\'ordonnance est obligatoire',
            'numero_ordonnance.min' => 'Le numéro d\'ordonnance doit contenir au moins :min caractères',
            'numero_ordonnance.unique' => 'Le numéro d\'ordonnance est déjà utilisé',
        ];
    }
}
