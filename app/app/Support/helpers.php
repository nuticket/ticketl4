<?php

if ( ! function_exists('config'))
{
	function config($string, $default = null) {
		
		return app('orchestra.memory')->make()->get($string, $default);

	}
}

if ( ! function_exists('theme'))
{
	function theme($path) {
		
		return app('orchestra.theme')->to($path);

	}
}