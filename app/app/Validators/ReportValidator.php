<?php namespace App\Validators;

class ReportValidator {

    protected $rules = [
		'daterange' => 'daterange'
    ];

    public function getRules() {
        return $this->rules;
    }

}