<?php

/**
 *
 */
class QueryLoopRelated
{
    /**
     * taxonomy related to post
     */
    public static $taxonomyRelated = '';


    public function __construct()
    {

        add_action('enqueue_block_editor_assets', [$this, 'enqueue_script']);

        $this->filter_pre_render_block();
    }



    private function get_asset()
    {
        // file exist
        if (!file_exists(plugin_dir_path(__DIR__) . '/build/index.asset.php')) {
            return false;
        }
        $infos = require_once(plugin_dir_path(__DIR__) . '/build/index.asset.php');

        return $infos;
    }


    /**
     * add variation to loop block
     */
    function enqueue_script()
    {

        $infos = $this->get_asset();

        if (!$infos) {
            return;
        }

        wp_enqueue_script(
            'wp-performance-related',
            plugin_dir_url(__DIR__) . 'build/index.js',
            $infos['dependencies'],
            $infos['version']
        );
    }




    /**
     * update query for related posts
     */
    function modify_loop_query($query)
    {
        global $post;

        if ($post) {
            // remove current post from results
            array_push($query['post__not_in'], $post->ID);

            $cats = [];
            if (
                self::$taxonomyRelated && self::$taxonomyRelated !== ''
            ) {
                $terms = get_the_terms($post->ID, self::$taxonomyRelated);
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

        // remove filter for avoid conflict with other loop block
        remove_filter(
            'query_loop_block_query_vars',
            [$this, 'modify_loop_query']
        );
        self::$taxonomyRelated = '';

        return $query;
    }



    function filter_pre_render_block()
    {
        /**
         * filter block to add query vars
         */
        add_filter(
            'pre_render_block',
            function ($prerender, $block) {
                // if good namespace
                if ($block['attrs'] && array_key_exists('namespace', $block['attrs']) && 'wp-performance/related' === $block['attrs']['namespace']) {
                    // store taxonomy from editor select for use in query
                    self::$taxonomyRelated = array_key_exists('taxonomyRelated', $block['attrs']) ? $block['attrs']['taxonomyRelated'] : '';

                    add_filter(
                        'query_loop_block_query_vars',
                        [$this, 'modify_loop_query']
                    );
                }
            },
            1,
            2
        );
    }
}
