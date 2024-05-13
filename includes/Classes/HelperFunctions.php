<?php

namespace Manage\Review\Classes;

class HelperFunctions
{
    public static function comment_text(){
        $comment_text = array(
            "I've had this product for months now, and it's still as good as new.",
            "This product is incredibly easy to use, even for someone like me who isn't tech-savvy.",
            "The sleek design of this product adds a touch of elegance to my workspace.",
            "I'm amazed by the performance of this product. It handles everything I throw at it effortlessly.",
            "I purchased this product as a gift for a friend, and they couldn't be happier with it.",
            "The battery life on this product is impressive. I can go days without needing to recharge.",
            "I've owned many products from this brand, and this one lives up to their reputation for quality.",
            "This product is so compact and lightweight, making it perfect for travel.",
            "I'm pleasantly surprised by the number of features packed into this small device.",
            "The user interface of this product is intuitive and user-friendly.",
            "I've tried cheaper alternatives, but they don't compare to the reliability of this product.",
            "The build quality of this product is excellent. It feels sturdy and well-made.",
            "This product arrived quickly and was well-packaged to prevent any damage.",
            "I've been recommending this product to all my friends and family. It's that good!",
            "The price may seem high, but the quality of this product justifies every penny.",
            "I've been using this product daily for months now, and it's still going strong.",
            "The customer support for this product is exceptional. They go above and beyond to help.",
            "I'm impressed by how versatile this product is. It can handle a wide range of tasks with ease.",
            "This product has made a noticeable improvement in my daily routine. I couldn't be happier with it.",
            "I love the attention to detail that went into designing this product. It's clear that a lot of thought was put into it.",
            "I've had issues with similar products in the past, but this one performs flawlessly.",
            "The included instructions were clear and easy to follow, making setup a breeze.",
            "I was hesitant to purchase this product at first, but now I wish I had bought it sooner.",
            "This product is perfect for anyone looking to upgrade their current setup.",
            "I've received numerous compliments on this product since I started using it.",
            "I've been using this product for weeks now, and it's still exceeding my expectations.",
            "The build quality of this product is top-notch. It feels like it will last for years to come.",
            "I'm impressed by the range of colors available for this product. There's something for everyone.",
            "I purchased this product on a whim, and it turned out to be one of the best decisions I've made.",
            "This product has become an essential part of my daily routine. I don't know how I lived without it."
        );

        return $comment_text;
    }

    public static function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function get_random_review_date( $date1, $date2 ){
        $timestamp1 = strtotime( $date1 );
        $timestamp2 = strtotime( $date2 );
        $minTimestamp = min( $timestamp1, $timestamp2 );
        $maxTimestamp = max( $timestamp1, $timestamp2 );
        $randomTimestamp = wp_rand( $minTimestamp, $maxTimestamp );
//        date_default_timezone_set('GMT');
        $randomDateTimeGMT = gmdate('Y-m-d H:i:s', $randomTimestamp);
        $randomDateTime = gmdate('Y-m-d H:i:s', $randomTimestamp);
        $commented_date_time =array(
            'comment_date' => $randomDateTime,
            'comment_date_gmt' => $randomDateTimeGMT,
        );

        return $commented_date_time;
    }

}