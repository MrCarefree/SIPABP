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

Route::prefix('user')->name('user')->group(function () {
    Route::get('', 'UserController@index');
    Route::get('/datatable', 'UserController@datatable')->name('.datatable');
    Route::post('/store', 'UserController@store')->name('.store');
    Route::get('/get', 'UserController@get')->name('.get');
    Route::put('/update', 'UserController@update')->name('.update');
    Route::delete('/delete', 'UserController@delete')->name('.delete');
});


Route::prefix('option')->name('option')->group(function () {
    Route::get('', 'OptionController@index');
    Route::post('/update-password', 'OptionController@updatePassword')->name('.updatePassword');
});

Route::prefix('pagu')->name('pagu')->group(function () {
    Route::get('', 'PaguController@index');
    Route::get('/datatable', 'PaguController@datatable')->name('.datatable');
    Route::get('/get', 'PaguController@get')->name('.get');
    Route::put('/update', 'PaguController@update')->name('.update');
});
