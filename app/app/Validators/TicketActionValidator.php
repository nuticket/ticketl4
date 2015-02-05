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
            'reply_message' => 'required|min:3',
            'reply_status' => 'required|in:closed,open,resolved', 
            'reply_hrs' => 'numeric',
        ],
        'comment' => [ 
            'comment_message' => 'required|min:3',
            'comment_hrs' => 'numeric',
        ],
        'transfer' => [
            'transfer_message' => 'required|min:3',
            'transfer_id' => ['required', 'numeric', 'exists:ticket_depts,id']
        ],
        'assign' => [
            'assign_message' => 'required|min:3',
            'assigned_id' => ['required', 'numeric', 'exists:staff,id'],
        ]
        
    ];

}