<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\ResponseService;
use App\Services\UserService;
use Exception;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private ResponseService $responseService
    ) {}

    public function index()
    {
        $pageNumber = request('page', 1);
        $pageQuantity = request('quantity', 15);

        $orders = $this->userService->getAll(true, $pageQuantity, $pageNumber);
        return $this->responseService->success($orders);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->create($data);
        
        return $this->responseService->created([
            "user" => $user,
            "message" => "Usuário criado com sucesso",
            "token" => $this->userService->createTokenById($user->id)
        ]);
    }

    public function show(string $id)
    {
        try {
            return $this->responseService->success([
                "user" => $this->userService->find($id)
            ]);
        } catch (Exception $e) {
            info($e);
            return $this->responseService->notFound();
        }
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            return $this->responseService->success([
                "user" => $this->userService->update($id, $request->validated()),
                "message" => "Usuário atualizado com sucess"
            ]);
        } catch (Exception $e) {
            info($e);
            return $this->responseService->error();
        }
    }

    public function destroy(string $id)
    {
        $this->userService->delete($id);
        return response()->noContent();
    }
}
