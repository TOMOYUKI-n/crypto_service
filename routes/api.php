<?php

use Illuminate\Http\Request;
use App\Temp;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => 'api'], function () {
//     Route::post('/account', 'IndexController@follows');
// });

// Route::middleware(['cors'])->group(function () {
//     Route::options('accounts', function () {
//         return response()->json();
//     });
//     //Route::post('accounts', 'AccountController@create');
//     Route::post('/account', 'IndexController@account');
// });
Route::middleware('api')->get('/account', function(Request $request) {
    return Temp::orderBy('id_str', 'desc')->paginate(50);
});