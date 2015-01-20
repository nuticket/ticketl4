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

if ( ! function_exists('old'))
{
	function old($string) {
		
		return app('request')->old($string);

	}
}

if ( ! function_exists('user'))
{
	function user($string) {
		
		return app('auth')->user()->{$string};

	}
}

if ( ! function_exists('datetime'))
{
	function datetime($time) {
		
		return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $time)->format(config('site.date_time_format'));
		
	}
}

if ( ! function_exists('sort_url'))
{
	function sort_url($field) {
		
		$query = app('request')->query();
		$order = 'desc';

		if (isset($query['sort']) && $query['sort'] == $field) {

			$order = $query['order'] == 'desc' ? 'asc' : 'desc';

		}

		$query['sort'] = $field;
		$query['order'] = $order;
		

		return route(app('router')->currentRouteName(), array_except($query, ['_url']));
		
	}
}

if ( ! function_exists('order'))
{
	function order($field, $default = null, $prefix = null) {
		
		$query = app('request')->query();
		if (isset($query['sort']) && $query['sort']== $field) {
			return $prefix . $query['order'];
		}
		
		return $default;
	}
}
// if ( ! function_exists('is_staff'))
// {
// 	function is_staff($id == null) {

// 		$id = is_null($id) ? user('id') : $id;
		
// 		return App\Staff::getByUserId($id);
// 	}
// }

