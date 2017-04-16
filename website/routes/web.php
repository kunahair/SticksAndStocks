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
use App\Stock;

Route::get('/', function () {
	return view('home');
});
Route::get('stockinfo', function () {
    return view('stockinfo');
});
Route::get('contact', function () {
    return view('contact');
});

Route::get('dashboard', function () {
	return view('dashboard');
})->middleware('auth');

Route::get('stock/{id}', 'ShowStock');

Auth::routes();

Route::get('logout', function () {
	Auth::logout();
	return redirect('/');
});

// Takes two parameters, user and email
// Also requires that there is a CSRF header on the webpage.
Route::post('editUser', 'UserAccountController@edit');
//API call version of the editUser functionality, used for AJAX calls
Route::post('api/editUser', 'UserAccountController@apiEdit');

// Takes one parameters, username
// Also requires that there is a CSRF header on the webpage.
Route::post('createTA', 'TradeAccountController@create');

// Takes two parameters, id and username
// The id show be the id of the Trade Account
Route::post('editTA', 'TradeAccountController@edit');

Route::get('tradeaccount/{accountId}', 'TradeAccountController@view');

//API call to add a new buy transaction for the current Trade Account
Route::post('api/addBuyTransaction', 'TransactionController@apiAddBuyTransaction');

//Get the Count of stock held for a particular Trade Account, via POST data
Route::post('api/getTradeAccountStockQuantity', 'TransactionController@getSingleStockQuantity');

Route::post('api/broker/buy', 'BrokerController@buy');
Route::post('api/broker/sell', 'BrokerController@sell');
