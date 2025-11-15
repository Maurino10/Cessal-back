<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAdminRequest extends FormRequest
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
            'email' => 'required|email|unique:profil,email',
            'login' => 'required|unique:admin',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array {
        return [
            'last_name.required'=> 'Le nom est obligatoire.',
            'first_name.required'=> 'Le prénom est obligatoire.',
            'email.required'=> 'L\'email est obligatoire',
            'email.email'=> 'L\'email doit être un email valide',
            'email.unique'=> 'Cet email est déjà utilisé',
            'login.required'=> 'Le login est obligatoire',
            'login.unique'=> 'Ce login est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.', 
        ];
    }
}
