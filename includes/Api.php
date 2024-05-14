<?php
namespace Manage\Review;

use Manage\Review\API\CreateMailSettings;
use Manage\Review\API\GetProductTitleBySearch;

class Api
{
    function __construct(){
        add_action( 'rest_api_init', [$this, 'register_api']);
    }

    public function register_api(){
        $tasktodo = new CreateMailSettings();
        $bysearch = new GetProductTitleBySearch();
        $tasktodo->register_routes();
        $bysearch->register_routes();
    }

}