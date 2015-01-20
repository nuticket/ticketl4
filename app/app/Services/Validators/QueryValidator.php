<?php

namespace App\Services\Validators;

use Crhayes\Validation\ContextualValidator;

class QueryValidator extends ContextualValidator
{
    protected $rules = [
    	'default' => [
    		'sort' => 'required_with:order|in:@fields', 
	        'order' => 'required_with:sort|in:asc,desc', 
	        'per_page' => 'numeric'
    	],
        'tickets' => [
        	'status' => ['regex:/^(-?(open|closed|new)){1,3}$/'],
        	'staff_id' => ['regex:/^\d+(-\d+)*$/']
        ]
        
    ];

}