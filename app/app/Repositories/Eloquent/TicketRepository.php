<?php namespace App\Repositories\Eloquent;

use App\Repositories\TicketInterface;
use App\Ticket;

class TicketRepository implements TicketInterface {

	public function __construct(Ticket $ticket) {

		$this->ticket = $ticket;

	}


	public function updateByReply($reply) {

		$ticket = $this->ticket->find($reply->ticket_id);
		$old_status = $ticket->status;

		$ticket->last_action_at = $reply->created_at;
		$ticket->worked_hrs += $reply->worked_hrs;
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

	public function updateByComment($comment) {

		$ticket = $this->ticket->find($comment->ticket_id);

		$ticket->last_action_at = $comment->created_at;
		$ticket->worked_hrs += $comment->worked_hrs;

		$ticket->save();

		return $ticket;
	}

	public function updateByTransfer($transfer) {

		$ticket = $this->ticket->find($transfer->ticket_id);

		$ticket->last_action_at = $transfer->created_at;
		$ticket->ticket_dept_id = $transfer->transfer_id;

		$ticket->save();

		return $ticket;
	}

	public function updateByAssign($assign) {

		$ticket = $this->ticket->find($assign->ticket_id);

		$ticket->last_action_at = $assign->created_at;
		$ticket->staff_id = $assign->assigned_id;

		$ticket->save();

		return $ticket;
	}


}