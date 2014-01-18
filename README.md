Pagination
==========

A Kohana module for pagination

Example of use
----

<pre>
$articles = ORM::factory('Articles')
	->where('status', '=', '1')
	->order_by('date', 'DESC')
	->reset(FALSE);

$count_articles = $articles->count_all();

$pagination = Pagination::factory(array(
		'total_items' => $count_articles,
		'group' => 'articles', // if don't set this value, then will be loaded default config group 'default'
	));

$articles = $articles
	->limit($pagination->items_per_page)
	->offset($pagination->offset)
	->find_all();

$view = View::factory('your/template/articles')
	->set('articles', $articles)
	->set('pagination', $pagination);
</pre>
