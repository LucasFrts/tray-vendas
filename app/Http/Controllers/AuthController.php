<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Services\ResponseService;
use App\Services\UserService;
use Exception;

class AuthController extends Controller
{

    public function __construct(
        private ResponseService $responseService,
        private UserService $userService
    ) {}

    public function viewLogin()
    {
        if(auth()->guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return inertia('Auth/Login');
    }

    public function viewRegister()
    {
        if(auth()->guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return inertia('Auth/Register');
    }

    public function login()
    {
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->guard('web')->attempt($data)) {
            return $this->responseService->success([
                "message" => "Logado com sucesso"
            ]);
        }

        return $this->responseService->badRequest([
            "message" => "Credenciais inválidas"
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        try{
            $user = $this->userService->create($request->validated());

            auth()->guard('web')->login($user);

            return $this->responseService->created([
                "message" => "Registrado com sucesso"
            ]);
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->error();
        }
        
    }

    public function logout()
    {
        if(auth()->guard('web')->check()) {
            auth()->guard('web')->logout();
        }
        
        if(auth()->guard('seller')->check()) {
            auth()->guard('seller')->logout();
        }

        return redirect('/');
    }
}
