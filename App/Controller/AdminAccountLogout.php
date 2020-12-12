<?php
namespace App\Controller;

/**
 * Class AccountLogin
 */
class AdminAccountLogout extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        if ($this->user->isLoggedInAdmin()) {
            $this->user->logoutAdmin();
        }

        $this->response->setRedirect("");
    }
}
