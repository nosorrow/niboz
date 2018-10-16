<?php

add_action('wp_ajax_my_action', 'my_action');
add_action('wp_ajax_nopriv_my_action', 'my_action');

function my_action ()
{
    echo "ul";
    $i = 1;
    $a = get_term_children(252, 'location');
    foreach ($a as $loc) {
        echo $i;
        $term = get_term_by( 'id', $loc, 'location' );
        echo '<li><a href="">' . $term->name . '</a></li>';
        $i++;

    }
    echo "</ul>";
    die;

}


/*add_action( 'wp_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { */?><!--
    <script type="text/javascript" >
        jQuery(document).ready(function($) {

            var data = {
                'action': 'my_action',
                'whatever': 1234
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                alert('Got this from the server: ' + response);
            });
        });
    </script> --><?php
/*}*/