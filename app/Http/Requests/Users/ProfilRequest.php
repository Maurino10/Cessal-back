<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
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
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'gender' => 'required|string|exists:gender,id',
            'birthday' => 'required|date|before:today',
            'address' => 'required|string',
            'cin' => 'required|numeric|min:12|unique:profil,cin',
            'immatriculation' => 'required|string|unique:profil,immatriculation',
            'email' => 'required|email|unique:users',
        ];
    }
    public function messages(): array {
        return [
            'last_name.required'=> 'Le nom est obligatoire.',
            'first_name.required'=> 'Le prénom est obligatoire.',
            'gender.required'=> 'Un genre doit être sélectionné.',
            'gender.exists' => 'Le genre sélectionné n’existe pas.',
            'birthday.required'=> 'La date de naissance est obligatoire.',
            'birthday.date'=> 'La date de naissance doit être une date valide.',
            'birthday.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'address.required' => 'L\'adresse est obligatoire.',
            'cin.required'=> 'La cin est obligatoire',
            'cin.numeric'=> 'La cin ne doit contenir que des chiffres.',
            'cin.unique'=> 'Cette cin est déjà utilisé.',
            'cin.min'=> 'La cin doit contenir au moins :min caractères.',
            'immatriculation.required' => 'L\'immatriculation est obligatoire.',
            'immatriculation.unique' => 'Cette immatriculation est déjà utilisé.',
            'email.required'=> 'L\'email est obligatoire',
            'email.email'=> 'L\'email doit être un email valide',
            'email.unique'=> 'Cet email est déjà utilisé',
        ];
    }
}
