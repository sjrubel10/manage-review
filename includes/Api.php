<?php
namespace Manage\Review;

use Manage\Review\API\CreateMailSettings;
use Manage\Review\API\GetProductTitleBySearch;
use Manage\Review\API\MakeSettingsChnage;

class Api
{
    function __construct(){
        add_action( 'rest_api_init', [$this, 'register_api']);
    }

    public function register_api(){
        $tasktodo = new CreateMailSettings();
        $bysearch = new GetProductTitleBySearch();
        $settings = new MakeSettingsChnage();
        $tasktodo->register_routes();
        $bysearch->register_routes();
        $settings->register_routes();
    }

}