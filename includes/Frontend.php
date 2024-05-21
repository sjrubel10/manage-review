<?php

namespace Manage\Review;

use Manage\Review\Classes\SendMailAfterOrderDone;

/**
 * Frontend handler class
 */
class Frontend {

    /**
     * Initialize the class
     */
    function __construct() {
        new Frontend\Shortcode();
        new SendMailAfterOrderDone();
    }
}
