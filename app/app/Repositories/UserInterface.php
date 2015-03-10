<?php namespace App\Repositories;

interface UserInterface {

	public function lists($value, $key = 'id');

	public function allWithTrashed();

	public function all();

	public function destroy($id);

	public function update($id, $data);

	public function insert($data);
}