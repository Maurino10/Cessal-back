<?php

namespace App\Http\Requests\Cessions;

use App\Models\Cessions\CessionLender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CessionLenderRequest extends FormRequest
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
        $idCessionLender = $this->route('idCessionLender') ?? null;

        $isUpdate = !is_null($idCessionLender);

        
        if ($this->input('type') === 'natural_person') {

            $cin = 'required|numeric|digits:12|unique:cession_natural_person,cin';
            $address = 'required|string';
            
            if ($isUpdate) {
                $cessionNaturalPerson = CessionLender::find($idCessionLender, ['id_cession_natural_person']);
                $cin = 'required|numeric|digits:12|unique:cession_natural_person,cin,'. $cessionNaturalPerson->id_cession_natural_person;
                
                if ($this->input('new_address') === false) {
                    $address = 'required|numeric';
                } 
            }

            return [
                'type' => 'required|string',
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'cin' => $cin,
                'address' => $address,
                'gender' => 'required|string',
            ];
        } else {
            // $name = 'required|string|unique:cession_legal_person,name';
            
            $nameRule = [
                'required',
                'string',
                Rule::unique('cession_legal_person', 'name')
                    ->where(fn ($query) => 
                        $query->where('id_tpi', $this->tpi)
                    )
            ];

            if ($isUpdate) {
                $cessionLegalPerson = CessionLender::find($idCessionLender, ['id_cession_legal_person']);
                // $name = 'required|string|unique:cession_legal_person,name,'. $cessionLegalPerson->id_cession_legal_person;

                $nameRule = [
                    'required',
                    'string',
                    Rule::unique('cession_legal_person', 'name')
                        ->where(fn ($query) => 
                            $query->where('id_tpi', $this->tpi)
                        )
                        ->ignore($cessionLegalPerson?->id_cession_legal_person)
                ];
            }

            return [
                'type' => 'required|string',
                'name' => $nameRule,
                'address' => 'required|string',
                'tpi' => 'required|string|exists:tpi,id',
            ];
        }

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
            'cin.unique'=> 'Cette cin est déjà utilisé.',
            'gender.required'=> 'Un genre doit être sélectionné.',
            'gender.exists' => 'Le genre sélectionné n’existe pas.',
            'name.required' => 'Le nom est obligatoire.',
            'name.unique' => 'Le nom existe déjà.',
        ];
    }
}
