<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class TicketAction extends Eloquent {

	// protected $table = 'staff';

	protected $fillable = [
        'ticket_id',
        'user_id', 
        'type',
        'worked_hrs',
        'message',
        'assigned_id',
        'transfer_id'
    ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];


	public function user() {
        return $this->belongsTo('App\User');
    }

	public function transfer() {
        return $this->belongsTo('App\TicketDept', 'transfer_id', 'id');
    }

	public function assigned() {
        return $this->belongsTo('App\Staff', 'assigned_id', 'id');
    }

}
