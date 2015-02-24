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
	public function createWithAction($attrs) {

		//if no comment/reply status remains new
		$attrs['status'] = $attrs['reply'] == '' && $attrs['comment'] == '' ? 'new' : $attrs['status'];

		//create ticket
		$ticket = $this->model->create($attrs);

		//create action type - create
		$action = ['ticket_id' => $ticket->id, 'user_id' => Auth::user()->id];
		$this->action->create(array_merge($action, ['type' => 'create']));

		//set a reply/comment time spent attr
		$action['time_spent'] = $ticket->time_spent;

		//only title in create action
		unset($action['title']);

		if ($attrs['reply'] != '') {

			$this->action->create(array_merge($action, ['body' => $attrs['reply'], 'type' => 'reply']));

			unset($action['time_spent']);

		}

		if ($attrs['comment'] != '') {

			$this->action->create(array_merge($action, ['body' => $attrs['reply'], 'type' => 'reply']));

		}

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
	 * @param  App\TicketAction $reply
	 * @return App\Ticket
	 */
	public function updateByReply(TicketAction $reply) {

		$ticket = $this->model->find($reply->ticket_id);
		$old_status = $ticket->status;

		$ticket->last_action_at = $reply->created_at;
		$ticket->time_spent += $reply->time_spent;
		$ticket->status = $reply->_status;

		if ($reply->_status == 'open') {
			$ticket->closed_at = null;
		} else {
			$ticket->closed_at = $reply->created_at;
		}

		$ticket->save();
		$ticket->_old_status = $old_status;

		return $ticket;
	}

	/**
	 * Update ticket by a comment ticket action.
	 * 
	 * @param  App\TicketAction $comment
	 * @return App\Ticket
	 */
	public function updateByComment(TicketAction $comment) {

		$ticket = $this->model->find($comment->ticket_id);

		$ticket->last_action_at = $comment->created_at;
		$ticket->time_spent += $comment->time_spent;

		$ticket->save();

		return $ticket;
	}

	/**
	 * Update ticket by a transfer ticket action.
	 * 
	 * @param  App\TicketAction $transfer
	 * @return App\Ticket
	 */
	public function updateByTransfer(TicketAction $transfer) {

		$ticket = $this->model->find($transfer->ticket_id);

		$ticket->last_action_at = $transfer->created_at;
		$ticket->ticket_dept_id = $transfer->transfer_id;

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