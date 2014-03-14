## Pagination

Navigation module for Kohana framework 3.3 or high.

Provide pagination links for your applications with the multi-page pagination component.

### Usage example

Controller `application/classes/Controller/News.php`:
~~~
<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class controller news, displayed list of news.
 */
class Controller_News extends Controller_Template {

	$template = 'news/list';

	public function action_list()
	{
		// Create news model and sets query options
		$news = ORM::factory('News')
			->where('active', '=', '1')
			->order_by('date', 'DESC')
			->reset(FALSE);

		// Get the number of news
		$total_news = $news->count_all();

		// Create Pagination object
		$pagination = Pagination::factory(
			$total_news,   // Number of news
			'small',       // Name of config group. If it's undefined, sets in `Pagination::$default`.
			$this->request // Current Request object, uses for for generating links.
		);

		// Find news for display on the current page
		$news_list = $news->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all();

		// Send news and pagination object in template (View)
		$this->template->news_list = $news_list;
		$this->template->pagination = $pagination;
	}

}
~~~

View `application/views/news/list.php`:
~~~
<?php defined('SYSPATH') OR die('No direct script access.') ?>

<h3><?php echo __('News list') ?></h3>

<div class="pagination footer">
	<?php echo $pagination; // 'Magical' calling `$pagination->render()` ?>
</div>

<ul class="list news">
<?php foreach ($news_list as $news): ?>
	<li>
		<b>[<?php echo $news->date ?>] <?php echo $news->title ?></b>
		<?php echo $news->brief_text ?>
	</li>
<?php endforeach ?>
</ul>

<div class="pagination headed">
	<?php echo $pagination->render(/* string $different_template */) ?>
</div>
~~~
