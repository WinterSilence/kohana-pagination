<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Pagination links generator.
 *
 * @package    Kohana/Pagination
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2014 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_Pagination {

	/**
	 * @val string Configuration group by default
	 */
	public static $default = 'default';

	/**
	 * @val array Configuration settings
	 */
	protected $_config = array(
		'current_page'      => array('source' => 'query', 'key' => 'page'), 
		'items_per_page'    => 10,
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,
		'view'              => 'pagination/basic',
	),

	/**
	 * @val integer Current page number
	 */
	protected $_current_page;

	/**
	 * @val integer Total item count
	 */
	protected $_total_items;

	/**
	 * @val integer Number of items to show per page
	 */
	protected $_items_per_page;

	/**
	 * @val integer Total page count
	 */
	protected $_total_pages;

	/**
	 * @val integer Previous page number, sets in FALSE if the current page is the first one
	 */
	protected $_previous_page;

	/**
	 * @val integer|boolean Next page number, sets in FALSE if the current page is the last one
	 */
	protected $_next_page;

	/**
	 * @val integer|boolean First page number, sets in FALSE if the current page is the first one
	 */
	protected $_first_page;

	/**
	 * @val integer|boolean Last page number, sets in FALSE if the current page is the last one
	 */
	protected $_last_page;

	/**
	 * @val integer Query offset
	 */
	protected $_offset;

	/**
	 * @val Request [Request] object, used for create page links 
	 */
	protected $_request;

	/**
	 * Creates new object instance.
	 *
	 * @param  integer      $total_items Total items in list
	 * @param  string|NULL  $group       Config group name
	 * @param  Request|NULL $request     HTTP request
	 * @return Pagination
	 */
	public static function factory($total_items, $group = NULL, Request $request = NULL)
	{
		if ($group === NULL)
		{
			$group = Pagination::$default;
		}

		if ($request === NULL)
		{
			$request = Request::initial();
		}

		return new Pagination($total_items, $config, $request);
	}

	/**
	 * Initializes base properties.
	 *
	 * @param  integer $total_items Total items in list
	 * @param  string  $group       Config group name
	 * @param  Request $request     HTTP request
	 * @return void
	 */
	protected function __construct($total_items, $group, Request $request)
	{
		//
		$config = Kohana::$config->load('pagination')->get($group);

		if ($config === NULL)
		{
			//
			throw new Kohana_Exception(
				':method: config group `pagination.:group` does not exist',
				array(':method' => __METHOD__, ':group' => $group)
			);
		}

		// 
		$this->_config = array_merge($this->_config, $config);

		// Retrieve the current page number
		$page_key = $this->_config['current_page']['key'];
		if ($this->_config['current_page']['source'] == 'query')
		{
			$this->_current_page = (int) $request->query($page_key, 1);
		}
		else
		{
			$this->_current_page = (int) $request->param($page_key, 1);
		}

		// Calculate and clean all pagination variables
		$this->_total_items    = max(0,  (int) $total_items);
		$this->_items_per_page = max(1, (int) $this->_config['items_per_page']);
		$this->_total_pages    = max(1, ceil($this->_total_items / $this->_items_per_page));
		$this->_current_page   = min(max(1, (int) $this->_current_page), $this->_total_pages);
		$this->_previous_page  = ($this->_current_page > 1 ? ($this->_current_page - 1) : FALSE);
		$this->_next_page      = ($this->_current_page < $this->_total_pages ? ($this->_current_page + 1) : FALSE);
		$this->_first_page     = ($this->_current_page === 1 ? FALSE : 1);
		$this->_last_page      = ($this->_current_page >= $this->_total_pages ? FALSE : $this->_total_pages);
		$this->_offset         = (($this->_current_page - 1) * $this->_items_per_page);

		// 
		$this->_request = $request;
	}

	/**
	 * Generates and return the URI for a certain page.
	 *
	 * @param  integer $page Page number
	 * @return string
	 */
	public function url($page = 1)
	{
		// Clean the page number
		$page = min($this->_total_pages, max(1, (int) $page));

		// No page number in URLs to first page
		if ($page === 1 AND ! $this->_config['first_page_in_url'])
		{
			$page = NULL;
		}

		$param = $this->_request->param();

		if ($this->_config['current_page']['source'] == 'query')
		{
			$query = array($this->_config['current_page']['key'] => $page);

			return $this->_route->uri($param).URL::query($query);
		}

		$param[$this->_config['current_page']['key']] = $page;

		return $this->_route->uri($param).URL::query();
	}

	/**
	 * Checks whether the given page number exists.
	 *
	 * @param  integer $page Page number
	 * @return boolean
	 */
	public function valid_page($page)
	{
		return (Valid::digit($page) AND $page > 0 AND $page <= $this->_total_pages);
	}

	/**
	 * Renders and returns the HTML code of pagination links.
	 * 
	 * @param  mixed $view [View] filename or object or NULL for use default filename
	 * @return string
	 */
	public function render($view = NULL)
	{
		// Automatically hide pagination whenever it is superfluous
		if ($this->_config['auto_hide'] === TRUE AND $this->_total_pages <= 1)
		{
			return '';
		}

		if ( ! $view instanceof View)
		{
			if ($view === NULL)
			{
				// By default uses view filename from config
				$view = $this->_config['view'];
			}
			$view = View::factory($view);
		}

		// Send self in template and compile him
		return $view->set('pagination', $this)->render();
	}

	/**
	 * Renders and returns the HTML code of pagination links.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Returns property values.
	 *
	 * @param  string $key Property name
	 * @return mixed
	 */
	public function __get($key)
	{
		// Add prefix 'protected'
		$key = '_'.$key;

		return (isset($this->$key) ? $this->$key : NULL);
	}

}
