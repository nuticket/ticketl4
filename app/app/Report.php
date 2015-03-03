<?php namespace App;

use DB;

class Report {

	public function __construct(DB $db) {
		$this->db = $db;
	}

}
