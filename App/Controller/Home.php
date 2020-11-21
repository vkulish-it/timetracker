<?php

namespace App\Controller;

use \App\Controller\HttpController;

class Home extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run()
    {
        include_once ROOT_DIR . "/templates/home/page.php";
    }
}
