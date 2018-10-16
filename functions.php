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