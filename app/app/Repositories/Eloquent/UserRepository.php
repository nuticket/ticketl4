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

	public function allWithTrashed() {
		return $this->user->withTrashed()->get()->toArray();
	}

	public function all() {
		return $this->user->all()->toArray();
	}

	public function destroy($id) {
		$this->user->destroy($id);
	}

	public function update($id, $data) {
		$this->user->where('id', $id)->update($data);
	}

	public function insert($data) {
		$row = $this->user->create($data);
		return $row->id;
	}
}