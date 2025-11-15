<?php

namespace App\Services\Admin;

use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminService {

    public function saveAdmin ($firstName, $lastName, $email, $login, $password) {
        $admin = Admin::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'status' => 1,
            'login' => $login,
            'password' => Hash::make($password),
        ]);

        return $admin;
    }

    public function updateAdmin ($idAdmin, $firstName, $lastName, $email,  $login, $password) {
        $admin = Admin::findOrFail($idAdmin);
        $admin->first_name = $firstName;
        $admin->last_name = $lastName;
        $admin->email = $email;
        $admin->login = $login;
        $admin->password = Hash::make($password);
        $admin->save();

        return $admin;
    }

    public function statusAdmin ($idAdmin, $status) {
        $admin = Admin::find($idAdmin);
        $admin->status = $status;
        $admin->save();

        return $admin;
    }

    public function findAdminByLoginAndPassword ($login, $password) {
        $admin = Admin::where('login', $login)->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            throw ValidationException::withMessages([
                'login' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        return $admin;
    }
}