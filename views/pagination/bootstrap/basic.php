<ul class="pagination <?php echo isset($config['append_class']) ? $config['append_class'] : NULL;?>">

	<?php if (isset($config['first_title'])): ?>
		<?php if ($first_page !== FALSE): ?>
			<li>
				<a href="<?php echo HTML::chars($page->url($first_page)) ?>" rel="first"><?php echo __($config['first_title']) ?></a>
			</li>
		<?php else: ?>
			<li class="disabled">
				<span><?php echo __($config['first_title']) ?></span>
			</li>
		<?php endif ?>
	<?php endif ?>

	<?php if (isset($config['previous_title'])): ?>
		<?php if ($previous_page !== FALSE): ?>
			<li>
				<a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev"><?php echo __($config['previous_title']) ?></a>
			</li>
		<?php else: ?>
			<li class="disabled">
				<span><?php echo __($config['previous_title']) ?></span>
			</li>
		<?php endif ?>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>

		<?php if ($i == $current_page): ?>
			<li class="active">
				<span><?php echo $i ?></span>
			</li>
		<?php else: ?>
			<li>
				<a href="<?php echo HTML::chars($page->url($i)) ?>"><?php echo $i ?></a>
			</li>
		<?php endif ?>

	<?php endfor ?>

	<?php if (isset($config['next_title'])): ?>
		<?php if ($next_page !== FALSE): ?>
			<li>
				<a href="<?php echo HTML::chars($page->url($next_page)) ?>" rel="next"><?php echo __($config['next_title']) ?></a>
			</li>
		<?php else: ?>
			<li class="disabled">
				<span><?php echo __($config['next_title']) ?></span>
			</li>
		<?php endif ?>
	<?php endif ?>

	<?php if (isset($config['last_title'])): ?>
		<?php if ($last_page !== FALSE): ?>
			<li>
				<a href="<?php echo HTML::chars($page->url($last_page)) ?>" rel="last"><?php echo __($config['last_title']) ?></a>
			</li>
		<?php else: ?>
			<li class="disabled">
				<span><?php echo __($config['last_title']) ?></span>
			</li>
		<?php endif ?>
	<?php endif ?>

</ul><!-- .pagination -->
