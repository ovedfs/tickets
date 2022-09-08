<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\AbogadoTicketController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ["auth:sanctum"]], function(){
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::apiResource('contracts', ContractController::class);

    Route::get('contracts/{contract}/tickets', [TicketController::class, 'index']);
    Route::post('contracts/{contract}/tickets', [TicketController::class, 'store']);
    Route::get('contracts/{contract}/tickets/{ticket}', [TicketController::class, 'show']);
    Route::put('contracts/{contract}/tickets/{ticket}', [TicketController::class, 'update']);
    Route::delete('contracts/{contract}/tickets/{ticket}', [TicketController::class, 'destroy']);
    Route::get('contracts/{contract}/tickets/{ticket}/close', [TicketController::class, 'close']);

    Route::get('abogado-tickets', [AbogadoTicketController::class, 'index']);
    Route::get('abogado-tickets/{ticket}', [AbogadoTicketController::class, 'show']);
    Route::put('abogado-tickets/{ticket}', [AbogadoTicketController::class, 'update']);
    Route::delete('abogado-tickets/{ticket}', [AbogadoTicketController::class, 'destroy']);
    Route::get('abogado-tickets/{ticket}/close', [AbogadoTicketController::class, 'close']);
});
