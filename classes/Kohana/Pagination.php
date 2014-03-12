<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Base class, generates pagination links.
 *
 * @package   Kohana/Pagination
 * @category  Base
 * @author    Kohana Team
 * @copyright (c) 2008-2014 Kohana Team
 * @license   http://kohanaframework.org/license
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
		'page'      => array('source' => 'query', 'key' => 'page'), 
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
	 * Marker replaced with the actual page number
	 */
	const PAGE_MARKER = '10101';

	/**
	 * @val string Pattern for generating links
	 */
	protected $uri_pattern;

	/**
	 * @val string Link to first page, used if `config['first_page_in_uri']` set as FALSE
	 */
	protected $first_page_uri;

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
		// Load config group
		$config = Kohana::$config->load('pagination')->get($group);
		// If group not exist, throw exception
		if ($config === NULL)
		{
			throw new Kohana_Exception(
				':method: config group `pagination.:group` does not exist',
				array(':method' => __METHOD__, ':group' => $group)
			);
		}
		// Merge base and group configs
		$this->_config = array_merge($this->_config, $config);

		// Get request options
		$params = $request->param();
		$query  = $request->query();

		$page_key = $this->_config['page']['key'];

		if ($this->_config['page']['source'] == 'query')
		{
			$this->_current_page = (int) $request->query($page_key, 1);
			$query[$page_key] = Pagination::PAGE_MARKER;
		}
		else
		{
			$this->_current_page = (int) $request->param($page_key, 1);
			$params[$page_key] = Pagination::PAGE_MARKER;
		}

		// Create pattern to quickly generating links
		$this->uri_pattern = $request->route()->uri($params).URL::query($query, FALSE);

		// Create link for for extra case: first page not displayed
		if ( ! $this->_config['first_page_in_uri'])
		{
			if ($this->_config['page']['source'] == 'query')
			{
				unset($query[$page_key]);
			}
			else
			{
				$params[$page_key] = NULL;
			}
			$this->first_page_uri = $request->route()->uri($params).URL::query($query, FALSE);
		}

		// Calculate pagination properties
		$this->_total_items    = max(0,  (int) $total_items);
		$this->_items_per_page = max(1, (int) $this->_config['items_per_page']);
		$this->_total_pages    = max(1, ceil($this->_total_items / $this->_items_per_page));
		$this->_current_page   = min(max(1, (int) $this->_current_page), $this->_total_pages);
		$this->_previous_page  = ($this->_current_page > 1 ? ($this->_current_page - 1) : FALSE);
		$this->_next_page      = ($this->_current_page < $this->_total_pages ? ($this->_current_page + 1) : FALSE);
		$this->_first_page     = ($this->_current_page === 1 ? FALSE : 1);
		$this->_last_page      = ($this->_current_page >= $this->_total_pages ? FALSE : $this->_total_pages);
		$this->_offset         = (($this->_current_page - 1) * $this->_items_per_page);
	}

	/**
	 * Generates link for page.
	 *
	 * @param  integer $page Page number
	 * @return string
	 */
	public function uri($page = 1)
	{
		// Clean the page number
		$page = min($this->_total_pages, max(1, (int) $page));

		if ($page == 1 AND ! $this->_config['first_page_in_uri'])
		{
			// Not page number in URI to first page
			return $this->first_page_uri;
		}

		// Substitutes the page number in pattern
		return str_replace(Pagination::PAGE_MARKER, $page, $this->uri_pattern);
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
		return $view->set('k_pagination', $this)->render();
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
