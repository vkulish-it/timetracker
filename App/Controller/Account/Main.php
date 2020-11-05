<?php

namespace App\Controller\Account;

use \App\Controller\HttpController;

// @todo account page controller
class Main extends HttpController
{
    protected $checkForLoggedIn = true;

    public function run() {
        include_once ROOT_DIR . "/templates/main/page.php";
    }
}
