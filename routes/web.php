<?php

use App\Http\Controllers\CreateTicketController;
use App\Http\Controllers\SuccessTicketStoreController;
use App\Http\Controllers\SuccessTicketUpdateController;
use App\Http\Controllers\UpdateTicketController;
use App\Http\Controllers\WebhookClientController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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
Route::post('/create-ticket', CreateTicketController::class);

Route::post('/update-ticket', UpdateTicketController::class);

Route::post('/success-update-ticket', SuccessTicketUpdateController::class);






