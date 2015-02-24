<?php namespace App\Composers;

use Illuminate\Foundation\Application;
use App\Repositories\DeptInterface;

class DeptComposer {

	public function __construct(DeptInterface $dept) {
        $this->dept = $dept;
	}

    public function compose($view)
    {
        $view->with('depts', $this->dept->lists('name'));

    }

}