<?php

namespace Manage\Review\Classes;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
if ( !class_exists('PHPMailer\PHPMailer\PHPMailer' ) ) {
    require_once(\ABSPATH . \WPINC . "/PHPMailer/PHPMailer.php");
    require_once(\ABSPATH . \WPINC . "/PHPMailer/Exception.php");
    require_once(\ABSPATH . \WPINC . "/PHPMailer/SMTP.php");
}

class ReviewRequest{

    public function make_product_link_for_getting_review( $product_titles ){
        $title_btn = '<div class="review-button">';
        foreach ( $product_titles as $product_title ){
            $tlt = $product_title["title"];
            $link = $product_title["product_url"];
            $title_btn .= "<a href='$link' target='_blank'>$tlt</a>";
        }
        $title_btn .= '</div>';

        return $title_btn;
    }
    public function make_review_request_after_order_completed( $ordered_info ){
        $customer_name = $ordered_info['ordered_name'];
        $order_id = $ordered_info['order_id'];
        $order_date = '';
        $review_link = "https://example.com/review?order_id=" . $order_id;

        $tltbtn = $this->make_product_link_for_getting_review( $ordered_info['ordered_products'] );

        $customer_email = $ordered_info['billing_email'];
        $email_subject = "We'd Love Your Feedback on Your Recent Order!";
        $email_body = "
        <!DOCTYPE html>
            <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            color: #333333;
                            line-height: 1.6;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 20px auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border: 1px solid #dddddd;
                            border-radius: 5px;
                        }
                        .header {
                            text-align: center;
                            padding: 10px 0;
                        }
                        .header h1 {
                            margin: 0;
                            color: #333333;
                        }
                        .content {
                            margin: 20px 0;
                        }
                        .content p {
                            margin: 10px 0;
                        }
                        .review-button {
                            display: block;
                            float: left;
                            width: 100%;
                            text-align: center;
                            margin: 20px 0;
                        }
                        .review-button a {
                            float: left;
                            background-color: #0073aa;
                            color: #ffffff;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            margin: 5px 10px;
                        }
                        .review-button a:hover {
                            background-color: #005a87;
                        }
                        .footer {
                            text-align: center;
                            padding: 10px 0;
                            color: #777777;
                            font-size: 0.9em;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Thank You for Your Order!</h1>
                        </div>
                        <div class='content'>
                            <p>Hi $customer_name,</p>
                            <p>We hope you are enjoying your recent purchase from our store. Your feedback is important to us and helps us improve our services.</p>
                            <p>Would you mind taking a moment to leave a review for your order placed on $order_date? It would mean a lot to us!</p>
                            $tltbtn
                            <p>Thank you for your time and support!</p>
                            <p>Best regards,</p>
                            <p>Your Company Name</p>
                        </div>
                        <div class='footer'>
                            <p>&copy; " . gmdate('Y') . " Your Company Name. All rights reserved.</p>
                        </div>
                    </div>
                </body>
            </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Your Company <noreply@example.com>" . "\r\n";
//        mail( $customer_email, $email_subject, $email_body, $headers);
        $result  = $this->send_custom_mail( $customer_email, $email_subject, $email_body, $headers );

        return $result;
    }

    public function send_custom_mail( $customer_email, $email_subject, $email_body, $headers ) {
        try {
            $mail = new PHPMailer(true );
            $mail->isSMTP();
            $is_mail_sent = $this->send_mail( $mail, $customer_email, $email_subject, $email_body, $headers );
            return 'Email sent successfully';
        } catch (Exception $e) {
            return "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function send_mail( $mail, $customer_email, $email_subject, $email_body, $headers ) {

        // Configure SMTP settings here
//        $send_mail_settings_data = get_option('PA_send_mail_settings');
//        $mail_from = trim( $send_mail_settings_data['email'] );
        $mail_from = 'rubel1004098@gmail.com';
//        $mail_from_name = trim( $send_mail_settings_data['fromname'] );
        $mail_from_name = 'Rubel';
//        $appkey = trim( $send_mail_settings_data['appkey'] );
        $appkey = trim('yvszgdupzqwbfntr' );
//        $subject = trim( $send_mail_settings_data['subject'] );
//        $body_message = trim( $send_mail_settings_data['body_message'] );

        $mail->Host       = 'smtp.gmail.com';                   // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = $mail_from;                         // SMTP username
        $mail->Password   = $appkey;                            // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
//        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Enable SSL encryption
        $mail->Port       = 587;                                // TCP port to connect to
        // $mail->Port     = 465;                               // Use 465 for SMTPS port with SSL encryption

        // Sender and recipient
        $mail->setFrom( $mail_from , $mail_from_name );
        $mail->addAddress( $customer_email );                            // Add a recipient
        // Content
        $mail->isHTML( true );                                  // Set email format to HTML
        $mail->Subject = $email_subject;
        $mail->Body = $email_body;
        $mail->AltBody = 'This is the plain text message body for non-HTML mail clients';

//        error_log( print_r( ['$mail'=>$mail], true ) );
        // Send email
        $mail->send();

        return true; // Return true if email is sent successfully
    }

}