<?php namespace App\Controllers;

use App\Repositories\TicketInterface;
use App\Repositories\TicketActionInterface;
use App\Validators\QueryValidator;
use App\Validators\TicketValidator;
use View, Request, Str, Auth, Redirect;

class TicketsController extends BaseController {

	public function __construct(TicketInterface $ticket, QueryValidator $queryValidator, TicketValidator $ticketValidator) {

		$this->tickets = $ticket;
		$this->queryValidator = $queryValidator;
		$this->ticketValidator = $ticketValidator;
	}

	/**
	 * Display a listing of the resource.
	 * GET /app\s\dash
	 *
	 * @return Response
	 */
	public function index()
	{

		$validator = $this->queryValidator->make(Request::query())
			->addContext('tickets')
		    ->bindReplacement('sort', ['fields' => 'id,last_action_at,subject,user,priority,staff']);

		if ($validator->passes()) {

			//date range
			$dates = Request::has('created_at') ? explode('-', Request::get('created_at')) : [null, null];

			$this->tickets
				->sort(Request::get('sort', 'id'), Request::get('order', 'desc'))
				->whereCreated($dates[0], $dates[1])
				->whereSearch(Request::has('q') ? explode('-', Str::slug(Request::get('q'))) : [])
				->whereStatus(array_filter(explode('-', Request::get('status'))))
				->wherePriority(array_filter(explode('-', Request::get('priority'))))
				->whereTicketDept(array_filter(explode('-', Request::get('ticket_dept_id'))))
				->whereStaff(array_filter(explode('-', Request::get('staff_id'))))
				->whereUser(Auth::user()->staff ? '*' : Auth::user()->id);

		}

		$errors = $validator->errors();

		$tickets = $this->tickets->paginate(Request::get('per_page'));
		
		return View::make('tickets.list', compact('tickets', 'errors'));
		
	}

	public function show($id) {
		$ticket = $this->tickets->find($id);
		return View::make('tickets.show', compact('ticket'));
	}

	public function create() {
		// $errors = null;
		return View::make('tickets.create');
	}

	public function store() {

		$validator = $this->ticketValidator->make(Request::all())->addContext('create');

		if ($validator->fails()) {

			$type = Request::get('comment') != '' && Request::get('reply') == '' ? 'comment' : 'reply';

			return Redirect::route('tickets.create')
		  		->withErrors($validator)
		  		->withInput(); 
		}

		$attrs = $validator->getAttributes();

		$ticket = $this->tickets->createWithAction($attrs);

		return Redirect::route('tickets.show', [$ticket['id']])
			->with('message', 'Ticket #' . $ticket['id'] . ' sucessfully created.');

	}

}