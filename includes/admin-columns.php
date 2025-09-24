<?php
if (!defined('ABSPATH')) exit;

/** Add column */
function tm_add_tour_image_column($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        if ($key === 'title') $new['tour_image'] = __('Image', 'tour-manager');
        $new[$key] = $label;
    }
    return $new;
}
add_filter('manage_tour_package_posts_columns', 'tm_add_tour_image_column');

/** Render column */
function tm_render_tour_image_column($column, $post_id) {
    if ($column === 'tour_image') {
        $image_id = get_post_meta($post_id, '_tp_tour_image_id', true);
        if ($image_id) {
            $src = wp_get_attachment_image_src($image_id, 'thumbnail');
            if ($src) echo '<img src="' . esc_url($src[0]) . '" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">';
        } else {
            echo '<span style="color:#888;">' . esc_html__('No Image', 'tour-manager') . '</span>';
        }
    }
}
add_action('manage_tour_package_posts_custom_column', 'tm_render_tour_image_column', 10, 2);

/** Narrow column */
add_action('admin_head', function () {
    echo '<style>.column-tour_image{width:80px;text-align:center}</style>';
});
