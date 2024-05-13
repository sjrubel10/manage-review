<?php

namespace Manage\Review\Classes;

/**
 * Class CreateReviews
 *
 * Handles creation and insertion of reviews into the database.
 *
 * @package Manage\Review\Classes
 */
class CreateReviews
{
    /**
     * User IP address.
     *
     * @var string
     */
    private $get_author_ip;

    /**
     * Current user object.
     *
     * @var \WP_User
     */
    private $current_user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->get_author_ip = HelperFunctions::get_client_ip();
        $this->current_user = wp_get_current_user();
    }

    /**
     * Generate reviews by product IDs.
     *
     * @param array        $product_ids           Array of product IDs.
     * @param int          $review_limit_per_product Number of reviews per product.
     * @param string       $date_start            Start date for reviews.
     * @param string       $date_end              End date for reviews.
     * @param array|string $comment_contents      Array of possible comment contents or a default string.
     * @param array|string $rating_ary            Array representing a range of ratings or a single rating.
     * @param CreateReviews $create_reviews_obj   Instance of CreateReviews class.
     *
     * @return string Insertion status.
     */
    public function generate_reviews_by_ids(
        $product_ids,
        $review_limit_per_product,
        $date_start,
        $date_end,
        $comment_contents,
        $rating_ary,
        $create_reviews_obj
    ) {
        $is_inserted = '';
        if (count($product_ids) > 0) {
            foreach ($product_ids as $post_id) {
                for ($i = 0; $i < $review_limit_per_product; $i++) {
                    if (is_array($comment_contents)) {
                        $comment_content_key = array_rand($comment_contents);
                        $comment_content = $comment_contents[$comment_content_key];
                    } else {
                        $comment_content = ' This is from fromt end';
                    }

                    $review_rating = is_array($rating_ary) ? wp_rand($rating_ary[0], $rating_ary[1]) : $rating_ary;
                    $is_inserted =  $create_reviews_obj->insert_review_into_comments_table($post_id, $date_start, $date_end, $review_rating, $comment_content);
                }
            }
        }
        return $is_inserted;
    }

    /**
     * Insert review into comments table.
     *
     * @param int    $post_id        Post ID.
     * @param string $date1          Start date for reviews.
     * @param string $date2          End date for reviews.
     * @param int    $review_rating  Rating of the review.
     * @param string $comment_content Content of the review.
     *
     * @return int|false Comment ID if inserted successfully, false on failure.
     */
    public function insert_review_into_comments_table(
        $post_id,
        $date1,
        $date2,
        $review_rating,
        $comment_content
    ) {
        // Sanitize input data
        $post_id = intval($post_id);
        $author_name = sanitize_text_field($this->current_user->user_login);
        $author_email = sanitize_email($this->current_user->user_email);
        $author_url = esc_url_raw($this->current_user->user_url);
        $author_ip = sanitize_text_field($this->get_author_ip);
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
        if ($inserted) {
            $meta_key = 'rating';
            $meta_added = add_comment_meta($inserted, $meta_key, $review_rating, false);
        }

        return $inserted;
    }
}
