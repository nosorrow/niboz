<?php
	$post_id = get_queried_object_id();	
	$hero = get_post_meta( $post_id, '_hero_type', true );
	$properties = get_post_meta( $post_id, '_hero_properties', true );
	$property_search = get_post_meta( $post_id, '_hero_property_search', true );
?>
<div class="hero-wrap">

<?php if($hero == 'slide'): 
	$property_slide_timeout = get_post_meta( $post_id, '_hero_property_slide_timeout', true );
	$property_slide_autoplay = ($property_slide_timeout)?'true':'false';
	$property_slide_height = get_post_meta( $post_id, '_hero_property_slide_height', true );
?>
<div class="hero" style="height: <?php echo $property_slide_height; ?>px;">

<div class="bg-wrap">
<?php 
	$index = 0;
	if(is_array($properties))
	foreach($properties as $property):
		$bg_image = wp_get_attachment_image_src( get_post_thumbnail_id($property['stack_title']), 'slide-full');
		$index++;
?>
	<div class="item <?php if($index==1): ?>active<?php endif; ?>" style="background-image: url('<?php echo $bg_image[0]; ?>');">
	</div>
<?php endforeach; ?>
</div>

<div class="lt-carousel lt-carousel-single full-width" data-items="1" data-single-item="true" data-smart-speed="500" data-bg=".bg-wrap" data-autoplay="<?php echo $property_slide_autoplay; ?>" data-autoplay-timeout="<?php echo $property_slide_timeout; ?>" data-autoplay-hover-pause="true" data-loop="true" data-nav="true" data-dots="false">
<?php 
	$counter = 0;
	global $post;
	if(is_array($properties))
	foreach($properties as $property):
		$post = get_post($property['stack_title']);
		setup_postdata($post);
		if($post->post_status != 'publish') break;
		$price = get_post_meta( $property['stack_title'], '_meta_price', true );
		if(!$price) $price = nt_get_option('property', 'null_price');
		$per = get_post_meta( $property['stack_title'], '_meta_per', true );
		$bed = get_post_meta( $property['stack_title'], '_meta_bedroom', true );
		$bath = get_post_meta( $property['stack_title'], '_meta_bathroom', true );
		$garage = get_post_meta( $property['stack_title'], '_meta_garage', true );
?>
<div class="row item" data-id="<?php echo $counter++; ?>">
<div class="columns large-12" style="position:relative;">
<?php if(get_post_meta( $post_id, '_hero_property_slide_style', true ) == '1'): ?>
	<div class="hero-card">
		<div class="card-head">
			<div class="card-title"><a href="<?php echo get_the_permalink($property['stack_title']); ?>"><?php echo get_the_title($property['stack_title']); ?> <i class="flaticon-next15"></i></a></div>
		</div>
		<div class="card-body">
			<?php the_excerpt(); ?>
		</div>
		<div class="card-bottom clearfix">
			<div class="card-meta">
				<?php if($bed): ?><i class="lt-icon flaticon-person1 big"></i> <?php echo wp_kses_data($bed); ?> <?php endif; ?>
				<?php if($bath): ?><i class="lt-icon flaticon-shower5"></i> <?php echo wp_kses_data($bath); ?> <?php endif; ?>
				<?php if($garage): ?><i class="lt-icon flaticon-car95"></i>  
				<?php echo wp_kses_data($garage); ?><?php endif; ?>
			</div>
			<?php if($price): ?><div class="card-price"><?php echo is_numeric($price)?nt_currency($price, $per):$price; ?></div><?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?php if(get_post_meta( $post_id, '_hero_property_slide_style', true ) == '2'): ?>
	<div class="badge">
		<div class="status">
		<ul class="meta-list">
			<?php echo get_the_term_list($property['stack_title'], 'status', '<li>', '</li><li>', '</li>'); ?>
		</ul>
		</div>
		<?php if($price): ?><div class="price"><?php echo nt_currency($price, $per); ?></div><?php endif; ?>
	</div>
	<div class="title"><a href="<?php echo get_the_permalink($property['stack_title']); ?>"><?php echo get_the_title($property['stack_title']); ?> <i class="flaticon-next15"></i></a></div>
<?php endif; ?>

</div>
</div>
<?php endforeach; wp_reset_postdata(); ?>
</div>

</div>
<?php endif; ?>

<?php if($hero == 'map'): 
	$map_height = get_post_meta( $post_id, '_hero_property_map_height', true );
	if(!$map_height) $map_height = 500;
	$map_zoom = get_post_meta( $post_id, '_hero_property_map_zoom', true );
	if($map_zoom == '') $map_zoom = -1;

	

?>
<div class="map-wrap" id="map-12" style="height: <?php echo $map_height; ?>px;" data-zoom="<?php echo $map_zoom; ?>" data-style="<?php echo nt_get_option('property', 'map_style', 'bw'); ?>">
<?php 
	
	$query = array('post_type' => 'property', 'posts_per_page' => -1);

	$map_properties = get_post_meta( $post_id, '_hero_property_map_properties', true );
	if(isset($map_properties) && !empty($map_properties) && $map_properties[0] != '') {
		$query['post__in'] = $map_properties;
	}

	$properties = get_posts($query);




	if(is_array($properties))
	foreach($properties as $property): 
	$location = get_post_meta($property->ID, '_meta_location', true );
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $property->ID ), 'marker' );
	$price = get_post_meta( $property->ID, '_meta_price', true );
	if(!$price) $price = nt_get_option('property', 'null_price');
	$per = get_post_meta( $property->ID, '_meta_per', true );
?>	
	<div data-latitude="<?php echo esc_attr($location[1]); ?>" data-longitude="<?php echo esc_attr($location[2]); ?>" data-content="<div class='lt-carousel'><div class='card'><div class='img-wrap'><a href='<?php echo get_permalink($property->ID); ?>'><img src='<?php echo $thumb[0]; ?>' /></a><div class='badge'><div class='status'><ul class='meta-list'><?php echo esc_attr(get_the_term_list($property->ID, 'status', '<li>', '</li><li>', '</li>')); ?></ul></div><?php if($price): ?><div class='price small'><?php echo nt_currency($price, $per); ?></div><?php endif; ?></div></div><div class='inner'><div class='title'><a href='<?php echo get_permalink($property->ID); ?>'><?php echo esc_attr(get_the_title($property->ID)); ?></a></div><ul class='meta-list'><?php echo esc_attr(get_the_term_list($property->ID, 'location', '<li>', '</li><li>', '</li>')); ?></ul></div></div></div>"></div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if($hero == 'general-slide'): 
$slides = get_post_meta( $post_id, '_hero_general_slide', true );
$slide_timeout = get_post_meta( $post_id, '_hero_general_slide_timeout', true );
$slide_autoplay = ($slide_timeout)?'true':'false';
$padding = get_post_meta( $post_id, '_hero_general_slide_padding', true );
if($padding == '') $padding = 140;
?>
<div class="hero slide-hero">

	<div class="bg-wrap">
	<?php 
		$index = 0;
		if(is_array($slides))
		foreach($slides as $slide):
			$bg_image = wp_get_attachment_image_src( $slide['bg_image'], 'slide-full');
			$index++;
	?>
		<div class="item <?php if($index==1): ?>active<?php endif; ?>" style="background-image: url('<?php echo $bg_image[0]; ?>');">
		</div>
	<?php endforeach; ?>
	</div>

	<div class="lt-carousel lt-carousel-single carousel-content" data-items="1" data-single-item="true" data-smart-speed="500" data-bg=".bg-wrap" data-autoplay="<?php echo $slide_autoplay; ?>" data-autoplay-timeout="<?php echo $slide_timeout; ?>" data-autoplay-hover-pause="true" data-loop="true" data-nav="true" data-dots="false">
		<?php 
			if(is_array($slides))
			foreach($slides as $slide):
		?>
		<div class="item <?php echo $slide['element_style']; ?>" style="padding-top: <?php echo $padding; ?>px; padding-bottom: <?php echo $padding+30; ?>px;">
		<div class="row">
		<div class="columns large-12">
			<?php if($slide['stack_title']): ?><div class="slide-title"><?php echo $slide['stack_title']; ?></div><?php endif; ?>
			<?php if($slide['description']): ?><div class="desc"><?php echo $slide['description']; ?></div><?php endif; ?>
			<?php if($slide['button']): ?><div class="bt-align-center"><a href="<?php echo $slide['url']; ?>" class="lt-button medium" style=""><?php echo $slide['button']; ?></a></div><?php endif; ?>
		</div>
		</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>

<?php if($hero == 'rev-slide'): 
$rev_slide = get_post_meta( $post_id, '_hero_rev_slide', true );
?>
<?php putRevSlider( $rev_slide ); ?>
<?php endif; ?>

<?php if($property_search == 'on' || isset($_REQUEST['property-search']) || is_page_template('template-property-search.php')) get_template_part('section/section', 'property-search'); ?>

</div>
