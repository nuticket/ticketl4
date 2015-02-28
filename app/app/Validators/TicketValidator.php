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
            'time_spent' => ['numeric'],
            'reply_body' => ['min:3'],
            'comment_body' => ['min:3'],
            'status' => ['in:open,closed,resolved']
        ]
    ];

    protected function addConditionalRules($validator)
    {
        $validator->sometimes(['reply_body', 'comment_body'], 'required_with:time_spent,status', function($input)
        {
            return $input->reply == '' && $input->comment == '';
        });
    }

}