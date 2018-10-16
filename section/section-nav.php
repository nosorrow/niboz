<?php if(function_exists('wp_pagenavi')): ?>
	<?php 
		wp_pagenavi(
		array(
			'options' => array(
				'pages_text' => '',
				'first_text' => '',
				'last_text' => '',
				'prev_text' => '',
				'next_text' => '',
				'use_pagenavi_css' => false
			)
		)
	); ?>
<?php else: ?>
	<div class="pagination-wrap clearfix">
		<?php posts_nav_link(' ', "<span class='button button-primary previouspostslink'>".__("&larr; Previous Page", 'theme_front')."</span>", "<span class='button button-primary nextpostslink'>".__('Next Page &rarr;', 'theme_front')."</span>"); ?>
	</div>
<?php endif; ?>
