<?php

namespace App\Services\Users;

use App\Models\Users\Post;
use App\Models\Users\Profil;


class ProfilService {

    public function saveProfil ($lastName, $firstName, $birthday, $address, $cin, $immatriculation, $email, $idGenre) {
        $profil = Post::insertGetId([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'birthday' => $birthday,
            'address' => $address,
            'cin' => $cin,
            'immatriculation' => $immatriculation,
            'email' => $email,
            'id_gender' => $idGenre,
        ]);

        return $profil;
    }

    public function updateProfil ($idProfil, $lastName, $firstName, $birthday, $address, $cin, $immatriculation, $email, $idGenre) {
        $profil = Post::findOrFail($idProfil);
        $profil->last_name = $lastName;
        $profil->first_name = $firstName;
        $profil->birthday = $birthday;
        $profil->address = $address;
        $profil->cin = $cin;
        $profil->immatriculation = $immatriculation;
        $profil->email = $email;
        $profil->id_gender = $idGenre;
        $profil->save();

        return $profil;
    }   

    public function deleteProfil ($idProfil) {
        $profil = Profil::findOrFail($idProfil);
        $profil->delete();
    }

    public function findAllProfil () {
        $profils = Profil::with(['gender', 'user', 'user.tpi', 'user.post'])->get();

        return $profils;
    }

    public function findProfil ($idProfil) {
        $profil = Profil::with(['gender', 'user', 'user.tpi', 'user.post'])->findOrFail($idProfil);

        return $profil;
    }
}