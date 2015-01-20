<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Staff extends Eloquent {

	protected $table = 'staff';

	protected $fillable = [];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public static function getByUserId($id) {
		return self::where('user_id', $id)->first();
	}

	public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
