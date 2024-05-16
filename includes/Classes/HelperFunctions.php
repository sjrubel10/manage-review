<?php

namespace Manage\Review\Classes;

class HelperFunctions
{
    public static function comment_text(){

        return  maybe_unserialize( get_option( 'rmCommentText' ) ) ;
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