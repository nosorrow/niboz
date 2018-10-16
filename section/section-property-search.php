<?php
$post_id = get_queried_object_id();	
$search_type = get_post_meta( $post_id, '_hero_property_search_type', true );
?>

<div class="property-search-box-wrap">
<div class="row">
<div class="columns large-12">
<div class="property-search-box">
<?php 
if($search_type == 'optima_express') {
	echo do_shortcode('[optima_express_quick_search style="horizontal" showPropertyType="true"]');
} elseif ($search_type == 'dsidx') {
	echo do_shortcode('[idx-quick-search format="horizontal"]');
} else {
	$search_layout = get_post_meta( $post_id, '_hero_property_search_style', true );
	property_search_form($search_layout);
} 
?>
</div>
</div>
</div>
</div>
