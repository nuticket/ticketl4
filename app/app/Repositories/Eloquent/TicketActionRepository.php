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

    public function create(array $attrs) {

        $attrs['body'] = nl2br($attrs[$attrs['type'] . '_body']);
        $attrs['user_id'] = Auth::user()->id; //move to controller

        return $this->action->create($attrs);
    }

    /**
     * Create Action and update ticket
     *         
     * @param  array $attrs ['type', 'body', 'status', 'ticket_id', 'time_spent']
     * @return App\TicketAction
     */
	public function createAndUpdateTicket(array $attrs) {

		$action = $this->create($attrs);
        
        $action_array = $action->toArray();

        if (isset($attrs['status'])) { $action_array['status'] = $attrs['status']; }

        //update ticket 
        $ticket = call_user_func_array([$this->ticket, 'updateBy' . ucfirst($action->type)], [$action_array]);

        if (isset($ticket['old_status']) && $ticket['old_status'] != $ticket['status']) {

            $action->type = $ticket['status'];

    	}

        $action->save();

        return $action;

	}


}