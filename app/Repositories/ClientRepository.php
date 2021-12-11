<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Client;
use App\Repositories\Infrastructure\IRepository;
use App\Repositories\Infrastructure\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ClientRepository extends Repository implements IRepository
{
    protected $model;
    protected $addressRepository;

    public function __construct(Client $model, AddressRepository $addressRepository)
    {
        $this->model = $model;
        $this->addressRepository = $addressRepository;
    }

    public function all(): Collection
    {
        return Client::query()->get();
    }

    public function getById($id): Client
    {
        return Client::query()->findOrFail($id);
    }

    public function create(array $attributes): Client
    {
        $clientData = Arr::only($attributes, $this->getFillable());
        $client = Client::create($clientData);

        if(Arr::has($attributes, 'address')){
            $address = $this->createOrUpdateAddress($attributes['address']);
            $client->address()->save($address);
        }

        return $client;
    }

    public function update(array $attributes, $id): Client
    {
        $clientData = Arr::only($attributes, $this->getFillable());
        $client = $this->getById($id);
        $client->update($clientData);
        return $client;
    }

    public function delete($id): Client
    {
        $client = $this->getById($id);
        $client->delete();
        return $client;
    }

    public function getFillable(): array
    {
        return $this->model->getFillable();
    }

    protected function createOrUpdateAddress($addressAttributes): Address
    {
        $clientData = Arr::only($addressAttributes, $this->addressRepository->getFillable());
        if(Arr::has($addressAttributes, 'id')){
            return $this->addressRepository->update($clientData, $addressAttributes['id']);
        }
        return $this->addressRepository->create($clientData);
    }
}
