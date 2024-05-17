<?php
    use Manage\Review\Classes\HelperFunctions;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php esc_attr_e('Settings Page', 'review-master'); ?></title>
    <style>

        .rmSettingContainer {
            display: flex;
            max-width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        .rmBlockSection {
            margin-bottom: 20px;
            display: block;
            overflow: auto;
            max-height: 400px;
            max-width: 420px;
            border: 1px solid #c2d3d3;
            padding: 10px 5px;
        }
        .rmBlockSection h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .rmAddCommentFormGroup {
            margin-bottom: 2px;
        }
        .rmAddCommentFormGroup label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .rmAddCommentFormGroup input[type="text"],
        .rmAddCommentFormGroup select {
            width: calc(100% - 4px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .rmAddCommentFormGroup select {
            width: auto;
        }
        .rmAddCommentFormGroup button {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 0;
        }
        .rmAllReviewCommentList {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
        .rmAllReviewCommentList li {
            margin-bottom: 5px;
        }
        .rmAllReviewCommentList li span {
            display: inline-block;
            /*background-color: #f2f2f2;*/
            padding: 5px 10px;
            border-radius: 5px;
        }
        .remove-comment {
            margin-left: 10px;
            color: #ff0000;
            cursor: pointer;
            height: 30px;
        }
        .commentList{
            display: flex;
            padding: 3px;
            border-bottom: 1px solid #f0e9e9;
        }
    </style>
</head>
<body>
<?php $initialCommentsArray = HelperFunctions::comment_text(); ?>
<div class="rmSettingContainer">
    <div class="rmBlockSection">
        <h2><?php esc_attr_e('Comment Set', 'review-master'); ?></h2>
        <div class="rmAddCommentFormGroup">
            <label for="new-comment"><?php esc_attr_e('Add Comment:', 'review-master'); ?></label>
            <input type="text" id="new-comment" name="new-comment">
            <button id="add-comment"><?php esc_attr_e('Add Comment', 'review-master'); ?></button>
        </div>
        <ul class="rmAllReviewCommentList" id="rmAllReviewCommentList"></ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let initialComments = <?php echo json_encode( $initialCommentsArray ); ?>;
        var totalComments = initialComments.length;
        let type = 'POST';
        let path ='<?php echo esc_url_raw( rest_url( 'createReviews/v1/settings/comment_add_remove' ) ); ?>';
        let nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';
        var settingsFormData = {};

        function renderInitialComments() {
            let commentList = jQuery("#rmAllReviewCommentList");
            initialComments.forEach(function( comment, index ) {
                let listItem = jQuery("<li class='commentList'>");
                let commentSpan = jQuery("<span>").text(comment);
                let removeButton = jQuery("<button id='"+index+"'>").text("X").addClass("remove-comment");
                listItem.append(commentSpan, removeButton);
                commentList.append(listItem);
            });
        }
        renderInitialComments();

        // Function to add a new comment
        function addComment() {
            let commentInput = jQuery("#new-comment");
            let commentText = commentInput.val().trim();
            if (commentText !== "") {
                totalComments++ ;
                let listItem = jQuery("<li class='commentList'>");
                let commentSpan = jQuery("<span>").text(commentText);
                initialComments.push( commentText );
                let removeButton = jQuery("<button id='"+totalComments+"'>").text("X").addClass("remove-comment");
                listItem.append(commentSpan, removeButton);

                settingsFormData.comments = initialComments;
                settingsFormData.nonce = nonce;
                set_settings_data( settingsFormData, type, path, listItem, 1 );
            }
        }

        function removeComment( getClickedId ){
            let valueToRemove = jQuery('#'+getClickedId).siblings().text().trim();
            let removeIndex = jQuery.inArray( valueToRemove, initialComments );
            if ( removeIndex !== -1 ) {
                totalComments++ ;
                initialComments.splice( removeIndex, 1);
            }
            settingsFormData.comments = initialComments;
            settingsFormData.nonce = nonce;
            set_settings_data( settingsFormData, type, path, getClickedId );
        }

        jQuery('body').on('click', '.remove-comment', function() {
            let getClickedId = jQuery(this).attr('id');
            removeComment( getClickedId );
        });
        // Add click event listener to the "Add Comment" button
        document.getElementById("add-comment").addEventListener("click", addComment);

        function set_settings_data( formData, type, path, removedId, what=0 ){
            jQuery.ajax({
                type: type,
                url: path,
                contentType: 'application/json',
                headers: {
                    'X-WP-Nonce': formData.nonce
                },
                data: JSON.stringify(formData),
                success: function( response ) {
                    if( what === 0 ){
                        jQuery('#'+removedId).parent().remove();
                    }else{
                        jQuery("#new-comment").val("");
                        jQuery("#rmAllReviewCommentList").prepend( removedId );
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
</script>
</body>
</html>
