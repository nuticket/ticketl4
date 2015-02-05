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

		$attr['message'] = nl2br($attr[$attr['type'] . '_message']);
        $attr['user_id'] = Auth::user()->id;
        isset($attr[$attr['type'] . '_hrs']) ? $attr['worked_hrs'] = $attr[$attr['type'] . '_hrs'] : null;

        $action = $this->action->create($attr);
        
        $action->_status = isset($attr[$attr['type'] . '_status']) ? $attr[$attr['type'] . '_status'] : null;

        //update ticket
        $ticket = call_user_func_array([$this->ticket, 'updateBy' . ucfirst($action->type)], [$action]);

        if (isset($ticket->_old_status) && $ticket->_old_status != $ticket->status) {
    		
    		$action->type = $ticket->status;

    	}

    	unset($action->_status);
        $action->save();

        return $action;

	}

}