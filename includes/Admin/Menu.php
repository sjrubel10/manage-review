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

        add_submenu_page(
            'manage-review',                       // Parent slug
            __( 'Settings', 'manage-review' ),    // Page title
            __( 'Settings', 'manage-review' ),    // Menu title
            'manage_options',                      // Capability
            'settings',              // Menu slug
            [ $this,'manage_review_submenu1_page'],       // Function to display submenu 1 page
        );

        // Add submenu 2
        /*add_submenu_page(
            'manage-review',                       // Parent slug
            __( 'Submenu 2', 'manage-review' ),    // Page title
            __( 'Submenu 2', 'manage-review' ),    // Menu title
            'manage_options',                      // Capability
            'manage-review-submenu2',              // Menu slug
            [ $this,'manage_review_submenu2_page']          // Function to display submenu 2 page
        );*/
    }



    /**
     * Render the plugin page
     *
     * @return void
     */
    public function plugin_page() {

        require_once plugin_dir_path( __FILE__ ) . 'templates/createmailsettings.php';
    }

    public function manage_review_submenu1_page(){
        require_once plugin_dir_path( __FILE__ ) . 'templates/settings.php';
    }
    public function manage_review_submenu2_page(){
        require_once plugin_dir_path( __FILE__ ) . 'templates/settings.php';
    }
}
