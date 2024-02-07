<?php

use App\Http\Controllers\API\AccountAPIController;
use App\Http\Controllers\API\TransactionAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



// Route::get('/ping', function () {
//     Route::get('/ping', [AccountAPIController::class, 'pingIndex']);
// });

Route::get('/ping', function () {
    return response('pong');
});


// accounts routes
Route::get('/accounts', [AccountAPIController::class, 'getAccounts']);
Route::post('/accounts', [AccountAPIController::class, 'createAccount']);
Route::get('/accounts/{account_id}', [AccountAPIController::class, 'getAccountByAccountId']);

// Route::group(['prefix' => '/accounts',], function(){
//     Route::get('/', [AccountAPIController::class, 'getAccounts']);
//     Route::post('/', [AccountAPIController::class, 'createAccount']);
//     Route::get('/{account_id}', [AccountAPIController::class, 'getAccountByAccountId']);
// });

  // transactions routes
  Route::get('/transactions', [TransactionAPIController::class, 'getTransactions']);
  Route::post('/transactions', [TransactionAPIController::class, 'createTransaction']);
  Route::get('/transactions/{trancation_id}', [TransactionAPIController::class, 'getTransactionByTransactionId']);

//   Route::group(['prefix' => '/transactions',], function(){
//     Route::get('/', [TransactionAPIController::class, 'getTransactions']);
//     Route::post('/', [TransactionAPIController::class, 'createTransaction']);
//     Route::get('/{trancation_id}', [TransactionAPIController::class, 'getTransactionByTransactionId']);
// });

