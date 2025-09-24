<?php
if (!defined('ABSPATH')) exit;

/** Add meta box */
function tm_add_tour_meta_boxes() {
    add_meta_box('tm_tour_details', __('Tour Details', 'tour-manager'), 'tm_render_tour_meta_box', 'tour_package', 'normal', 'high');
}
add_action('add_meta_boxes', 'tm_add_tour_meta_boxes');

/** Meta box UI */
function tm_render_tour_meta_box($post) {
    wp_nonce_field('tm_save_tour_meta', 'tm_tour_meta_nonce');

    $price      = get_post_meta($post->ID, '_tp_price', true);
    $currency   = get_post_meta($post->ID, '_tp_currency', true);
    $duration   = get_post_meta($post->ID, '_tp_duration', true);
    $location   = get_post_meta($post->ID, '_tp_location', true);
    $start_date = get_post_meta($post->ID, '_tp_start_date', true);
    $end_date   = get_post_meta($post->ID, '_tp_end_date', true);
    $features   = get_post_meta($post->ID, '_tp_features', true);
    $image_id   = get_post_meta($post->ID, '_tp_tour_image_id', true);

    $currencies = [
        'USD' => 'USD ($)',
        'EUR' => 'EUR (€)',
        'GBP' => 'GBP (£)',
        'NGN' => 'NGN (₦)',
        'JPY' => 'JPY (¥)',
    ];
    ?>
    <table class="form-table">
        <tr><th><label for="tm_price"><?php _e('Price', 'tour-manager'); ?></label></th>
            <td><input type="text" id="tm_price" name="tp_price" value="<?php echo esc_attr($price); ?>" class="regular-text"></td></tr>

        <tr><th><label for="tm_currency"><?php _e('Currency', 'tour-manager'); ?></label></th>
            <td>
                <select id="tm_currency" name="tp_currency">
                    <?php foreach ($currencies as $code => $label): ?>
                        <option value="<?php echo esc_attr($code); ?>" <?php selected($currency, $code); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td></tr>

        <tr><th><label for="tm_duration"><?php _e('Duration', 'tour-manager'); ?></label></th>
            <td><input type="text" id="tm_duration" name="tp_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" placeholder="e.g. 5 days / 4 nights"></td></tr>

        <tr><th><label for="tm_location"><?php _e('Location', 'tour-manager'); ?></label></th>
            <td><input type="text" id="tm_location" name="tp_location" value="<?php echo esc_attr($location); ?>" class="regular-text" placeholder="e.g. Zanzibar, Tanzania"></td></tr>

        <tr><th><label for="tm_start"><?php _e('Start Date', 'tour-manager'); ?></label></th>
            <td><input type="date" id="tm_start" name="tp_start_date" value="<?php echo esc_attr($start_date); ?>"></td></tr>

        <tr><th><label for="tm_end"><?php _e('End Date', 'tour-manager'); ?></label></th>
            <td><input type="date" id="tm_end" name="tp_end_date" value="<?php echo esc_attr($end_date); ?>"></td></tr>

        <tr><th><label for="tm_features"><?php _e('Features (one per line or comma-separated)', 'tour-manager'); ?></label></th>
            <td><textarea id="tm_features" name="tp_features" rows="4" class="large-text"><?php echo esc_textarea($features); ?></textarea></td></tr>

        <tr><th><label for="tm_tour_image_id"><?php _e('Tour Image ID', 'tour-manager'); ?></label></th>
            <td><input type="number" id="tm_tour_image_id" name="tp_tour_image_id" value="<?php echo esc_attr($image_id); ?>" class="small-text" placeholder="<?php esc_attr_e('Attachment ID', 'tour-manager'); ?>"></td></tr>
    </table>
    <?php
}

/** Save */
function tm_save_tour_meta($post_id) {
    if (!isset($_POST['tm_tour_meta_nonce']) || !wp_verify_nonce($_POST['tm_tour_meta_nonce'], 'tm_save_tour_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $map = [
        'tp_price'       => ['_tp_price', 'sanitize_text_field'],
        'tp_currency'    => ['_tp_currency', 'sanitize_text_field'],
        'tp_duration'    => ['_tp_duration', 'sanitize_text_field'],
        'tp_location'    => ['_tp_location', 'sanitize_text_field'],
        'tp_start_date'  => ['_tp_start_date', 'sanitize_text_field'],
        'tp_end_date'    => ['_tp_end_date', 'sanitize_text_field'],
        'tp_features'    => ['_tp_features', 'sanitize_textarea_field'],
        'tp_tour_image_id' => ['_tp_tour_image_id', 'absint'],
    ];
    foreach ($map as $post_key => [$meta_key, $cb]) {
        if (array_key_exists($post_key, $_POST)) {
            $val = call_user_func($cb, $_POST[$post_key]);
            update_post_meta($post_id, $meta_key, $val);
        }
    }
}
add_action('save_post', 'tm_save_tour_meta');
