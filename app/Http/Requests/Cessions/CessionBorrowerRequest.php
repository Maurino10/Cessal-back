<?php

namespace App\Http\Requests\Cessions;

use App\Models\Cessions\CessionBorrower;
use Illuminate\Foundation\Http\FormRequest;

class CessionBorrowerRequest extends FormRequest
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
        $idCessionBorrower = $this->route('idCessionBorrower') ?? null;

        $isUpdate = !is_null($idCessionBorrower);

        $cin = 'required|numeric|digits:12|unique:cession_party,cin';
    
        if ($isUpdate) {
            $cessionParty = CessionBorrower::find($idCessionBorrower, ['id_cession_party']);
            $cin = 'required|numeric|digits:12|unique:cession_party,cin,'. $cessionParty->id_cession_party;
        }

        return [
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'address' => 'required|string',
            'cin' => $cin,
            'salary_amount' => 'required|numeric|min:0',
            'remark' => 'nullable|string',
            'gender' => 'required|string|exists:gender,id',
        ];
    }

    public function messages(): array {
        return [
            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.string' => 'Le nom doit être une chaîne de caractères.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'first_name.string' => 'Le prénom doit être une chaîne de caractères.',
            'address.required' => 'L’adresse est obligatoire.',
            'address.string' => 'L’adresse doit être une chaîne de caractères.',
            'cin.required' => 'Le numéro CIN est obligatoire.',
            'cin.digits' => 'Le numéro CIN doit contenir exactement 12 chiffres.',
            'salary_amount.required' => 'Le revenu est obligatoire.',
            'salary_amount.numeric' => 'Le revenu doit être un nombre.',
            'salary_amount.min' => 'Le revenu doit être positif.',
            'remark.string' => 'La remarque doit être une chaîne de caractères.',
            'gender.required'=> 'Un genre doit être sélectionné.',
            'gender.exists' => 'Le genre sélectionné n’existe pas.',
        ];
    }
}
