<?php
namespace App\Controller\Admin;

use App\Controller\HttpController;

abstract class AdminHttpController extends HttpController
{
    /** Check for ADMIN login */
    protected function checkLogin() {
        if (!$this->user->isLoggedInAdmin()) {
            $this->response->setRedirect('admin/login');
            exit();
        }
    }
}
