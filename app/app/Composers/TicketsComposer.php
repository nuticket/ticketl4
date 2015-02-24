<?php namespace App\Composers;

use Illuminate\Foundation\Application;
use App\Ticket;
use App\Staff;
use App\TicketDept;

class TicketsComposer {

	public function __construct(Application $app) {
		$this->app = $app;
	}

    public function compose($view)
    {
        $view->with('is_staff', ($this->app['auth']->user()->staff ? true : false));
        // $view->with('staff', Staff::all()->lists('display_name', 'id'));

        $view->with('open_count', Ticket::getOpenCount(!$this->app['auth']->user()->staff ? $this->app['auth']->user()->id : null));
        $view->with('close_count', Ticket::getClosedCount(!$this->app['auth']->user()->staff ? $this->app['auth']->user()->id : null));
        $view->with('assigned_count', (!$this->app['auth']->user()->staff ? 0 : Ticket::getAssignedCount($this->app['auth']->user()->staff->id)));

        $view->with('priorities', [
            '1' => '1 - Business is stopped',
            '2' => '2 - User is stopped',
            '3' => '3 - Business is hendered',
            '4' => '4 - User is hendered',
            '5' => '5 - Increase productivity/savings',
        ]);
    }

}