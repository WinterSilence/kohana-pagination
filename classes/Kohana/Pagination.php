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
	 * Marker replaced with the actual page number, uses to quickly create links
	 */
	const PAGE_MARKER = '10101';

	/**
	 * @var string Configuration group by default
	 */
	public static $default = 'default';

	/**
	 * @var array Current [configuration options](pagination/config)
	 */
	protected $_config = array(
		'page'              => array('source' => 'query', 'key' => 'page'), 
		'items_per_page'    => 10,
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,
		'view'              => 'pagination/basic',
		'class'             => '',
	);

	/**
	 * @var string Pattern for generating links, uses to quickly create link
	 */
	protected $url_pattern;

	/**
	 * @var string Link to first page, is set, if Pagination::$_config['first_page_in_url'] is FALSE
	 */
	protected $url_first_page;

	/**
	 * @var integer Current page number
	 */
	protected $_current_page;

	/**
	 * @var integer Total item count
	 */
	protected $_total_items;

	/**
	 * @var integer Number of items to show per page
	 */
	protected $_items_per_page;

	/**
	 * @var integer Total page count
	 */
	protected $_total_pages;

	/**
	 * @var integer Previous page number, sets in FALSE if the current page is the first one
	 */
	protected $_previous_page;

	/**
	 * @var integer|boolean Next page number, sets in FALSE if the current page is the last one
	 */
	protected $_next_page;

	/**
	 * @var integer|boolean First page number, sets in FALSE if the current page is the first one
	 */
	protected $_first_page;

	/**
	 * @var integer|boolean Last page number, sets in FALSE if the current page is the last one
	 */
	protected $_last_page;

	/**
	 * @var integer Query offset
	 */
	protected $_offset;

	/**
	 * Create new object instance.
	 * 
	 * @param  integer      $total_items  Total number of items in the list
	 * @param  string|NULL  $config_group Name of config group
	 * @param  Request|NULL $request      HTTP request
	 * @return Pagination
	 */
	public static function factory($total_items, $config_group = NULL, Request $request = NULL)
	{
		if ($config_group === NULL)
		{
			$config_group = Pagination::$default;
		}

		if ($request === NULL)
		{
			$request = Request::initial();
		}

		return new Pagination($total_items, $config_group, $request);
	}

	/**
	 * Initializes base properties.
	 * 
	 * @param  integer $total_items  Total items in list
	 * @param  string  $config_group Name of config group 
	 * @param  Request $request      HTTP request
	 * @return void
	 */
	protected function __construct($total_items, $config_group, Request $request)
	{
		// Load config group
		$config = Kohana::$config->load('pagination')->get($config_group);
		// If group not exist, throw exception
		if ($config === NULL)
		{
			throw new Kohana_Exception(
				':method: config group `pagination.:group` does not exist',
				array(':method' => __METHOD__, ':group' => $config_group)
			);
		}
		// Merge base and group configs
		$this->_config = array_merge($this->_config, $config);

		// 
		$query  = $request->query();
		$params = $request->param();

		// 
		$params['directory']  = strtolower($request->directory());
		$params['controller'] = strtolower($request->controller());
		$params['action']     = strtolower($request->action());

		$page_key = $this->_config['page']['key'];

		if ($this->_config['page']['source'] == 'query')
		{
			$this->_current_page = $request->query($page_key);
			$query[$page_key] = Pagination::PAGE_MARKER;
		}
		else
		{
			$this->_current_page = $request->param($page_key);
			$params[$page_key] = Pagination::PAGE_MARKER;
		}

		// Create pattern to quickly generating links
		$this->url_pattern = $request->route()->uri($params).URL::query($query, FALSE);

		// Create link for for extra case: first page not displayed
		if ( ! $this->_config['first_page_in_url'])
		{
			if ($this->_config['page']['source'] == 'query')
			{
				unset($query[$page_key]);
			}
			else
			{
				unset($params[$page_key]);
			}
			$this->url_first_page = $request->route()->uri($params).URL::query($query, FALSE);
		}

		// Calculate pagination properties
		$this->_total_items    = max(0, (int) $total_items);
		$this->_items_per_page = max(1, (int) $this->_config['items_per_page']);
		$this->_total_pages    = max(1, ceil($this->_total_items / $this->_items_per_page));
		$this->_current_page   = min(max(1, (int) $this->_current_page), $this->_total_pages);
		$this->_previous_page  = $this->_current_page > 1 ? $this->_current_page - 1 : FALSE;
		$this->_next_page      = $this->_current_page < $this->_total_pages ? $this->_current_page + 1 : FALSE;
		$this->_first_page     = $this->_current_page == 1 ? FALSE : 1;
		$this->_last_page      = $this->_current_page >= $this->_total_pages ? FALSE : $this->_total_pages;
		$this->_offset         = ($this->_current_page - 1) * $this->_items_per_page;
	}

	/**
	 * Generates link for page.
	 *
	 * @param  integer $page Page number
	 * @return string
	 */
	public function url($page = 1)
	{
		// Clean the page number
		$page = min($this->_total_pages, max(1, (int) $page));

		if ($page == 1 AND ! $this->_config['first_page_in_url'])
		{
			// Not page number in URI to first page
			return $this->url_first_page;
		}

		// Substitutes the page number in pattern
		return str_replace(Pagination::PAGE_MARKER, $page, $this->url_pattern);
	}

	/**
	 * Checks whether the given page number exists.
	 *
	 * @param  integer $page Page number
	 * @return boolean
	 */
	public function valid_page($page)
	{
		return Valid::digit($page) AND $page > 0 AND $page <= $this->_total_pages;
	}

	/**
	 * Return compiled the HTML code of pagination links.
	 * 
	 * @param  string|NULL $view Filename
	 * @return string
	 */
	public function render($view = NULL)
	{
		// Automatically hide pagination whenever it is superfluous
		if ($this->_config['auto_hide'] === TRUE AND $this->_total_pages <= 1)
		{
			return '';
		}

		if ($view === NULL)
		{
			// Use default filename
			$view = $this->_config['view'];
		}

		// Create and compile pagination [View]
		return View::factory($view, array('kpagination' => $this))->render();
	}

	/**
	 * Returns property value.
	 *
	 * @param  string $key     Property name
	 * @param  mixed  $default Returned if the value is not found
	 * @return mixed
	 */
	public function get($key, $default = NULL)
	{
		// Add 'protected' prefix 
		$key = '_'.$key;

		return isset($this->$key) ? $this->$key : $default;
	}

	/**
	 * Get config value.
	 *
	 * @param  string $key     Option name
	 * @param  mixed  $default Returned if the value is not found
	 * @return mixed
	 */
	public function config($key, $default = NULL)
	{
		return isset($this->_config[$key]) ? $this->_config[$key] : $default;
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
	 * 'Magical' version of property getter.
	 *
	 * @param  string $key Property name
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

}
