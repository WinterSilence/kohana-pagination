<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * First Previous 1 2 3 ... 22 23 24 25 26 [27] 28 29 30 31 32 ... 48 49 50 Next Last
*/

// Number of page links in the begin and end of whole range
$count_out = (isset($k_pagination->config['count_out']) ? $k_pagination->config['count_out'] : 3);
// Number of page links on each side of current page
$count_in = (isset($k_pagination->config['count_in']) ? $k_pagination->config['count_in'] : 5);

// Beginning group of pages: $n1...$n2
$n1 = 1;
$n2 = min($count_out, $k_pagination->total_pages);

// Ending group of pages: $n7...$n8
$n7 = max(1, $k_pagination->total_pages - $count_out + 1);
$n8 = $k_pagination->total_pages;

// Middle group of pages: $n4...$n5
$n4 = max($n2 + 1, $k_pagination->current_page - $count_in);
$n5 = min($n7 - 1, $k_pagination->current_page + $count_in);
$use_middle = ($n5 >= $n4);

// Point $n3 between $n2 and $n4
$n3 = (int) (($n2 + $n4) / 2);
$use_n3 = ($use_middle && (($n4 - $n2) > 1));

// Point $n6 between $n5 and $n7
$n6 = (int) (($n5 + $n7) / 2);
$use_n6 = ($use_middle && (($n7 - $n5) > 1));

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
<p class="pagination">

<?php if ($k_pagination->first_page !== FALSE): ?>
	<?php echo HTML::anchor($k_pagination->uri($k_pagination->first_page), __('First')) ?>
<?php else: ?>
	<?php echo __('First') ?>
<?php endif ?>

<?php if ($previous_page !== FALSE): ?>
	<?php echo HTML::anchor($k_pagination->uri($k_pagination->previous_page), __('Previous')) ?>
<?php else: ?>
	<?php echo __('Previous') ?>
<?php endif ?>

<?php foreach ($links as $number => $content): ?>

	<?php if ($number === $k_pagination->current_page): ?>
		<strong><?php echo $content ?></strong>
	<?php else: ?>
		<?php echo HTML::anchor($k_pagination->uri($number), $content) ?>
	<?php endif ?>

<?php endforeach ?>

<?php if ($k_pagination->next_page !== FALSE): ?>
	<?php echo HTML::anchor($k_pagination->uri($k_pagination->next_page), __('Next')) ?>
<?php else: ?>
	<?php echo __('Next') ?>
<?php endif ?>

<?php if ($k_pagination->last_page !== FALSE): ?>
	<?php echo HTML::anchor($k_pagination->uri($k_pagination->last_page), __('Last')) ?>
<?php else: ?>
	<?php echo __('Last') ?>
<?php endif ?>

</p><!-- .pagination -->
