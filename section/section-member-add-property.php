<?php 
	$current_user = wp_get_current_user();
	
	$property = array();

	// Update
	if($_REQUEST['fn']=='edit-property') {
		if(!isset($_REQUEST['property_id'])) {
			wp_redirect(get_the_permalink());
			exit;
		}
		
		$edit_property = get_posts(array('post_type' => 'property', 'post__in' => array($_REQUEST['property_id']), 'post_status' => 'any', 'author' => $current_user->ID));
		if(!$edit_property) {
			wp_redirect(get_the_permalink());
			exit;
		}
		// var_dump($edit_property[0]->post_status);
		$property['post_status'] = $edit_property[0]->post_status;
		$property_id = $_REQUEST['property_id'];
	}

	// Apply New Value
	if(isset($_REQUEST['submit'])) {
		$property['post_type'] = 'property';
		$property['post_content'] = wp_strip_all_tags($_REQUEST['post_content']);
		$property['post_title'] = wp_strip_all_tags($_REQUEST['post_title']);
		$property['post_author'] = $current_user->ID;
	}
	
	// Add
	if(isset($_REQUEST['submit']) && $_REQUEST['fn']=='submit-property') {
		if(nt_get_option('property', 'submit_require_payment') == 'on') {
			$property['post_status'] = 'draft';
		} else {
			$property['post_status'] = 'pending';
		}
		$property_id = wp_insert_post($property, false);
		update_post_meta($property_id, '_meta_user_agent', $current_user->ID);
	}

	// Meta
	if(isset($_REQUEST['submit'])) {
		update_post_meta($property_id, '_meta_id', sanitize_text_field($_REQUEST['id']));
		update_post_meta($property_id, '_meta_area', sanitize_text_field($_REQUEST['area']));
		update_post_meta($property_id, '_meta_price', sanitize_text_field($_REQUEST['price']));
		update_post_meta($property_id, '_meta_per', sanitize_text_field($_REQUEST['per']));
		update_post_meta($property_id, '_meta_bedroom', sanitize_text_field($_REQUEST['bedroom']));
		update_post_meta($property_id, '_meta_bathroom', sanitize_text_field($_REQUEST['bathroom']));
		update_post_meta($property_id, '_meta_garage', sanitize_text_field($_REQUEST['garage']));
		update_post_meta($property_id, '_meta_location', array(sanitize_text_field($_REQUEST['location']),sanitize_text_field($_REQUEST['latitude']),sanitize_text_field($_REQUEST['longitude'])));
		

		wp_set_post_terms($property_id, $_REQUEST['s-location'], 'location');
		wp_set_post_terms($property_id, $_REQUEST['s-type'], 'type');
		wp_set_post_terms($property_id, $_REQUEST['s-status'], 'status');
		if(isset($_REQUEST['s-features'])) wp_set_post_terms($property_id, $_REQUEST['s-features'], 'features');

		// Featured Image
		if(!isset($_REQUEST['featured_image_id'])) {
			delete_post_thumbnail($property_id);
		}
		if(isset($_FILES['featured_image'])) {
			$thumbnail_id = nt_upload_user_file( $_FILES['featured_image'] );
			set_post_thumbnail($property_id, $thumbnail_id );
		}

		// Gallery Image
		if(isset($_REQUEST['gallery_ids'])) {
			update_post_meta($property_id, '_meta_gallery', $_REQUEST['gallery_ids']);
		} else {
			update_post_meta($property_id, '_meta_gallery', array());
		}
		if(is_array($_FILES['gallery'])) {

			$gallery_files = nt_reArrayFiles($_FILES['gallery']);
			$gallery = get_post_meta($property_id, '_meta_gallery', true);
			foreach($gallery_files as $file) {
				if($file['name']) {
					$thumbnail_id = nt_upload_user_file($file);
					array_push($gallery, $thumbnail_id);
				}
			}
			update_post_meta($property_id, '_meta_gallery', $gallery);
		}
		
	}
	
	// Submit
	if(isset($_REQUEST['submit']) && $_REQUEST['fn']=='submit-property') {
		wp_redirect(get_the_permalink());
		exit;
	}

	// Update
	if(isset($_REQUEST['submit']) && $_REQUEST['fn']=='edit-property') {
		$property['ID'] = $_REQUEST['property_id'];
		
		global $nt_site_message;
		$nt_site_message = __('Your property has been updated.', 'theme_front');
		$property_id = wp_insert_post($property, false);
	}

	// Current Property
	$gallery = $bedroom = $bathroom = $garage = $area = $price = $id = $location = $per = '';
	$latitude = '37.4418834';
	$longitude = '-122.14301949999998';
	if(isset($property_id)) {
		$cur_property = get_post($property_id);
		$gallery = get_post_meta( $property_id, '_meta_gallery', true );
		$bedroom = get_post_meta( $property_id, '_meta_bedroom', true );
		$bathroom = get_post_meta( $property_id, '_meta_bathroom', true );
		$garage = get_post_meta( $property_id, '_meta_garage', true );
		$area = get_post_meta( $property_id, '_meta_area', true );
		$price = get_post_meta( $property_id, '_meta_price', true );
		$per = get_post_meta( $property_id, '_meta_per', true );
		$id = get_post_meta( $property_id, '_meta_id', true );
		$location_array = get_post_meta( $property_id, '_meta_location', true );
		$location = $location_array[0];
		$latitude = $location_array[1];
		$longitude = $location_array[2];

		$s_location = wp_get_post_terms($property_id, 'location');
		if(isset($s_location[0])) $s_location = $s_location[0];

		$s_status = wp_get_post_terms($property_id, 'status');
		if(isset($s_status[0])) $s_status = $s_status[0];

		$s_type = wp_get_post_terms($property_id, 'type');
		if(isset($s_type[0])) $s_type = $s_type[0];
	}

	$action_url = add_query_arg(array('fn'=>$_REQUEST['fn']), get_permalink());
	if(isset($_REQUEST['property_id'])) add_query_arg(array('property_id'=>$_REQUEST['property_id']), $action_url);
?>
<h3><?php _e('Submit / Edit Property', 'theme_front'); ?></h3>
<div class="vspace"></div>
<form method="post" class="validate-form" action="<?php esc_url($action_url); ?>" enctype="multipart/form-data">
<div class="row">
<div class="columns large-11">
	<p>
		<label><?php _e('Title', 'theme_front'); ?></label>
		<input type="text" value="<?php echo esc_attr(@nt_check($cur_property->post_title)); ?>" name="post_title" data-rule-required="true" data-msg-required="Please fill required field." />
	</p>
	<p>
		<label><?php _e('Property ID', 'theme_front'); ?></label>
		<input type="text" value="<?php echo esc_attr(nt_check($id)); ?>" name="id" data-rule-required="true" data-msg-required="Please fill required field." />
	</p>
	<p>
		<label><?php _e('Description', 'theme_front'); ?></label>
		<textarea rows="6" name="post_content" data-rule-required="true" data-msg-required="Please fill required field."><?php echo @nt_check($cur_property->post_content); ?></textarea>
	</p>
	<div class="row">
		<div class="columns large-6">
			<p>
			<label><?php _e('Type', 'theme_front'); ?></label>
			<select class="select2" name="s-type">
				<option value=""><?php _e('Any', 'theme_front'); ?></option>
				<?php 
				$terms = get_terms( 'type', array('orderby' => 'none', 'hide_empty' => false));
				$terms_sorted = array();
				nt_sort_terms_hierarchicaly($terms, $terms_sorted);
				foreach($terms_sorted as $term):
				?>
				<option value="<?php echo esc_attr($term->term_id); ?>" <?php if(isset($s_type->term_id) && $s_type->term_id == $term->term_id) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
					<?php foreach($term->children as $term_child): ?>
						<option value="<?php echo esc_attr($term_child->term_id); ?>" <?php if(isset($s_type->term_id) && $s_type->term_id == $term_child->term_id) echo 'selected="selected"'; ?>>- <?php echo $term_child->name; ?></option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
			</p>
		</div>
		<div class="columns large-6">
			<p>
			<label><?php _e('Status', 'theme_front'); ?></label>
			<select class="select2" name="s-status">
				<option value=""><?php _e('Any', 'theme_front'); ?></option>
				<?php 
				$terms = get_terms( 'status', array('orderby' => 'none', 'hide_empty' => false));
				$terms_sorted = array();
				nt_sort_terms_hierarchicaly($terms, $terms_sorted);
				foreach($terms_sorted as $term):
				?>
				<option value="<?php echo esc_attr($term->term_id); ?>" <?php if(isset($s_status->term_id) && $s_status->term_id == $term->term_id) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
					<?php foreach($term->children as $term_child): ?>
						<option value="<?php echo esc_attr($term_child->term_id); ?>" <?php if(isset($s_status->term_id) && $s_status->term_id == $term_child->term_id) echo 'selected="selected"'; ?>>- <?php echo $term_child->name; ?></option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
			</p>
		</div>
		<div class="columns large-6">
			<p>
			<label><?php _e('Location', 'theme_front'); ?></label>
			<select class="select2" name="s-location">
				<option value=""><?php _e('Any', 'theme_front'); ?></option>
				<?php 
				$terms = get_terms( 'location', array('orderby' => 'none', 'hide_empty' => false));
				$terms_sorted = array();
				nt_sort_terms_hierarchicaly($terms, $terms_sorted);
				foreach($terms_sorted as $term):
				?>
				<option value="<?php echo esc_attr($term->term_id); ?>" <?php if(isset($s_location->term_id) && $s_location->term_id == $term->term_id) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
					<?php foreach($term->children as $term_child): ?>
						<option value="<?php echo esc_attr($term_child->term_id); ?>" <?php if(isset($s_location->term_id) && $s_location->term_id == $term_child->term_id) echo 'selected="selected"'; ?>>- <?php echo $term_child->name; ?></option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>
			</p>
		</div>
		<div class="columns large-6">
			<p>
				<label><?php _e('Price', 'theme_front'); ?> <small>(<?php echo nt_get_option('property', 'currency'); ?>)</small></label>
				<input type="text" class="numeric" value="<?php echo esc_attr(nt_check($price)); ?>" name="price" data-rule-required="true" data-msg-required="Please fill required field." />
			</p>
		</div>
		<div class="columns large-6">
			<p>
				<label><?php _e('Price Per', 'theme_front'); ?> <small>(<?php _e('for example: month, year', 'theme_front'); ?>)</small></label>
				<input type="text" class="text" value="<?php echo esc_attr(nt_check($per)); ?>" name="per"  />
			</p>
		</div>
		<div class="columns large-6">
			<p>
				<label><?php _e('Area', 'theme_front'); ?> <small>(<?php echo nt_get_option('property', 'area'); ?>)</small></label>
				<input type="text" class="numeric" value="<?php echo esc_attr(nt_check($area)); ?>" name="area" data-rule-required="true" data-msg-required="Please fill required field." />
			</p>
		</div>
		<div class="columns large-6">
			<p>
				<label><?php _e('Bathroom', 'theme_front'); ?></label>
				<input type="text" class="numeric" value="<?php echo esc_attr(nt_check($bathroom)); ?>" name="bathroom" />
			</p>
		</div>
		<div class="columns large-6">
			<p>
				<label><?php _e('Bedroom', 'theme_front'); ?></label>
				<input type="text" class="numeric" value="<?php echo esc_attr(nt_check($bedroom)); ?>" name="bedroom" />
			</p>
		</div>
		<div class="columns large-6">
			<p>
				<label><?php _e('Garage', 'theme_front'); ?></label>
				<input type="text" class="numeric" value="<?php echo esc_attr(nt_check($garage)); ?>" name="garage" />
			</p>
		</div>
	</div>
	<div class="vspace"></div>
	<p>
		<label><?php _e('Features', 'theme_front'); ?></label>
		<ul class="large-block-grid-3 small-block-grid-1 amenity-list">
		<?php 
			$all_features = get_terms('features', array('hide_empty' => false));
			foreach($all_features as $feature):
		?>
			<?php if(isset($property_id) && has_term($feature->term_id, 'features', $property_id)): ?>
				<li class="active"><input type="checkbox" checked="checked" name="s-features[]" value="<?php echo esc_attr($feature->term_id); ?>" /> <?php echo $feature->name; ?></li>
			<?php else: ?>
				<li class="active"><input type="checkbox" name="s-features[]" value="<?php echo esc_attr($feature->term_id); ?>" /> <?php echo $feature->name; ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</p>
	<div class="vspace"></div>
	<p>
		<label><?php _e('Featured Image', 'theme_front'); ?></label>
		<input type="file" name="featured_image" />
		<?php if(isset($property_id) && has_post_thumbnail($property_id)): ?>
		<div class="upload-img">
			<input type="hidden" name="featured_image_id" value="<?php echo esc_attr(get_post_thumbnail_id($property_id)); ?>" />
			<?php echo get_the_post_thumbnail($property_id, 'thumbnail'); ?>
			<i class="flaticon-prohibited1 remove-bt"></i> 
		</div>
		<?php endif; ?>
	</p>
	<div class="vspace"></div>
	<p>
		<label><?php _e('Gallery', 'theme_front'); ?></label>
		<input type="file" name="gallery[]" multiple />
		<?php 
			if(is_array($gallery))
			foreach($gallery as $img): 
			$thumb = wp_get_attachment_image_src($img, 'thumbnail');
		?>
		<div class="upload-img">
			<img src="<?php echo $thumb[0]; ?>" />
			<input type="hidden" name="gallery_ids[]" value="<?php echo esc_attr($img); ?>">
			<i class="flaticon-prohibited1 remove-bt"></i> 
		</div>
		<?php endforeach; ?>
	</p>
	<div class="vspace"></div>
	<div>
		<label><?php _e('Location', 'theme_front'); ?></label>
		<p>
		<input type="text" value="<?php echo esc_attr($location); ?>" class="location" name="location" placeholder="Location"  />
		</p>
		<div class="row">
			<div class="columns large-6">
				<p>
				<input type="text" value="<?php echo esc_attr($latitude); ?>" class="latitude" name="latitude" placeholder="Latitude" />
				</p>
			</div>
			<div class="columns large-6">
				<p>
				<input type="text" value="<?php echo esc_attr($longitude); ?>" class="longitude" name="longitude" placeholder="Longitude" />
				</p>
			</div>
		</div>
		<span class="location-picker" data-latitude="<?php echo esc_attr($latitude); ?>" data-longitude="<?php echo esc_attr($longitude); ?>" data-location="" style="height: 300px; display: block;"></span>
	</div>

	<div class="vspace"></div>
	<div class="vspace"></div>

	<div class="form-response"></div>
	<?php if($_REQUEST['fn']=='submit-property'): ?>
		<p><input type="submit" name="submit" class="lt-button primary" value="<?php _e('Submit Property', 'theme_front'); ?>" /></p>
	<?php else: ?>
		<p><input type="submit" name="submit" class="lt-button  primary" value="<?php _e('Update Property', 'theme_front'); ?>" /></p>
	<?php endif; ?>
</div>
</div>
</form>