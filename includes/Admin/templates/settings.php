<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Page</title>
    <style>

        .rmSettingContainer {
            max-width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .rmBlockSection {
            margin-bottom: 20px;
            display: block;
            overflow: auto;
            max-height: 400px;
            width: 600px;
        }
        .rmBlockSection h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .rmAddCommentFormGroup {
            margin-bottom: 15px;
        }
        .rmAddCommentFormGroup label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .rmAddCommentFormGroup input[type="text"],
        .rmAddCommentFormGroup select {
            width: 100%;
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
        }
        .commentList{
            padding: 3px;
            border-bottom: 1px solid #f0e9e9;
        }
    </style>
</head>
<body>
<?php
$initialCommentsArray = array(
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
?>
<div class="rmSettingContainer">
    <div class="rmBlockSection">
        <h2>Comment Section</h2>
        <ul class="rmAllReviewCommentList" id="rmAllReviewCommentList">
            <!-- Existing comments will be dynamically added here -->
        </ul>
        <div class="rmAddCommentFormGroup">
            <label for="new-comment">Add Comment:</label>
            <input type="text" id="new-comment" name="new-comment">
            <button id="add-comment">Add Comment</button>
        </div>
    </div>
</div>

<script>
    // JavaScript for adding and removing comments
    document.addEventListener("DOMContentLoaded", function() {
        // Initial array of comments
        var initialComments = <?php echo json_encode($initialCommentsArray); ?>;

        // Function to render initial comments
        function renderInitialComments() {
            var commentList = jQuery("#rmAllReviewCommentList");
            initialComments.forEach(function(comment) {
                var listItem = jQuery("<li class='commentList'>");
                var commentSpan = jQuery("<span>").text(comment);

                // Create a remove button
                var removeButton = jQuery("<button>").text("X").addClass("remove-comment");

                // Add click event listener to remove button
                removeButton.on("click", function() {
                    jQuery(this).parent().remove();
                });

                // Append comment span and remove button to list item
                listItem.append(commentSpan, removeButton);

                // Append list item to comment list
                commentList.append(listItem);
            });
        }

        // Render initial comments on page load
        renderInitialComments();

        // Function to add a new comment
        function addComment() {
            var commentInput = jQuery("#new-comment");
            var commentText = commentInput.val().trim();

            if (commentText !== "") {
                var commentList = jQuery("#rmAllReviewCommentList");
                var listItem = jQuery("<li>");
                var commentSpan = jQuery("<span>").text(commentText);

                // Create a remove button
                var removeButton = jQuery("<button>").text("X").addClass("remove-comment");

                // Add click event listener to remove button
                removeButton.on("click", function() {
                    jQuery(this).parent().remove();
                });

                // Append comment span and remove button to list item
                listItem.append(commentSpan, removeButton);

                // Append list item to comment list
                commentList.append(listItem);

                // Clear the input field
                commentInput.val("");
            }
        }


        // Add click event listener to the "Add Comment" button
        document.getElementById("add-comment").addEventListener("click", addComment);
    });
</script>
</body>
</html>
