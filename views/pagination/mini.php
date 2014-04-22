<?php defined('SYSPATH') OR die('No direct script access.') ?>

<ul class="pagination <?php echo $kpagination->config('class') ?>">

<?php if ($kpagination->previous_page !== FALSE): ?>
	<li><?php echo HTML::anchor($kpagination->url($kpagination->previous_page), '&laquo;') ?></li>
<?php else: ?>
	<li class="disabled"><span>&laquo;</span></li>
<?php endif ?>

<?php for ($i = 1; $i <= $kpagination->total_pages; $i++): ?>

<?php if ($i == $kpagination->current_page): ?>
	<li class="active"><span><?php echo $i ?></span></li>
<?php else: ?>
	<li><?php echo HTML::anchor($kpagination->url($i), $i) ?></li>
<?php endif ?>

<?php endfor ?>

<?php if ($kpagination->next_page !== FALSE): ?>
	<li><?php echo HTML::anchor($kpagination->url($kpagination->next_page), '&raquo;') ?></li>
<?php else: ?>
	<li class="disabled"><span>&raquo;</span></li>
<?php endif ?>

</ul>
