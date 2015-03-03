<?php namespace App\Services;

use Illuminate\Validation\Validator;
use App\Ticket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ValidationRules extends Validator {

    public function validateCanReply($attribute, $value)
    {
        if (Auth::user()->staff) {
        	return true;
        }

        if (Ticket::checkUserTicket($value, Auth::user()->id)) {
        	return true;
        }

        return false;
    }

    public function validateDateRange($attribute, $value) {

        $range = explode('-', $value);

        if (!$this->validateDateFormat($attribute, trim($range[0]), ['m/d/Y']) || !$this->validateDateFormat($attribute, trim($range[1]), ['m/d/Y'])) {
            return false;
        }

        if (!$this->validateDate($attribute, trim($range[0])) || !$this->validateDate($attribute, trim($range[1]))) {
            return false;
        }

        $start = Carbon::createFromFormat('m/d/Y', trim($range['0']));
        $end = Carbon::createFromFormat('m/d/Y', trim($range['1']));

        return $start->lt($end);
    }

}