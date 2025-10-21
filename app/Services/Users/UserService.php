<?php

namespace App\Services\Users;

use App\Models\Users\Profil;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService {

    public function saveUser ($password, $idProfil, $idPost, $idTPI) {
        $user = User::create([
            'password' => Hash::make($password),
            'id_profil' => $idProfil,
            'id_post' => $idPost,
            'id_tpi' => $idTPI,
        ]);

        return $user;
    }

    public function updateUser ($idUser, $password, $idProfil, $idPost, $idTPI) {
        $user = User::findOrFail($idUser);
        $user->password = $password;
        $user->id_profil = $idProfil;
        $user->id_post = $idPost;
        $user->id_tpi = $idTPI;
        $user->save();

        return $user;
    }   

    public function deleteUser ($idUser) {
        $user = User::findOrFail($idUser);
        $user->delete();
    }

    public function findAllUser () {
        $users = User::with(['profil', 'profil.gender', 'tpi', 'post'])->get();

        return $users;
    }

    public function findUser ($idUser) {
        $user = User::with(['profil', 'profil.gender', 'tpi', 'post'])->findOrFail($idUser);

        return $user;
    }

    public function findUserByLoginAndPassword ($loginType, $login, $password) {
        $column = $loginType === 'immatriculation' ? 'immatriculation' :'cin';

        $profil = Profil::where($column, $login)
                        ->with(['gender', 'user', 'user.tpi', 'user.tpi.ca', 'user.post'])
                        ->first();
        
        if (!$profil || !$profil->user) {
            throw ValidationException::withMessages([
                'error' => ['Utilisateur introuvable'],
            ]);
        }

        if (!Hash::check($password, $profil->user->password)) {
            throw ValidationException::withMessages([
                'error' => ['Mot de passe incorrect'],
            ]);
        }

        return $profil;
    }
}