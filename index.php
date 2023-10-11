<?php

/**
 * Plugin Name:       Related Loop Block
 * Description:       Add variation to loop block to display related posts
 * Update URI:        wp-performance-related
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           0.0.1
 * Author:            Faramaz Patrick <infos@goodmotion.fr>
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-performance-related
 *
 * @package           wp-performance
 */


/**
 * add variation to loop block
 */
function wp_perf_editor_assets()
{
    wp_enqueue_script(
        'wp-performance-related',
        plugin_dir_url(__FILE__) . 'assets/block-variations.js',
        array('wp-blocks'),
        filemtime(plugin_dir_path(__FILE__) . 'assets/block-variations.js')
    );
}

add_action('enqueue_block_editor_assets', 'wp_perf_editor_assets');


/**
 * update query for related posts
 */
function wp_perf_loop_query($query)
{
    global $post;

    if ($post) {
        // remove current post from results
        array_push($query['post__not_in'], $post->ID);
        // get taxonomies name for this post type
        $tax = get_post_taxonomies($post);
        $cats = [];
        foreach ($tax as $key => $value) {
            // get terms for the current post
            $terms = get_the_terms($post->ID, $value);
            if ($terms) {
                foreach ($terms as $key => $value) {
                    array_push($cats, $value);
                }
            }
        }
        $tax_query = null;
        if (count($cats)) {
            $tax_query = [];
            // add taxonomies related to query
            foreach ($cats as $key => $value) {
                array_push($tax_query, [
                    'taxonomy' => $value->taxonomy,
                    'field'    => 'slug',
                    'terms'    => $value->slug
                ]);
            }
        }
        if ($tax_query) {
            // add condition relation
            $tax_query['relation'] = 'OR';
            $query['tax_query'] = $tax_query;
        }
    }
    return $query;
}




/**
 * filter block to add query vars
 */
add_filter(
    'pre_render_block',
    function ($prerender, $block) {
        // if good namespace
        if ($block['attrs'] && array_key_exists('namespace', $block['attrs']) && 'wp-performance/related' === $block['attrs']['namespace']) {
            add_filter(
                'query_loop_block_query_vars',
                'wp_perf_loop_query'
            );
        }
    },
    1,
    2
);
