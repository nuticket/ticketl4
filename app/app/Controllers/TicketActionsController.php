<?php namespace App\Controllers;

use App\Repositories\TicketActionInterface;
use App\Validators\TicketActionValidator;
use Illuminate\Foundation\Application;

class TicketActionsController extends BaseController {

	public function __construct(Application $app, TicketActionInterface $action, TicketActionValidator $validator) {

		$this->app = $app;
		$this->action = $action;
		$this->validator = $validator;
	}

	public function store($type) {

		$actionValidator = $this->validator->make($this->app['request']->all())->addContext($type);

		if ($actionValidator->fails()) {

		  	return $this->app['redirect']->route('tickets.show', [$this->app['request']->input('ticket_id'), '#action'])
		  		->withErrors($actionValidator)
		  		->withInput()
		  		->with('type', $type);
			
		} else {

			$action = $this->action->create(array_merge($actionValidator->getAttributes(), ['type' => $type]));

			return $this->app['redirect']->route('tickets.show', [$action->ticket_id, '#action-' . $action->id]);
		}
	}


}