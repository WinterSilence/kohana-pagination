#HMVC usage

Add [Route] for [Controller_Pagination] in [bootstrap file](kohana/bootstrap):
~~~
Route::set(
	'paginate', 
	'paginate/<total_items>(/<config_group>)', 
	array('total_items' => '[0-9]+', 'config_group' => '[-\w]+')
)
	->default(array(
		'controller'   => 'Pagination',
		'action'       => 'index',
		'total_items'  => 0,
		'config_group' => NULL
	));
~~~

Create and execute [HMVC request](kohana/requests) to [Controller_Pagination] in [View] templates.

Minimal request:
~~~
<ul class="list">
	<?php foreach ($items as $item): ?>
	<li><?php echo $item ?></li>
	<?php endforeach ?>
</ul>

<div class="navigation">
	<?php echo Request::factory(Route::url('paginate', array('total_items' => $total)))->execute() ?>
</div>
~~~

Complete request:
~~~
<div class="navigation">
	<?php 
	echo Request::factory(
		Route::url(
			'pagination', 
			array(
				'total_items' => $total, // Number of items
				'config_group' => $group // Use settings from this config group
			)
		),
		array('cache' => Cache::instance()) // Optional, use caching results
	)
		->execute(); 
	?>
</div>
~~~

[!!] Hint: create function or class for easy and short calling HMVC requests.

~~~
/**
 * Create and execute HMVC request.
 *
 * @param  string $url     URL
 * @param  array  $data    An array of data to send
 * @param  string $method  Method of sending data
 * @param  array  $options Request options
 * @return string
 */
function hmvc_request($url, array $data = array(), $method = Request::GET, array $options = array())
{
	// Define name of method to send data
	$data_method = ($method == Request::POST ? 'post' : 'query');
	// Create and execute request
	return Request::factory($url, $options)
		->method($method)
		->$data_method($data)
		->execute();
}
~~~
