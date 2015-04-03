<?php 

return [
	'Tickets' => [
		'public' => true,
		'children' => [
			'Tickets' => [
				'route' => 'tickets.index',
				'public' => true
			],
			'Create Ticket' => [
				'route' => 'tickets.create',
				'public' => true
			]
		] 
	],

	'Reports' => [
		'public' => true,
		'children' => [
			'Staff Performance' => [
				'url' => 'report/staff-performance',
				'public' => true
			],
			'Another Report' => [
				'route' => 'dash.index',
				'public' => true
			]
		]
	], 
	'Development' => [
		'public' => false,
		'url' => 'dev'
	]
];