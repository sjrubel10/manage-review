<?php

namespace Manage\Review\Admin;

/**
 * The Menu handler class
 */
class Menu {

    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page(
            __( 'Manage Review', 'manage-review' ), // Menu title
            __( 'Review Manage', 'manage-review' ), // Menu label
            'manage_options', // Capability required to access the menu
            'manage-review', // Menu slug
            [ $this, 'plugin_page' ], // Callback function to render the page
            'dashicons-star-filled' // Icon
        );
    }



    /**
     * Render the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        require_once plugin_dir_path( __FILE__ ) . 'templates/createmailsettings.php';
    }
}
