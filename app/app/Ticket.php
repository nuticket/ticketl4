<?php namespace App;

use Str;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Ticket extends Eloquent {

	protected $table = 'tickets';


	 protected $fillable = ['id', 'last_action_at', 'subject', 'user_id', 'priority', 'staff_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public static function getByQuery($query, $user_id = null) {

		//defaults
		$per_page = isset($query['per_page']) ? $query['per_page'] : null;
		unset($query['per_page']);

		$tickets = new self;
		$tickets = $tickets->select('tickets.id as id', 'last_action_at', 'subject', 'users.display_name as user', 'priority', 'su.display_name as staff');
		$tickets = $tickets->join('users', 'users.id', '=', 'tickets.user_id');
		$tickets = $tickets->join('staff', 'staff.id', '=', 'tickets.staff_id');
		$tickets = $tickets->join('users as su', 'su.id', '=', 'staff.user_id');

		if (isset($query['sort']) && isset($query['order'])) {

			$tickets = $tickets->orderBy($query['sort'], $query['order']); 

			unset($query['sort'], $query['order']);

		}



		if (isset($query['q'])) {
			$terms = explode('-', Str::slug($query['q']));

			foreach ($terms as $term) {

				$tickets = $tickets->where(function($query) use ($term) {

					$query->where('tickets.id', 'LIKE', '%' . $term . '%')
						->orWhere('subject', 'LIKE', '%' . $term . '%')
						->orWhere('description', 'LIKE', '%' . $term . '%')
						->orWhere('description', 'LIKE', '%' . $term . '%')
						->orWhere('users.display_name', 'LIKE', '%' . $term . '%')
						->orWhere('users.username', 'LIKE', '%' . $term . '%')
						->orWhere('su.display_name', 'LIKE', '%' . $term . '%')
						->orWhere('su.username', 'LIKE', '%' . $term . '%');
					});
			}

			unset($query['q']);
		}

		// dd($query);
		// do rest
		foreach ($query as $param => $value) {


			// $array = $value;

			// if (!is_array($array)) {
			// 	$array = [$value];
			// }

			$tickets = $tickets->whereIn($param, explode('-', $value));
			
		}

		return $tickets->paginate($per_page);
	}



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

}
