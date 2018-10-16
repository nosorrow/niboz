<?php
	$current_user = wp_get_current_user();
	$user_meta = get_user_meta($current_user->ID, '', true);
	$bio = get_user_meta($current_user->ID, 'description', true);

	// Delete Favourites
	if(isset($_REQUEST['delete-fav'])) {
		$favourites = (array)get_post_meta($_REQUEST['delete-fav'], '_meta_favourites', true );
		if(in_array($current_user->ID, $favourites)) {
			unset($favourites[array_search($current_user->ID, $favourites)]);
		}
		update_post_meta($_REQUEST['delete-fav'], '_meta_favourites', $favourites);
	}

	// Delete Property
	if(isset($_REQUEST['delete-property'])) {
		$edit_property = get_posts(array('post_type' => 'property', 'p' => $_REQUEST['delete-property'], 'post_status' => 'any', 'author' => $current_user->ID));
		if($edit_property) {
			wp_delete_post($_REQUEST['delete-property']);
		}
	}
?>
<?php
if($bio) {
	echo apply_filters('the_content', $bio);
} else {
	printf(__('<p>Your profile is empty. Please edit your profile <a href="%s">here</a>.</p>', 'theme_front'), add_query_arg(array('fn'=>'edit-profile'), get_permalink()));
}
?>

<div class="vspace"></div>
<div class="vspace"></div>
<h3><?php _e('My Properties', 'theme_front'); ?></h3>

<table>
	<thead>
		<tr>
			<th><?php _e('Property', 'theme_front'); ?></th>
			<th style="width: 150px;"><?php _e('Status', 'theme_front'); ?></th>
			<th style="width: 100px;"><?php _e('Manage', 'theme_front'); ?></th>
		</tr>
	</thead>
	<?php
	$post_per_page = 5;
	$properties = get_posts(array('post_type' => 'property', 'post_status' => 'any', 'author' => $current_user->ID, 'posts_per_page' => $post_per_page, 'paged'=> $paged));
	foreach($properties as $property):
	?>
	<tr>
		<td>
			<?php nt_property_list($property->ID, get_post_status($property->ID) == 'publish'); ?>
		</td>
		<td>
			<?php if(get_post_status($property->ID) != 'publish' && get_post_status($property->ID) != 'pending'):
				if(nt_get_option('property', 'paypal_server', 'sandbox') == 'sandbox') {
					$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
				} else {
					$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
				}
			?>
				<form method="post" action="<?php echo esc_url($paypal_url); ?>" target="_top">
					<input type="hidden" name="notify_url" value="<?php echo esc_attr(get_template_directory_uri()); ?>/paypal/ipn.php">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="amount" value="<?php echo esc_attr(nt_get_option('property', 'submission_price')); ?>">
					<input type="hidden" name="quantity" value="1">
					<input type="hidden" name="item_name" value="<?php echo esc_attr($property->post_title); ?>">
					<input type="hidden" name="item_number" value="<?php echo esc_attr($property->ID); ?>">
					<input type="hidden" name="return" value="<?php the_permalink(); ?>">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="<?php echo esc_attr(nt_get_option('property', 'paypal_merchant_id')); ?>">
					<input type="image" src="<?php echo get_template_directory_uri(); ?>/image/paypal-checkout.png" />
				</form>
			<?php else: ?>
				<?php echo ucfirst(get_post_status($property->ID)); ?>
			<?php endif; ?>
		</td>
		<td>
			<ul class="manage-list">
				<li><a href="<?php echo add_query_arg(array('fn'=>'edit-property', 'property_id'=>$property->ID), get_permalink()); ?>"><i class="flaticon-edit45"></i></a></li>
				<li><a href="<?php echo add_query_arg(array('delete-property'=>$property->ID), get_permalink()); ?>"><i class="flaticon-trash29"></i></a></li>
			</ul>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<div class="pagination-wrap clearfix">
	<?php posts_nav_link(' ', "<span class='button button-primary nextpostslink'>".__('Next Page &rarr;', 'theme_front')."</span>", "<span class='button button-primary previouspostslink'>".__("&larr; Previous Page", 'theme_front')."</span>"); ?>
	<?php
$big = 999999999;
$arg =  array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => ceil(wp_count_posts( 'property' )->publish/$post_per_page),
	'prev_text' => "<span class='button button-primary nextpostslink'>".__('Next Page &rarr;', 'theme_front')."</span>",
	'next_text' => "<span class='button button-primary previouspostslink'>".__("&larr; Previous Page", 'theme_front')."</span>"
);
echo paginate_links( $arg ); ?>
</div>



<div class="vspace"></div>
<div class="vspace"></div>
<h3><?php _e('Favourite Properties', 'theme_front'); ?></h3>

<table>
	<thead>
		<tr>
			<th><?php _e('Property', 'theme_front'); ?></th>
			<th style="width: 100px;"><?php _e('Manage', 'theme_front'); ?></th>
		</tr>
	</thead>
	<?php
		$properties = get_posts(array('post_type' => 'property', 'posts_per_page' => -1));
		foreach($properties as $property):
			$favourites = (array)get_post_meta( $property->ID, '_meta_favourites', true );
			if(in_array($current_user->ID, $favourites)):
	?>
	<tr>
		<td>
			<?php nt_property_list($property->ID); ?>
		</td>
		<td>
			<ul class="manage-list">
				<li><a href="<?php echo add_query_arg(array('delete-fav'=>$property->ID), get_permalink()); ?>"><i class="flaticon-trash29"></i></a></li>
			</ul>
		</td>
	</tr>
	<?php endif; endforeach; ?>
</table>
