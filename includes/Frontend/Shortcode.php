<?php

namespace Manage\Review\Frontend;

/**
 * Shortcode handler class
 */
class Shortcode {

    /**
     * Initializes the class
     */
    function __construct() {
        add_shortcode( 'review_manage', [ $this, 'render_shortcode_reviews' ] );
    }

    /**
     * Shortcode handler class
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_shortcode_reviews( $atts, $content = '' ) {
        return 'Hello from Shortcode';
    }
}
