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

Route::prefix('prodi')->name('prodi')->group(function () {
    Route::get('', 'ProdiController@index');
    Route::get('/datatable', 'ProdiController@datatable')->name('.datatable');
    Route::post('/store', 'ProdiController@store')->name('.store');
    Route::get('/get', 'ProdiController@get')->name('.get');
    Route::put('/update', 'ProdiController@update')->name('.update');
    Route::put('/update-kaprodi', 'ProdiController@updateKaprodi')->name('.update_kaprodi');
    Route::delete('/delete', 'ProdiController@delete')->name('.delete');
});

Route::prefix('option')->name('option')->group(function () {
    Route::get('', 'OptionController@index');
    Route::post('/update-password', 'OptionController@updatePassword')->name('.updatePassword');
});
