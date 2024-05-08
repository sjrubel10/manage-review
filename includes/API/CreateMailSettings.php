<?php

namespace Manage\Review\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class CreateMailSettings extends WP_REST_Controller
{

    private $product_reviews;
    function __construct()
    {
        $this->namespace = 'createReviews/v1';
//        $this->rest_base = 'mail-settings';

        $this->product_reviews = array(
            "I've had this product for months now, and it's still as good as new.",
            "This product is incredibly easy to use, even for someone like me who isn't tech-savvy.",
            "The sleek design of this product adds a touch of elegance to my workspace.",
            "I'm amazed by the performance of this product. It handles everything I throw at it effortlessly.",
            "I purchased this product as a gift for a friend, and they couldn't be happier with it.",
            "The battery life on this product is impressive. I can go days without needing to recharge.",
            "I've owned many products from this brand, and this one lives up to their reputation for quality.",
            "This product is so compact and lightweight, making it perfect for travel.",
            "I'm pleasantly surprised by the number of features packed into this small device.",
            "The user interface of this product is intuitive and user-friendly.",
            "I've tried cheaper alternatives, but they don't compare to the reliability of this product.",
            "The build quality of this product is excellent. It feels sturdy and well-made.",
            "This product arrived quickly and was well-packaged to prevent any damage.",
            "I've been recommending this product to all my friends and family. It's that good!",
            "The price may seem high, but the quality of this product justifies every penny.",
            "I've been using this product daily for months now, and it's still going strong.",
            "The customer support for this product is exceptional. They go above and beyond to help.",
            "I'm impressed by how versatile this product is. It can handle a wide range of tasks with ease.",
            "This product has made a noticeable improvement in my daily routine. I couldn't be happier with it.",
            "I love the attention to detail that went into designing this product. It's clear that a lot of thought was put into it.",
            "I've had issues with similar products in the past, but this one performs flawlessly.",
            "The included instructions were clear and easy to follow, making setup a breeze.",
            "I was hesitant to purchase this product at first, but now I wish I had bought it sooner.",
            "This product is perfect for anyone looking to upgrade their current setup.",
            "I've received numerous compliments on this product since I started using it.",
            "I've been using this product for weeks now, and it's still exceeding my expectations.",
            "The build quality of this product is top-notch. It feels like it will last for years to come.",
            "I'm impressed by the range of colors available for this product. There's something for everyone.",
            "I purchased this product on a whim, and it turned out to be one of the best decisions I've made.",
            "This product has become an essential part of my daily routine. I don't know how I lived without it."
        );
    }

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/create_multiple_review',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'create_send_mail_settings'],
                    'permission_callback' => [$this, 'get_item_permissions_check'],
                    'args' => [$this->get_collection_params()],
                ],
            ]
        );

    }

    public function create_send_mail_settings( $request ){
        $nonce = $request->get_header('X-WP-Nonce');
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_Error( 'invalid_nonce', 'Invalid nonce.', array( 'status' => 403 ) );
        }
        // Get the form data from the request body
        $form_data = $request->get_json_params();

        unset($form_data['nonce']);

        $category_slug = 'tshirts';
        $product_ids = wc_get_products( array(
            'return' => 'ids', // Return only product IDs
            'status' => 'publish',
            'limit' => -1,
            'category'  => array( $category_slug ),
        ) );
        error_log( print_r( ['$product_ids'=>$product_ids], true ) );
    }

    public function insert_review_into_comments_table($post_id, $author_name, $author_email, $author_url, $author_ip, $comment_content) {
        // Sanitize input data
        $post_id = intval($post_id);
        $author_name = sanitize_text_field($author_name);
        $author_email = sanitize_email($author_email);
        $author_url = esc_url_raw($author_url);
        $author_ip = sanitize_text_field($author_ip);
        $comment_content = wp_kses_post($comment_content);

        // Prepare data for insertion
        $data = array(
            'comment_post_ID'      => $post_id,
            'comment_author'       => $author_name,
            'comment_author_email' => $author_email,
            'comment_author_url'   => $author_url,
            'comment_author_IP'    => $author_ip,
            'comment_content'      => $comment_content,
            'comment_date'         => current_time('mysql'),
            'comment_date_gmt'     => current_time('mysql', 1),
            'comment_approved'     => 1, // Automatically approve comments
            'comment_type'         => '', // Empty string for regular comments
        );

        // Insert data into the wp_comments table
        $inserted = wp_insert_comment($data);

        return $inserted;
    }

    public function get_item_permissions_check( $request ){
        if( current_user_can( 'manage_options' ) ){
            return true;
        }
        return false;
    }
}