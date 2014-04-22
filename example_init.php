<?php defined('SYSPATH') OR die('No direct script access.');

if ( ! Route::cache())
{
	Route::set(
		'paginate', 
		'paginate/<total_items>(/<config_group>)', 
		array('total_items' => '[0-9]+', 'config_group' => '[-\w]+')
	)
	->default(array(
		'controller'   => 'pagination',
		'action'       => 'index',
		'total_items'  => 0,
		'config_group' => NULL
	));
}
