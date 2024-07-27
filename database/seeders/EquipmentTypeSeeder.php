<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        EquipmentType::create([
           'name' => 'TP-Link TL-WR74',
           'mask' => 'XXAAAAAXAA',
        ]);

        EquipmentType::create([
            'name' => 'D-Link DIR-300',
            'mask' => 'NXXAAXZXaa',
        ]);

        EquipmentType::create([
            'name' => 'D-Link DIR-300 E',
            'mask' => 'NAAAAXZXXX',
        ]);
    }
}
