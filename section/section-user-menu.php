<?php
$member_pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'template-member.php'
));
if($member_pages) {
	$member_page_url = get_permalink($member_pages[0]->ID);
} else {
	$member_page_url = '#';
}
?>

<ul class="menu user-menu">
<?php if(is_user_logged_in()): 
	$current_user = wp_get_current_user();
?>
	<li><a href="<?php echo esc_url($member_page_url); ?>"><?php echo $current_user->display_name; ?></a></li><li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('LOGOUT', 'theme_front'); ?></a></li>
<?php else: ?>
	<li class="login"><a href="#" class="modal-link" data-modal="login-modal"><?php _e('LOGIN / REGISTER', 'theme_front'); ?></a></li>
<?php endif; ?>
</ul>