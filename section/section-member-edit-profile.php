<?php 
	$current_user = wp_get_current_user();
	
	if(isset($_REQUEST['update_profile'])) {
		update_user_meta($current_user->ID, 'display_name', sanitize_text_field($_REQUEST['display_name']));
		update_user_meta($current_user->ID, 'user_url', sanitize_text_field($_REQUEST['user_url']));
		update_user_meta($current_user->ID, 'phone', sanitize_text_field($_REQUEST['phone']));
		update_user_meta($current_user->ID, 'description', sanitize_text_field($_REQUEST['description']));
	}

	$description = get_user_meta($current_user->ID, 'description', true);
	$phone = get_user_meta($current_user->ID, 'phone', true);
	$url = get_user_meta($current_user->ID, 'user_url', true);
	$display_name = get_user_meta($current_user->ID, 'display_name', true);
	$action_url = add_query_arg(array('fn'=>'edit-profile'), get_permalink());
?>
<h3><?php _e('Edit Profile', 'theme_front'); ?></h3>
<div class="vspace"></div>
<form method="post" action="<?php echo esc_url($action_url); ?>">
<div class="row">
<div class="columns large-9">
	<p>
		<label><?php _e('Username', 'theme_front'); ?></label>
		<input type="text" disabled value="<?php echo esc_attr($current_user->user_login); ?>" />
	</p>
	<p>
		<label><?php _e('Email Address', 'theme_front'); ?></label>
		<input type="text" disabled value="<?php echo esc_attr($current_user->user_email); ?>" />
	</p>
	<p>
		<label><?php _e('Profile Image', 'theme_front'); ?></label>
		<span class="box"><?php _e('Your profile image is managed with Gravatar, please manage it', 'theme_front'); ?> <a href="https://en.gravatar.com/" rel="no-follow"><?php _e('here', 'theme_front'); ?></a>.</span>
	</p>
	<p>
		<label><?php _e('Display Name', 'theme_front'); ?></label>
		<input type="text" name="display_name" value="<?php echo esc_attr($display_name); ?>" />
	</p>
	<p>
		<label><?php _e('Phone Number', 'theme_front'); ?></label>
		<input type="text" name="phone" value="<?php echo esc_attr($phone); ?>" />
	</p>
	<p>
		<label><?php _e('Website', 'theme_front'); ?></label>
		<input type="text" name="user_url" placeholder="http://" value="<?php echo esc_attr($url); ?>" />
	</p>
	<p>
		<label><?php _e('Description', 'theme_front'); ?></label>
		<textarea name="description" rows="8"><?php echo esc_html($description); ?></textarea>
	</p>
	<p><input type="submit" name="update_profile" class="primary" value="<?php _e('Update Profile', 'theme_front'); ?>" /></p>
</div>
<div class="columns large-7">
	
</div>
</div>
</form>