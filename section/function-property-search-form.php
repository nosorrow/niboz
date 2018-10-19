<?php
function property_search_form ($search_layout, $widget = false)
{

    // Prepare form attribute
    $search_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-property-search.php'
    ));
    if ($search_pages) {
        $search_pages = array_values($search_pages);
        $search_page_id = $search_pages[0]->ID;
        $target_url = get_permalink($search_page_id);
    } else {
        $target_url = get_home_url();
    }

    // Prepare All metas
    $all_properties = get_posts(array('post_type' => 'property', 'posts_per_page' => -1, 'bypass_filter' => true, 'suppress_filters' => 0));
    $meta_beds = $meta_baths = $meta_price = $meta_area = array();
    foreach ($all_properties as $property) {
        $meta_beds[] = get_post_meta($property->ID, '_meta_bedroom', true);
        $meta_bathroom[] = get_post_meta($property->ID, '_meta_bathroom', true);
        $meta_price[] = get_post_meta($property->ID, '_meta_price', true);
        $meta_area[] = get_post_meta($property->ID, '_meta_area', true);
    }

    $meta_price = array_filter($meta_price, 'lt_filter_blank');
    $meta_area = array_filter($meta_area, 'lt_filter_blank');
    ?>

    <form method="get" action="<?php echo $target_url; ?>" class="property-search-form">

        <?php if (isset($_REQUEST['lang'])): ?>
            <input type="hidden" name="lang" value="<?php echo $_REQUEST['lang']; ?>"/>
        <?php endif; ?>

        <?php if (!isset($search_page_id)): ?>
            <input type="hidden" name="post_type" value="<?php echo nt_get_option('property', 'slug', 'property'); ?>"/>
        <?php endif; ?>

        <input type="hidden" name="property-search" value="true"/>
        <div class="row">

            <?php /*if ($search_layout != 'compact' && !$widget): */?><!--
                <div class="columns large-2 medium-4 search-id">
                    <label><?php /*_e('Property ID', 'theme_front'); */?></label>
                    <input type="text" name="property-id" placeholder="<?php /*_e('Any', 'theme_front'); */?>"
                           value="<?php /*echo esc_attr(nt_get_request('property-id')); */?>" autofocus tabindex="1"/>
                </div>
            --><?php /*endif; */?>

            <?php // @todo Plamen make change ss-location;
            ?>
            <div class="columns large-3 medium-3 search-city">
                <label><?php _e('City', 'theme_child'); ?></label>
                <select class="select2" name="ss-location" tabindex="2">
                    <option value=""><?php _e('Any', 'theme_front'); ?></option>
                    <?php
                    $terms = get_terms('location', array('orderby' => 'name', 'hide_empty' => 1));
                    $terms_sorted = array();
                    nt_sort_terms_hierarchicaly($terms, $terms_sorted);
                    foreach ($terms_sorted as $term):
                        ?>
                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php if (nt_get_request('ss-location') ==
                            $term->term_id) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
                        <?php /*foreach($term->children as $term_child): */
                        ?><!--
                <option value="<?php /*echo esc_attr($term_child->term_id); */
                        ?>" <?php /*if(nt_get_request('s-location') == $term_child->term_id) echo 'selected="selected"'; */
                        ?>>- <?php /*echo $term_child->name; */
                        ?></option>
                <?php /*foreach($term_child->children as $term_3child): */
                        ?>
                    <option value="<?php /*echo esc_attr($term_3child->term_id); */
                        ?>" <?php /*if(nt_get_request('s-location') == $term_3child->term_id) echo 'selected="selected"'; */
                        ?>>-- <?php /*echo $term_3child->name; */
                        ?></option>
                <?php /*endforeach; */
                        ?>
            --><?php /*endforeach; */
                        ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="columns large-3 medium-3 search-location">
                <label><?php _e('Property Location', 'theme_front'); ?></label>
                <select class="select2" name="s-location" tabindex="2">
                    <option value=""><?php _e('Any', 'theme_front'); ?></option>
                    <?php
/*                    $terms = get_terms('location', array('orderby' => 'name', 'hide_empty' => 1));
                    $terms_sorted = array();
                    nt_sort_terms_hierarchicaly($terms, $terms_sorted);
                    foreach ($terms_sorted as $term):
                        */?><!--
                        <option value="<?php /*echo esc_attr($term->term_id); */?>" <?php /*if (nt_get_request('s-location') == $term->term_id) echo 'selected="selected"'; */?>><?php /*echo $term->name; */?></option>
                        <?php /*foreach ($term->children as $term_child): */?>
                        <option value="<?php /*echo esc_attr($term_child->term_id); */?>" <?php /*if (nt_get_request('s-location') == $term_child->term_id) echo 'selected="selected"'; */?>>
                            - <?php /*echo $term_child->name; */?></option>
                        <?php /*foreach ($term_child->children as $term_3child): */?>
                            <option value="<?php /*echo esc_attr($term_3child->term_id); */?>" <?php /*if (nt_get_request('s-location') == $term_3child->term_id) echo 'selected="selected"'; */?>>
                                -- <?php /*echo $term_3child->name; */?></option>
                        <?php /*endforeach; */?>
                    <?php /*endforeach; */?>
                    --><?php /*endforeach; */?>
                </select>
            </div>
            <div class="columns large-3 medium-4 small-6 search-status">
                <label><?php _e('Property Status', 'theme_front'); ?></label>
                <select class="select2" name="s-status" tabindex="3">
                    <option value=""><?php _e('Any', 'theme_front'); ?></option>
                    <?php
                    $terms = get_terms('status', array('orderby' => 'name', 'hide_empty' => 1));
                    $terms_sorted = array();
                    nt_sort_terms_hierarchicaly($terms, $terms_sorted);
                    foreach ($terms_sorted as $term):
                        ?>
                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php if (nt_get_request('s-status') == $term->term_id) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
                        <?php foreach ($term->children as $term_child): ?>
                        <option value="<?php echo esc_attr($term_child->term_id); ?>" <?php if (nt_get_request('s-status') == $term_child->term_id) echo 'selected="selected"'; ?>>
                            - <?php echo $term_child->name; ?></option>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="columns large-3 medium-4 small-6 search-type">
                <label><?php _e('Property Type', 'theme_front'); ?></label>
                <select class="select2" name="s-type" tabindex="4">
                    <option value=""><?php _e('Any', 'theme_front'); ?></option>
                    <?php
                    $terms = get_terms('type', array('orderby' => 'name', 'hide_empty' => 1));
                    $terms_sorted = array();
                    nt_sort_terms_hierarchicaly($terms, $terms_sorted);
                    foreach ($terms_sorted as $term):
                        ?>
                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php if (nt_get_request('s-type') == $term->term_id) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
                        <?php foreach ($term->children as $term_child): ?>
                        <option value="<?php echo esc_attr($term_child->term_id); ?>" <?php if (nt_get_request('s-type') == $term_child->term_id) echo 'selected="selected"'; ?>>
                            - <?php echo $term_child->name; ?></option>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($search_layout != 'compact' && !$widget): ?>
                <div class="vspace show-for-large-up"></div>
            <?php endif; ?>

            <?php if ($search_layout != 'compact' && !$widget): ?>
                <div class="columns large-2 medium-4 small-6 search-bed">
                    <label><?php _e('Min Beds', 'theme_child'); ?></label>
                    <select class="select2" name="min-bed" data-minimum-results-for-search="Infinity" tabindex="5">
                        <option value=""><?php _e('Any', 'theme_front'); ?></option>
                        <?php
                        $meta = $meta_beds;
                        $max = floor(max($meta));
                        for ($i = 1; $i <= $max; $i++):
                            ?>
                            <option value="<?php echo esc_attr($i); ?>" <?php if (nt_get_request('min-bed') == $i) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ($search_layout != 'compact' && !$widget): ?>
                <div class="columns large-2 medium-4 small-6 search-bath">
                    <label><?php _e('Baths', 'theme_child'); ?></label>
                    <select class="select2" name="min-bath" data-minimum-results-for-search="Infinity" tabindex="6">
                        <option value=""><?php _e('Any', 'theme_front'); ?></option>
                        <?php
                        $meta = $meta_bathroom;
                        $max = floor(max($meta));
                        for ($i = 1; $i <= $max; $i++):
                            ?>
                            <option value="<?php echo esc_attr($i); ?>" <?php if (nt_get_request('min-bath') == $i) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ($search_layout != 'compact' && $meta_price): ?>
                <div class="columns large-3 medium-6 search-price">
                    <?php
                    $meta = $meta_price;

                    // Remove non numeric value
                    foreach ($meta as $key => $m) {
                        if (!is_numeric($m)) unset($meta[$key]);
                    }

                    $max = ceil(max($meta));
                    $min = 0;
                    $exp = strlen($max - $min) - 2;
                    if ($exp < 0) $exp = 0;
                    $step = pow(10, $exp);
                    $step = 10;
                    $cur_min = nt_get_request('l-price');
                    $cur_max = nt_get_request('u-price');
                    if (!$cur_min) $cur_min = $min;
                    if (!$cur_max) $cur_max = $max;
                    ?>
                    <label><?php _e('Price', 'theme_front'); ?>
                        <small class="right"><span class="lower"><?php echo nt_currency($cur_min); ?></span> - <span
                                    class="upper"><?php echo nt_currency($cur_max); ?></span></small>
                    </label>
                    <div class="rangeSlider" data-min="<?php echo esc_attr($min); ?>" data-max="<?php echo esc_attr($max); ?>"
                         data-cmin="<?php echo esc_attr($cur_min); ?>" data-cmax="<?php echo esc_attr($cur_max); ?>"
                         data-step="<?php echo esc_attr($step); ?>" data-margin="<?php echo esc_attr($step); ?>"
                         data-decimal-point="<?php echo nt_get_option('property', 'decimal_point', '.'); ?>"
                         data-thousands-sep="<?php echo nt_get_option('property', 'thousands_sep', ','); ?>"
                         data-direction="<?php echo nt_text_direction(); ?>"></div>
                    <input type="hidden" name="l-price" class="lower"/>
                    <input type="hidden" name="u-price" class="upper"/>
                </div>
            <?php endif; ?>

            <?php if ($search_layout != 'compact' && $meta_area): ?>
                <div class="columns large-3 medium-6 search-area">
                    <?php
                    $meta = $meta_area;

                    // Remove non numeric value
                    foreach ($meta as $key => $m) {
                        if (!is_numeric($m)) unset($meta[$key]);
                    }

                    $max = ceil(max($meta));
                    $min = 0;
                    $exp = strlen($max - $min) - 2;
                    if ($exp < 0) $exp = 0;
                    $step = pow(10, $exp);
                    $step = 10;
                    $cur_min = nt_get_request('l-area');
                    $cur_max = nt_get_request('u-area');
                    if (!$cur_min) $cur_min = $min;
                    if (!$cur_max) $cur_max = $max;
                    ?>
                    <label><?php _e('Area', 'theme_front'); ?>
                        <small class="right"><span class="lower"><span></span> <?php echo nt_get_option('property', 'area'); ?></span> -
                            <span class="upper"><span></span> <?php echo nt_get_option('property', 'area'); ?></span></small>
                    </label>
                    <div class="rangeSlider" data-min="<?php echo esc_attr($min); ?>" data-max="<?php echo esc_attr($max); ?>"
                         data-cmin="<?php echo esc_attr($cur_min); ?>" data-cmax="<?php echo esc_attr($cur_max); ?>"
                         data-step="<?php echo esc_attr($step); ?>" data-margin="<?php echo esc_attr($step); ?>"
                         data-decimal-point="<?php echo nt_get_option('property', 'decimal_point', '.'); ?>"
                         data-thousands-sep="<?php echo nt_get_option('property', 'thousands_sep', ','); ?>"
                         data-direction="<?php echo nt_text_direction(); ?>"></div>
                    <input type="hidden" name="l-area" class="lower"/>
                    <input type="hidden" name="u-area" class="upper"/>
                </div>
            <?php endif; ?>

            <div class="columns large-2 search-submit">
                <label class="hidden"><?php _e('SEARCH', 'theme_front'); ?></label>
                <input type="submit" value="<?php _e('SEARCH', 'theme_front'); ?>" class="lt-button primary"/>
            </div>
        </div>
    </form>

<?php } ?>
