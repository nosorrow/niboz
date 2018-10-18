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

add_action('wp_footer', 'dynamic_location_javascript'); // Write our JS below here

function dynamic_location_javascript ()
{ ?>
    <script type="text/javascript">
        (function ($) {

            $("select[name=ss-location]").on('change', function () {

                var s_location =  $("select[name=s-location]");
                s_location.attr('disabled', 'disabled');
                s_location.empty();

                var term_id = $(this).val();
                var data = {
                    term_id: term_id,
                    action: 'dynamic_location'
                };
                $.post('<?php echo admin_url('admin-ajax.php')?>', data, function (result) {

                    if (result === '0') {

                        $("select[name=s-location]").append('<option value="">Няма Райони</option>');
                        s_location.removeAttr('disabled', 'disabled');

                        return false;

                    } else {
                        s_location.append(result);
                        s_location.removeAttr('disabled', 'disabled');

                    }
                });

            });

        })(jQuery)

    </script> <?php
}

add_action( 'after_setup_theme', 'setup_child_domain' );

function setup_child_domain(){
    load_theme_textdomain( 'theme_child', get_stylesheet_directory() . '/languages' );
}