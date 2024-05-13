<?php

namespace Manage\Review\Classes;
//use Manage\Review\Classes\HelperFunctions;

class CreateReviews
{
    private $product_reviews;
    private $get_author_ip;
    private $current_user;
    function __construct()
    {
        $this->get_author_ip = HelperFunctions::get_client_ip();
        $this->current_user = wp_get_current_user();
//        $this->product_reviews = HelperFunctions::comment_text();

    }

    public function insert_review_into_comments_table( $post_id, $date1, $date2, $review_rating, $comment_content ) {
        // Sanitize input data
        $post_id = intval($post_id);
        $author_name = sanitize_text_field( $this->current_user->user_login );
        $author_email = sanitize_email( $this->current_user->user_email );
        $author_url = esc_url_raw( $this->current_user->user_url );
        $author_ip = sanitize_text_field( $this->get_author_ip );
//        $comment_content_key = array_rand( $this->product_reviews );
//        $comment_content = $this->product_reviews[$comment_content_key];
        $get_review_date_time = HelperFunctions::get_random_review_date( $date1, $date2 );

        $data = array(
            'comment_post_ID'      => $post_id,
            'comment_author'       => $author_name,
            'comment_author_email' => $author_email,
            'comment_author_url'   => $author_url,
            'comment_author_IP'    => $author_ip,
            'comment_content'      => $comment_content,
            'comment_date'         => $get_review_date_time['comment_date'],
            'comment_date_gmt'     => $get_review_date_time['comment_date_gmt'],
            'comment_approved'     => 1, // Automatically approve comments
            'comment_agent'        => $_SERVER['HTTP_USER_AGENT'],
            'comment_type'         => 'review', // Empty string for regular comments
            'comment_parent'       => 0,
            'user_id'              => $this->current_user->ID,
        );

        // Insert data into the wp_comments table
        $inserted = wp_insert_comment($data);
        if( $inserted ){
            $meta_key = 'rating';
            $meta_added = add_comment_meta( $inserted, $meta_key, $review_rating, false );
        }

        return $inserted;
    }


}