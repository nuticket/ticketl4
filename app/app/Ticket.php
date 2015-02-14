<?php namespace App;

use Str;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class Ticket extends Eloquent {

	protected $table = 'tickets';


	 protected $fillable = ['id', 'last_action_at', 'subject', 'user_id', 'priority', 'staff_id', 'status'];



	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

	public function staff() {
        return $this->belongsTo('App\Staff', 'staff_id', 'id');
    }

	public function dept() {
        return $this->belongsTo('App\TicketDept', 'ticket_dept_id', 'id');
    }

	public function actions() {
        return $this->hasMany('App\TicketAction')->orderBy('created_at', 'asc');
    }

    public static function getOpenCount($id = false) {

    	$ticket = self::wherein('status', ['open', 'new']);

    	$ticket = $id ? $ticket->where('user_id', $id) : $ticket;

    	return $ticket->count();
    }
    
    public static function getClosedCount($id = false) {

    	$ticket = self::where('status', 'closed');

    	$ticket = $id ? $ticket->where('user_id', $id) : $ticket;

    	return $ticket->count();
    }

    public static function getAssignedCount($staff_id) {

    	return self::wherein('status', ['open', 'new'])->where('staff_id', $staff_id)->count();
    }

    public static function checkUserTicket($ticket_id, $user_id) {
    	return self::where('id', $ticket_id)->where('user_id', $user_id)->count();
    }

}
