<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Proclame\Monday\MondayFacade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Action\CreateTicketAction;
use App\Http\Controllers\CreateTicketController;
use App\Http\Controllers\SuccessTicketStoreController;
use App\Http\Controllers\SuccessTicketUpdateController;
use App\Http\Controllers\UpdateTicketController;
use App\Http\Controllers\WebhookClientController;

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


Route::get('/check', function(){

    $payLoad = json_decode(request()->getContent(), true);


    $token = env('MONDAY_TOKEN');
    $MondayBoard = new TBlack\MondayAPI\MondayBoard();
    $MondayBoard->setToken(new TBlack\MondayAPI\Token($token));

    $board_id = 2570123971;
    $boardColumns = $MondayBoard->on($board_id)->getColumns();
    dd($boardColumns);

    # Insert new Item on Board
    $board_id = 2570123971;
    $id_group = 'tickets';
    $column_values = [ 'text' => 'Value...' ];
});


#Success to Monday
Route::post('/create-ticket', CreateTicketController::class);

Route::post('/update-ticket', UpdateTicketController::class);

#Monday to Success
// Route::post('/get-any-changes',SuccessTicketStoreController::class);

Route::post('/success-update-ticket', SuccessTicketUpdateController::class);
    





