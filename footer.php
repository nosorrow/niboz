<footer class="footer-main <?php echo nt_get_option('footer', 'footer_element_style', 'element-dark') ?>">

<?php if(nt_get_option('footer', 'footer_show', 'on') == 'on'): ?>
<div class="footer-top">
<div class="row">
<?php if(nt_get_option('footer', 'pre_footer_columns', '4-cols') == '4-cols'): ?>
	<div class="large-3 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-1' ) ); ?></div>
	<div class="large-3 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-2' ) ); ?></div>
	<div class="large-3 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-3' ) ); ?></div>
	<div class="large-3 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-4' ) ); ?></div>
<?php else: ?>
	<div class="large-4 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-1' ) ); ?></div>
	<div class="large-4 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-2' ) ); ?></div>
	<div class="large-4 medium-6 columns"><?php if ( dynamic_sidebar( 'footer-3' ) ); ?></div>
<?php endif; ?>
</div>	
</div>
<?php endif; ?>

<div class="footer-bottom">
	<div class="row">

	<?php if(is_array(nt_get_option('footer', 'social_items'))): ?>
	<ul class="social-list">
	<?php foreach( nt_get_option('footer', 'social_items') as $link ): ?>
	<li><a href="<?php echo esc_url($link['stack_title']); ?>"><?php echo do_shortcode('[nt_icon id="'.$link['icon'].'"]'); ?></a></li>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<div class="copyright-text"><?php echo nt_get_option('footer', 'footer_text', 'Copyright &copy; '.date("Y").' '.get_bloginfo('name')); ?></div>	
	</div>
</div>

</footer>

<div class="mobile-menu">
	<nav>
	<?php if(nt_get_option('header', 'header_type') == 'logo-center') {
			wp_nav_menu( array( 'theme_location' => 'primary-left', 'container' => false, 'container_class' => false, 'menu_id' => false, 'fallback_cb' => '', 'depth' => 0  ) );
			wp_nav_menu( array( 'theme_location' => 'primary-right', 'container' => false, 'container_class' => false, 'menu_id' => false, 'fallback_cb' => '', 'depth' => 0  ) );
		} else {
			wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'container_class' => false, 'menu_id' => false, 'fallback_cb' => '', 'depth' => 0  ) );
		} 
	?>
	</nav>

	<?php if(nt_get_option('header', 'show_topbar', 'on') == 'on'): ?>
		
		<?php wp_nav_menu( array( 'theme_location' => 'top-right', 'container' => 'nav', 'container_class' => false, 'menu_id' => false, 'fallback_cb' => '', 'depth' => 1  ) ); ?>
		
		<?php if(function_exists('icl_get_languages') && nt_get_option('header', 'show_wpml', 'on') == 'on'): ?>
			<nav><?php get_template_part('section/section', 'wpml-menu'); ?></nav>
		<?php endif; ?>

		<?php if(is_array(nt_get_option('header', 'social_items'))): ?>
		<nav>
		<ul class="menu social-menu">
		<?php foreach( nt_get_option('header', 'social_items') as $link ): ?>
		<li><a href="<?php echo esc_url($link['stack_title']); ?>"><?php //echo $link['stack_title']; ?> <?php echo do_shortcode('[nt_icon id="'.$link['icon'].'"]'); ?></a></li>
		<?php endforeach; ?>
		</ul>
		</nav>
		<?php endif; ?>

		<?php if(nt_get_option('header', 'show_search', 'on') == 'on'): ?>
		<nav>
		<form method="get" class="nt-search-form" action="<?php echo home_url(); ?>">
			<input type="text" id="search-text" class="input-text" name="s" placeholder="<?php _e('Search &#8230;', 'theme_front');?>" autocomplete="off" />
		</form>
		</nav>
		<?php endif; ?>

		<?php if(nt_get_option('header', 'show_login', 'on') == 'on'): ?>
			<?php if(is_user_logged_in()): ?>
				<nav><?php get_template_part('section/section', 'user-menu'); ?></nav>
			<?php endif; ?>

			<?php if(!is_user_logged_in()) get_template_part('section/section', 'login-register'); ?>
		<?php endif; ?>




	<?php endif; ?>
</div>

</div></div><!-- .layout-wrap -->

<div class="modal-mask">
	<div class="modal login-modal">
		<?php get_template_part('section/section', 'login-register'); ?>
		<i class="flaticon-cross37 close-bt"></i>
	</div>
</div>

<?php 
global $nt_site_message;
if($nt_site_message): ?>
<div class="message-mask">
<div class="inner">
<p><?php echo esc_html($nt_site_message); ?></p>
<i class="flaticon-correct7"></i> 
</div>
</div>
<?php endif; ?>

<div id="test">

</div>

<?php wp_footer(); ?>
<script>
    (function ($) {
        var data = {
            c: 'Hi',
            action: 'my_action'
        }
       $.post('<?php echo admin_url('admin-ajax.php')?>', data ,function(result){

           if(result !==0){
               $("#test").text(result);

           }
           // alert("Data: " + result);
        });

    })(jQuery)
</script>
</body>
</html>