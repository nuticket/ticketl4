<?php namespace App\Filters;

use Illuminate\Foundation\Application;

class UiFilter {

	public function __construct(Application $app) {

		$this->app = $app;

	}

	public function filter() {

		$this->loadComposers();
		$this->loadSideBarMenu();

	}

	private function loadComposers() {

		$this->app['view']->composers(array(
		    'App\\Composers\\TicketsComposer' => ['tickets.list', 'tickets.show'] 
		));
	}

	private function loadSideBarMenu() {

		$this->app['menu']->make('main', function($menu){

		  	// $menu->add('Dashboard', ['route' => 'dash.index'])->data('public', false);


		   	$menu->add('Tickets')->data('public', true);

		   	$menu->tickets->add('Tickets', ['route' => 'tickets.index'])->data('public', true);
		   	$menu->tickets->add('Create Ticket', ['route' => 'tickets.create'])->data('public', true);

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