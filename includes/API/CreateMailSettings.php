<?php

namespace Manage\Review\API;

use Manage\Review\Classes\CreateReviews;
use Manage\Review\Classes\HelperFunctions;
use Manage\Review\Classes\MakeProductsbatch;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class CreateMailSettings extends WP_REST_Controller
{

    private $create_reviews;
    private $make_batch_product;
    private $comment_contents;
    function __construct(){
        $this->namespace = 'createReviews/v1';
        $this->make_batch_product = new MakeProductsbatch();
        $this->create_reviews = new CreateReviews();

        $this->comment_contents = HelperFunctions::comment_text();
    }

    public function register_routes(){

        register_rest_route(
            $this->namespace,
            '/create_multiple_review_by_batch',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'generate_review_from_settings'],
                    'permission_callback' => [$this, 'get_item_permissions_check'],
                    'args' => [$this->get_collection_params()],
                ],
            ]
        );
        register_rest_route(
            $this->namespace,
            '/get_product_ids',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_product_ids'],
                    'permission_callback' => [$this, 'get_item_permissions_check'],
                    'args' => [$this->get_collection_params()],
                ],
            ]
        );

    }

    public function get_product_ids( $request ){
        $nonce = $request->get_header('X-WP-Nonce');
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_Error( 'invalid_nonce', 'Invalid nonce.', array( 'status' => 403 ) );
        }
        $form_data = $request->get_json_params();
        unset($form_data['nonce']);
        $categories_slug = $form_data['categorySelector'];
        $args = array(
            'return' => 'ids', // Return only product IDs
            'status' => 'publish',
            'limit' => -1,
        );
        if ( !in_array( 'all_Categories', $categories_slug ) ) {
            $args['category'] = $categories_slug;
        }
        $product_ids = wc_get_products( $args );
        $review_per_product  = sanitize_text_field( $form_data['numberOfReviewPerProduct'] );

        $total_products = count( $product_ids );
        $batch_product_ids = $this->make_batch_product->make_batch_product( $product_ids, $review_per_product, $total_products );
        return $batch_product_ids;
    }

    public function generate_review_from_settings( $request ){
        $nonce = $request->get_header('X-WP-Nonce');
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_Error( 'invalid_nonce', 'Invalid nonce.', array( 'status' => 403 ) );
        }
        $form_data = $request->get_json_params();
        unset($form_data['nonce']);
        $product_ids = $form_data['productIds'];

        $review_limit_per_product  = sanitize_text_field( $form_data['numberOfReviewPerProduct'] );
        $date_start = sanitize_text_field( $form_data['reviewStartDate'] );
        $date_end = sanitize_text_field( $form_data['reviewEndDate'] );
        $review_rating = sanitize_text_field( $form_data['numberOfReviewRating'] );

        if( preg_match('/\bto\b/', $review_rating ) ){
            $rating_ary = explode(' to ', $review_rating );
        }else{
            if( is_numeric( $review_rating ) ){
                $rating_ary = $review_rating;
            }else{
                $rating_ary = 5;
            }
        }

        $is_inserted = '';
        if( count( $product_ids ) > 0 ){
            foreach( $product_ids as $post_id ){
                for( $i =0; $i<$review_limit_per_product; $i++ ){

                    $comment_content_key = array_rand( $this->comment_contents );
                    $comment_content = $this->comment_contents[$comment_content_key];
                    if( is_array( $rating_ary ) ){
                        $review_rating = rand( $rating_ary[0], $rating_ary[1] );
                    }else{
                        $review_rating = $rating_ary;
                    }
                    $is_inserted =  $this->create_reviews->insert_review_into_comments_table( $post_id, $date_start, $date_end, $review_rating, $comment_content );
                }
            }
        }

        return $is_inserted;
    }


    public function get_item_permissions_check( $request ){
        if( current_user_can( 'manage_options' ) ){
            return true;
        }
        return false;
    }
}