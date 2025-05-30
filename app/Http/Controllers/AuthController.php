<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return response()->json(
            [
                'status' => 'successful',
                'message' => 'Register Successfully',
                'data' => $result
            ],
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $user = $request->validated();

        $responseData = $this->authService->login($user);

        return response()->json([
            'status' => 'successful',
            'message' => 'Login Successfully',
            'data' => $responseData
        ], 200);
    }

    public function logout(Request $request)
    {
        $responseData = $this->authService->logout($request->user());

        return response()->json([
            'status' => 'successful',
            'message' => "Logged out successfully",
        ], 204);
    }

}
