<?php namespace App\Repositories\Eloquent;

use App\Repositories\UserInterface;
use App\User;

class UserRepository extends BaseRepository implements UserInterface {

	public function __construct(User $user) {

		$this->user = $user;
	}

	public function lists($value, $key = 'id') {
		return $this->user->lists($value, $key);
	}


}