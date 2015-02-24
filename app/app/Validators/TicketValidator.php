<?php

namespace App\Validators;

use Crhayes\Validation\ContextualValidator;

class TicketValidator extends ContextualValidator
{
    protected $rules = [
    	'default' => [
            'user_id' => ['required', 'exists:users,id'],
            'dept_id' => ['required', 'exists:depts,id'],
            'staff_id' => ['exists:staff,id'],
            'priority' => ['required', 'between:1,5'],
            'title' => ['required', 'min:3'],
            'body' => ['required', 'min:3'],
    	],
        'create' => [
            'time_spent' => ['numeric', 'required_with:reply,comment'],
            'reply' => ['min:3'],
            'comment' => 'min:3',
            'status' => ['required_with:reply,comment', 'in:open,closed,resolved']
        ]
    ];

}