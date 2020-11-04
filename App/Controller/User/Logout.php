<?php

namespace App\Controller\User;

use App\Service\Response;
use App\Models\User;

class Logout
{
    private $response;
    private $user;

    public function __construct() {
        $this->response = new Response();
        $this->user = new User();
    }

    public function run() {
        $this->user->logout();
        $this->response->setRedirect('login');
    }
}
