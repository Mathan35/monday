<?php

use App\Models\Logs;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckDuplicateRequest;
use App\Http\Controllers\CreateTicketController;
use App\Http\Controllers\UpdateTicketController;
use App\Http\Controllers\WebhookClientController;
use App\Http\Controllers\SuccessTicketStoreController;
use App\Http\Controllers\SuccessTicketUpdateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

#Success to Monday
<<<<<<< HEAD
Route::post('/create-ticket', CreateTicketController::class)->middleware(CheckDuplicateRequest::class)->name('create-ticket');
Route::post('/update-ticket', UpdateTicketController::class)->middleware(CheckDuplicateRequest::class)->name('update-ticket');

#Monday to Success
Route::post('/success-update-ticket', SuccessTicketUpdateController::class);
=======
Route::middleware(\App\Http\Middleware\AbortDuplicateRequest::class)->group(function () {
    Route::post('/create-ticket', CreateTicketController::class)->name('create-ticket');
    Route::post('/update-ticket', UpdateTicketController::class)->name('update-ticket');
});

Route::post('/success-update-ticket', SuccessTicketUpdateController::class)->name('success-update-ticket');
>>>>>>> 9fad4a3310e9e7588588e6f06f5fe107919e9353



