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

Route::group(['namespace' => 'App\\Controllers'], function() {

	Route::group(['before' => 'ui|csfr'], function() {

	
		// Route::get('session/start', array('as' => 'session.start', 'uses' => 'SessionController@getStart'));
		// Route::post('session/start', array('as' => 'session.post', 'uses' => 'SessionController@postStart'));
		Route::resource('session', 'SessionController', ['only' => ['store', 'create', 'index']]);
		

		Route::group(array('before' => 'auth'), function() {

			// Route::get('session/end', array('as' => 'session.end', 'uses' => 'SessionController@getEnd'));
			Route::get('/', array('as' => 'dash.index', 'uses' => 'DashController@getIndex'));

			Route::resource('tickets', 'TicketsController', ['except' => ['destroy']]); 
			
			Route::post('actions/{type?}', array('as' => 'actions.store', 'uses' => 'TicketActionsController@store'))->where('type', '(reply|comment|transfer|assign)');

			// Route::get('report/{report}', array('as' => 'report.index', 'uses' => 'ReportController@index'));

			Route::resource('reports', 'ReportsController', ['only' => ['index', 'show']]); 
			Route::resource('dev', 'DevController', ['only' => ['index']]); 
		});

	});

	Route::group(['namespace' => 'Api', 'prefix' => 'api', 'before' => 'auth|csfr'], function() {


		Route::resource('users', 'UsersController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
		Route::resource('tickets', 'TicketsController', ['except' => ['index', 'create', 'store', 'show', 'edit', 'destroy']]);

	});

});