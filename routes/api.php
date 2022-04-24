<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// // Route::post('/test', function (Request $request) {
    
// //     return Response::json($request); // Status code here

// // });

// Route::get('/testget', function (Request $request) {
    
//     return Response::json($request); // Status code here

// });




// Route::webhooks('testpost');






