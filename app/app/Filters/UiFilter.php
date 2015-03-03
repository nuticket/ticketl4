<?php namespace App\Filters;

use Illuminate\Foundation\Application;
use App\Services\Menu;

class UiFilter {

	public function __construct(Application $app, Menu $menu) {

		$this->app = $app;
		$this->menu = $menu;

	}

	public function filter() {

		$this->loadComposers();
		$this->menu->make('nav');

	}

	private function loadComposers() {

		$this->app['view']->composers(array(
		    'App\\Composers\\TicketsComposer' => ['tickets.list', 'tickets.show', 'tickets.create'],
		    'App\\Composers\\UserComposer' => ['tickets.create'],
		    'App\\Composers\\DeptComposer' => ['tickets.list', 'tickets.show', 'tickets.create', 'reports.index'],
		    'App\\Composers\\StaffComposer' => ['tickets.create', 'tickets.show']
		));
	}

}