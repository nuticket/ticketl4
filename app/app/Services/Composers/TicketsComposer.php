<?php namespace App\Services\Composers;

use Illuminate\Foundation\Application;
use App\Ticket;
use App\Staff;

class TicketsComposer {

	public function __construct(Application $app) {
		$this->app = $app;
	}

    public function compose($view)
    {
        $view->with('is_staff', ($this->app['auth']->user()->staff ? true : false));

        $view->with('open_count', Ticket::getOpenCount(!$this->app['auth']->user()->staff ? $this->app['auth']->user()->id : null));
        $view->with('close_count', Ticket::getClosedCount(!$this->app['auth']->user()->staff ? $this->app['auth']->user()->id : null));
        $view->with('assigned_count', (!$this->app['auth']->user()->staff ? 0 : Ticket::getAssignedCount($this->app['auth']->user()->staff->id)));

    }

}