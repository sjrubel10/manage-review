<?php
//echo 'Hello World';

$categorySelector = 'all category';
// Access individual values from the array
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    h1, h2 {
        margin-top: 0;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    /* Style for form inputs */
    input[type="number"],
    input[type="date"],
    input[type="text"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    /*Check box style*/
    .mailSendNoOff{
        /*background-color: #7b805d;*/
        padding: 10px;
        border: 1px solid #e1d6d6;
        border-radius: 5px;
        margin: 5px 5px 15px 5px;
    }


    .progress {
        width: 100%;
        background-color: #f0f0f0;
        margin: 10px 0;
    }

    .progress-bar {
        width: 0%;
        height: 20px;
        background-color: #4caf50;
    }
</style>

<div class="container">

    <h1>Add Reviews</h1>
    <h2>Multiple Reviews Added</h2>
    <form method="post" action="" id="emailSettingsForm">
        <label for="numberOfReviewPerProduct">Number Of Review Per Product:</label>
        <input type="number" id="numberOfReviewPerProduct" name="numberOfReviewPerProduct" value="1"><br>

        <label for="numberOfReviewRating">Review Rating:</label>
        <input type="number" id="numberOfReviewRating" name="numberOfReviewRating" value="5"><br>

        <label for="reviewStartDate">Review Start Date</label>
        <input type="date" id="reviewStartDate" name="reviewStartDate" value="<?php echo esc_attr( gmdate("Y-m-d") ); ?>"><br>

        <label for="reviewEndDate">Review End Date</label>
        <input type="date" id="reviewEndDate" name="reviewEndDate" value="<?php echo esc_attr( gmdate("Y-m-d") )?>"><br>

        <h2>Select Category</h2>
        <label for="categorySelector">Category:</label>
        <input type="text" id="categorySelector" name="categorySelector" value="<?php echo esc_attr($categorySelector); ?>"><br>

        <input type="submit" name="submit" value="Add reviews">
    </form>

    <div class="progress" id="progressHolder" style="display: none">
        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    jQuery(document).ready(function() {

        function set_settings_data( formData, type, path ){
            jQuery("#progressHolder").show();
            animateProgressBar( 10, 2000 );
            jQuery.ajax({
                type: type,
                url: path,
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    // alert(response);
                    animateProgressBar( 100, 2000 );
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        jQuery('#emailSettingsForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission
            var formData = {
                'numberOfReviewPerProduct': jQuery('#numberOfReviewPerProduct').val(),
                'reviewStartDate': jQuery('#reviewStartDate').val(),
                'reviewEndDate': jQuery('#reviewEndDate').val(),
                'categorySelector': jQuery('#categorySelector').val(),
                'numberOfReviewRating': jQuery('#numberOfReviewRating').val(),
            };
            // Add nonce to form data
            formData.nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';
            let path ='<?php echo esc_url_raw( rest_url( 'createReviews/v1/create_multiple_review' ) ); ?>';
            let type = 'POST';
            set_settings_data( formData, type , path );
        });

        function animateProgressBar( progress, duration ) {
            $('.progress-bar').animate({
                width: progress + '%'
            }, duration );
        }

    });


</script>



