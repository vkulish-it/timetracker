<?php

namespace App\Controller\User;

use App\Service\Response;
use App\Models\User;

class Logout
{
    private $response;
    private $user;

    public function __construct() {
        include_once ROOT_DIR . "/App/Service/Response.php";
        include_once ROOT_DIR . "/App/Models/User.php";
        $this->response = new Response();
        $this->user = new User();
    }

    public function run() {
        $this->user->logout();
        $this->response->setRedirect('login.php');
    }
}
