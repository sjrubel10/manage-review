<?php
function get_woocommerce_reviews($search_product_title = '', $search_rating = '') {
    // Define the arguments for getting comments
    $args = [
        'post_type'   => 'product',
        'status'      => 'approve',
        'type'        => 'review',
        'number'      => 0, // Get all reviews
    ];

    // Get the comments
    $comments = get_comments($args);
    $reviews_data = [];

    // Loop through the comments and collect necessary information
    foreach ($comments as $comment) {
        $product_id = (int) $comment->comment_post_ID;
        $product = wc_get_product($product_id);

        if ($product) {
            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
            $product_title = $product->get_name();
            $reviewer_name = $comment->comment_author;
            $review_content = $comment->comment_content;

            // Apply search filters
            if ($search_product_title && stripos($product_title, $search_product_title) === false) {
                continue;
            }
            if ($search_rating && (int)$rating != (int)$search_rating) {
                continue;
            }

            $reviews_data[] = [
                'review_id'      => (int) $comment->comment_ID,
                'productTitle'  => sanitize_text_field($product->get_name()),
                'username'  => sanitize_text_field($comment->comment_author),
                'comment' => sanitize_textarea_field($comment->comment_content),
                'rating'         => (int) $rating,
                'review_date'    => esc_html($comment->comment_date),
            ];
        }
    }

    return $reviews_data;
}

// Fetch the reviews, example with search filters
//$search_product_title = isset($_GET['search_product_title']) ? sanitize_text_field($_GET['search_product_title']) : '';
$search_product_title = isset($_GET['search_product_title']) ? sanitize_text_field($_GET['search_product_title']) : '';
//$search_rating = isset($_GET['search_rating']) ? intval($_GET['search_rating']) : '';
$search_rating = 5;

$reviews = get_woocommerce_reviews( $search_product_title, $search_rating );

function get_woocommerce_reviews_old() {
    // Define the arguments for getting comments
    $args = [
        'post_type'   => 'product',
        'status'      => 'approve',
        'type'        => 'review',
        'number'      => 0, // Get all reviews
    ];

    // Get the comments
    $comments = get_comments($args);
    $reviews_data = [];

    // Loop through the comments and collect necessary information
    foreach ($comments as $comment) {
        $product_id = (int) $comment->comment_post_ID;
        $product = wc_get_product($product_id);

        if ($product) {
            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
            $reviews_data[] = [
                'review_id'      => (int) $comment->comment_ID,
                'productTitle'  => sanitize_text_field($product->get_name()),
                'username'  => sanitize_text_field($comment->comment_author),
                'comment' => sanitize_textarea_field($comment->comment_content),
                'rating'         => (int) $rating,
                'review_date'    => esc_html($comment->comment_date),
            ];
        }
    }

    return $reviews_data;
}

// Fetch the reviews
error_log( print_r( ['$reviews_data'=>$reviews], true ) );
//var_dump( $reviews );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage WooCommerce Reviews</title>
<!--    <link rel="stylesheet" href="css/manage-review.css">-->
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .manageReviewContainer {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 200px;
            padding: 10px;
            margin-right: 10px;
        }

        .search-bar button {
            padding: 10px 20px;
        }

        .reviews-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .review-block {
            max-width: 300px;
            width: 100%;
            padding: 20px;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .review-block .username {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .review-block .comment {
            margin-bottom: 10px;
        }

        .review-block .rating {
            font-size: 14px;
        }

        .review-block .product-title {
            color: #0073aa;
            cursor: pointer;
            text-decoration: underline;
        }


    </style>
</head>
<body>
<div class="manageReviewContainer">
    <h1>Manage WooCommerce Reviews</h1>
    <div class="search-bar">
        <input type="number" id="search-rating" placeholder="Search by Rating (1-5)">
        <button id="search-btn">Search</button>
    </div>
    <div class="reviews-container" id="reviews-container">
        <!-- Review blocks will be inserted here -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--<script src="scripts.js"></script>-->
</body>
</html>

<script>
    // scripts.js
    $(document).ready(function() {
        let reviews = <?php echo wp_json_encode($reviews); ?>;

        // Function to render reviews
        function renderReviews(reviewsToRender) {
            const reviewsContainer = $('#reviews-container');
            reviewsContainer.empty();
            reviewsToRender.forEach(review => {
                const reviewBlock = $(`
                <div class="review-block">
                    <div class="product-title">${review.productTitle}</div>
                    <div class="username">${review.username}</div>
                    <div class="comment">${review.comment}</div>
                    <div class="rating">Rating: ${review.rating}</div>
                </div>
            `);
                reviewBlock.on('click', function() {
                    $(this).attr('contenteditable', 'true').focus();
                });
                reviewsContainer.append(reviewBlock);
            });
        }

        // Initial render of all reviews
        renderReviews(reviews);

        // Search functionality
        $('#search-btn').on('click', function() {
            const rating = $('#search-rating').val();
            const filteredReviews = reviews.filter(review => review.rating == rating);
            renderReviews(filteredReviews);
        });
    });

</script>
