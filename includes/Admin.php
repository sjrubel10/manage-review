<?php

namespace Manage\Review;

use Manage\Review\Admin\Menu;
//use Manage\Review\Admin\SendMail;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
        new Menu();
    }
}
