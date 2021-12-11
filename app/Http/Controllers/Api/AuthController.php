<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->all());
        $accessToken = $this->userRepository->generateToken($user);

        return response()->json([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->all())) {
            return response()->json(['message' => 'Invalid Credentials']);
        }

        /**
         * @var User $user
         */
        $user = Auth::user();
        $accessToken = $this->userRepository->generateToken($user);

        return response()->json(['user' => Auth::user(), 'access_token' => $accessToken]);

    }

    public function logout (): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $user->token()->revoke();

        return response()->json(['message' => 'You have been successfully logged out!']);
    }
}
