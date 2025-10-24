<?php

namespace App\Http\Requests\Cessions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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

        $idCessionReference = $this->route('idCessionReference') ?? null;
        $isUpdate = !is_null($idCessionReference);

        if ($isUpdate) {
            return [
                'numero_recu' => 'required|string|max:15|unique:cession_reference,numero_recu,' . $idCessionReference,
                'numero_feuillet' => 'required|string|max:15|unique:cession_reference,numero_feuillet,' . $idCessionReference,
                'numero_repertoire' => 'required|string|max:15|unique:cession_reference,numero_repertoire,' . $idCessionReference,
                'date' => 'required|date', // tu peux ajouter après "before:tomorrow" par ex.
            ];
        }

        return [
            'numero_recu' => 'required|string|max:15|unique:cession_reference,numero_recu',
            'numero_feuillet' => 'required|string|max:15|unique:cession_reference,numero_feuillet',
            'numero_repertoire' => 'required|string|max:15|unique:cession_reference,numero_repertoire',
            'date' => 'required|date', // tu peux ajouter après "before:tomorrow" par ex.
        ];
    }

    /**
     * Les messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'numero_recu.required' => 'Le numéro de reçu est obligatoire.',
            'numero_recu.unique' => 'Ce numéro de reçu existe déjà.',

            'numero_feuillet.required' => 'Le numéro de feuillet est obligatoire.',
            'numero_feuillet.unique' => 'Ce numéro de feuillet existe déjà.',

            'numero_repertoire.required' => 'Le numéro de répertoire est obligatoire.',
            'numero_repertoire.unique' => 'Ce numéro de répertoire existe déjà.',

            'date.required' => 'La date est obligatoire.',
            'date.date' => 'Le format de la date est invalide.',
        ];
    }
}

