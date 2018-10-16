<?php
function nt_agent_card($post_id) {
	$role = get_post_meta( $post_id, '_meta_role', true );
	$phone = get_post_meta( $post_id, '_meta_phone', true );
	$email = get_post_meta( $post_id, '_meta_email', true );
?>
<div class="card">
<div class="img-wrap">
	<a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_post_thumbnail($post_id, 'agent-card'); ?></a>
	<div class="badge">
		
		<?php if($phone): ?><div class="price"><?php echo $phone; ?></div><?php endif; ?>

		<?php if($email): ?>
		<div class="status">
		<ul class="meta-list">
			<?php echo $email; ?>
		</ul>
		</div>
		<?php endif; ?>
		
	</div>
</div>
<div class="content-wrap align-center">
<div class="title"><a href="<?php echo get_the_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a></div>
<ul class="meta-list">
<li><?php echo wp_kses_data($role); ?></li>
</ul>
</div>
</div>
<?php } ?>