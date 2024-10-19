<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AlertController,
    AuthController,
    EnergyUsageController,
    MachineController,
    ProductionController,
    SensorReadingController,
    TaskController,
    UserController,
    DefectController
};

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])
    // ->middleware('permission:login')
    ;

    // Protected routes for logged-in users
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout/{user_id}', [AuthController::class, 'logout'])->middleware('check.ownership');
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });
});

// Protected routes with Sanctum and permissions check
Route::middleware(['auth:sanctum'])->group(function () {

    // Routes for Users with 'manage users' permission
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view users');
        Route::get('/{user_id}', [UserController::class, 'show'])->middleware('permission:view users');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:create users');
        Route::put('/{user_id}', [UserController::class, 'update'])->middleware('permission:edit users');
        Route::delete('/{user_id}', [UserController::class, 'destroy'])->middleware('permission:delete users');
    });

    // Routes for Machines
    Route::prefix('machines')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->middleware('permission:view machines');
        Route::get('/{machine_id}', [MachineController::class, 'show'])->middleware('permission:view machines');
        Route::post('/', [MachineController::class, 'store'])->middleware('permission:create machines');
        Route::put('/{machine_id}', [MachineController::class, 'update'])->middleware('permission:edit machines');
        Route::delete('/{machine_id}', [MachineController::class, 'destroy'])->middleware('permission:delete machines');
    });

    // Routes for Alerts
    Route::prefix('alerts')->group(function () {
        Route::get('/', [AlertController::class, 'index'])->middleware('permission:view alerts');
        Route::get('/{alert_id}', [AlertController::class, 'show'])->middleware('permission:view alerts');
        Route::delete('/{alert_id}', [AlertController::class, 'destroy'])->middleware('permission:delete alerts');
    });

    // Routes for Tasks
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->middleware('permission:view tasks');
        Route::get('/{task_id}', [TaskController::class, 'show'])->middleware('permission:view tasks');
        Route::put('/{task_id}', [TaskController::class, 'update'])->middleware('permission:edit tasks');
        Route::delete('/{task_id}', [TaskController::class, 'destroy'])->middleware('permission:delete tasks');
    });

    // Routes for Energy Usage
    Route::prefix('energy-usage')->group(function () {
        Route::get('/', [EnergyUsageController::class, 'index'])->middleware('permission:view energy_usage');
        Route::get('/{energy_usage_id}', [EnergyUsageController::class, 'show'])->middleware('permission:view energy_usage');
        Route::post('/{machine_id}', [EnergyUsageController::class, 'store'])->middleware('permission:create energy_usage');
        Route::put('/{energy_usage_id}', [EnergyUsageController::class, 'update'])->middleware('permission:edit energy_usage');
        Route::delete('/{energy_usage_id}', [EnergyUsageController::class, 'destroy'])->middleware('permission:delete energy_usage');
    });

    // Routes for Productions
    Route::prefix('productions')->group(function () {
        Route::get('/', [ProductionController::class, 'index'])->middleware('permission:view production');
        Route::get('/{production_id}', [ProductionController::class, 'show'])->middleware('permission:view production');
        Route::post('/{machine_id}', [ProductionController::class, 'store'])->middleware('permission:create production');
        Route::put('/{production_id}', [ProductionController::class, 'update'])->middleware('permission:edit production');
        Route::delete('/{production_id}', [ProductionController::class, 'destroy'])->middleware('permission:delete production');
    });

    // Routes for Sensor Readings
    Route::prefix('sensor-readings')->group(function () {
        Route::get('/', [SensorReadingController::class, 'index'])->middleware('permission:view sensor_readings');
        Route::get('/{sensor_reading_id}', [SensorReadingController::class, 'show'])->middleware('permission:view sensor_readings');
        Route::delete('/{sensor_reading_id}', [SensorReadingController::class, 'destroy'])->middleware('permission:delete sensor_readings');
    });

    Route::prefix('defects')->group(function () {
        Route::get('/', [DefectController::class, 'index'])->middleware('permission:view defects');
        Route::get('/{defect_id}', [DefectController::class, 'show'])->middleware('permission:view defects');
        Route::post('/{machine_id}', [DefectController::class, 'store'])->middleware('permission:create defects');
        Route::put('/{defect_id}', [DefectController::class, 'update'])->middleware('permission:edit defects');
        Route::delete('/{defect_id}', [DefectController::class, 'destroy'])->middleware('permission:delete defects');
    });
});
