<?php namespace App\Controllers;

use \Auth, \Input, \Redirect;

use Gigabill\Repositories\Interfaces;
use \View;

class SessionController extends BaseController {

    public function create() {
        return View::make('session.create');
    }

    public function store() {

        try {
            Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')));
            return Redirect::intended('/');
        } catch (\InvalidArgumentException $e) {
            return Redirect::route('session.create')
                ->with('message', 'Your username/password combination was incorrect')
                ->withInput();
        }
    }

    // end session
    public function index() {
        Auth::logout();
        return Redirect::to('session/start');
    }

}