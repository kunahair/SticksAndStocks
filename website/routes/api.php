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

//Get the current stock details of selected company
Route::get('/company/{code?}/current', 'CompanyController@currentDetails');

//Get the latest days hourly history for company
Route::get('/company/{code?}/hourly', 'CompanyHistoryController@historyHour');

Route::get('/top/{count}', 'TopASXController@getList');

Route::get('/all-ords', 'AllOrdinariesController@getCurrentAllOrdinaries');

Route::get('/all-stocks', 'DatabaseStockCodes@getAllStockCodesAndCompanyNames');

Route::post('/getTransactionsInDateRange', 'TransactionController@getTransactionsInDateRange');

