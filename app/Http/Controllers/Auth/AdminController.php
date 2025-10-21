<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAdminRequest;
use App\Http\Requests\Auth\RegisterAdminRequest;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;


class   AdminController extends Controller
{

    protected $adminService;

    public function __construct(AdminService $adminService) {
        $this->adminService = $adminService;
    }

    public function register () {    
        $this->adminService->saveAdmin('admin', 'admin123098');
    }

    public function storeAdmin (RegisterAdminRequest $request) {

        $data = $request->validated();

        $admin = $this->adminService->saveAdmin($data['login'], $data['password']);

        return response()->json([
            'admin' => $admin,
        ]);
    }

    public function editAdmin (RegisterAdminRequest $request, $idAdmin) {

        $data = $request->validated();

        $admin = $this->adminService->updateAdmin($idAdmin, $data['login'], $data['password']);

        return response()->json([
            'admin' => $admin,
        ]);
    }

    public function removeAdmin ($idAdmin) {
        $this->adminService->deleteAdmin($idAdmin);

        return response()->json([
            "message" => "L'admin a été supprimée",
        ]);
    }

    public function login(LoginAdminRequest $request)
    {
        $data = $request->validated();
        
        
        $admin = $this->adminService->findAdminByLoginAndPassword($data['login'], $data['password']);
        $admin->tokens()->delete();
        
        return response()->json([
            'token' => $admin->createToken('admin')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('admin')->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
