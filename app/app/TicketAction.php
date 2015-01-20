<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class TicketAction extends Eloquent {

	// protected $table = 'staff';

	protected $fillable = [];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function user() {
        return $this->belongsTo('App\User');
    }

}
