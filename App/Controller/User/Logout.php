<?php

namespace App\Controller\User;

use App\Controller\HttpController;

class Logout extends HttpController
{
    public function run() {
        $this->user->logout();
        $this->response->setRedirect('home');
    }
}
