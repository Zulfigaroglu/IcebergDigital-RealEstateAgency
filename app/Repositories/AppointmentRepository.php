<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Client;
use App\Repositories\Infrastructure\IRepository;
use App\Repositories\Infrastructure\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AppointmentRepository extends Repository implements IRepository
{
    protected $model;
    protected $clientRepository;

    public function __construct(Appointment $model,
                                ClientRepository $clientRepository,
                                AddressRepository $addressRepository)
    {
        $this->model = $model;
        $this->clientRepository = $clientRepository;
        $this->addressRepository = $addressRepository;
    }

    public function all(): Collection
    {
        return Appointment::query()->get()->append(['user_name', 'client_name']);
    }

    public function getById($id): Appointment
    {
        return Appointment::query()->findOrFail($id);
    }

    public function create(array $attributes): Appointment
    {
        $appointmentData = Arr::only($attributes, $this->getFillable());
        $appointmentData['user_id'] = Auth::user()->id;

        if(Arr::has($attributes, 'client')){
            $client = $this->createOrUpdateClient($attributes['client']);
            $appointmentData['client_id'] = $client->id;
        }

        /**
         * @var Appointment $appointment
         */
        $appointment = Appointment::create($appointmentData);

        if(Arr::has($attributes, 'address')){
            $address = $this->createOrUpdateAddress($attributes['address']);
            $appointment->address()->save($address);
        }

        return $appointment->refresh();
    }

    public function update(array $attributes, $id): Appointment
    {
        if(Arr::has($attributes, 'client')){
            $this->createOrUpdateClient($attributes['client']);
        }

        $appointmentData = Arr::only($attributes, $this->getFillable());
        $appointmentData['user_id'] = Auth::user()->id;
        $appointment = $this->getById($id);
        $appointment->update($appointmentData);
        return $appointment;
    }

    public function delete($id): Appointment
    {
        $appointment = $this->getById($id);
        $appointment->address()->delete();
        $appointment->delete();
        return $appointment;
    }

    /**
     * @return string[]
     */
    public function getFillable(): array
    {
        return $this->model->getFillable();
    }

    protected function createOrUpdateClient($clientAttributes): Client
    {
        $clientData = Arr::only($clientAttributes, $this->clientRepository->getFillable());
        if(Arr::has($clientAttributes, 'id')){
            return $this->clientRepository->update($clientData, $clientAttributes['id']);
        }
        return $this->clientRepository->create($clientData);
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
