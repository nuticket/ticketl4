<?php namespace App\Controllers;

use App\Repositories\TicketInterface;
use App\Repositories\TicketActionInterface;
use App\Validators\QueryValidator;
use App\Validators\TicketValidator;
use View, Request, Str, Auth, Redirect;

class TicketsController extends BaseController {

	public function __construct(TicketInterface $ticket, QueryValidator $queryValidator, TicketValidator $ticketValidator, TicketActionInterface $action) {

		$this->tickets = $ticket;
		$this->action = $action;
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
				->whereDept(array_filter(explode('-', Request::get('dept_id'))))
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

		$ticket = $this->tickets->create(array_except($attrs, ['time_spent']));
		$attrs['ticket_id'] = $ticket['id'];
		$attrs['status'] = $ticket['status'];

		if ($attrs['reply_body'] != '') { 
			$attrs['body'] = $attrs['reply_body'];
			$attrs['type'] = in_array($attrs['status'], ['closed', 'resolved']) ? $attrs['status'] : 'reply';
			$this->action->createAndUpdateTicket($attrs);
			unset($attrs['time_spent']);
		}

		if ($attrs['comment_body'] != '') { 
			$attrs['body'] = $attrs['comment_body'];
			$attrs['type'] = 'comment';
			$this->action->createAndUpdateTicket($attrs);
		}

		return Redirect::route('tickets.show', [$ticket['id']])
			->with('message', 'Ticket #' . $ticket['id'] . ' sucessfully created.');

	}

	public function edit($id) {
		$ticket = $this->tickets->find($id);
		return View::make('tickets.edit', compact('ticket'));
	}

	public function update($id) {

		$validator = $this->ticketValidator->make(Request::all())->addContext('edit');

		if ($validator->fails()) {

			return Redirect::route('tickets.edit', [$id])
		  		->withErrors($validator)
		  		->withInput(); 
		}

		$attrs = $validator->getAttributes();

		//get old ticket/sction data so we can see if anything is changing
		$old_ticket = $this->tickets->find($id);
		$old_create = $this->action->findTicketCreate($id);

		//lets see what's exactly changing
		$changed = [];
		
		if ($attrs['user_id'] != $old_ticket['user_id']) {
			$changed[] = 'user_id';
		}	

		if ($attrs['priority'] != $old_ticket['priority']) {
			$changed[] = 'priority';
		}	

		if ($attrs['title'] != $old_create['title']) {
			$changed[] = 'title';
		}

		if ($attrs['body'] != $old_create['body']) {
			$changed[] = 'body';
		}

		//no changes, user is wasting our time
		if (empty($changed)) {
	
			return Redirect::route('tickets.edit', [$id])
				->with('message', 'There were no changes made to this ticket.')
				->withInput();
		}

		//update ticket/create action
		$this->tickets->update($id, array_only($attrs, ['user_id', 'priority']));
		$this->action->update($old_create['id'], array_only($attrs, ['body', 'title']));

		//lets create a edit action
		$body = '';
		foreach ($changed as $change) {
			$body .= $change . ' changed from ' . $old_ticket[$change] . ' to ' . $attrs[$change] . "\n";
		}

		$new_action = $this->action->create([
			'ticket_id' => $id,
			'user_id' => Auth::user()->id,
			'type' => 'edit',
			'body' => $body . "\n" . $attrs['reason']
		]);

		return Redirect::route('tickets.show', [$id, '#action-' . $new_action['id']]);
	}

}