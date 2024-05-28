<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Illuminate\Routing\Route as RoutingRoute;

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

/* Homepage route */
Route::get('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\Organizations\LoginRegister\LoginController@index']);
Route::post('/submitLogin', ['as' => 'submitLogin', 'uses' => 'App\Http\Controllers\Organizations\LoginRegister\LoginController@submitLogin']);

Route::group(['middleware' => ['admin']], function () {

Route::get('/', [MainController::class, 'getHomepage']);

/* Movie page route */
Route::get('/log-out', ['as' => 'log-out', 'uses' => 'App\Http\Controllers\Organizations\LoginRegister\LoginController@logout']);
Route::get('/movie/{id}', [MainController::class, 'getMovie']);

});