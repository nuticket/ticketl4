<?php namespace App\Services\Ldap;

class LocalUser {

	public function infoCollection($username, $fileds = array('*')) {
		return (object) [
			'samaccountname' => 'hfletcher',
			'displayname' => 'Hugh Fletcher',
			'distinguishedname' => 'CN=Hugh Fletcher,OU=TT_USERS,DC=hlc,DC=local',
			'memberof' => [
				'CN=Weekend Support,CN=Users,DC=hlc,DC=local',
				'CN=RemoteAccessUsers,OU=Groups,DC=hlc,DC=local',
				'CN=HLC-IT,CN=Users,DC=hlc,DC=local',
				'CN=TT-IT,CN=Users,DC=hlc,DC=local',
				'CN=HLC_All Personnel,CN=Users,DC=hlc,DC=local',
				'CN=TT-All Personnel,CN=Users,DC=hlc,DC=local',
  				'CN=TT-Parts Personnel,CN=Users,DC=hlc,DC=local',
				'CN=TT-Service Mgr.,CN=Users,DC=hlc,DC=local',
				'CN=TT_UNION CITY,OU=TT_SECURITY_GROUPS,DC=hlc,DC=local',
  				'CN=Domain Admins,CN=Users,DC=hlc,DC=local',
			]
		];
	}
}