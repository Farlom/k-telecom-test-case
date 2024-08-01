<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentBulkRequest;
use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class EquipmentController extends Controller
{

    /**
     * @param EquipmentService $service
     */
    public function __construct(protected EquipmentService $service)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return EquipmentResource|JsonResource
     */
    public function index(): EquipmentResource|JsonResource
    {
        return EquipmentResource::collection($this->service->getEquipment(5));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param EquipmentBulkRequest $request
     * @return JsonResponse
     */
    public function store(EquipmentBulkRequest $request): JsonResponse
    {
        $response = $this->service->store($request);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param Equipment $equipment
     * @return EquipmentResource|JsonResource
     */
    public function show(Equipment $equipment): EquipmentResource|JsonResource
    {
        return new EquipmentResource($equipment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EquipmentRequest $request
     * @param Equipment $equipment
     * @return EquipmentResource|JsonResource
     */
    public function update(EquipmentRequest $request, Equipment $equipment): EquipmentResource|JsonResource
    {
        $equipment->update($request->validated());

        return new EquipmentResource($equipment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Equipment $equipment
     * @return Response
     */
    public function destroy(Equipment $equipment): Response
    {
        $equipment->delete();

        return response()->noContent();
    }
}
