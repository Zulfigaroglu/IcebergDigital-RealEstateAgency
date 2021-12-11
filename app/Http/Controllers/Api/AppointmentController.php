<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * @var AppointmentRepository
     */
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->appointmentRepository->all());
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = $this->appointmentRepository->create($request->all());
        return response()->json($appointment->load(['address','user','client']));
    }

    public function show($id): JsonResponse
    {
        $appointment = $this->appointmentRepository->getById($id);
        return response()->json($appointment->load(['address','user','client']));
    }

    public function update(UpdateAppointmentRequest $request, $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->update($request->all(), $id);
        return response()->json($appointment->load(['address','user','client']));
    }

    public function destroy($id): JsonResponse
    {
        $appointment = $this->appointmentRepository->delete($id);
        return response()->json($appointment);
    }
}
