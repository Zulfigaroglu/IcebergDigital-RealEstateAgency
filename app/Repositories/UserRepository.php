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

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return User::query()->get();
    }

    /**
     * @param $id
     * @return User
     */
    public function getById($id): User
    {
        return User::query()->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User
    {
        $userData = Arr::only($attributes, $this->getFillable());
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);
        return $user;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return User
     */
    public function update(array $attributes, $id): User
    {
        $userData = Arr::only($attributes, $this->getFillable());
        $user = $this->getById($id);
        $user->update($userData);
        return $user;
    }

    /**
     * @param $id
     * @return User
     */
    public function delete($id): User
    {
        $user = $this->getById($id);
        $user->delete();
        return $user;
    }

    /**
     * @return string[]
     */
    public function getFillable(): array
    {
        return $this->model->getFillable();
    }

    /**
     * @param User $user
     * @return string
     */
    public function generateToken(User $user): string
    {
        $activeToken = $user->token();
        if($activeToken){
            $activeToken->revoke();
        }

        return $user->createToken(null)->accessToken;
    }
}
