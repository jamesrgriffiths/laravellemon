<?php

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
});
Auth::routes();

Route::resource('home', 'HomeController');

// Admin only routes
Route::resource('logs', 'LogController');
Route::resource('users', 'UserController');
Route::resource('user_types', 'UserTypeController');


Route::resource('a', 'TestController');
Route::resource('b', 'TestController');
Route::resource('c', 'TestController');
Route::resource('d', 'TestController');
Route::resource('e', 'TestController');
Route::resource('f', 'TestController');

Route::resource('zoo_route', 'TestController');
