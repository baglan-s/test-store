<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use App\Http\Resources\AuthResource;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends Controller
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function login(AuthRequest $request): JsonResource
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            $user->token = $user->createToken('authToken')->plainTextToken;

            return new AuthResource($user);
        }

        return new ErrorResource(new \Exception(
            'Incorrect login or password',
            401
        ));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([]);
    }
}
