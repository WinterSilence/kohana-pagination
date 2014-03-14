# Configuration

The default config file is located in `MODPATH/pagination/config/pagination.php`. 
You should copy this file to `APPPATH/config/pagination.php` and make changes there, 
in keeping with the [cascading filesystem](../kohana/files).

## Groups

The pagination configuration file contains an array of configuration groups.

The default group is loaded based on the [Pagination::$default] setting. 
It is set to the `default` group as standard, 
however this can be changed within the [boostrap](../kohana/bootstrap) file.

~~~
// Change the default group to 'floating'
Pagination::$default = 'floating';
// Load the 'floating' config group using default setting
$pagination = Pagination::factory($total_items);
~~~

## Group settings

General settings:

Name              | Type      | Description                                            | Default value
------------------|-----------|--------------------------------------------------------|-----------------------------------------------
page              |  `array`  | [Request] settings for current page                    | array('source' => 'query', 'key' => 'page')
items_per_page    | `integer` | The number of elements per page                        | 10
auto_hide         | `boolean` | If list of items is empty pagination not displayed     | TRUE
first_page_in_url | `boolean` | Add first page in link                                 | FALSE
view              | `string`  | [View] filename, used for render HTML code             | 'pagination/basic'
class             | `string`  | Attribute 'class' of root HTML tag, uses in view       | NULL

Also allows adding custom settings used in the pagination [views](../kohana/mvc/views).

[!!] All settings are optional.

## Example

~~~
'floating' => array(
	'page'              => array('source' => 'param', 'key' => 'p'), 
	'items_per_page'    => 20,
	'first_page_in_url' => TRUE,
	'view'              => 'pagination/floating',
	// Special settings for 'pagination/floating':
	'count_out'         => 3, // Number of page links in the begin and end of whole range
	'count_in'          => 3, // Number of page links on each side of current page
),
~~~
