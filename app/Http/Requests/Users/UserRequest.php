<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'password' => 'required|string|min:8|confirmed',
            'profil' => 'required|string|exists:profil,id',
            'post' => 'required|string|exists:post,id',
            'tpi' => 'required|string|exists:tpi,id',
        ];
    }

    public function messages(): array {
        return [
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'profil.required' => 'Un profil doit être sélectionné.',
            'profil.exists' => 'Le profil sélectionné n’existe pas.',
            'post.required' => 'Un poste doit être sélectionné.',
            'post.exists' => 'Le poste sélectionné n’existe pas.',
            'tpi.required' => 'Un tpi doit être sélectionné..',
            'tpi.exists' => 'Le tpi sélectionné n’existe pas.',
        ];
    }
}
