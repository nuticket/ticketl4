<?php namespace App\Repositories\Eloquent;

use App\Repositories\TicketInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Ticket;
use App\TicketAction;
use Auth;

class TicketRepository extends BaseRepository implements TicketInterface {

	/**
	 * Create the TicketRepository instance.
	 * 
	 * @param App\Ticket
	 */
	public function __construct(Ticket $model, TicketAction $action) {

		$this->model = $model;
		$this->action = $action;

	}
	/**
	 * Create a ticket and/or a reply/comment action
	 * 		
	 * @param  string $attrs 
	 * @return array 
	 */
	public function create($attrs) {

		//if no comment/reply status remains new
		$attrs['status'] = $attrs['reply_body'] == '' && $attrs['comment_body'] == '' ? 'new' : $attrs['status'];

		//create ticket
		$ticket = $this->model->create($attrs);

		//create action type - create
		$action = ['ticket_id' => $ticket->id, 'user_id' => Auth::user()->id];

		$this->action->create(array_merge($action, ['type' => 'create', 'title' => $attrs['title'], 'body' => $attrs['body']]));

		//set a reply/comment time spent attr
		// $action['time_spent'] = $ticket->time_spent;

		//only title in create action
		// unset($action['title']);

		// if ($attrs['reply'] != '') {

		// 	$type = 'reply';

		// 	$type = $attrs['status'] == 'closed' ? 'close' : $type;
		// 	$type = $attrs['status'] == 'resolved' ? 'resolve' : $type;

		// 	$this->action->create(array_merge($action, ['body' => $attrs['reply'], 'type' => $type]));

		// 	unset($action['time_spent']);
		// 	$attrs['status'] == null;

		// }

		// if ($attrs['comment'] != '') {

		// 	$type = 'comment';

		// 	$type = $attrs['status'] == 'closed' ? 'close' : $type;
		// 	$type = $attrs['status'] == 'resolved' ? 'resolve' : $type;

		// 	$this->action->create(array_merge($action, ['body' => $attrs['comment'], 'type' => $type]));

		// }

		return $ticket;
	}

	/**
	 * Find record by id.
	 * 
	 * @param  string $id
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public function find($id) {
		return $this->model->find($id);
	}

	/**
	 * Create the select.
	 * 
	 * @return $this;
	 */
	public function select() {

		$this->model = $this->model->select('tickets.id as id', 'last_action_at', 'ticket_actions.title as subject', 'users.display_name as user', 'priority', 'su.display_name as staff')
			->join('users', 'users.id', '=', 'tickets.user_id')
			->join('staff', 'staff.id', '=', 'tickets.staff_id')
			->join('users as su', 'su.id', '=', 'staff.user_id')
			->join('ticket_actions','ticket_actions.ticket_id', '=', 'tickets.id')
			->where('ticket_actions.type', 'create');		

		return $this;

	}

	/**
	 * Get a paginator for the "select" statement.
	 *
	 * @param  int    $per_page
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function paginate($per_page) {

		return $this->select()->model->paginate($per_page);
	}
	/**
	 * Filter the query by creation date range
	 * 
	 * @param  string $start
	 * @param  string $end
	 * @param  string $table
	 * @return parent
	 */
	public function whereCreated($start = null, $end = null, $table = null) {

		return parent::whereCreated($start, $end, 'tickets');
	}

	/**
	 * Search filter on query
	 * 
	 * @param  array $query
	 * @param  array $cols
	 * @return $this
	 */
	public function whereSearch(array $query = [], array $cols = []) {

		$search = ['tickets.id', 'subject', 'description', 'users.display_name', 'users.username', 'su.display_name'];

		return parent::whereSearch($query, $search);
	}

	/**
	 * Ticket status filter on the query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function whereStatus(array $values) {

		return parent::where('status', $values);
	}

	/**
	 * Ticket priority filter on query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function wherePriority(array $values) {

		return $this->where('priority', $values);
	} 

	/**
	 * Ticket staff filter on query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function whereStaff(array $values) {

		return $this->where('staff_id', $values);
	} 

	/**
	 * Ticket dept filter on query.
	 * 
	 * @param  array $values
	 * @return $this
	 */
	public function whereTicketDept(array $values) {

		return $this->where('ticket_dept_id', $values);
	} 

	/**
	 * User filter on query.
	 * 	
	 * @param  string $id
	 * @return $this
	 */
	public function whereUser($id = '*') {

		if ($id == '*') {

			return $this;
			
		}

		$this->model = $this->model->where('tickets.user_id', $id);

		return $this;

	}

	/**
	 * Update ticket by a reply ticket action
	 * 
	 * @param  array App\TicketAction::toArray() + $action['status']
	 * @return array App\Ticket::toArray() + $['old_status']
	 */
	public function updateByReply(array $action) {

		$ticket = $this->model->find($action['ticket_id']);
		$ticket_array['old_status'] = $ticket->status;

		$ticket->last_action_at = $action['created_at'];
		$ticket->time_spent += $action['time_spent'];
		$ticket->status = $action['status'];

		if ($action['status'] == 'open') {
			$ticket->closed_at = null;
		} else {
			$ticket->closed_at = $action['created_at'];
		}

		$ticket->save();

		return array_merge($ticket->toArray(), $ticket_array);
	}

	/**
	 * Update ticket by a comment ticket action.
	 * 
	 * @param  array $action App\TicketAction::toArray()
	 * @return App\Ticket
	 */
	public function updateByComment(array $action) {

		$ticket = $this->model->find($action['ticket_id']);

		$ticket->last_action_at = $action['created_at'];
		$ticket->time_spent += $action['time_spent'];

		$ticket->save();

		return $ticket;
	}

	/**
	 * Update ticket by a transfer ticket action.
	 * 
	 * @param  array $action App\TicketAction::toArray()
	 * @return App\Ticket
	 */
	public function updateByTransfer(array $action) {

		$ticket = $this->model->find($action['ticket_id']);

		$ticket->last_action_at = $action['created_at'];
		$ticket->dept_id = $action['transfer_id'];

		$ticket->save();

		return $ticket;
	}

	/**
	 * Update ticket by a assign ticket action.
	 * 
	 * @param  App\TicketAction $assign
	 * @return App\Ticket
	 */
	public function updateByAssign(TicketAction $assign) {

		$ticket = $this->model->find($assign->ticket_id);

		$ticket->last_action_at = $assign->created_at;
		$ticket->staff_id = $assign->assigned_id;

		$ticket->save();

		return $ticket;
	}


}