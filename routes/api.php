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
    UserController
};

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    // Protected routes for logged-in users
    Route::middleware(['auth:sanctum', 'CheckUserIdOwnership'])->group(function () {
        Route::post('logout/{user_id}', [AuthController::class, 'logout']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });
});

// Protected routes with Sanctum and permissions check
Route::middleware(['auth:sanctum'])->group(function () {

    // Routes for Users with 'manage users' permission
    Route::middleware(['permission:view users|edit users|delete users|create users'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view users');
        Route::get('/{user_id}', [UserController::class, 'show'])->middleware('permission:view users');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:create users');
        Route::put('/{user_id}', [UserController::class, 'update'])->middleware('permission:edit users');
        Route::delete('/{user_id}', [UserController::class, 'destroy'])->middleware('permission:delete users');
    });

    // Routes for Machines
    Route::middleware(['permission:view machines|edit machines|delete machines|create machines'])->prefix('machines')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->middleware('permission:view machines');
        Route::get('/{machine_id}', [MachineController::class, 'show'])->middleware('permission:view machines');
        Route::post('/', [MachineController::class, 'store'])->middleware('permission:create machines');
        Route::put('/{machine_id}', [MachineController::class, 'update'])->middleware('permission:edit machines');
        Route::delete('/{machine_id}', [MachineController::class, 'destroy'])->middleware('permission:delete machines');
    });

    // Routes for Alerts
    Route::middleware(['permission:view alerts|edit alerts|delete alerts'])->prefix('alerts')->group(function () {
        Route::get('/', [AlertController::class, 'index'])->middleware('permission:view alerts');
        Route::get('/{alert_id}', [AlertController::class, 'show'])->middleware('permission:view alerts');
        Route::put('/{alert_id}', [AlertController::class, 'update'])->middleware('permission:edit alerts');
        Route::delete('/{alert_id}', [AlertController::class, 'destroy'])->middleware('permission:delete alerts');
    });

    // Routes for Tasks
    Route::middleware(['permission:view tasks|edit tasks|delete tasks'])->prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->middleware('permission:view tasks');
        Route::get('/{task_id}', [TaskController::class, 'show'])->middleware('permission:view tasks');
        Route::put('/{task_id}', [TaskController::class, 'update'])->middleware('permission:edit tasks');
        Route::delete('/{task_id}', [TaskController::class, 'destroy'])->middleware('permission:delete tasks');
    });

    // Routes for Energy Usage
    Route::middleware(['permission:view energy_usage|edit energy_usage|delete energy_usage|create energy_usage'])->prefix('energy-usage')->group(function () {
        Route::get('/', [EnergyUsageController::class, 'index'])->middleware('permission:view energy_usage');
        Route::get('/{energy_usage_id}', [EnergyUsageController::class, 'show'])->middleware('permission:view energy_usage');
        Route::post('/', [EnergyUsageController::class, 'store'])->middleware('permission:create energy_usage');
        Route::put('/{energy_usage_id}', [EnergyUsageController::class, 'update'])->middleware('permission:edit energy_usage');
        Route::delete('/{energy_usage_id}', [EnergyUsageController::class, 'destroy'])->middleware('permission:delete energy_usage');
    });

    // Routes for Productions
    Route::middleware(['permission:view production|edit production|delete production|create production'])->prefix('productions')->group(function () {
        Route::get('/', [ProductionController::class, 'index'])->middleware('permission:view production');
        Route::get('/{production_id}', [ProductionController::class, 'show'])->middleware('permission:view production');
        Route::post('/', [ProductionController::class, 'store'])->middleware('permission:create production');
        Route::put('/{production_id}', [ProductionController::class, 'update'])->middleware('permission:edit production');
        Route::delete('/{production_id}', [ProductionController::class, 'destroy'])->middleware('permission:delete production');
    });

    // Routes for Sensor Readings
    Route::middleware(['permission:view sensor_readings|delete sensor_readings'])->prefix('sensor-readings')->group(function () {
        Route::get('/', [SensorReadingController::class, 'index'])->middleware('permission:view sensor_readings');
        Route::get('/{sensor_reading_id}', [SensorReadingController::class, 'show'])->middleware('permission:view sensor_readings');
        Route::delete('/{sensor_reading_id}', [SensorReadingController::class, 'destroy'])->middleware('permission:delete sensor_readings');
    });
});
