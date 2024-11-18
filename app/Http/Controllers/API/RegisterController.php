<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterController extends Controller
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function register(RegisterRequest $request): JsonResource
    {
        try {
            $user = $this->repository->create($request->validated());
            $user->token = $user->createToken('authToken')->plainTextToken;

            return new RegisterResource($user);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }
}
