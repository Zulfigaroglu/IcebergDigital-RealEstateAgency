<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Infrastructure\IRepository;
use App\Repositories\Infrastructure\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository implements IRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return User::query()->get();
    }

    public function getById($id): User
    {
        return User::query()->findOrFail($id);
    }

    public function create(array $attributes): User
    {
        $userData = Arr::only($attributes, $this->getFillable());
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);
        return $user;
    }

    public function update(array $attributes, $id): User
    {
        $userData = Arr::only($attributes, $this->getFillable());
        $user = $this->getById($id);
        $user->update($userData);
        return $user;
    }

    public function delete($id): User
    {
        $user = $this->getById($id);
        $user->delete();
        return $user;
    }

    public function getFillable(): array
    {
        return $this->model->getFillable();
    }

    public function generateToken(User $user): string
    {
        $activeToken = $user->token();
        if ($activeToken) {
            $activeToken->revoke();
        }

        return $user->createToken(null)->accessToken;
    }
}
