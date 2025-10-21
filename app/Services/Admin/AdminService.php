<?php

namespace App\Services\Admin;

use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminService {

    public function saveAdmin ($login, $password) {
        $admin = Admin::create([
            'login' => $login,
            'password' => Hash::make($password),
        ]);

        return $admin;
    }

    public function updateAdmin ($idAdmin, $login, $password) {
        $admin = Admin::findOrFail($idAdmin);
        $admin->login = $login;
        $admin->password = Hash::make($password);
        $admin->save();

        return $admin;
    }

    public function deleteAdmin ($idAdmin) {
        $admin = Admin::find($idAdmin);
        $admin->delete();
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