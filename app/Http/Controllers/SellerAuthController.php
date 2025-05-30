<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Illuminate\Http\Request;

class SellerAuthController extends Controller
{

    public function __construct(
        private ResponseService $responseService
    ){}

    public function loginView()
    {
        return inertia('Auth/SellerLogin');
    }

    public function login()
    {
        $validate = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->guard('seller')->attempt($validate)) {
            return $this->responseService->success([
                "message" => "Logado com sucesso"
            ]);
        }

        return $this->responseService->badRequest([
            "message" => "Credenciais inválidas"
        ]);
    }
}
