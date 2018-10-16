<?php
function nt_post_card($post_id, $show_excerpt) {
global $post;
$post = get_post($post_id);
setup_postdata( $post ); 
?>
<div class="card">
<div class="img-wrap">
	<a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_post_thumbnail($post_id, 'card'); ?></a>
	<div class="badge">
		<div class="status">
		<ul class="meta-list">
			<li><?php echo get_the_date(); ?></li>
		</ul>
		</div>
	</div>
</div>
<div class="content-wrap">
<div class="title"><a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></div>
<ul class="meta-list">
<?php echo get_the_term_list($post_id, 'category', '<li>', '</li><li>', '</li>'); ?>
</ul>
<?php if($show_excerpt): ?>
<div class="excerpt"><?php the_excerpt(); ?></div>
<?php endif; ?>
</div>
</div>
<?php 
wp_reset_postdata();
} ?>