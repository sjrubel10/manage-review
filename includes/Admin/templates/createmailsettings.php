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
    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    textarea {
        height: 150px;
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
</style>

<div class="container">

    <h1>Add Reviews</h1>
    <h2>Multiple Reviews Added</h2>
    <form method="post" action="" id="emailSettingsForm">
        <label for="numberOfReviewPerProduct">Number Of Review Per Product:</label>
        <input type="number" id="numberOfReviewPerProduct" name="numberOfReviewPerProduct" value="1"><br>

        <label for="reviewStartDate">Review Start Date</label>
        <input type="date" id="reviewStartDate" name="reviewStartDate" value="<?php echo esc_attr( date("Y-m-d") ); ?>"><br>

        <label for="reviewEndDate">Review End Date</label>
        <input type="date" id="reviewEndDate" name="reviewEndDate" value="<?php echo esc_attr( date("Y-m-d") )?>"><br>

        <h2>Select Category</h2>
        <label for="categorySelector">Category:</label>
        <input type="text" id="categorySelector" name="categorySelector" value="<?php echo esc_attr($categorySelector); ?>"><br>

        <input type="submit" name="submit" value="Add reviews">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    jQuery(document).ready(function() {

        function set_settings_data( formData, type, path ){
            jQuery.ajax({
                type: type,
                url: path,
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    alert(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }



        jQuery('#emailSettingsForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Gather input field values into an object fromname
            var formData = {
                'numberOfReviewPerProduct': jQuery('#numberOfReviewPerProduct').val(),
                'reviewStartDate': jQuery('#reviewStartDate').val(),
                'reviewEndDate': jQuery('#reviewEndDate').val(),
                'categorySelector': jQuery('#categorySelector').val(),
            };

            // Add nonce to form data
            formData.nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';

            let path ='<?php echo esc_url_raw( rest_url( 'createReviews/v1/create_multiple_review' ) ); ?>';
            let type = 'POST';

            // console.log( formData );
            set_settings_data( formData, type , path );
            // Send the data to the custom REST API endpoint

        });
    });


</script>



