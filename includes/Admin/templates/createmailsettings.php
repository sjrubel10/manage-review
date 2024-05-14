<?php
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
    .reviewMasterContainer {
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
    .testBatchData{
        text-align: center;
        padding: 10px 10px 0px 100px;
        font-size: 15px;
    }


    .titleSearchHolder{
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        position: relative;
        padding: 2px;
        width: 100%;
        border: 1px solid #ccc;
    }
    .removeSelectedItems{
        width: 25px;
        text-align: center;
        margin-top: 14px;
    }
    .titleSearchContainer{
        width: calc( 100% - 25px );
    }
    #productTitleSearchBox{
        background: none;
        border: none;
        outline: none;
        font-size: 1em;
        margin: 2px;
        padding: 4px 0px;
        vertical-align: middle;
    }
    #productTitleSearchBox:focus{
        box-shadow: 0 0 0 0;
    }
    .searchResultContainer{
        display: flex;
    }
    .productTitleHolder{
        padding: 5px 2px 5px 5px;
        margin: 5px;
        border: 1px solid #d9d1d1;
        border-radius: 3px;
    }
    .removeSingleProduct{
        margin-left: 5px;
        cursor: pointer;
        padding: 5px;
        background-color: #666666;
        color: #ffff;
    }
    .productDropDownMenu{
        width: 100%;
        /*border-color: #b3b3b3 #ccc #d9d9d9;*/
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        background: #fff;
        border: 1px solid #ccc;
        margin-top: -1px;
        box-sizing: border-box;
        overflow: auto;
        position: absolute;
        max-height: 200px;
        z-index: 10;
    }
    .productDropDownMenu .option-wrapper {
        cursor: pointer;
        outline: none;
    }

    .productDropDownMenu .option-wrapper .productTitleSelect {
        color: #666;
        cursor: pointer;
        padding: 8px 10px;
    }

    .productDropDownMenu .option-wrapper .productTitleSelect:hover {
        background-color: #b2d2ee;
    }

</style>

<div class="reviewMasterContainer">

    <h1>Add Reviews</h1>
    <div class="createMultipleReviews" id="createMultipleReviews" style="display: none">
        <h2>Multiple Reviews Added</h2>
        <form method="post" action="" id="createReviewForm" style="display: none">
            <label for="numberOfReviewPerProduct">Number Of Review Per Product:</label>
            <input type="number" id="numberOfReviewPerProduct" name="numberOfReviewPerProduct" value="1"><br>

            <label for="numberOfReviewRating">Review Rating:</label>
            <input type="text" id="numberOfReviewRating" name="numberOfReviewRating" placeholder="5 or 1 to 5"><br>

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

            <div id="testBatchData" class="testBatchData">
                <div id="status"></div>
            </div>
        </form>
    </div>

    <div class="createSingleReviews" id="createSingleReviews" style="display: block">
        <h2>Single Reviews Added</h2>
        <form method="post" action="" id="createReviewForm">
            <label for="numberOfReviewPerProduct">Number Of Review </label>
            <input type="number" id="numberOfReviewPerProduct" name="numberOfReviewPerProduct" value="1"><br>

            <label for="numberOfReviewRating">Review Rating:</label>
            <input type="text" id="numberOfReviewRating" name="numberOfReviewRating" placeholder="Between 1 to 5"><br>

            <label for="reviewStartDate">Review Date</label>
            <input type="date" id="reviewStartDate" name="reviewStartDate" value="<?php echo esc_attr( gmdate("Y-m-d") ); ?>"><br>

            <div class="titleSearchHolder">
                <div class="titleSearchContainer">
                    <div class="searchResultContainer" id="searchResultContainer">
                        <div class="productTitleHolder"><span class="productTitle">This is titl</span><span class="removeSingleProduct">x</span></div>
                        <div class="productTitleHolder"><span class="productTitle">This new a is titl</span><span class="removeSingleProduct">x</span></div>
                        <div class="productTitleHolder"><span class="productTitle">Way od This is titl</span><span class="removeSingleProduct">x</span></div>
                    </div>
                    <input type="text" class="productTitleSearchBox" id="productTitleSearchBox" placeholder="Type to search...">
                    <div class="productDropDownMenu" id="productDropDownMenu">
                        <div class="option-wrapper" id="productTitleWrapper"></div>
                    </div>
                </div>
                <div class="removeSelectedItems">X</div>
            </div>

            <input type="submit" name="submit" value="Add Multiple reviews">

            <div id="testBatchData" class="testBatchData">
                <div id="status"></div>
            </div>
        </form>
    </div>


    <div class="progress" id="progressHolder" style="display: none">
        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    jQuery(document).ready(function() {

        function display_search_data( productTitles ) {
            console.log( productTitles );
            jQuery("#productTitleWrapper").children().remove();
            let length = productTitles.length;
            let titleText = ''; // Use a string to accumulate HTML content
            for (let i = 0; i < length; i++) {
                titleText += '<div class="productTitleSelect" id="'+productTitles[i]['id']+'">\
                    <span id="productId">' + productTitles[i]['id'] + '::' + productTitles[i]['title'] + '</span>\
                </div>';
            }

            return titleText;
        }
        //productTitleSearchBox
        $('#productTitleSearchBox').on('input', function() {
            // let formData = {};
            let search_term = $(this).val();
            let nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';
            let setUrl ='<?php echo esc_url_raw( rest_url( 'createReviews/v1/search/search_by_text' ) ); ?>';
            let type = 'GET';
            // console.log( search_term );
            if( search_term.length === 3 ) { // Trigger search when input length is more than 2
                jQuery("#productTitleWrapper").show();
                jQuery.ajax({
                    type: type,
                    url: setUrl,
                    contentType: 'application/json',
                    headers: {
                        'X-WP-Nonce': nonce
                    },
                    data: {
                        search_term: search_term,
                        limit: 10 // Adjust the limit as needed
                    },
                    success: function( response ) {
                        let searchData = display_search_data( response );
                        jQuery("#productTitleWrapper").append( searchData )
                        // console.log( response );
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }else if( search_term.length === 0 ){
                jQuery("#productTitleWrapper").hide();
            }
        });

        var selectedCategories = [];
        jQuery('#categorySelect').on('change', function() {
            jQuery('#categorySelect option:selected').each(function() {
                var $this = jQuery(this);
                if (!$this.prop('disabled')) {
                    selectedCategories.push($this.text());
                    $this.prop('disabled', true); // Disable the option
                }
                var index = (this).value; // Get the current length of the array as the index
                var value = $this.text(); // Generate a value based on the index (example)
                var removeButton = jQuery('<button>').addClass('remove-button').html('&times;');
                var div = jQuery('<li id=selected-'+index+'>').addClass('selected-category').text(value);
                div.append(removeButton);
                jQuery('#selectedCategories').append(div);

                jQuery("#all_Categories").remove();
            });
        });

        let is_category_shows = 0;
        jQuery('#createReviewForm').on('click', '#selectedCategories', function() {
            if( is_category_shows === 0){
                is_category_shows ++;
                jQuery("#categorySelectHolder").show();
            }else{
                is_category_shows --;
                jQuery("#categorySelectHolder").hide();
            }

        });

        jQuery('#selectedCategories').on('click', '.remove-button', function() {
            let text = jQuery(this).parent().text().trim('x');
            text = text.replace('×', '');
            jQuery('#categorySelect option').filter(function() {
                return jQuery(this).text().trim() === text;
            }).prop('disabled', false); // Enable the option
            jQuery(this).parent().remove(); // Remove the selected category div

            let liExists = jQuery('#selectedCategories').find('li').length;
            if( liExists === 0 ){
                jQuery("#selectedCategories").append( '<li id="all_Categories" class="selected-category">All Categories</li>' );
                jQuery("#categorySelectHolder").hide();
            }
        });


        function set_settings_data( formData, type, path ){
            jQuery("#progressHolder").show();
            animateProgressBar( per_complt_start, 1000 );
            jQuery.ajax({
                type: type,
                url: path,
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify(formData),
                success: function( response ) {
                    batches = response;
                    totalBatches = batches.length;
                    per_complt = Math.floor( 90/totalBatches );
                    if( totalBatches > 0 ){
                        currentBatch = 0;
                        makeBatchCall() ;
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        jQuery('#createReviewForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission
            per_complt_start = 10;
            jQuery('#status').text(' Reviews Are Generating... ').css({
                'color': 'blue', // Set text color to blue
                'font-weight': 'bold' // Set font weight to bold
            });
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
            let path ='<?php echo esc_url_raw( rest_url( 'createReviews/v1/get_product_ids' ) ); ?>';
            let type = 'POST';
            set_settings_data( formData, type , path );
        });

        function animateProgressBar( progress, duration ) {
            jQuery('.progress-bar').animate({
                width: progress + '%'
            }, duration );
        }



        //make per batch review

        var currentBatch = 0;
        function makeBatchCall( ) {
            var formData = {
                'numberOfReviewPerProduct': jQuery('#numberOfReviewPerProduct').val(),
                'reviewStartDate': jQuery('#reviewStartDate').val(),
                'reviewEndDate': jQuery('#reviewEndDate').val(),
                'numberOfReviewRating': jQuery('#numberOfReviewRating').val(),
            };
            if ( currentBatch >= totalBatches ) {
                review_status_bar_success_message_hide();
                return;
            }
            let batchData = batches[currentBatch];
            formData.productIds = batchData;
            formData.nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';
            // batchData.formData = formData;
            let path ='<?php echo esc_url_raw( rest_url( 'createReviews/v1/create_multiple_review_by_batch' ) ); ?>';
            let type = 'POST';
            jQuery.ajax({
                type: type,
                url: path,
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify( formData ),
                success: function( response ) {
                    per_complt_start = per_complt_start + per_complt;
                    animateProgressBar( per_complt_start, 1000 );
                    currentBatch++;
                    makeBatchCall();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function review_status_bar_success_message_hide(){
            per_complt_start = 0;
            animateProgressBar( per_complt_start, 1000 );
            jQuery("#progressHolder").hide();
            jQuery('#status').text(' Reviews Are Successfully Created ').css({
                'color': 'green', // Set text color to blue
                'font-weight': 'bold' // Set font weight to bold
            });
        }

    });


</script>



