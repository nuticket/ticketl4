<?php namespace App\Repositories\Eloquent;

use App\Repositories\TicketActionInterface;
use App\Repositories\Eloquent\TicketRepository;
use App\TicketAction;
use Illuminate\Support\Facades\Auth;

class TicketActionRepository implements TicketActionInterface {

	public function __construct(TicketAction $action, TicketRepository $ticket) {
		
		$this->action = $action;
		$this->ticket = $ticket;

	}

    public function create($attr) {

        $attr['message'] = nl2br($attr['message']); //move to observer

    }

	public function createAndUpdateTicket($attr) {

		$attr['body'] = nl2br($attr[$attr['type'] . '_body']);
        $attr['user_id'] = Auth::user()->id; //move to controller
        isset($attr[$attr['type'] . '_time']) ? $attr['time_spent'] = $attr[$attr['type'] . '_time'] : null;

        $action = $this->action->create($attr);
        
        $action->_status = isset($attr['status']) ? $attr['status'] : null;

        //update ticket - move to this repo
        $ticket = call_user_func_array([$this->ticket, 'updateBy' . ucfirst($action->type)], [$action]);

        if (isset($ticket->_old_status) && $ticket->_old_status != $ticket->status) {

            $statuses = ['closed' => 'close', 'resolved' => 'resolve', 'open' => 'open'];
    		
    		$action->type = $statuses[$ticket->status];

    	}

    	unset($action->_status);
        $action->save();

        return $action;

	}

}