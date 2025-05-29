<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreSellerRequest;
use App\Http\Requests\Seller\UpdateSellerRequest;
use App\Services\ResponseService;
use App\Services\SellerService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SellerController extends Controller
{

    public function __construct(
        private SellerService $sellerService,
        private ResponseService $responseService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user() instanceof \App\Models\Seller) {
            return $this->responseService->forbidden();
        }

        $pageNumber = request('page', 1);
        $pageQuantity = request('quantity', 15);

        $sellers = $this->sellerService->getAll(true, $pageQuantity, $pageNumber);
        return $this->responseService->success($sellers);
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellerRequest $request)
    {
        if(auth()->guard('web')->check()) {
            return $this->responseService->redirect("dashboard");
        }

        $data = $request->validated();
        $seller = $this->sellerService->create($data);
        $token = $this->sellerService->createTokenById($seller->id);

        if (!$request->is('api/*')) {
            Auth::guard('seller')->login($seller);
            $cookie = Cookie::make(
                name:     'api_token',
                value:    $token,
                minutes:  60 * 24 * 7,
                path:     '/',           
                domain:   config('session.domain'), 
                secure:   config('session.secure_cookie'), 
                httpOnly: true,          
                sameSite: 'lax'          
            );

            return $this->responseService->created([
                "seller" => $seller,
                "message" => "Vendedor criado com sucesso"
            ])->withCookie($cookie);
        }        
        
        return $this->responseService->created([
            "seller" => $seller,
            "message" => "Vendedor criado com sucesso",
            "token" => $token
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $actor = Auth::user();
            
            if ($actor instanceof \App\Models\Seller && $actor->id != $id) {
                return $this->responseService->forbidden();
            }

            return $this->responseService->success([
                "seller" => $this->sellerService->find($id)
            ]);
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->notFound();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSellerRequest $request, string $id)
    {
        try{
            return $this->responseService->success([
                "seller" => $this->sellerService->update($id, $request->validated()),
                "message" => "Vendedor atualizado com sucess"
            ]);
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->sellerService->delete($id);
            return $this->responseService->deleted();
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->error();
        }
    }
}
