<?php
/**
 * Plugin Name: Review Master
 * Description: Product Announcer sends automatic email notifications for new product creations on your WordPress site. Customize emails to match your brand and enhance user engagement. Integrated with the WordPress dashboard, it ensures users stay informed without missing updates.
 * Plugin URI: https://manage-review.co
 * Author: Rubel
 * Author URI: https://rubelmia.co
 * Text Domain: review-master
 * Version: 1.0.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

use Manage\Review\Classes\SendMailAfterOrderDone;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// ReviewMaster
require_once __DIR__ . '/vendor/autoload.php';
new Manage\Review\Api();

/**
 * The main plugin class
 */
final class Manage_Review {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Manage_Review
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'MR_ManageReview_VERSION', self::version );
        define( 'MR_ManageReview_FILE', __FILE__ );
        define( 'MR_ManageReview_PATH', __DIR__ );
        define( 'MR_ManageReview_URL', plugins_url( '', MR_ManageReview_FILE ) );
        define( 'MR_ManageReview_ASSETS', MR_ManageReview_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
//        new SendMailAfterOrderDone();
        if ( is_admin() ) {
            new Manage\Review\Admin();
        } else {
            new Manage\Review\Frontend();
        }

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'manage-review_installed' );

        if ( ! $installed ) {
            update_option( 'manage-review_installed', time() );
        }

        update_option( 'manage-review_version', MR_ManageReview_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Manage_Review
 */
function manage_review() {
    return Manage_Review::init();
}

// kick-off the plugin
manage_review();
