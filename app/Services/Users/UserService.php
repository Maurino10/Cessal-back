<?php

namespace App\Services\Users;

use App\Models\Users\Profil;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

    public function updateUser ($idUser, $idPost, $idTPI) {
        $user = User::findOrFail($idUser);
        $user->id_post = $idPost;
        $user->id_tpi = $idTPI;
        $user->save();

        return $user;
    }    
    public function deleteUser ($idUser) {
        $user = User::findOrFail($idUser);
        $user->delete();
    }

    public function findAllUser ($search, $idPost, $idTPI) {
        $users = User::query()
            ->join('profil', 'users.id_profil', '=', 'profil.id')
            ->orderBy('profil.last_name')
            ->select('users.*');

        if (!empty($idPost) && $idPost !== 'null' && $idPost !== '') {
            $users->where('id_post', $idPost);
        }

        if (!empty($idTPI) && $idTPI !== 'null' && $idTPI !== '') {
            $users->where('id_tpi', $idTPI);
        }

        if (!empty($search) && $search !== '') {
            $users->where(function ($query) use ($search) {
                $query->where('profil.immatriculation', 'ILIKE', "%$search%")
                ->orWhereHas('profil', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(last_name, ' ', first_name) ILIKE ?", ["%$search%"])
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE ?", ["%$search%"]);
                });
            });
        }


        return $users->with(['profil.gender', 'tpi', 'post'])
            ->orderBy('profil.last_name', 'asc')
            ->orderBy('profil.first_name', 'asc')
            ->paginate(10);
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