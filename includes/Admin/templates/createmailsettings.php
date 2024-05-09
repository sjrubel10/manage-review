<?php
//echo 'Hello World';
$taxonomy = 'product_cat'; // WooCommerce product category taxonomy
$terms = get_terms( array(
    'taxonomy' => $taxonomy,
    'hide_empty' => false, // Set to false to include empty categories
));
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
        background-color: #4682c3;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 15px;
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

    .selected-category {
        background-color: #f0f0f0;
        padding: 5px 30px 5px 10px;
        margin-right: 5px;
        margin-bottom: 5px;
        border-radius: 5px;
        display: inline-block;
        position: relative;
    }
    .selected-category .remove-button {
        position: absolute;
        top: 0;
        right: 0;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        margin: 0;
    }
    .selectedCategories{
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        cursor: pointer;
        margin-block-end: 0;
    }
    .categorySelectHolder{
        margin-bottom: 20px;
    }
    .wp-core-ui select[multiple]{
        width: 300px;
        padding: 10px;
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

        <label for="categorySelector">Select Category:</label>
        <ul id="selectedCategories" class="selectedCategories">
            <li id="all_Categories" class="selected-category">All Categories</li>
        </ul>


        <div class="categorySelectHolder" id="categorySelectHolder" style="display: none">
            <select id="categorySelect" multiple>
                <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) { ?>
                <option id="<?php esc_attr_e( $term->slug, 'manage-review' );?>" value="<?php esc_attr_e( $term->slug, 'manage-review' );?>"><?php esc_attr_e( $term->name, 'manage-review' );?></option>
                <?php } }?>
            </select>
        </div>

        <input type="submit" name="submit" value="Add Multiple reviews">
    </form>

    <div class="progress" id="progressHolder" style="display: none">
        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    jQuery(document).ready(function() {

        var selectedCategories = [];
        jQuery('#categorySelect').on('change', function() {
            jQuery('#categorySelect option:selected').each(function() {
                var $this = $(this);
                if (!$this.prop('disabled')) {
                    selectedCategories.push($this.text());
                    $this.prop('disabled', true); // Disable the option
                }
                var index = (this).value; // Get the current length of the array as the index
                var value = $this.text(); // Generate a value based on the index (example)
                var removeButton = jQuery('<button>').addClass('remove-button').html('&times;');
                var div = jQuery('<li id=selected-'+index+'>').addClass('selected-category').text(value);
                div.append(removeButton);
                $('#selectedCategories').append(div);

                jQuery("#all_Categories").remove();
            });
        });

        let is_category_shows = 0;
        $('#emailSettingsForm').on('click', '#selectedCategories', function() {
            if( is_category_shows === 0){
                is_category_shows ++;
                jQuery("#categorySelectHolder").show();
            }else{
                is_category_shows --;
                jQuery("#categorySelectHolder").hide();
            }

        });

        $('#selectedCategories').on('click', '.remove-button', function() {
            let text = $(this).parent().text().trim('x');
            text = text.replace('×', '');
            $('#categorySelect option').filter(function() {
                return $(this).text().trim() === text;
            }).prop('disabled', false); // Enable the option
            $(this).parent().remove(); // Remove the selected category div

            let liExists = $('#selectedCategories').find('li').length;
            if( liExists === 0 ){
                jQuery("#selectedCategories").append( '<li id="all_Categories" class="selected-category">All Categories</li>' );
                jQuery("#categorySelectHolder").hide();
            }
        });


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

            jQuery("#categorySelectHolder").hide();

            let selectedCategoryArray = [];
            jQuery("#selectedCategories li").each(function(){
                let eachCategory = jQuery(this).attr('id').replace('selected-', '').trim();
                selectedCategoryArray.push( eachCategory );
            });

            var formData = {
                'numberOfReviewPerProduct': jQuery('#numberOfReviewPerProduct').val(),
                'reviewStartDate': jQuery('#reviewStartDate').val(),
                'reviewEndDate': jQuery('#reviewEndDate').val(),
                'categorySelector': selectedCategoryArray,
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



