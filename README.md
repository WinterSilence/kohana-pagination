##Pagination
---
Navigation module for Kohana framework 3.3 or high.

Provide pagination links for your applications with the multi-page pagination component, 
or the simpler pager alternative.

###Usage example

Controller class `Controller_News`:
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
		$amount_news = $news->count_all();

		// Create Pagination object
		$pagination = Pagination::factory(
			$amount_news,  // Total news
			'small',       // Group name of config. If set as NULL or not set, uses `Pagination::$default`.
			$this->request // Current Request object, uses for for generating links.
		);

		// Find news for display on the current page
		$news = $news->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all();

		// Send news and pagination object in template (View)
		$this->template->news_list = $news;
		$this->template->pagination = $pagination;
	}

}
~~~
View template `news/list`:
~~~
<?php defined('SYSPATH') OR die('No direct script access.') ?>

<h3><?php echo __('News list') ?></h3>

<div class="pagination headed">
	<?php echo $pagination->render(/* string $different_template */) ?>
</div>

<ul class="list news">
<?php foreach ($news_list as $news): ?>
	<li>
		<b>[<?php echo $news->date ?>] <?php echo $news->title ?></b>
		<?php echo $news->brief_text ?>
	</li>
<?php endforeach ?>
</ul>

<div class="pagination footer">
	<?php echo $pagination; // `Magic` calling $pagination->render() ?>
</div>

~~~
