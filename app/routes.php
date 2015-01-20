<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::model('ticket', 'App\\Ticket');

Route::group(array('namespace' => 'App\\Controllers'), function() {

	
	Route::get('session/start', array('as' => 'session.start', 'uses' => 'SessionController@getStart'));
	Route::post('session/start', array('as' => 'session.post', 'uses' => 'SessionController@postStart'));
	

	Route::group(array('before' => 'auth'), function() {

		Route::get('session/end', array('as' => 'session.end', 'uses' => 'SessionController@getEnd'));
		Route::get('/', array('as' => 'dash.index', 'uses' => 'DashController@getIndex'));
		Route::get('tickets', array('as' => 'tickets.list', 'uses' => 'TicketsController@getList'));
		Route::get('tickets/{ticket}', array('as' => 'tickets.show', 'uses' => 'TicketsController@getShow'));
		

	});

});