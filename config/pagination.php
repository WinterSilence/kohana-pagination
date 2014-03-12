<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Pagination, base settings:
 * current_page      `array`   Contain items: 'source' ('query', 'param') and 'key'
 * items_per_page    `integer` The number of elements per page
 * auto_hide         `boolean` If list of items is empty pagination not displayed
 * first_page_in_url `boolean` Add first page in URL
 * view              `string`  View  filename, used for render HTML code
 */
return array(
	'default' => array(
		'current_page'      => array('source' => 'query', 'key' => 'page'), 
		'items_per_page'    => 10,
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,
		'view'              => 'pagination/basic',
	),
	/* 
	Group - example:
	'floating' => array(
		'current_page'      => array('source' => 'param', 'key' => 'page'), 
		'items_per_page'    => 20,
		'auto_hide'         => TRUE,
		'first_page_in_url' => TRUE,
		'view'              => 'pagination/floating',
		// Special settings for view 'pagination/floating':
		'count_out'         => 5, // Number of page links in the begin and end of whole range
		'count_in'          => 3,  // Number of page links on each side of current page
	),
	*/
);
