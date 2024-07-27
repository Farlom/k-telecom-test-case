<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentCollection;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(EquipmentService $service) // TODO "либо указанием параметра q"
    {
//        return new EquipmentCollection($service->getEquipment());
        return EquipmentResource::collection($service->getEquipment());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        $equipment = Equipment::create($request->validated());
        return new EquipmentResource($equipment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        // TODO findOrFail
//        dd(Equipment::findOrFail($equipment->id));
        return new EquipmentResource($equipment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        //
        $equipment->update($request->validated());
//        dd($equipment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        //
    }
}
