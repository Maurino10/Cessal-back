<?php

namespace App\Services\Users;

use App\Models\Users\Inscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class InscriptionService {

    public function saveInscription ($lastName, $firstName, $birthday, $address, $cin, $email, $idGenre, $immatriculation, $password,  $idPost, $idTPI) {
        $profil = Inscription::create([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'birthday' => $birthday,
            'address' => $address,
            'cin' => $cin,
            'email' => $email,
            'id_gender' => $idGenre,
            'immatriculation' => $immatriculation,
            'password' => Hash::make($password),
            'id_post' => $idPost,
            'id_tpi' => $idTPI,
            'date_inscription' => Carbon::now(),
        ]);

        return $profil;
    }

    public function updateStatusInscription ($idInscription, $status) {
        $inscription = Inscription::findOrFail($idInscription);
        $inscription->status = $status;
        $inscription->save();

        return $inscription;
    }

    public function findAllInscription () {
        $inscriptions = Inscription::where('status', '!=', 1)
            ->with(['gender', 'tpi', 'post'])
            ->get();

        return $inscriptions;
    }

    public function findInscription ($idInscription) {
        $inscription = Inscription::with(['gender', 'tpi', 'post'])->findOrFail($idInscription);

        return $inscription;
    }
}