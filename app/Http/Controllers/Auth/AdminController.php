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

    public function storeAdmin (RegisterAdminRequest $request) {

        $data = $request->validated();

        $admin = $this->adminService->saveAdmin(
            $data['first_name'], 
            $data['last_name'], 
            $data['email'], 
            $data['login'], 
            $data['password']
        );

        return response()->json([
            'admin' => $admin,
        ]);
    }

    public function editAdmin (RegisterAdminRequest $request, $idAdmin) {

        $data = $request->validated();

        $admin = $this->adminService->updateAdmin(
            $idAdmin, 
            $data['first_name'], 
            $data['last_name'], 
            $data['email'], 
            $data['login'], 
            $data['password']
        );

        return response()->json([
            'admin' => $admin,
        ]);
    }

    public function statusAdminAticf ($idAdmin) {
        $this->adminService->statusAdmin(
            $idAdmin,
            1
        );

        return response()->json([
            "message" => "L'admin est aticf",
        ]);
    }

    public function statusAdminInaticf ($idAdmin) {
        $this->adminService->statusAdmin(
            $idAdmin,
            0
        );

        return response()->json([
            "message" => "L'admin est aticf",
        ]);
    }

    public function login(LoginAdminRequest $request)
    {
        $data = $request->validated();
        
        
        $admin = $this->adminService->findAdminByLoginAndPassword(
            $data['login'], 
            $data['password']
        );
        
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
