<?php

add_action('wp_ajax_dynamic_location', 'dynamic_location');
add_action('wp_ajax_nopriv_dynamic_location', 'dynamic_location');

function dynamic_location ()
{
    $term_id = $_POST['term_id'];
    $a = get_term_children($term_id, 'location');

    foreach ($a as $loc) {
        $term = get_term_by('id', $loc, 'location');

        $location_arr[$term->term_id] = $term->name;
        //echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
    }
    asort($location_arr);
    if (count($location_arr) > 0) {
        foreach ($location_arr as $id => $location) {
            echo '<option value="' . $id . '">' . $location . '</option>';
        }
    } else {
        echo 0;
    }

    die;

}
// Set Ajax JS in Footer
function dynamic_location_javascript ()
{ ?>
    <script type="text/javascript">
        (function ($) {

            var selected = "<?php echo child_get_request('s-location')?>";

            dynamicSelect("select[name=ss-location]");

            function dynamicSelect(a) {
                var text_any = "<?php _e("Any", 'theme_front');?>";
                var s_location = $("select[name=s-location]");
                var span_s_location = $("span[id^=select2-s-location-]");
                span_s_location.text(text_any);

                s_location.attr('disabled', 'disabled');

                var term_id = $(a).val();
                var data = {
                    term_id: term_id,
                    action: 'dynamic_location'
                };
                $.post('<?php echo admin_url('admin-ajax.php')?>', data, function (result) {

                    if (result === '0') {
                        s_location.empty();
                        $("select[name=s-location]").append('<option value="">Няма Райони</option>');
                        s_location.removeAttr('disabled', 'disabled');

                        return false;

                    } else {
                        s_location.empty();
                        s_location.append('<option value="">' + text_any + '</option>');
                        s_location.append(result);
                        if(selected){
                            $("option[value=" + selected + "]").attr('selected', 'selected');
                        }
                        s_location.removeAttr('disabled', 'disabled');

                    }
                });
            }


            $("select[name=ss-location]").on('change', function () {

                dynamicSelect(this);
            });


        })(jQuery)

    </script> <?php
}
add_action('wp_footer', 'dynamic_location_javascript');


// Add City in bg_BG.po
function setup_child_domain ()
{
    load_theme_textdomain('theme_child', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'setup_child_domain');

/*
 * This function extend and add new location filter (City)
 * /hometown-theme/custom/theme-functions.php
*/
function nt_child_property_filter( $query ) {

    if(is_admin()) {
        return;
    }

    if(isset($query->query['bypass_filter'])) {
        return;
    }

    // City - location
    if(isset($_REQUEST['ss-location']) && $_REQUEST['ss-location']) {
        $query->query_vars['tax_query'][] = array(
            'taxonomy' => 'location',
            'include_children' => true,
            'field' => 'term_id',
            'terms'=> array($_REQUEST['ss-location']), 'operator' => 'IN');
    }
}
add_action('pre_get_posts', 'nt_child_property_filter');

// Get Request Parameter
function child_get_request($key) {
    return (isset($_REQUEST[$key]))?$_REQUEST[$key]:'';
}