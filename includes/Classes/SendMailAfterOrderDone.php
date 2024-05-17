<?php

namespace Manage\Review\Classes;

class SendMailAfterOrderDone{

    public function __construct(){
//        error_log(print_r('Entry', true));
        add_action('woocommerce_payment_complete', array( $this, 'custom_action_on_order_complete' ), 10, 1);
    }

    public function custom_action_on_order_complete( $order_id ) {
        // Get the order object
//        $order = wc_get_order( $order_id );

        error_log( print_r( [ '$order' => '$order' ], true ) );

        // Perform your custom actions here
        // Example: Sending a custom email notification
        $to = 'customer@example.com';
        $subject = 'Your Order is Completed';
        $body = 'Thank you for your purchase. Your order has been completed.';
        $headers = array('Content-Type: text/html; charset=UTF-8');

//        wp_mail($to, $subject, $body, $headers);

        // Example: Logging order information
//        $log = "Order #" . $order_id . " completed on " . date('Y-m-d H:i:s') . "\n";
//        file_put_contents(__DIR__ . '/order-log.txt', $log, FILE_APPEND);

        // Example: Updating a custom field or post meta
//        update_post_meta($order_id, '_custom_order_completed', 'yes');
    }

}