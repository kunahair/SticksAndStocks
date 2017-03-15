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

	$stock = new Stock(array(
		'stock_symbol' => 'NAB',
		'stock_name' => 'National Australia Bank',
		'current_price' => 0.0,
		'history' => json_encode(array())
	));
	$stock->save();
    
	return view('welcome');
});
