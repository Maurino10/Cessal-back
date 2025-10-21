<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'login_type' => 'required|string|in:cin,immatriculation',
            'login' => 'required',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array {
        return [
            'login_type.required'=> 'Veuillez sélectionner un type de connexion (CIN ou Immatriculation).',
            'login_type.in' => 'Le type de connexion choisi est invalide.',
            'login.required'=> 'Le login est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.', 
        ];
    }
}
