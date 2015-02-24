<?php namespace App\Composers;

use App\Repositories\UserInterface;

class UserComposer {

	public function __construct(UserInterface $user) {
        $this->user = $user;
	}

    public function compose($view)
    {
        $view->with('users', $this->user->lists('display_name'));

    }

}