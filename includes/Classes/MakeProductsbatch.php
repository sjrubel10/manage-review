<?php

namespace Manage\Review\Classes;

class MakeProductsbatch
{
    private $max_generate_review_per_batch;
    function __construct(){
        $this->max_generate_review_per_batch = 1000;
    }

    public function make_batch_product( $product_ids, $review_per_product, $total_products ){
        $total_reviews = $total_products * $review_per_product;
        if( $total_reviews > $this->max_generate_review_per_batch ){
            $aaa = $total_reviews / $this->max_generate_review_per_batch;
            $total_batch = floor( $total_products / $aaa );
            $product_batch_array = array_chunk( $product_ids, $total_batch );
        }else{
            $product_batch_array[] = $product_ids;

        }

        return $product_batch_array;
    }

}