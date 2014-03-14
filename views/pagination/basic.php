<?php defined('SYSPATH') OR die('No direct script access.') ?>

<ul class="pagination <?php echo $kpagination->config('class') ?>">

<?php if ($kpagination->first_page !== FALSE): ?>
	<li><?php echo HTML::anchor($kpagination->url($kpagination->first_page), __('First')) ?></li>
<?php else: ?>
	<li class="disabled"><span><?php echo __('First') ?></span></li>
<?php endif ?>

<?php if ($kpagination->previous_page !== FALSE): ?>
	<li><?php echo HTML::anchor($kpagination->url($kpagination->previous_page), __('Previous')) ?></li>
<?php else: ?>
	<li class="disabled"><span><?php echo __('Previous') ?></span></li>
<?php endif ?>

<?php for ($i = 1; $i <= $kpagination->total_pages; $i++): ?>

<?php if ($i == $kpagination->current_page): ?>
	<li class="active"><span><?php echo $i ?></span></li>
<?php else: ?>
	<li><?php echo HTML::anchor($kpagination->url($i), $i) ?></li>
<?php endif ?>

<?php endfor ?>

<?php if ($kpagination->next_page !== FALSE): ?>
	<li><?php echo HTML::anchor($kpagination->url($kpagination->next_page), __('Next')) ?></li>
<?php else: ?>
	<li class="disabled"><span><?php echo __('Next') ?></span></li>
<?php endif ?>

<?php if ($kpagination->last_page !== FALSE): ?>
	<li><?php echo HTML::anchor($kpagination->url($kpagination->last_page), __('Last')) ?></li>
<?php else: ?>
	<li class="disabled"><span><?php echo __('Last') ?></span></li>
<?php endif ?>

</ul>
