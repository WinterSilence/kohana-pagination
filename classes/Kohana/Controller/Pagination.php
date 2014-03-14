<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller class, creates [HMVC triad](http://wikipedia.org/wiki/HMVC) containing of pagination links. 
 * For more information, see [guide](pagination/usage/hmvc).
 *
 * @package   Kohana/Pagination
 * @category  Controller
 * @author    Kohana Team
 * @copyright (c) 2008-2014 Kohana Team
 * @license   http://kohanaframework.org/license
 */
abstract class Kohana_Controller_Pagination extends Controller {

	/**
	 * @var boolean Whether to checks cache?
	 */
	protected $_check_cache = TRUE;

	/**
	 * Display pagination.
	 * 
	 * @return void
	 */
	public function action_index()
	{
		// Get parameters from current [Request]
		$config_group = $this->request->param('config_group');
		$total_items = $this->request->param('total_items');

		// Create [Pagination] object
		$pagination = Pagination::factory($total_items, $config_group);

		// Send compiled HTML code of pagination in [Response]
		$this->response->body($pagination->render());

		// If cache is enabled, check browser cache using [ETag](http://wikipedia.org/wiki/HTTP_ETag)
		if (Kohana::$caching === TRUE AND $this->_check_cache === TRUE)
		{
			$tag = sha1($this->request->referrer().$this->request->uri());
			$this->check_cache($tag);
		}
	}

}
