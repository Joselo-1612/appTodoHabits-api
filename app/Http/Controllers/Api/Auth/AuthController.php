<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController
{

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = User::create([
                'usu_name' => $data['name'],
                'usu_email' => $data['email'],
                'usu_password' => bcrypt($data['password']),
            ]);

            return ApiResponse::successResponse(
                $user,
                'User registered successfully',
                201
            );

        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error registering user',
                500,
                $e->validator->errors()
            );
        }
    }


    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $usu_email = $data['usu_email'];
            $usu_password = $data['usu_password'];

            $user = User::where('usu_email', $usu_email)->first();

            if (!$user || !Hash::check($usu_password, $user->usu_password)) {
                return ApiResponse::errorResponse('Credenciales inválidas', 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::successResponse([
                'user' => $user,
                'token' => $token,
            ], 'Login successful', 200);

        } catch (ValidationException $e) {
            return ApiResponse::errorResponse(
                'Error login user',
                500,
                $e->validator->errors()
            );
        }
    }

    // public function logout(): JsonResponse
    // {

    // }
}
