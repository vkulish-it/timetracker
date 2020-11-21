<?php
namespace App\Controller\Admin;

use App\Controller\HttpController;

class LoginForm extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        include ROOT_DIR . "/templates/admin/login/page.php";
    }
}
