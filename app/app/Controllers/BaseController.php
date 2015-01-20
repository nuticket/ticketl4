<?php  namespace App\Controllers;

use Illuminate\Foundation\Application;

class BaseController extends \Controller {

	public function __construct(Application $app) {
		$this->app = $app;

		$this->loadComposers();
		$this->loadSideBarMenu();

	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = $this->app['view']->make($this->layout);
		}
	}

	private function loadComposers() {

		$this->app['view']->composers(array(
		    'App\\Services\\Composers\\TicketsComposer' => ['tickets.list', 'ticket.show'] 
		));
	}

	private function loadSideBarMenu() {

		$this->app['menu']->make('main', function($menu){

		  	// $menu->add('Dashboard', ['route' => 'dash.index'])->data('public', false);


		   	$menu->add('Tickets')->data('public', true);

		   	$menu->tickets->add('Tickets', ['route' => 'tickets.list'])->data('public', true);
		   	$menu->tickets->add('Create Ticket', 'tickets/create')->data('public', true);

		  	// $menu->add('Users', 'users')->data('public', false);
		  	// $menu->add('Knowledge Base',  'kb')->data('public', false);

		})->filter(function($item){
			if ($item->data('public')) { return true; }

			if (!$item->data('public') && $this->app['auth']->user()->staff) {
				return true;
			}
  			return false;
		});;
	}



}
