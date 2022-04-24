<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Proclame\Monday\MondayFacade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Action\CreateTicketAction;
use App\Http\Controllers\CreateTicketController;
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
});


Route::get('/check', function(){

    $payLoad = json_decode(request()->getContent(), true);
// $code = $payLoad;
// logger($code);


// logger($code['code']);


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
    
    // $addResult = $MondayBoard
    //             ->on($board_id)
    //             ->group($id_group)
    //             ->addItem( 'jeevi' );

                // dd($addResult);

    // $all_boards = MondayFacade::getBoards();
    // dd($all_boards);


    $query = '
        items_by_column_values(board_id: 2570123971,column_id: "name", column_value: "12345") {
          id
          name
          column_values {
            id
            text
            title
          }
        }

    ';

# For Query
$items = $MondayBoard->customQuery( $query );
dd($items['items_by_column_values']);


});





Route::get('/check-update', function(){

    $payLoad = json_decode(request()->getContent(), true);
$code = $payLoad;
// logger($code);


// logger($code['code']);


    // $token = env('MONDAY_TOKEN');
    // $MondayBoard = new TBlack\MondayAPI\MondayBoard();
    // $MondayBoard->setToken(new TBlack\MondayAPI\Token($token));

    // $board_id = 2570123971;
    // $boardColumns = $MondayBoard->on($board_id)->getColumns();
    // dd($boardColumns);

    // # Insert new Item on Board
    // $board_id = 2570123971;
    // $id_group = 'topics';
    // $column_values = [ 'text' => 'Value...' ];
    
    // $addResult = $MondayBoard
    //             ->on($board_id)
    //             ->group($id_group)
    //             ->addItem( 'jeevi' );

                // dd($addResult);

    // $all_boards = MondayFacade::getBoards();
    // dd($all_boards);
});

Route::post('/create-ticket', CreateTicketController::class);

Route::post('/update-ticket', UpdateTicketController::class);

Route::post('/get-any-changes', function(Request $request){
    
    // logger($request['event']['value']['date']);
    // return response()->json(request());

    $payload = request()->toArray();


    $response = Http::post('https://local-api.success.test/v1/acmeinc/public/monday', $payload);


    //  $data =  response()->json('get', 'http://success-connect.test/hello', $payload);
    //  logger($data);
    

    //  $data =  response()->json(["payload"=>'http://success-connect.test/hello'. $payload]);
    //  logger($data);
    //  return $data;

});


Route::post('/hello', function(Request $request){
    
    // logger($request['event']['value']['date']);
    // return response()->json(request());

    $payLoad = json_decode(request()->getContent(), true);
    logger($payLoad);

    //  $data =  response()->json(["payload"=>'https://local-api.success.test/v1/acmeinc/public/monday'. $payload]);
    //  logger($data);
    //  return $data;

});





