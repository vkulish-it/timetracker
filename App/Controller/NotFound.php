<?php

namespace App\Controller;

use App\Controller\HttpController;

class NotFound extends HttpController
{
    protected $checkForLoggedIn = true;

    public function run() {
        echo "Not Found Page";
    }
}
