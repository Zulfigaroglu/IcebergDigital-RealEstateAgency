<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return response()->json($this->userRepository->all());
    }

    public function show($id): JsonResponse
    {
        $user = $this->userRepository->getById($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        $user = $this->userRepository->update($request->all(), $id);
        return response()->json($user);
    }

    public function destroy($id): JsonResponse
    {
        $user = $this->userRepository->delete($id);
        return response()->json($user);
    }

    public function me(): JsonResponse
    {
        return response()->json(Auth::user());
    }
}
