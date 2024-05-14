<?php

namespace Manage\Review\API;

use WP_Error;
use WP_Query;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;
class GetProductTitleBySearch extends WP_REST_Controller {
    public function __construct(){

        $this->namespace = 'createReviews/v1';
        $this->rest_base = 'search';
    }

    public function register_routes(){

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base .
            '/search_by_text',
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_product_title_by_search_test'),
                'permission_callback' => function () {
                    return current_user_can('read'); // Adjust the capability as needed
                },
                'args' => array($this->get_collection_params()),
            )
        );
    }

    public function get_product_title_by_search_test( $request ){
        $search_term = sanitize_text_field($request->get_param('search_term'));
        $nonce = $request->get_header('X-WP-Nonce');
        if ( !wp_verify_nonce( $nonce, 'wp_rest') ) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }
        // Retrieve form data

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            's' => $search_term,
            'fields' => 'ids',
            'posts_per_page' => 20,
        ];
        $query = new WP_Query($args);
        $titles = [];

        // Collect the product titles
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $titles[] = array(
                    'title' => get_the_title(),
                    'id' => get_the_ID(),
                );
            }
            wp_reset_postdata();
        }

        return new WP_REST_Response( $titles, 200 );
    }
}