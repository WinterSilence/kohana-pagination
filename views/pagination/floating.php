<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * First Previous 1 2 3 ... 26 [27] 28 ... 48 49 50 Next Last
*/
// Number of page links in the begin and end of whole range
$count_out = $kpagination->config('count_out', 3);
// Number of page links on each side of current page
$count_in = $kpagination->config('count_in', 5);

// Beginning group of pages: $n1 ... $n2
$n1 = 1;
$n2 = min($count_out, $kpagination->total_pages);

// Ending group of pages: $n7 ... $n8
$n7 = max(1, $kpagination->total_pages - $count_out + 1);
$n8 = $kpagination->total_pages;

// Middle group of pages: $n4 ... $n5
$n4 = max($n2 + 1, $kpagination->current_page - $count_in);
$n5 = min($n7 - 1, $kpagination->current_page + $count_in);
$use_middle = ($n5 >= $n4);

// Point $n3 between $n2 and $n4
$n3 = (int) (($n2 + $n4) / 2);
$use_n3 = ($use_middle AND (($n4 - $n2) > 1));

// Point $n6 between $n5 and $n7
$n6 = (int) (($n5 + $n7) / 2);
$use_n6 = ($use_middle AND (($n7 - $n5) > 1));

// Links to display as array(page => content)
$links = array();

// Generate links data in accordance with calculated numbers
for ($i = $n1; $i <= $n2; $i++)
{
	$links[$i] = $i;
}
if ($use_n3)
{
	$links[$n3] = '&hellip;';
}
for ($i = $n4; $i <= $n5; $i++)
{
	$links[$i] = $i;
}
if ($use_n6)
{
	$links[$n6] = '&hellip;';
}
for ($i = $n7; $i <= $n8; $i++)
{
	$links[$i] = $i;
}
?>
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

<?php foreach ($links as $i => $num): ?>

<?php if ($i == $kpagination->current_page): ?>
	<li class="active"><span><?php echo $num ?></span></li>
<?php else: ?>
	<li><?php echo HTML::anchor($kpagination->url($i), $num) ?></li>
<?php endif ?>

<?php endforeach ?>

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
