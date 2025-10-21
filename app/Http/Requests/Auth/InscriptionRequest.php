<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Log;

class InscriptionRequest extends FormRequest
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
            'immatriculation' => 'required|string|unique:profil',
            'cin' => 'required|numeric|min:12|unique:profil',
            'email' => 'required|email|unique:profil',
            'password' => 'required|string|min:8|confirmed',
            'post' => 'required|string|exists:post,id',
            'tpi' => 'required|string|exists:tpi,id',
        ];
    }

    public function messages(): array   
    {   
        return [   
            'last_name.required'=> 'Le nom est obligatoire.',
            'first_name.required'=> 'Le prénom est obligatoire.',
            'gender.required'=> 'Un genre doit être sélectionné.',
            'gender.exists' => 'Le genre sélectionné n’existe pas.',
            'birthday.required'=> 'La date de naissance est obligatoire.',
            'birthday.date'=> 'La date de naissance doit être une date valide.',
            'birthday.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'address.required' => 'L\'adresse est obligatoire.',
            'immatriculation.required' => 'L\'immatriculation est obligatoire.',
            'immatriculation.unique' => 'Cette immatriculation est déjà utilisé.',
            'cin.required'=> 'La cin est obligatoire',
            'cin.numeric'=> 'La cin ne doit contenir que des chiffres.',
            'cin.unique'=> 'Cette cin est déjà utilisé.',
            'cin.min'=> 'La cin doit contenir au moins :min caractères.',
            'email.required'=> 'L\'email est obligatoire',
            'email.email'=> 'L\'email doit être un email valide',
            'email.unique'=> 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'post.required' => 'Un poste doit être sélectionné..',
            'post.exists' => 'Le poste sélectionné n’existe pas.',
            'tpi.required' => 'Un tpi doit être sélectionné..',
            'tpi.exists' => 'Le tpi sélectionné n’existe pas.',
        ];
    }
}
