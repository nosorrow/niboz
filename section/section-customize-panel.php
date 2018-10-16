<?php
wp_enqueue_script('iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), THEME_VERSION, true );

?>
<script type="text/javascript">
//<![CDATA[ 
	jQuery(document).ready(function($) {
		
		// Layout
		$('[name="custom-layout"]').change(function(){
			$('.layout-wrap').removeClass('boxed').addClass( $(this).val() );
			var evt = document.createEvent('UIEvents');
		    evt.initUIEvent('resize', true, false,window,0);
		    window.dispatchEvent(evt);
		});

		// Element Style
		$('[name="custom-element-style"]').change(function(){
			$('body').removeClass('element-round element-semi-round').addClass( $(this).val() );
		});

		// Header Style
		$('[name="custom-header"]').change(function(){
			$('.main-header, .top-bar').removeClass('element-light element-dark');
			$('.main-header, .top-bar').addClass($(this).val());
			var logo_src = $('#branding img').attr('src');
			if( $(this).val() == 'element-dark' ){
				$('.main-header, #main-header-sticky-wrapper').css('background-color', '#fafafa');
				$('#branding img').attr('src', logo_src.replace('white','dark'));
			} else {
				$('.main-header, #main-header-sticky-wrapper').css('background-color', $('#primary-color-custom').iris('color').toString());
				$('#branding img').attr('src', logo_src.replace('dark','white'));
			}

		});

		// Top Bar
		$('[name="custom-top-bar"]').change(function(){
			if( $(this).val() == 'on' ) {
				$('.top-bar').show();
			} else {
				$('.top-bar').hide();
			}
		});

		// Color
		$('#primary-color-custom').iris({
		    palettes: ['#ad0000', '#ff6600', '#70b001', '#00ad8d', '#008abc', '#600089', '#bc0054', '#333333'],
		    change: function(event, ui) {
		        $(this).siblings('.color-indicator').css('background-color', ui.color.toString());

		        // Color
		        $(document).contents().find('head').append('<style type="text/css"> a, .header-wrap .header-top .nav-language.type-text li.active a, .primary-nav li.current-menu-item > a, .primary-nav li.current-menu-ancestor > a, .login-form .tab-list li a, .box-icon .feature-icon { color: '+ui.color.toString()+';} </style>');
		        // Background
		        $(document).contents().find('head').append("<style type='text/css'>.primary-nav > ul > li.bubble a, .lt-button.primary, input.primary[type='submit'], .rangeSlider .noUi-connect, .map-wrap .marker .dot, .map-wrap .marker:after, .map-wrap .cluster:before, .map-wrap .cluster:after, .card .status:before, .hero .status:before, .property-hero .status:before, #nprogress .bar, .button:hover, input[type='submit']:hover, input[type='button']:hover, .lt-button:hover, .tooltip, .map-outer-wrap .overlay-link, .select2-container--default .select2-results__option--highlighted[aria-selected], .hero .badge .status:before { background-color: "+ui.color.toString()+";} </style>");
		        // Border
		        $(document).contents().find('head').append('<style type="text/css"> .primary-nav > ul > li > ul.sub-menu, #nprogress .spinner-icon, .lt-button.primary, input.primary[type="submit"], .button:hover, input[type="submit"]:hover, input[type="button"]:hover, .lt-button:hover { border-color: '+ui.color.toString()+';} </style>');
		        $(document).contents().find('head').append("<style type='text/css'>.tooltip:after { border-top-color: "+ui.color.toString()+";} </style>");
		        // Shadow
		        $(document).contents().find('head').append('<style type="text/css"> #nprogress .peg { box-shadow: 0 0 10px '+ui.color.toString()+', 0 0 5px '+ui.color.toString()+';} </style>');

		       
		        
		    }
		});
		$('#bg-color-custom').iris({
		    palettes: ['#ad0000', '#ff6600', '#70b001', '#00ad8d', '#008abc', '#600089', '#bc0054', '#333333'],
		    change: function(event, ui) {
		        $(this).siblings('.color-indicator').css('background-color', ui.color.toString());
		        $('body').css('background-color', ui.color.toString()).css('background-image', 'none');
		    }
		});
		$(document).on('mousedown', function(ev){
			if ( $(ev.target).closest('.iris-picker').length == 0
				&& $(ev.target).siblings('.iris-picker').length == 0 ) {
				$('.iris-picker').hide();
			}
		});
		$('.input-color').focus(function(){
			$(this).iris('show');
		});

		// Background Pattern
		$('#customize-background-pattern li').click(function(){
			$('.customize-list li.active').removeClass('active');
			$(this).addClass('active');
			$('body').css('background-size', 'auto');
			$('body').css('background-attachment', 'fixed');
			if( $(this).data('src') ) {
				$('body').css('background-image', 'url(' + $(this).data('src') + ')' );
			} else {
				$('body').css('background-image', 'none' );
			}
		});

		// Background Image
		$('#customize-background-image li').click(function(){
			$('.customize-list li.active').removeClass('active');
			$(this).addClass('active');
			$('body').css('background-image', 'url(' + $(this).data('src') + ')' );
			$('#hero, #section-footer, #primary-nav').removeClass('element-light elment-dark').addClass($(this).data('style'));
			// $('body').css('background-size', 'cover');
			// $('body').css('background-attachment', 'fixed');
		});

		// Cover
		$('#customize-cover-image li').click(function(){
			$('#customize-cover-image li.active').removeClass('active');
			$(this).addClass('active');
			$('#book .cover img').attr('src', $(this).data('src') );
		});
		
		// Customize Box
		$('#customize-box-open').click(function(){
			if( $('#customize-box').hasClass('open') ) {
				$('#customize-box').stop().animate({
					left: '-202'
				}, 250);
			} else {
				$('#customize-box').stop().animate({
					left: '0'
				}, 250);
			}
			$('#customize-box').toggleClass('open');
		});
			
	});
//]]>		
</script>
<!-- End - Home Slide JS -->

<div id="customize-box" class="">
<div id="customize-box-wrap">

<section class="customize-section">
<!-- <div class="customize-title">Appearance</div> -->

	<div class="customize-item-title">Layout</div>
	<div class="customize-item">
		<input type="radio" checked id="custom-layout-full-width" name="custom-layout" value="full-width"  /><label for="custom-layout-full-width">Full Width</label> 
		<input type="radio" name="custom-layout" id="custom-layout-boxed" value="boxed"  /><label for="custom-layout-boxed">Boxed</label>
	</div>

	<div class="customize-item-title">Looks & Feels</div>
	<div class="customize-item">
		<input type="radio" name="custom-element-style" id="custom-element-style-crisp" value="" checked  /><label for="custom-element-style-crisp">Crisp</label>
		<input type="radio" id="custom-element-style-round" name="custom-element-style" value="element-round" <?php if(nt_get_option('appearance', 'looks_feels', 'element-round') == 'element-round'): ?>checked<?php endif; ?>  /><label for="custom-element-style-round">Round</label> 
	</div>
	

	<div class="customize-item-title">Accent Color</div>
	<div class="customize-item">
		<div class="color-indicator" style="background:<?php echo nt_get_option('appearance', 'site_color'); ?>;"></div><input type="text" class="input-color" id="primary-color-custom" value="<?php echo esc_attr(nt_get_option('appearance', 'site_color')); ?>" />
	</div>

	

</section>


</div><!-- #customize-box-wrap -->

<div id="customize-box-open">
	<i class="nt-icon-cog-1"></i>
	<i class="nt-icon-cancel"></i>
</div>

</div><!-- #customize-box -->