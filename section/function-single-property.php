<?php
function nt_single_property($post_id, $compare = false) {

	global $post;
	$post = get_post($post_id);
	setup_postdata( $post );
	$gallery = get_post_meta( $post->ID, '_meta_gallery', true );

	$video_thumb = get_post_meta( $post->ID, '_meta_video_thumb', true );
	$video_url = get_post_meta( $post->ID, '_meta_video_url', true );

	$bed = (get_post_meta( $post->ID, '_meta_bedroom', true ));
	$bath = (get_post_meta( $post->ID, '_meta_bathroom', true ));
	$garage = (get_post_meta( $post->ID, '_meta_garage', true ));
	$area = (get_post_meta( $post->ID, '_meta_area', true ));
	$price = get_post_meta( $post->ID, '_meta_price', true );
	if(!$price) $price = nt_get_option('property', 'null_price');
	$per = get_post_meta( $post->ID, '_meta_per', true );
	$id = get_post_meta( $post->ID, '_meta_id', true );
	$location = get_post_meta( $post->ID, '_meta_location', true );
	$location_text = get_post_meta( $post->ID, '_meta_location_text', true );

	$has_location = (($location[0] && $location[1] && $location[2]) || $location_text);

	$agents = get_post_meta( $post->ID, '_meta_agent', true );
	// if(get_post_status($agent) != 'publish' || $agent == '') {
	// 	unset($agent);
	// }

	$details = get_post_meta( $post->ID, '_meta_detail', true );
	$attachments = get_post_meta( $post->ID, '_meta_attachment', true );
	$floorplans = get_post_meta( $post->ID, '_meta_floorplan', true );
	$has_details = (is_array($details) || is_array($attachments) || is_array($floorplans));

	$user_agent = get_post_meta( $post->ID, '_meta_user_agent', true );
	$favourites = (array)get_post_meta( $post->ID, '_meta_favourites', true );
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'marker' );

	$property_slide_timeout = nt_get_option('property', 'slide_timeout', '4000');
	$property_slide_autoplay = ($property_slide_timeout)?'true':'false';
?>

<?php if($gallery):
	$uid = uniqid();
?>

<?php if(isset($_REQUEST['compare-with'])): ?>
<div class="property-head">
	<div class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	<ul>
		<?php echo get_the_term_list($post->ID, 'location', '<li>', '</li><li>', '</li>'); ?>
		<?php echo get_the_term_list($post->ID, 'type', '<li>', '</li><li>', '</li>'); ?>
	</ul>
</div>
<?php endif; ?>

<div class="property-hero">
<div class="carousel-wrap">
	<div class="lt-carousel lt-carousel-single property-carousel property-carousel-<?php echo $uid; ?>" data-items="1" data-dots="false"  data-auto-height="true"  data-single-item="true" data-autoplay="<?php echo $property_slide_autoplay; ?>" data-autoplay-timeout="<?php echo $property_slide_timeout; ?>" data-autoplay-hover-pause="true" data-nav="true" data-loop="true" data-nav-thumb=".property-thumb-nav-<?php echo $uid; ?>">

		<?php if($video_thumb):
			$img_src = wp_get_attachment_image_src($video_thumb, 'slide');
		?>
		<div class="item">
			<a class="swipebox" rel="<?php echo $uid; ?>" href="<?php echo $video_url; ?>"><img src="<?php echo $img_src[0]; ?>" /><i class="flaticon-play43 overlay-icon"></i></a>
		</div>
		<?php endif; ?>

		<?php foreach($gallery as $image):
			$img_src = wp_get_attachment_image_src($image, 'slide');
			$img_src_full = wp_get_attachment_image_src($image, 'full');
		?>
		<div class="item"><a href="<?php echo $img_src_full[0]; ?>" class="swipebox" rel="<?php echo $uid; ?>" title="<?php echo get_the_title($image); ?>"><img src="<?php echo $img_src[0]; ?>" width="<?php echo $img_src[1]; ?>" height="<?php echo $img_src[2]; ?>" alt="<?php echo get_the_title($image); ?>" /></a></div>
		<?php endforeach; ?>
	</div>

	<div class="badge">
		<div class="status">
		<ul class="meta-list">
			<?php echo get_the_term_list($post->ID, 'status', '<li>', '</li><li>', '</li>'); ?>
		</ul>
		</div>
		<?php if($price): ?><div class="price"><?php echo nt_currency($price, $per); ?></div><?php endif; ?>
	</div>
</div>

<?php if(nt_get_option('property', 'favourite', 'on') == 'on'): ?>
	<a href="#" class="add-wish-list <?php if(in_array(get_current_user_id(), $favourites)): ?>active<?php endif; ?>" data-property-id="<?php echo $post->ID; ?>"><span class="lt-icon flaticon-favorite21"></span></a>
<?php endif; ?>

</div>
<?php endif; ?>

<?php if(count($gallery) > 1):
?>
<ul class="property-thumb-nav-<?php echo $uid; ?> thumb-nav large-block-grid-8 medium-block-grid-7 small-block-grid-4 clearfix" data-nav-thumb=".property-carousel-<?php echo $uid; ?>">

	<?php if($video_thumb):
			$img_src = wp_get_attachment_image_src($video_thumb, 'thumbnail');
		?>
		<li><img src="<?php echo $img_src[0]; ?>" width="<?php echo $img_src[1]; ?>" height="<?php echo $img_src[2]; ?>" alt="<?php echo get_the_title($image); ?>" /><i class="flaticon-play43 overlay-icon"></i></li>
		<?php endif; ?>

	<?php foreach($gallery as $image):
		$img_src = wp_get_attachment_image_src($image, 'thumbnail');
	?>
	<li><img src="<?php echo $img_src[0]; ?>" width="<?php echo $img_src[1]; ?>" height="<?php echo $img_src[2]; ?>" alt="<?php echo get_the_title($image); ?>" /></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<ul class="meta-box-list">
	<?php if($id): ?><li><?php echo $id; ?><span class="tooltip"><?php _e('ID', 'theme_front'); ?></span></li><?php endif; ?><?php if($area): ?><li><span class="lt-icon flaticon-display6"></span> <?php echo nt_area_format($area); ?> <?php echo nt_get_option('property', 'area'); ?><span class="tooltip"><?php _e('Area', 'theme_front'); ?></span></li><?php endif; ?><?php if($bed): ?><li><i class="lt-icon flaticon-person1 big"></i> <?php echo floatval($bed); ?><span class="tooltip"><?php echo _n( 'Bedroom', 'Bedrooms', $bed, 'theme_front' ); ?></span></li><?php endif; ?><?php if($bath): ?><li><span class="lt-icon flaticon-shower5"></span> <?php echo floatval($bath); ?><span class="tooltip"><?php echo _n( 'Bathroom', 'Bathrooms', $bath, 'theme_front' ); ?></span></li><?php endif; ?><?php if($garage): ?><li><i class="lt-icon flaticon-list26"></i> <?php echo floatval($garage); ?><span class="tooltip"><?php echo _n( 'Garage', 'Garages', $garage, 'theme_front' ); ?></span></li><?php endif; ?>
</ul>
<div class="vspace"></div>

<?php the_content(); ?>

<div class="vspace"></div><div class="vspace"></div>

<div class="vc_tta-tabs wpb_content_element property-features-tabs" data-interval="0">
	<div class="vc_tta-tabs-container">
	<ul class="vc_tta-tabs-list">
		<?php if($has_details): ?><li class="tab-details"><a href="#tab-details" data-vc-tabs data-vc-container=".vc_tta"><?php _e('DETAILS', 'theme_front'); ?></a></li><?php endif; ?>

		<li class="tab-features"><a href="#tab-features" data-vc-tabs data-vc-container=".vc_tta"><?php _e('FEATURES', 'theme_front'); ?></a></li>

		<?php if($has_location): ?><li class="tab-location"><a href="#tab-location" data-vc-tabs data-vc-container=".vc_tta"><?php _e('LOCATION', 'theme_front'); ?></a></li><?php endif; ?>

		<li class="tab-contact"><a href="#tab-contact" data-vc-tabs data-vc-container=".vc_tta"><?php _e('CONTACT', 'theme_front'); ?></a></li>
	</ul>
	</div>

	<div class="vc_tta-panels-container">
	<div class="vc_tta-panels">

<?php if($has_details): ?>
<div id="tab-details" class="vc_tta-panel" data-vc-content=".vc_tta-panel-body">
<ul class="table-list">
		<?php if(is_array($details)) foreach($details as $detail):
		?>
			<li><strong><?php echo esc_html($detail['stack_title']); ?></strong> <span><?php echo esc_html($detail['detail']); ?></span></li>
		<?php endforeach; ?>

		<?php if(is_array($attachments)): ?>
		<li><strong><?php _e('ATTACHMENTS', 'theme_front'); ?></strong>
			<ul class="attachment-list">
				<?php foreach($attachments as $attachment):
					$url = wp_get_attachment_url($attachment['file']);
				?><li><a href="<?php echo $url; ?>"><?php echo $attachment['stack_title']; ?></a></li><?php endforeach; ?>
			</ul>
		</li>
		<?php endif; ?>

		<?php if(is_array($floorplans)):
			$uid = uniqid();
		?>
		<li><strong><?php _e('FLOOR PLANS', 'theme_front'); ?></strong>
			<ul class="floorplan-list clearfix">
				<?php foreach($floorplans as $image):
					$img_src = wp_get_attachment_image_src($image, 'thumbnail');
					$img_full_src = wp_get_attachment_image_src($image, 'full');
				?><li><a href="<?php echo $img_full_src[0]; ?>" class="swipebox" rel="<?php echo $uid; ?>" title="<?php echo get_the_title($image); ?>"><img src="<?php echo $img_src[0]; ?>" alt="<?php echo get_the_title($image); ?>" /></a></li><?php endforeach; ?>
			</ul>
		</li>
		<?php endif; ?>
</ul>
</div>
<?php endif; ?>


<div id="tab-features" class="vc_tta-panel">
<ul class="large-block-grid-<?php echo ($compare)?'2':'3'; ?> medium-block-grid-2 small-block-grid-1 amenity-list">
<?php
	$all_features = get_terms('features', array('hide_empty' => false));
	foreach($all_features as $feature):
?>
	<?php if(has_term($feature->term_id, 'features', $post->ID)): ?>
		<li class="active"><i class="flaticon-correct7"></i> <?php echo wp_kses_data($feature->name); ?></li>
	<?php else: ?>
		<li><i class="flaticon-cross37"></i> <?php echo wp_kses_data($feature->name); ?></li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
</div>

<?php if($has_location): ?>
<div id="tab-location" class="vc_tta-panel">

<?php if($location_text): ?>
<?php echo apply_filters('the_content', $location_text); ?>
<?php endif; ?>

<?php if(($location[0] && $location[1] && $location[2])): ?>
<div class="map-outer-wrap">
	<div class="map-wrap" data-zoom="<?php echo isset($location[3])?$location[3]:'15'; ?>" style="height:500px;" data-latitude="<?php echo esc_attr($location[1]); ?>" data-longitude="<?php echo esc_attr($location[2]); ?>" data-style="<?php echo nt_get_option('property', 'map_style', 'bw'); ?>">
		<div data-latitude="<?php echo esc_attr($location[1]); ?>" data-longitude="<?php echo esc_attr($location[2]); ?>"></div>
	</div>
	<a href="https://www.google.com/maps/?q=<?php echo wp_kses_data($location[1]); ?>,<?php echo wp_kses_data($location[2]); ?>&z=10" rel="no-follow" class="overlay-link" target="_blank"><?php _e('View on Google Map', 'theme_front'); ?></a>
</div>
<?php endif; ?>

</div>
<?php endif; ?>


<div id="tab-contact" class="vc_tta-panel">

	<?php $mailto = array(); ?>

	<?php if(nt_get_option('property', 'contact_note')): ?>
		<div class="contact-note">
		<?php echo apply_filters('the_content', nt_get_option('property', 'contact_note')); ?>
		</div>
	<?php endif; ?>

	<?php
		if(isset($agents) && $agents):

			if(!is_array($agents)) {
				$agents = array($agents);
			}

			foreach($agents as $agent):
			$agent_post = get_post($agent);
			$phone = get_post_meta( $agent, '_meta_phone', true );
			$email = sanitize_email(get_post_meta( $agent, '_meta_email', true ));
			$mailto[] = $email;
			$description = $agent_post->post_content;
	?>
	<div class="agent-card clearfix">
	<div class="card-head clearfix">
		<a href="<?php echo get_the_permalink($agent); ?>">
			<?php echo get_the_post_thumbnail( $agent, 'thumbnail', array('class'=>'thumb') ); ?>
		</a>
		<div class="title"><a href="<?php echo get_the_permalink($agent); ?>"><?php echo wp_kses_data($agent_post->post_title); ?></a></div>
		<div class="sub">
			<ul class="inline-list">
				<?php if($phone): ?><li><?php echo wp_kses_data($phone); ?></li><?php endif; ?><?php if($email): ?><li><a href="mailto:<?php echo antispambot($email); ?>"><?php echo antispambot($email); ?></a></li><?php endif; ?>
			</ul>
		</div>
	</div>
	</div>
	<?php endforeach; ?>

	<?php elseif(isset($user_agent) && $user_agent):
		$user = get_user_by('id', $user_agent);
		$user_agent_phone = get_user_meta($user_agent, 'phone', true);
		$user_agent_display_name = get_user_meta($user_agent, 'display_name', true);
		if(!$user_agent_display_name) $user_agent_display_name = get_user_meta($user_agent, 'nickname', true);
		$user_agent_description = get_user_meta($user_agent, 'description', true);
		$mailto[] = $user->user_email;
	?>
	<div class="agent-card clearfix">
		<div class="card-head clearfix">
			<?php echo get_avatar($user->user_email, 512, '', $user_agent_display_name); ?>
			<div class="title"><?php echo wp_kses_data($user_agent_display_name); ?></div>
			<div class="sub">
				<ul class="inline-list">
					<?php if($user_agent_phone): ?><li><?php echo wp_kses_data($user_agent_phone); ?></li><?php endif; ?><?php if($user->user_email): ?><li><a href="mailto:<?php echo antispambot($user->user_email); ?>"><?php echo antispambot($user->user_email); ?></a></li><?php endif; ?>
				</ul>
			</div>
		</div>

	</div>
	<?php endif; ?>

	
	<form method="post" class="validate-form agent-contact-form" id="agent-contact-form">
		<p><input type="text" name="from" placeholder="<?php _e('Email Address', 'theme_front'); ?> *" data-rule-required="true" data-rule-email="true" data-msg-required="Email Address is required." data-msg-email="Invalid Email address."/></p>
		<p><input type="text" name="phone" placeholder="<?php _e('Phone Number', 'theme_front'); ?>" /></p>
		<p><textarea name="message" placeholder="<?php _e('Message', 'theme_front'); ?>" rows="5"></textarea></p>
		<p>

		<input name="to" type="hidden" value="<?php echo implode(',', $mailto); ?>" />
		</p>

		<div class="form-response"></div>

		<?php if(nt_get_option('advance', 'recaptchar_site_key') && nt_get_option('advance', 'recaptchar_secret_key')): ?>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
     <script>
       function onSubmit(token) {
         document.getElementById("agent-contact-form").submit();
       }
     </script>
		 <input type="submit" name="bt_submit" class="full-width primary lt-button g-recaptcha" value="<?php _e('SUBMIT', 'theme_front'); ?>" data-sitekey="<?php echo nt_get_option('advance', 'recaptchar_site_key'); ?>" data-callback="onSubmit" data-size="invisible" />
	 <?php else: ?>
		<input type="submit" name="bt_submit" class="full-width primary lt-button" value="<?php _e('SUBMIT', 'theme_front'); ?>" />
	<?php endif; ?>
	</form>

</div>

</div>


</div>
</div>

<?php if( !$compare && nt_get_option('property', 'single_share', 'on') == 'on' ) get_template_part('section/section', 'share'); ?>

<?php if( !$compare && false ) get_template_part('section/section', 'related-property'); ?>



<?php
}
