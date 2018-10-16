<?php
function nt_property_list($post_id, $link = true) {
	$price = get_post_meta( $post_id, '_meta_price', true );
	if(!$price) $price = nt_get_option('property', 'null_price');
	$per = get_post_meta( $post_id, '_meta_per', true );
?>
<div class="property-list">
	<?php if($link): ?>
		<a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?></a>
	<?php else: ?>
		<?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?>
	<?php endif; ?>
	<div class="title">
		<?php if($link): ?>
			<a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a>
		<?php else: ?>
			<?php echo get_the_title($post_id); ?>
		<?php endif; ?>
	</div>
	<ul class="meta-list">
		
	<?php echo get_the_term_list($post_id, 'location', '<li>', '</li><li>', '</li>'); ?>
	</ul>
</div>
<?php }

function nt_property_list_price($post_id) {
	$price = get_post_meta( $post_id, '_meta_price', true );
	if(!$price) $price = nt_get_option('property', 'null_price');
	$per = get_post_meta( $post_id, '_meta_per', true );
?>
<div class="property-list">
	<a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?></a>
	<div class="title"><a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></div>
	<ul class="meta-list">
		<?php if($price): ?><li class="price"><?php echo nt_currency($price, $per); ?></li><?php endif; ?>
	</ul>
</div>
<?php } ?>