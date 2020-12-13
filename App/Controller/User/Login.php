<?php
namespace App\Controller\User;

use App\Controller\HttpController;

class Login extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
        $login = $this->request->getParam("email");
        $passwd = $this->request->getParam("psw");
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "SELECT * FROM users WHERE email ='" . $login . "';";
        if ($result = $connection->query($sqlCommand)) {
            $userData = $result->fetch_object();
            $connection->close();
            if ($userData && password_verify($passwd, $userData->passwd_hash)) {
                $userDataArray = get_object_vars($userData);
                if ($userDataArray['enabled'] != 1) {
                    $this->user->addMessage("Your user is inactive. Please, contact support team.");
                    $this->response->setRedirect("home");
                }
                $this->user->login($userDataArray);
                $this->response->setRedirect("main");
            } else {
                $this->user->addMessage("Invalid email or password");
                $this->response->setRedirect("home");
            }
        } else {
            $connection->close();
            $this->user->addMessage("Error while checking email and password");
            $this->response->setRedirect("home");
        }
    }
}
