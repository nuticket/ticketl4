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

Route::group(['namespace' => 'App\\Controllers', 'before' => 'ui'], function() {

	
	Route::get('session/start', array('as' => 'session.start', 'uses' => 'SessionController@getStart'));
	Route::post('session/start', array('as' => 'session.post', 'uses' => 'SessionController@postStart'));
	

	Route::group(array('before' => 'auth'), function() {

		Route::get('session/end', array('as' => 'session.end', 'uses' => 'SessionController@getEnd'));
		Route::get('/', array('as' => 'dash.index', 'uses' => 'DashController@getIndex'));
		Route::get('tickets', array('as' => 'tickets.index', 'uses' => 'TicketsController@index'));
		Route::get('tickets/create', array('as' => 'tickets.create', 'uses' => 'TicketsController@create'));
		Route::post('tickets/create', array('as' => 'tickets.store', 'uses' => 'TicketsController@store'));
		Route::get('tickets/{ticket}', array('as' => 'tickets.show', 'uses' => 'TicketsController@show'));
		
		Route::post('actions/{type?}', array('as' => 'actions.store', 'uses' => 'TicketActionsController@store'))->where('type', '(reply|comment|transfer|assign)');

		Route::get('report/{report}', array('as' => 'report.index', 'uses' => 'ReportController@index'));

	});

});