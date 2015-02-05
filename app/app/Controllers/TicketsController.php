<?php namespace App\Controllers;

use App\Ticket;
use App\Validators\QueryValidator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TicketsController extends BaseController {

	// protected $layout = 'layouts.master';

	/**
	 * Display a listing of the resource.
	 * GET /app\s\dash
	 *
	 * @return Response
	 */
	public function index()
	{

		// $tickets = new Ticket; 
		$query = ['sort' => 'id', 'order' => 'desc'];
		$errors = null;

		$queryValidator = QueryValidator::make(Request::query())
			->addContext('tickets')
		    ->bindReplacement('sort', ['fields' => 'id,last_action_at,subject,user,priority,staff']);

		if ($queryValidator->fails()) {
			
		  	$errors = $queryValidator->errors();
			
		} else {
			$query = array_merge($query, array_filter($queryValidator->getAttributes()));
		}
		
		// staff or user?
		if (!Auth::user()->staff) {
			$query['tickets.user_id'] = Auth::user()->id;
		}

		$tickets = Ticket::getByQuery(array_except($query, ['_url']));
		$query = array_except($query, ['with', '_url']);
		return View::make('tickets.list', compact('query', 'errors', 'tickets'));
		
	}

	public function show(Ticket $ticket) {
		// $errors = null;
		return View::make('tickets.show', compact('ticket'));
	}

	public function create(Ticket $ticket) {
		$errors = null;
		return $this->app['view']->make('tickets.show', compact('errors', 'ticket'));
	}

}