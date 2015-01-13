<?php

return [
	'copy' => [
		'copy' => [
			'src' => ['vendor/bower_components/AdminLTE/css/AdminLTE.css'],
			'dest' => 'public/themes/default/assets/css/',
		],
	],
	// 'concatjs' => [
	// 	'concat' => [
	// 		'src' => ['app/assets/js/tickets.js', 'app/assets/js/default.js'],
	// 		'dest' => 'public/js/default.js'
	// 	]
	// ],
	// 'minifyjs' => [
	// 	'minify' => [
	// 		'type' => 'js',
	// 		'src' => ['public/js/default.js'],
	// 		'dest' => 'public/js/default.min.js'
	// 	]
	// ],
	// 'bustcss' => [
	// 	'buster' => [
	// 		'src' => 'css/default.css',
	// 		'dest' => 'css/default-{{hash}}.css',
	// 		'public_path' => 'public' //default
	// 	],
	// ],
	// 'bustjs' => [
	// 	'buster' => [
	// 		'src' => 'js/default.min.js',
	// 		'dest' => 'js/default-{{hash}}.js'
	// 	]
	// ],
	// 'watch' => [
	// 	'watch' => [
	// 		'minifyjs' => [
	// 			'app/assets/js/tickets.js',
	// 			'app/assets/js/default.js'
	// 		],

	// 	]
	// ],
	// 'lessz' => [
	// 	'less' => [
	// 		'src' => 'app/assetz/less/default.less',
	// 		'dest' => 'public/css/default.css',
	// 		'minify' => true
	// 	]
	// ],
	// 'watchz' => [
	// 	'watch' => [
	// 		'lessz' => [
	// 			'app/assetz/less/default.less',
	// 			'app/assetz/less/common.less',
	// 		]
	// 	]
	// ],
	// 'phpunit' => [
	// 	'phpunit' => array(
	// 		'bin_path' => '/usr/local/bin/phpunit' 
	// 	)
	// ],
	// 'deploy' => [
	// 	'run' => ['less', 'concatjs', 'minifyjs', 'bustcss', 'bustjs']
	// ]
];