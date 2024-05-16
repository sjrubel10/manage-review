<?php

namespace Manage\Review\API;

use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class MakeSettingsChnage extends WP_REST_Controller {

    public function __construct(){

        $this->namespace = 'createReviews/v1';
        $this->rest_base = 'settings';
    }

    public function register_routes(){

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base .
            '/comment_add_remove',
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array( $this, 'add_remove_comment_text' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args' => array( $this->get_collection_params() ),
            )
        );
    }

    public function add_remove_comment_text( $request ){
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }

        // Retrieve form data
        $form_data = $request->get_json_params();
        unset( $form_data['nonce'] );

        $is_update = false;
        $all_comments = $form_data['comments'];
        if( ! empty( $all_comments ) ) {
            if ( is_array( $all_comments ) ) {
                $all_comments = array_map( 'sanitize_text_field', $all_comments );
            }else{
                $all_comments = sanitize_text_field( $all_comments );
            }
            $all_comments = maybe_serialize( $all_comments );
            $is_update = update_option( 'rmCommentText', $all_comments );
        }

        return new WP_REST_Response( $is_update, 200 );
    }
    public function get_item_permissions_check( $request )
    {
        if (current_user_can('manage_options')) {
            return true;
        }
        return false;
    }

}