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

Route::get('', function (){
    return redirect()->route('login');
});

Route::prefix('login')->name('login')->group(function (){
    Route::get('', 'LoginController@index');
    Route::post('/process', 'LoginController@login')->name('.process');
});

Route::post('logout', 'LoginController@logout')->name('logout');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::prefix('user')->name('user')->group(function (){
    Route::get('', 'UserController@index');
});
