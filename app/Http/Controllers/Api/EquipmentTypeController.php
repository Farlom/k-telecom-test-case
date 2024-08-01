<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentTypeResource;
use App\Models\EquipmentType;
use App\Services\EquipmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentTypeController extends Controller
{

    public function __construct(protected EquipmentService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return EquipmentTypeResource|JsonResource
     */
    public function index(): EquipmentTypeResource|JsonResource
    {
        return EquipmentTypeResource::collection($this->service->getEquipmentTypes());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
