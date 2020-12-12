<?php
namespace App\Controller;

/**
 * Class AccountLogin
 */
class AdminAccountLogin extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        if ($this->user->isLoggedInAdmin()) {
            $this->response->setRedirect("admin/setting");
        }

        $login = $this->request->getParam("login");
        $passwd = $this->request->getParam("psw");

        $adminLogin = $this->config->getAdminLogin();
        $adminPasswd = $this->config->getAdminPassword();

        if ($login === $adminLogin && $passwd === $adminPasswd) {
            $this->user->loginAdmin();
            $this->response->setRedirect("admin/setting");
        } else {
            $this->user->addMessage("Invalid email or password");
            $this->response->setRedirect("admin/login");
        }
    }
}
