<?php

namespace App\Services;

use App\Models\Equipment;

/**
 *
 */
class EquipmentService
{
    /**
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEquipment(int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Equipment::query();
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

        return $query->paginate($perPage);
    }


}
