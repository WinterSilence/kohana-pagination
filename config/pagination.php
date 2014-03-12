<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Pagination, base settings:
 * page              `array`   Request settings for current page
 * items_per_page    `integer` The number of elements per page
 * auto_hide         `boolean` If list of items is empty pagination not displayed
 * first_page_in_uri `boolean` Add first page in link
 * view              `string`  View  filename, used for render HTML code
 */
return array(
	'default' => array(
		'page'              => array('source' => 'query', 'key' => 'page'), 
		'items_per_page'    => 10,
		'auto_hide'         => TRUE,
		'first_page_in_uri' => FALSE,
		'view'              => 'pagination/basic',
	),
	/* 
	Group - example:
	'floating' => array(
		'page'              => array('source' => 'query', 'key' => 'p'), 
		'items_per_page'    => 20,
		'auto_hide'         => TRUE,
		'first_page_in_uri' => TRUE,
		'view'              => 'pagination/floating',
		// Special settings for view 'pagination/floating':
		'count_out'         => 5, // Number of page links in the begin and end of whole range
		'count_in'          => 3,  // Number of page links on each side of current page
	),
	*/
);
