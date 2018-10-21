<?php

class DynamicLocation
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {

            static::$instance = new \DynamicLocation();
        }
        return static::$instance;
    }

    /**
     * DynamicLocation constructor.
     */
    protected function __construct()
    {

    }

    /**
     *  Running DynamicLocation on function.php
     */
    public function run()
    {
        /*
        * Create Ajax response
        */
        add_action('wp_ajax_dynamic_location', [$this, 'dynamic_location']);
        add_action('wp_ajax_nopriv_dynamic_location', [$this, 'dynamic_location']);
        /*
         *  Set Ajax JavaScript in Footer
        */
        add_action('wp_footer', [$this, 'dynamic_location_javascript']);

        add_action('wp_head', [$this, 'setCssStyle']);

        /*
         * This function overwrite and add new location filter (City)
         * original function /hometown-theme/custom/theme-functions.php
        */
        add_action('pre_get_posts', [$this, 'nt_child_property_filter']);
    }

    /**
     * Create Ajax response
     */
    public function dynamic_location()
    {
        $term_id = (int)$_POST['term_id'];
        $a = get_term_children($term_id, 'location');

        foreach ($a as $loc) {
            $term = get_term_by('id', $loc, 'location');
            $location_arr[$term->term_id] = $term->name;

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

    /**
    * Set Ajax JavaScript in Footer
    */
    public function dynamic_location_javascript()
    { ?>
        <script type="text/javascript">
            (function ($) {

                $(document).ready(function () {

                    function dynamicSelect(a) {

                        var text_any = "<?php _e("Any", 'theme_front');?>";
                        var s_location = $("select[name=s-location]");

                        var term_id = $(a).val();
                        var data = {
                            term_id: term_id,
                            action: 'dynamic_location'
                        };

                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php')?>',
                            method: 'POST',
                            data: data,
                            global: true,
                            beforeSend: function () {
                                s_location.attr('disabled', 'disabled');
                                $("span[id^=select2-s-location-]").text('.').addClass('loading');
                            }
                        }).done(
                            function (result) {
                                if (result === '0') {
                                    s_location.empty();
                                    $("select[name=s-location]").append('<option value="">Няма Райони</option>');
                                    $("span[id^=select2-s-location-]").text(text_any).removeClass('loading');

                                    s_location.removeAttr('disabled', 'disabled');

                                    return false;

                                } else {

                                    s_location.empty();
                                    s_location.append('<option value="">' + text_any + '</option>');
                                    s_location.append(result);

                                    s_location.removeAttr('disabled', 'disabled');

                                }

                                var selectedId = "<?php echo $this->child_get_request('s-location')?>";
                                // if have selected
                                if (selectedId) {

                                    var selectedOpt = $("option[value=" + selectedId + "]");
                                    selectedOpt.attr('selected', 'selected');
                                    var locationName = selectedOpt[0].text;

                                    $("span[id^=select2-s-location-]").text(locationName).attr("title", locationName).removeClass('loading');

                                } else {

                                    $("span[id^=select2-s-location-]").text(text_any).attr("title", text_any).removeClass('loading');

                                }
                            }).fail(function () {
                            console.log('%c An unexpected error has occurred-in dynamicSelect()', 'background: red; color: #fff; ' +
                                'fon-size:12px');

                        });
                    }

                    // =====
                    $("select[name=ss-location]").on('change', function () {

                        dynamicSelect(this);
                    });
                    // ======
                    dynamicSelect($("select[name=ss-location]"));

                });

            })(jQuery)

        </script> <?php
    }

    /**
     * Set css in header
     */
    public function setCssStyle()
    { ?>
        <style>
            .loading {
                background-color: rgba(255, 255, 255, 0.8);
                background-image: url("<?php echo get_stylesheet_directory_uri();?>/img/1.gif");
                background-size: 16px 11px;
                background-position: center center;
                background-repeat: no-repeat;
                color: #fff !important;

            }
        </style>

        <?php
    }

    /**
    * This function overwrite and add new location filter (City)
    * original function /hometown-theme/custom/theme-functions.php
    */
    public function nt_child_property_filter($query)
    {

        if (is_admin()) {
            return;
        }

        if (isset($query->query['bypass_filter'])) {
            return;
        }

        // Sorting
        if (($query->is_tax(array('location', 'status', 'type')) || $query->is_post_type_archive('property')) || (is_page() && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'property')) {

            $sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : nt_get_option('property', 'default_sorting', '');
            $_REQUEST['sort'] = $sort;
            if ($sort) {
                if ($sort == 'price-desc') {
                    $query->query_vars['orderby'] = 'meta_value_num';
                    $query->query_vars['order'] = 'desc';
                    $query->query_vars['meta_key'] = '_meta_price';
                } else if ($sort == 'price-asc') {
                    $query->query_vars['orderby'] = 'meta_value_num';
                    $query->query_vars['order'] = 'asc';
                    $query->query_vars['meta_key'] = '_meta_price';
                } else if ($sort == 'date-asc') {
                    $query->query_vars['orderby'] = 'date';
                    $query->query_vars['order'] = 'asc';
                } else if ($sort == 'date-desc') {
                    $query->query_vars['orderby'] = 'date';
                    $query->query_vars['order'] = 'desc';
                } else if ($sort == 'name-asc') {
                    $query->query_vars['orderby'] = 'title';
                    $query->query_vars['order'] = 'asc';
                } else if ($sort == 'name-desc') {
                    $query->query_vars['orderby'] = 'title';
                    $query->query_vars['order'] = 'desc';
                }
            }

        }


        if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'property') {

            // WPML
            $query->query_vars['suppress_filters'] = 0;

            // Property per Page
            if (!is_admin() && !isset($query->query_vars['posts_per_page'])) {
                $query->query_vars['posts_per_page'] = nt_get_option('property', 'per_page', get_option('posts_per_page'));
            }


            // ID
            if (isset($_REQUEST['property-id']) && $_REQUEST['property-id']) {
                $query->query_vars['meta_query'][] = array('key' => '_meta_id', 'value' => $_REQUEST['property-id'], 'compare' => 'LIKE');
            }

            // Bedroom
            if (isset($_REQUEST['min-bed']) && $_REQUEST['min-bed']) {
                $query->query_vars['meta_query'][] = array('key' => '_meta_bedroom', 'value' => $_REQUEST['min-bed'], 'compare' => '>=', 'type' => 'NUMERIC');
            }

            // Bathroom
            if (isset($_REQUEST['min-bath']) && $_REQUEST['min-bath']) {
                $query->query_vars['meta_query'][] = array('key' => '_meta_bathroom', 'value' => $_REQUEST['min-bath'], 'compare' => '>=', 'type' => 'NUMERIC');
            }

            // Price
            if (isset($_REQUEST['l-price']) && isset($_REQUEST['u-price'])) {
                $prices = get_meta_values('_meta_price', 'property');
                foreach ($prices as $key => $price) {
                    if (($price >= $_REQUEST['l-price'] && $price <= $_REQUEST['u-price']) || $price == '0') {
                        unset($prices[$key]);
                    }
                }

                $query->query_vars['meta_query'][] = array('key' => '_meta_price', 'value' => $prices, 'compare' => 'NOT IN');
            }

            // Area
            if (isset($_REQUEST['l-area']) && isset($_REQUEST['u-area'])) {
                $areas = get_meta_values('_meta_area', 'property');
                foreach ($areas as $key => $area) {
                    if (($area >= $_REQUEST['l-area'] && $area <= $_REQUEST['u-area']) || $area == '0') {
                        unset($areas[$key]);
                    }
                }
                $query->query_vars['meta_query'][] = array('key' => '_meta_area', 'value' => $areas, 'compare' => 'NOT IN', 'type' => 'NUMERIC');
            }

            $query->query_vars['tax_query']['relation'] = 'AND';

            // City - location
            if (isset($_REQUEST['ss-location']) && $_REQUEST['ss-location']) {
                $query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'location',
                    'include_children' => true,
                    'field' => 'term_id',
                    'terms' => array($_REQUEST['ss-location']), 'operator' => 'IN');
            }

            // Location
            if (isset($_REQUEST['s-location']) && $_REQUEST['s-location']) {
                $query->query_vars['tax_query'][] = array('taxonomy' => 'location', 'include_children' => true, 'field' => 'term_id', 'terms' => array($_REQUEST['s-location']), 'operator' => 'IN');
            }
            // Status
            if (isset($_REQUEST['s-status']) && $_REQUEST['s-status']) {
                $query->query_vars['tax_query'][] = array('taxonomy' => 'status', 'include_children' => true, 'field' => 'term_id', 'terms' => array($_REQUEST['s-status']), 'operator' => 'IN');
            }
            // Type
            if (isset($_REQUEST['s-type']) && $_REQUEST['s-type']) {
                $query->query_vars['tax_query'][] = array('taxonomy' => 'type', 'include_children' => true, 'field' => 'term_id', 'terms' => array($_REQUEST['s-type']), 'operator' => 'IN');
            }

            // var_dump($query);
            // die();

        }
    }

    /**
     * Get Request Parameter
     * @param $key
     * @return string
     */
    public function child_get_request($key)
    {
        return (isset($_REQUEST[$key])) ? $_REQUEST[$key] : '';
    }

}
