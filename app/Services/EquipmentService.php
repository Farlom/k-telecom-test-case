<?php

namespace App\Services;

use App\Http\Requests\EquipmentBulkRequest;
use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentErrorResource;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class EquipmentService
{
    /**
     * get equipment list
     *
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEquipment(int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Equipment::query();

        if (request('q')) {
            $columns = array(
                'serial_number',
                'desc',
            );

            foreach ($columns as $column) {
                $query->orWhere($column, 'like', '%' . request('q') . '%');
            }
        } else {
            if (request('id')) {
                $query->where('id', request('id'));
            }

            if (request('equipment_type_id')) {
                $query->where('equipment_type_id', request('equipment_type_id'));

            }

            if (request('serial_number')) {
                $query->where('serial_number', request('serial_number'));
            }

            if (request('desc')) {
                $query->where('desc', request('desc'));
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * get equipment types list
     *
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public
    function getEquipmentTypes(int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = EquipmentType::query();

        if (request('q')) {
            $columns = array(
                'name',
                'mask',
            );

            foreach ($columns as $column) {
                $query->orWhere($column, 'like', '%' . request('q') . '%');
            }
        } else {
            if (request('id')) {
                $query->where('id', request('id'));
            }

            if (request('name')) {
                $query->where('name', request('name'));
            }

            if (request('mask')) {
                $query->where('mask', request('mask'));
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * @param EquipmentBulkRequest $request
     * @return JsonResponse
     */
    public
    function store(EquipmentBulkRequest $request): JsonResponse
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
}
