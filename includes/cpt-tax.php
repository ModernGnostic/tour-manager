<?php
if (!defined('ABSPATH')) exit;

/** 1) Taxonomy: tour_category */
function tm_register_tour_categories() {
    $labels = [
        'name'              => __('Tour Categories', 'tour-manager'),
        'singular_name'     => __('Tour Category', 'tour-manager'),
        'search_items'      => __('Search Tour Categories', 'tour-manager'),
        'all_items'         => __('All Tour Categories', 'tour-manager'),
        'parent_item'       => __('Parent Tour Category', 'tour-manager'),
        'parent_item_colon' => __('Parent Tour Category:', 'tour-manager'),
        'edit_item'         => __('Edit Tour Category', 'tour-manager'),
        'update_item'       => __('Update Tour Category', 'tour-manager'),
        'add_new_item'      => __('Add New Tour Category', 'tour-manager'),
        'new_item_name'     => __('New Tour Category Name', 'tour-manager'),
        'menu_name'         => __('Tour Categories', 'tour-manager'),
    ];
    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tour-category'],
        'show_in_rest'      => true,
    ];
    register_taxonomy('tour_category', ['tour_package'], $args);
}
add_action('init', 'tm_register_tour_categories', 0);

/** 2) Post type: tour_package */
function tm_register_tour_packages() {
    $labels = [
        'name'               => __('Tour Packages', 'tour-manager'),
        'singular_name'      => __('Tour Package', 'tour-manager'),
        'menu_name'          => __('Tour Manager', 'tour-manager'),
        'add_new'            => __('Add New', 'tour-manager'),
        'add_new_item'       => __('Add New Tour Package', 'tour-manager'),
        'edit_item'          => __('Edit Tour Package', 'tour-manager'),
        'new_item'           => __('New Tour Package', 'tour-manager'),
        'view_item'          => __('View Tour Package', 'tour-manager'),
        'all_items'          => __('All Tour Packages', 'tour-manager'),
        'search_items'       => __('Search Tour Packages', 'tour-manager'),
        'not_found'          => __('No tour packages found', 'tour-manager'),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'tours'],
        'supports'           => ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'page-attributes'],
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-palmtree',
        'taxonomies'         => ['tour_category'],
        'hierarchical'       => true, // matches your need for template dropdown-like behavior
    ];
    register_post_type('tour_package', $args);
}
add_action('init', 'tm_register_tour_packages', 1);
