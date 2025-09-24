<?php
if (!defined('ABSPATH')) exit;

function tm_symbol($code) {
    $symbols = ['USD'=>'$','EUR'=>'€','GBP'=>'£','NGN'=>'₦','JPY'=>'¥'];
    return $symbols[$code] ?? $code;
}

/** [tour_packages cats="beach,safari" limit="-1"] */
function tm_tour_packages_shortcode($atts = []) {
    $atts = shortcode_atts([
        'cats'  => '',   // comma-separated term slugs
        'limit' => -1,
    ], $atts, 'tour_packages');

    $tax_q = [];
    if ($atts['cats']) {
        $slugs = array_filter(array_map('trim', explode(',', $atts['cats'])));
        if ($slugs) {
            $tax_q[] = [
                'taxonomy' => 'tour_category',
                'field'    => 'slug',
                'terms'    => $slugs,
            ];
        }
    }

    $q = new WP_Query([
        'post_type'      => 'tour_package',
        'posts_per_page' => intval($atts['limit']),
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
        'tax_query'      => $tax_q,
    ]);

    if (!$q->have_posts()) return '<p class="tm-empty">'.esc_html__('No tour packages found.', 'tour-manager').'</p>';

    ob_start();
    echo '<div class="tm-container"><div class="tm-grid">';
    while ($q->have_posts()) {
        $q->the_post();
        $id        = get_the_ID();
        $price     = get_post_meta($id, '_tp_price', true);
        $currency  = get_post_meta($id, '_tp_currency', true);
        $duration  = get_post_meta($id, '_tp_duration', true);
        $location  = get_post_meta($id, '_tp_location', true);
        $features  = get_post_meta($id, '_tp_features', true);
        $image_id  = get_post_meta($id, '_tp_tour_image_id', true);
        $thumb     = $image_id ? wp_get_attachment_image($image_id, 'medium_large', false, ['class'=>'tm-thumb']) : '';

        // normalize features to list
        $items = [];
        if (!empty($features)) {
            if (strpos($features, "\n") !== false) $items = array_map('trim', preg_split('/\r\n|\r|\n/', $features));
            else $items = array_map('trim', explode(',', $features));
            $items = array_filter($items);
        }

        echo '<article class="tm-card">';
            if ($thumb) echo '<a href="'.esc_url(get_permalink()).'">'.$thumb.'</a>';
            echo '<h3 class="tm-title"><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';

            echo '<ul class="tm-meta">';
                if ($location)  echo '<li><strong>'.esc_html__('Location:', 'tour-manager').'</strong> '.esc_html($location).'</li>';
                if ($duration)  echo '<li><strong>'.esc_html__('Duration:', 'tour-manager').'</strong> '.esc_html($duration).'</li>';
                if ($price)     echo '<li><strong>'.esc_html__('Price:', 'tour-manager').'</strong> '.esc_html(tm_symbol($currency)).esc_html($price).'</li>';
            echo '</ul>';

            if ($items) {
                echo '<ul class="tm-features">';
                    foreach ($items as $f) echo '<li>'.esc_html($f).'</li>';
                echo '</ul>';
            }
        echo '</article>';
    }
    echo '</div></div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('tour_packages', 'tm_tour_packages_shortcode');
