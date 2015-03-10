<?php namespace App;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, SoftDeletingTrait;

	protected $fillable = [
        'username', 
        'password', 
        'first_name', 
        'last_name', 
        'display_name', 
        'email',
        'adldap_guid',
        'adldap_updated_at',    ];

    protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function staff() {
        return $this->hasOne('App\Staff');
        	// ->rememberForever()
            // ->CacheTags('staff' . Cfg::get('app.id'));
    }

}
