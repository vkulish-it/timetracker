<?php
namespace App\Controller;

use App\Controller\HttpController;

class LoginForm extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        include ROOT_DIR . "/templates/login/page.php";
    }
}
