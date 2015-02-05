<?php namespace App\Support;

use Illuminate\Validation\Validator;
use App\Ticket;
use Illuminate\Support\Facades\Auth;

class AppValidator extends Validator {

    public function validateCanReply($attribute, $value, $parameters)
    {
        if (Auth::user()->staff) {
        	return true;
        }

        if (Ticket::checkUserTicket($value, Auth::user()->id)) {
        	return true;
        }

        return false;
    }

}