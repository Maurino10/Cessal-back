<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InscriptionRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Services\Users\InscriptionService;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    protected $inscriptionService;
    protected $userService;
    public function __construct(InscriptionService $inscriptionService, UserService $userService) {

        $this->inscriptionService = $inscriptionService;
        $this->userService = $userService;
    }
    public function register(InscriptionRequest $request)
    {
        $data = $request->validated();

        $inscription = $this->inscriptionService->saveInscription(
            $data['last_name'],
            $data['first_name'],
            $data['birthday'],
            $data['address'],
            $data['cin'],
            $data['email'],
            $data['gender'],
            $data['immatriculation'],
            $data['password'],
            $data['post'],
            $data['tpi'],
        );
        
        return response()->json([
            'messages' => 'Merci de votre patience. Votre inscription est en attente d’approbation.'
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();
        
        $profil = $this->userService->findUserByLoginAndPassword(
            $data['login_type'], 
            $data['login'], 
            $data['password']
        );

        $profil->user->tokens()->delete();
        
        return response()->json([
            'token' => $profil->user->createToken('user')->plainTextToken,
            'profil' => $profil,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('user')->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

}
