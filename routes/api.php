<?php

use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\EquipmentTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('equipment')->controller(EquipmentController::class)->group(function () {
    Route::get('/', 'index')->name('api.equipment.index');
    Route::get('/{equipment:id}', 'show')->name('api.equipment.show');
    Route::post('/', 'store')->name('api.equipment.store');
    Route::put('/{equipment:id}', 'update')->name('api.equipment.update');
    Route::delete('/{equipment:id}', 'destroy')->name('api.equipment.destroy');
});

Route::get('equipment-type', [EquipmentTypeController::class, 'index'])->name('api.equipment-type.index');

