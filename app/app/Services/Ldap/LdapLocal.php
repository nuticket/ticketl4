<?php namespace App\Services\Ldap;

use App\Services\Ldap\LocalUser;

class LdapLocal {

	public function user() {
		return new LocalUser;
	}

	public function authenticate($username, $password) {
		return true;
	}
}