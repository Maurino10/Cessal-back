<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InscriptionRequest;
use App\Mail\InscriptionApprovedMail;
use App\Mail\InscriptionRejectedMail;
use App\Models\Users\Gender;
use App\Models\Users\Post;
use App\Services\Users\InscriptionService;
use App\Services\Users\ProfilService;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserController extends Controller 
{

    protected $profilService;
    protected $userService;
    protected $inscriptionService;

    public function __construct(ProfilService $profilService, UserService $userService, InscriptionService $inscriptionService) {
        $this->profilService = $profilService;
        $this->userService = $userService;
        $this->inscriptionService = $inscriptionService;

    }

// ------------------------------- ------------------------------- ------------------------------- User
    
    public function storeUser (InscriptionRequest $request) {
        $data = $request->validated();

        $idProfil = $this->profilService->saveProfil(
            $data['last_name'],
            $data['first_name'],
            $data['birthday'],
            $data['address'],
            $data['cin'],
            $data['immatriculation'],
            $data['email'],
            $data['gender'],

        );

        $user = $this->userService->saveUser(
            $data['password'],
            $idProfil,
            $data['post'],
            $data['tpi'],
        );
        
        return response()->json([
            'user' => $user
        ], 201);
    }

    public function getAllUser () {
        $users = $this->userService->findAllUser();
        return response()->json([
            'users' => $users
        ]);
    }

    public function getUser ($idUser) {
        $user = $this->userService->findUser($idUser);
        return response()->json([
            'user' => $user
        ]);
    }


// ------------------------------- ------------------------------- ------------------------------- Post

    public function getAllPost (){
        $posts = Post::all();
        
        return response()->json([
            'posts' => $posts
        ], 201); 
    }

// ------------------------------- ------------------------------- ------------------------------- Gender
    public function getAllGender () {
        $genders = Gender::all();

        return response()->json([   
            'genders'=> $genders
        ], 200);
    }

// ------------------------------- ------------------------------- ------------------------------- Inscription 

    public function getAllInscription () {
        $inscriptions = $this->inscriptionService->findAllInscription();

        return response()->json([
            'inscriptions' => $inscriptions
        ], 200);
    }
    public function inscriptionApproved ($idInscription) {
        $inscription = $this->inscriptionService->updateStatusInscription($idInscription, 1);

        Mail::to($inscription->email)->send(new InscriptionApprovedMail($inscription));

        return response()->json(['message' => 'Mails envoyés avec succès.']);
    }   

    public function inscriptionRejected ($idInscription) {
        $inscription = $this->inscriptionService->updateStatusInscription($idInscription, -1);


        Mail::to($inscription->email)->send(new InscriptionRejectedMail($inscription));

        return response()->json(['message' => 'Mails envoyés avec succès.']);
    }  
}