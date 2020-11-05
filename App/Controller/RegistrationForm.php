<?php
namespace App\Controller;

use App\Controller\HttpController;

class RegistrationForm extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        include ROOT_DIR . "/templates/registration/page.php";
    }
}
