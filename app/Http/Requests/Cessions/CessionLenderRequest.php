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

        
        if ($this->input('type') === 'person') {

            $cin = 'required|numeric|digits:12|unique:cession_party,cin';
    
            if ($isUpdate) {
                $cessionParty = CessionLender::find($idCessionLender, ['id_cession_party']);
                $cin = 'required|numeric|digits:12|unique:cession_party,cin,'. $cessionParty->id_cession_party;
            }

            return [
                'type' => 'required|string',
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'cin' => $cin,
                'address' => 'required|string',
                'gender' => 'required|string',
            ];
        } else {
            // $name = 'required|string|unique:cession_entity,name';
            
            $nameRule = [
                'required',
                'string',
                Rule::unique('cession_entity', 'name')
                    ->where(fn ($query) => $query->where('id_tpi', $this->tpi))
            ];

            if ($isUpdate) {
                $cessionEntity = CessionLender::find($idCessionLender, ['id_cession_entity']);
                // $name = 'required|string|unique:cession_entity,name,'. $cessionEntity->id_cession_entity;

                $nameRule = [
                    'required',
                    'string',
                    Rule::unique('cession_entity', 'name')
                        ->where(fn ($query) => $query->where('id_tpi', $this->tpi))
                        ->ignore($cessionEntity?->id_cession_entity)
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
