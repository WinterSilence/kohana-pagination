<?php defined('SYSPATH') OR die('No direct script access.') ?>

<p class="pagination">

<?php if ($pagination->first_page !== FALSE): ?>
	<?php echo HTML::anchor($pagination->uri($pagination->first_page), __('First')) ?>
<?php else: ?>
	<?php echo __('First') ?>
<?php endif ?>

<?php if ($pagination->previous_page !== FALSE): ?>
	<?php echo HTML::anchor($pagination->uri($pagination->previous_page), __('Previous')) ?>
<?php else: ?>
	<?php echo __('Previous') ?>
<?php endif ?>

<?php for ($i = 1; $i <= $pagination->total_pages; $i++): ?>

	<?php if ($i == $pagination->current_page): ?>
		<strong><?php echo $i ?></strong>
	<?php else: ?>
		<?php echo HTML::anchor($pagination->uri($i), $i) ?>
	<?php endif ?>

<?php endfor ?>

<?php if ($pagination->next_page !== FALSE): ?>
	<?php echo HTML::anchor($pagination->uri($pagination->next_page), __('Next')) ?>
<?php else: ?>
	<?php echo __('Next') ?>
<?php endif ?>

<?php if ($pagination->last_page !== FALSE): ?>
	<?php echo HTML::anchor($pagination->uri($pagination->last_page), __('Last')) ?>
<?php else: ?>
	<?php echo __('Last') ?>
<?php endif ?>

</p><!-- .pagination -->
