<?php

namespace Manage\Review\Classes;

class SendMailAfterOrderDone{

    private $review_request;
    public function __construct(){
        $this->review_request = new ReviewRequest();
        add_action('woocommerce_order_status_changed', array( $this, 'custom_action_on_order_complete' ), 10, 4 );
    }

    public function custom_action_on_order_complete( $order_id, $from, $to, $get_data ) {

        $order = wc_get_order( $order_id );
        $ordered_products = [];
        foreach ( $order->get_items() as $item_id => $item ) {
            $product = $item->get_product();
            if ( $product ) {
                $ordered_products[] = array(
                    'title' => $product->get_name(),
                    'product_url' => get_permalink( $product->get_id() ),
                );
            }
        }
        $billing_email = $order->get_billing_email();
        $order_total = $order->get_total();
        $order_date = $order->get_date_created();

        $ordered_info = array(
            'ordered_products' => $ordered_products,
            'billing_email' => $billing_email,
            'ordered_name' => $order->get_billing_first_name().' '.$order->get_billing_last_name(),
            'order_date' => $order_date,
            'order_total' => $order_total,
            'order_id' => $order_id,
        );

        $is_done = $this->review_request->make_review_request_after_order_completed( $ordered_info );
//        error_log( print_r( [ '$ordered_info' => $ordered_info ], true ) );
        return $is_done;
    }

}