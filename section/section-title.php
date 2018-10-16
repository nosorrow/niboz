<?php

	$post_id = get_queried_object_id();	

	if( is_singular('post') || is_home() ) {
		$post_id = get_option('page_for_posts'); 
	}
	$title = '';
	$sub_title = '';

	// Show or Hide title
	$show_title = get_post_meta( $post_id, '_general_show_main_title', true );
	if( $show_title == 'off' ) return;
	$custom_title = get_post_meta( $post_id, '_general_custom_main_title', true );
	$custom_sub_title = get_post_meta( $post_id, '_general_custom_sub_title', true );
	
	$title = ( $custom_title ) ? $custom_title : get_the_title( $post_id );
	$sub_title = ( $custom_sub_title ) ? $custom_sub_title : '';

	// Blog
	if( is_singular('post') || is_home() ) {
		if(!$post_id) $title = __('Blog', 'theme_front');
	}

	// 404 page
	if( is_404() ) {
		$title = __('<strong>404</strong> page not found', 'theme_front');
		$sub_title = __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. isn&rsquo;t it?', 'theme_front' );
	}

	// Search page
	if( is_search() ) {
		$title = __('<strong>Search</strong> ', 'theme_front') . $_REQUEST['s'];

		if( $wp_query->found_posts > 0 ) {
			$sub_title = $wp_query->found_posts . __(  ' results matched search query', 'theme_front' );
		} else {
			$sub_title = __( 'no results matched search query', 'theme_front' );
		}
	}

	// Attachment
	if(is_attachment()) {
		$title = __('Attachment', 'theme_front');
	}

	// Archive
	if( is_archive() ) {
		if ( is_category() ) {
			$title = sprintf( __( '<em>Category</em> %s', 'theme_front' ), single_cat_title( '', false ) );
			$sub_title = category_description();
		} elseif (is_tag() ) {
			$title = sprintf( __( '<em>Tag</em> %s', 'theme_front' ), single_tag_title( '', false ) );
			$sub_title = tag_description();
		} elseif ( is_day() ) {
			$title = sprintf( __( '<em>Daily</em> %s', 'theme_front' ), get_the_time( 'F jS, Y' ) );
		} elseif ( is_month() ) {
			$title = sprintf( __( '<em>Monthly</em> %s', 'theme_front' ), get_the_time( 'F, Y' ) );
		} elseif ( is_year() ) {
			$title = sprintf( __( '<em>Yearly</em> %s', 'theme_front' ), get_the_time( 'Y' ) );
		} elseif ( is_author() ) {
			if( get_query_var( 'author_name' ) ) {
				$curauth = get_user_by( 'slug', get_query_var('author_name') );
			} else {
				$curauth = get_userdata( get_query_var( 'author' ) );
			}
			$author_name = get_the_author_meta('display_name', $curauth->ID);
			$author_desc = get_the_author_meta('description', $curauth->ID);
			$title = sprintf( __( '<em>Author</em> %s', 'theme_front' ), $author_name );
			$sub_title = '';
		}
	}

	// Post Type Archive
	if(is_post_type_archive()) {
		$title = post_type_archive_title(false, false);
	}

	// Taxonomy Archive
	if(is_tax()) {
		$title = single_term_title(false, false);
	}

	// Agent
	if(is_singular('agent')) {
		$sub_title = '<ul>';
		if(get_post_meta( $post_id, '_meta_role', true )) $sub_title .= '<li>'.get_post_meta( $post_id, '_meta_role', true ).'</li>';
		if(get_post_meta( $post_id, '_meta_phone', true )) $sub_title .= '<li>'.get_post_meta( $post_id, '_meta_phone', true ).'</li>';
		if(get_post_meta( $post_id, '_meta_email', true )) $sub_title .= '<li>'.get_post_meta( $post_id, '_meta_email', true ).'</li>';
		$sub_title .= '</ul>';
	}

	// Member
	if(is_page_template('template-member.php')) {
		$current_user = wp_get_current_user();
		$phone = get_user_meta($current_user->ID, 'phone', true);
		$title = get_user_meta($current_user->ID, 'display_name', true);

		$description = get_user_meta($current_user->ID, 'description', true);
		$phone = get_user_meta($current_user->ID, 'phone', true);
		$url = get_user_meta($current_user->ID, 'user_url', true);

		$sub_title = '<ul>';
		if($url) $sub_title .= '<li><a href="'.esc_url($url).'" rel="no-follow">'.str_replace('http://', '', $url).'</a></li>';
		if($phone) $sub_title .= '<li>'.$phone.'</li>';
		if($current_user->user_email) $sub_title .= '<li>'.$current_user->user_email.'</li>';
		$sub_title .= '</ul>';
	}

	// Property
	if(is_singular('property')) {
		if(isset($_REQUEST['compare-with'])) {
			return;
		} else {
			$sub_title = '<ul>';
			
			$locations = wp_get_post_terms($post->ID, 'location');
			$locations_sorted = array();
			nt_sort_terms_hierarchicaly($locations, $locations_sorted);
			foreach($locations_sorted as $l) {
				$sub_title .= '<li><a href="'.get_term_link($l).'">'.$l->name.'</a></li>';
				foreach($l->children as $lc) {
					$sub_title .= '<li><a href="'.get_term_link($lc).'">'.$lc->name.'</a></li>';
				}
			}
			
			$sub_title .= get_the_term_list($post->ID, 'type', '<li>', '</li><li>', '</li>');
			$sub_title .= '</ul>';
		}
	}

	// Style
	$title_element_style = ( get_post_meta( $post_id, '_general_title_element_style', true ) ) ? get_post_meta( $post_id, '_general_title_element_style', true ) : nt_get_option('page', 'element_style', 'element-dark');
	
	$title_bg_color = ( get_post_meta( $post_id, '_general_title_bg_color', true ) ) ? get_post_meta( $post_id, '_general_title_bg_color', true ) : nt_get_option('page', 'bg_color');
	$title_bg_color = 'background-color:'.$title_bg_color.';';
	
	$title_bg_image = ( get_post_meta( $post_id, '_general_title_bg_image', true ) ) ? get_post_meta( $post_id, '_general_title_bg_image', true ) : nt_get_option('page', 'bg_image');
	if( $title_bg_image ) { 
		$title_bg_image = wp_get_attachment_image_src($title_bg_image, 'full');
		$title_bg_image = $title_bg_image[0];
	}
	$title_bg_image = 'background-image: url('.$title_bg_image.');';

	$title_bg_image_style = ( get_post_meta( $post_id, '_general_title_bg_image_style', true ) ) ? get_post_meta( $post_id, '_general_title_bg_image_style', true ) : nt_get_option('page', 'bg_image_style');
	$title_bg_image_style = 'background-size:'.$title_bg_image_style.';';

	
?>

<div class="section-title <?php if(is_singular('agent') || is_page_template('template-member.php')): ?>with-thumb<?php endif; ?> <?php echo esc_attr($title_element_style); ?>" style="<?php echo esc_attr($title_bg_color); ?> <?php echo esc_attr($title_bg_image); ?> <?php echo esc_attr($title_bg_image_style); ?>">
<div class="row">
<div class="columns">
	
	<?php if(is_singular('agent')): ?>
		<div class="thumb"><i class="flaticon-bust"></i><?php the_post_thumbnail('full'); ?></div>
	<?php endif; ?>

	<?php if(is_page_template('template-member.php')): 
		$current_user = wp_get_current_user();
	?>
		<div class="thumb"><i class="flaticon-bust"></i><?php echo get_avatar($current_user->user_email, 512, '', $current_user->display_name); ?></div>
	<?php endif; ?>

	<h1 class="page-title"><?php echo wp_kses_data($title); ?></h1>
	<?php if($sub_title): ?>
	<div class="sub-title"><?php echo wp_kses_post($sub_title); ?></div>
	<?php endif; ?>
</div>
</div>
</div>