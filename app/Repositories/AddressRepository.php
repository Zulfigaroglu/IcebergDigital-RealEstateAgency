<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\Infrastructure\IRepository;
use App\Repositories\Infrastructure\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class AddressRepository extends Repository implements IRepository
{
    protected $model;

    public function __construct(Address $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return Address::query()->get();
    }

    public function getById($id): Address
    {
        return Address::query()->findOrFail($id);
    }

    public function create(array $attributes): Address
    {
        $addressData = Arr::only($attributes, $this->getFillable());
        $address = Address::create($addressData);
        return $address;
    }

    public function update(array $attributes, $id): Address
    {
        $addressData = Arr::only($attributes, $this->getFillable());
        $address = $this->getById($id);
        $address->update($addressData);
        return $address;
    }

    public function delete($id): Address
    {
        $address = $this->getById($id);
        $address->delete();
        return $address;
    }

    public function getFillable(): array
    {
        return $this->model->getFillable();
    }
}
