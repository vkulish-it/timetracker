<?php

namespace App\Controller;

use App\Controller\HttpController;

// @todo account page controller
class Home extends HttpController
{
    public function run() {
        // @todo fix after tracker page is done
        include_once ROOT_DIR . "/tracker.php";
    }
}
