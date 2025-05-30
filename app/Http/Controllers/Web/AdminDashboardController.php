<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OrderService;
use App\Services\ResponseService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function __construct(
        private ResponseService $responseService,
        private UserService $userService,
        private OrderService $orderService
    )
    {}
    public function index()
    {

        $totalAmount = $this->orderService->getTotalAmount();
        
        return inertia('Admin/Dashboard', [
            'totalAmount' => $totalAmount
        ]);
    }

    public function getApiKey(Request $request)
    {
        $user = auth()->guard('web')->user();
        $user = User::find($user->id);
        $key = $user->api_key;

        return response()->json([
            'api_key' => $key ? '************' . substr($key, -8) : null,
            'exists' => !is_null($key),
        ]);
    }

    public function generateApiKey(Request $request)
    {
        $user = auth()->guard('web')->user();
        $user = User::find($user->id);
        $user->api_key = Str::random(40);
        $user->save();

        return response()->json([
            'api_key' => $this->userService->createTokenById($user->id),
        ]);
    }
}
