<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentCollection;
use App\Http\Resources\EquipmentErrorResource;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\EquipmentStoreResource;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

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
        $validData = [];
        $invalidData = [];

        foreach ($request->validated() as $value) {
                $equipment = Equipment::create($value);
                $validData[] = $equipment;
        }

        foreach ($request->invalid() as $key => $value) {
            $invalidData[$key] = new Request($value);
        }

        return response()->json([
            'errors' => EquipmentErrorResource::collection(collect($invalidData)),
            'success' => EquipmentResource::collection(collect($validData)),
        ]);
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
        $equipment->delete();

        return response()->noContent();
    }
}
