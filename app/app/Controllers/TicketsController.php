<?php namespace App\Controllers;

use App\Ticket;
use App\Services\Validators\QueryValidator;

class TicketsController extends BaseController {

	// protected $layout = 'layouts.master';

	/**
	 * Display a listing of the resource.
	 * GET /app\s\dash
	 *
	 * @return Response
	 */
	public function getList()
	{
		// $tickets = new Ticket; 
		$query = ['sort' => 'id', 'order' => 'desc', 'status' => 'new-open'];
		$errors = null;

		$queryValidator = QueryValidator::make($this->app['request']->query())
			->addContext('tickets')
		    ->bindReplacement('sort', ['fields' => 'id,last_action_at,subject,user,priority,staff']);

		if ($queryValidator->fails()) {
			
		  	$errors = $queryValidator->errors();
			
		} else {
			$query = array_merge($query, $queryValidator->getAttributes());
		}
		
		// staff or user?
		if (!$this->app['auth']->user()->staff) {
			$query['tickets.user_id'] = $this->app['auth']->user()->id;
		}

		$tickets = Ticket::getByQuery(array_except($query, ['_url']));
		$query = array_except($query, ['with', '_url']);
		return $this->app['view']->make('tickets.list', compact('query', 'errors', 'tickets'));
		
	}

	public function getShow(Ticket $ticket) {
		$errors = null;
		return $this->app['view']->make('tickets.show', compact('errors', 'ticket'));
	}

}