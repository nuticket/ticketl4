<?php

namespace App\Validators;

use Crhayes\Validation\ContextualValidator;

class TicketValidator extends ContextualValidator
{
    protected $rules = [
    	'default' => [
            'user_id' => ['required', 'exists:users,id'],
            'priority' => ['required', 'between:1,5'],
            'title' => ['required', 'min:10'],
            'body' => ['required', 'min:10'],
    	],
        'create' => [
            'time_spent' => ['numeric'],
            'reply_body' => ['min:3'],
            'comment_body' => ['min:3'],
            'status' => ['in:open,closed,resolved'],
            'dept_id' => ['required', 'exists:depts,id'],
            'staff_id' => ['exists:staff,id'],
        ],
        'edit' => [
            'reason' => ['required', 'min:5']
        ]
    ];

    protected function addConditionalRules($validator)
    {
        $validator->sometimes(['reply_body', 'comment_body'], 'required_with:time_spent,status', function($input)
        {
            return $input->reply_body == '' && $input->comment_body == '';
        });
    }

}