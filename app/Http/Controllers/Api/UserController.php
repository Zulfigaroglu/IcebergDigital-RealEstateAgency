<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::query()->get());
    }

    public function show($id)
    {
        $user = User::query()->findOrFail($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $userData = $request->toArray();
        if ($request->has('password')) {
            $userData['password'] = Hash::make($request->get('password'));
        }

        $user = User::query()->findOrFail($id);
        $user->update($userData);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::query()->findOrFail($id)->delete();
        return response()->json($user);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
}
