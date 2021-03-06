<?php

namespace App\Validators;

use Crhayes\Validation\ContextualValidator;

class TicketActionValidator extends ContextualValidator
{
    protected $rules = [
    	'default' => [
            'ticket_id' => ['required', 'numeric', 'exists:tickets,id']
    	],
        'reply' => [
            'reply_body' => 'required|min:3',
            'reply_status' => 'required|in:closed,open,resolved', 
            'reply_time' => 'numeric',
        ],
        'comment' => [ 
            'comment_body' => 'required|min:3',
            'comment_time' => 'numeric',
        ],
        'transfer' => [
            'transfer_body' => 'required|min:3',
            'transfer_id' => ['required', 'numeric', 'exists:depts,id']
        ],
        'assign' => [
            'assign_body' => 'required|min:3',
            'assigned_id' => ['required', 'numeric', 'exists:staff,id'],
        ]
        
    ];

}