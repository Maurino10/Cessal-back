<?php

namespace App\Services\Users;

use App\Models\Users\Inscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

    public function findAllInscription ($search, $idPost, $idTPI) {
        $inscriptions = Inscription::where('status', '!=', 1);

        if (!empty($idPost) && $idPost !== 'null' && $idPost !== '') {
            $inscriptions->where('id_post', $idPost);
        }

        if (!empty($idTPI) && $idTPI !== 'null' && $idTPI !== '') {
            $inscriptions->where('id_tpi', $idTPI);
        }

        if (!empty($search) && $search !== '') {
            $inscriptions->where(function ($query) use ($search) {
                $query->where('immatriculation', 'ILIKE', "%$search%")
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) ILIKE ?", ["%$search%"])
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE ?", ["%$search%"]);
            });
        }


        return $inscriptions->with(['gender', 'tpi', 'post'])
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->paginate(10);

    }

    public function findInscription ($idInscription) {
        $inscription = Inscription::with(['gender', 'tpi', 'post'])->findOrFail($idInscription);

        return $inscription;
    }
}