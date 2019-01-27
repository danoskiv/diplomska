<?php

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

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('stations', 'StationsController');

Route::get('/rawdata', 'SensorDataController@index');
Route::post('/rawdata', 'SensorDataController@show');

Route::get('/predictions/', 'PredictionsController@index');
Route::post('/predictions/{id}', 'PredictionsController@show');

Route::get('/weatherData', 'WeatherDataController@index');
Route::post('/weatherData/{id}', 'WeatherDataController@show');