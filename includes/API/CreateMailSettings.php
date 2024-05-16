<?php
/**
 * CreateMailSettings class file.
 *
 * PHP version 7.0
 *
 * @category API
 * @package  Manage\Review\API
 */

namespace Manage\Review\API;

use Manage\Review\Classes\CreateReviews;
use Manage\Review\Classes\HelperFunctions;
use Manage\Review\Classes\MakeProductsbatch;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class CreateMailSettings.
 *
 * API endpoints for managing review settings.
 *
 * @package Manage\Review\API
 */
class CreateMailSettings extends WP_REST_Controller
{
    /**
     * Namespace for the REST API endpoint.
     *
     * @var string
     */
//    private $namespace;

    /**
     * Instance of MakeProductsbatch class.
     *
     * @var MakeProductsbatch
     */
    private $make_batch_product;

    /**
     * Instance of CreateReviews class.
     *
     * @var CreateReviews
     */
    private $create_reviews;

    /**
     * Comment contents.
     *
     * @var array
     */
    private $comment_contents;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->namespace = 'createReviews/v1';
        $this->make_batch_product = new MakeProductsbatch();
        $this->create_reviews = new CreateReviews();
        $this->comment_contents = HelperFunctions::comment_text();

    }

    /**
     * Register REST API routes.
     */
    public function register_routes(){
        register_rest_route(
            $this->namespace,
            '/create_multiple_review_by_batch',
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'generate_review_from_settings'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array($this->get_collection_params()),
            )
        );

        register_rest_route(
            $this->namespace,
            '/get_product_ids',
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'get_product_ids'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array($this->get_collection_params()),
            )
        );

        register_rest_route(
            $this->namespace,
            '/generate_single_review',
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'generate_single_review'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array($this->get_collection_params()),
            )
        );

    }

    /**
     * Retrieve product IDs based on request parameters.
     *
     * @param \WP_REST_Request $request The request object.
     *
     * @return \WP_REST_Response|\WP_Error Response object or WP_Error.
     */
    public function get_product_ids( $request ){
        // Verify nonce
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }

        // Retrieve form data
        $form_data = $request->get_json_params();
        unset($form_data['nonce']);
        $categories_slug = $form_data['categorySelector'];

        // Query products
        $args = array(
            'return' => 'ids', // Return only product IDs
            'status' => 'publish',
            'limit' => -1,
        );
        if (!in_array('all_Categories', $categories_slug)) {
            $args['category'] = $categories_slug;
        }
        $product_ids = wc_get_products($args);

        // Process and return product IDs
        $review_per_product = sanitize_text_field($form_data['numberOfReviewPerProduct']);
        $total_products = count($product_ids);
        $batch_product_ids = $this->make_batch_product->make_batch_product($product_ids, $review_per_product, $total_products);

        return new WP_REST_Response( $batch_product_ids, 200 );
    }
    /**
     * Generate reviews based on settings provided in the request.
     *
     * @param \WP_REST_Request $request The request object.
     *
     * @return \WP_REST_Response|\WP_Error Response object or WP_Error.
     */
    public function generate_single_review( $request ){
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }

        // Retrieve form data
        $form_data = $request->get_json_params();
        unset( $form_data['nonce'] );

        $product_ids = $form_data['productIds'];

        // Extract review settings
        $review_limit_per_product = sanitize_text_field( $form_data['numberOfSingleReviewPerProduct'] );
        $date_start = sanitize_text_field( $form_data['singleReviewStartDate'] );
        $review_rating = sanitize_text_field( $form_data['numberOfSingleReviewRating'] );
        $comment_context = sanitize_text_field( $form_data['commentContextForReview'] );

        // Generate reviews
        $is_inserted = $this->create_reviews->generate_reviews_by_ids( $product_ids, $review_limit_per_product, $date_start, $date_start, $comment_context, $review_rating, $this->create_reviews );

        return new WP_REST_Response( $is_inserted, 200 );
    }
    public function generate_review_from_settings( $request ){
        // Verify nonce
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('invalid_nonce', 'Invalid nonce.', array('status' => 403));
        }

        // Retrieve form data
        $form_data = $request->get_json_params();
        unset( $form_data['nonce'] );
        $product_ids = $form_data['productIds'];

        // Extract review settings
        $review_limit_per_product = sanitize_text_field( $form_data['numberOfReviewPerProduct'] );
        $date_start = sanitize_text_field( $form_data['reviewStartDate'] );
        $date_end = sanitize_text_field( $form_data['reviewEndDate'] );
        $review_rating = sanitize_text_field( $form_data['numberOfReviewRating'] );

        // Process review rating
        if ( preg_match('/\bto\b/', $review_rating) ) {
            $rating_ary = explode(' to ', $review_rating );
        } else {
            $rating_ary = is_numeric($review_rating) ? $review_rating : 5;
        }
        // Generate reviews
        $is_inserted = $this->create_reviews->generate_reviews_by_ids( $product_ids, $review_limit_per_product, $date_start, $date_end, $this->comment_contents, $rating_ary, $this->create_reviews );

        return new WP_REST_Response( $is_inserted, 200 );
    }

    /**
     * Check permissions for the request.
     *
     * @param \WP_REST_Request $request The request object.
     *
     * @return bool Whether the request has permission.
     */

    public function get_item_permissions_check($request){
        if (current_user_can('manage_options')) {
            return true;
        }
        return false;
    }
}
