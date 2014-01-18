<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Application defaults
	'default' => array(
		'current_page'      => array('source' => 'query_string', 'key' => 'page'), // source: "query_string" or "route"
		'total_items'       => 0,
		'items_per_page'    => 10,
		'view'              => 'pagination/bootstrap/basic',
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,

		/**
		 * for floating template
		 */
		'count_out'	=> 3,
		'count_in'	=> 5,

		/**
		 * Append class to block <ul>
		 * e.g: pagination-lg, pagination-sm, etc
		 * <ul class="pagination pagination-lg">
		 * .....
		 */
		'append_class' => NULL,

		/**
		 * if value equal NULL or FALSE, then it will be hidden
		 * e.g.
		 * 		'first_title' => 'First page', 	// First page, Previous, 1, 2, ....
		 * 		'first_title' => FALSE,			// Previous, 1, 2, ....		 *
		 */
		'first_title' 		=> 'First',
		'previous_title'	=> 'Previous',
		'next_title' 		=> 'Next',
		'last_title' 		=> 'Last',
	),

);
