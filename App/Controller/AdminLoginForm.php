<?php
namespace App\Controller;

use App\Controller\HttpController;

class AdminLoginForm extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        include ROOT_DIR . "/templates/admin/login/page.php";
    }
}
