<?php namespace App\Controllers;

use \Auth, \Input, \Redirect;

use Gigabill\Repositories\Interfaces;
use \View;

class SessionController extends BaseController {

    public function getStart() {
        return View::make('session.start');
    }

    public function postStart() {

        try {
            Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')));
            return Redirect::intended('/');
        } catch (\InvalidArgumentException $e) {
            return Redirect::to('session/start')
                ->with('message', 'Your username/password combination was incorrect')
                ->withInput();
        }
    }

    public function getEnd() {
        Auth::logout();
        return Redirect::to('session/start');
    }

}