<?php 
	$tax_query['relation'] = 'OR';
	$cur_locations = wp_get_post_terms( $post->ID, 'location', array('fields' => 'ids') );
	if($cur_locations) {
		$tax_query[] = array(
						'taxonomy' => 'location',
						'field'    => 'id',
						'terms'    => $cur_locations,
						'operator' => 'IN'
					);
	}
	$cur_type = wp_get_post_terms( $post->ID, 'type', array('fields' => 'ids') );
	// if($cur_type) {
	// 	$tax_query[] = array(
	// 					'taxonomy' => 'type',
	// 					'field'    => 'id',
	// 					'terms'    => $cur_type,
	// 					'operator' => 'IN'
	// 				);
	// }
	$properties = get_posts(array('post_type' => 'property', 'post_status' => 'publish', 'posts_per_page' => 4, 'post__not_in' => array($post->ID), 'tax_query' => $tax_query));
	
	if($properties):
?>
<div class="vspace" style="height: 60px;"></div>
<h3><?php _e('Related Properties', 'theme_front'); ?></h3>
<div class="vspace" style="height: 15px;"></div>
<ul class="large-block-grid-2 medium-block-grid-2 small-block-grid-1">
<?php 
	foreach($properties as $property): ?>
		<li><?php nt_property_list_price($property->ID); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>