<?php

namespace App\Http\Requests\Cessions;

use Illuminate\Foundation\Http\FormRequest;

class CessionReferenceRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Mettre à true si tous les utilisateurs peuvent envoyer ce formulaire
        return true;
    }

    /**
     * Les règles de validation.
     */
    public function rules(): array
    {
        return [
            'numero_recu' => 'required|string',
            'numero_feuillet' => 'required|string',
            'numero_repertoire' => 'required|string',
            'date' => 'required|string',
        ];
    }

    /**
     * Les messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'numero_recu.required' => 'Le champ est obligatoire',
            'numero_feuillet.required' => 'Le champ est obligatoire',
            'numero_repertoire.required' => 'Le champ est obligatoire',
            'date.required' => 'Le champ est obligatoire',
        ];
    }
}

