<?php

use Illuminate\Http\Request;

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

//Companies route, should return all companies listed on ASX stock exchange in form of JSON string
Route::get('/companies', 'CompaniesController@index');

Route::get('/company/{id?}/hourly', 'CompanyController@historyHour');
