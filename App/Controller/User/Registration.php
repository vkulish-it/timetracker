<?php
namespace App\Controller\User;

use App\Service\Response;
use App\Controller\HttpController;

class Registration extends HttpController
{
    protected $checkForLoggedIn = false;

    public function run() {
// @TODO check all request data
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "INSERT INTO users (id, email, passwd_hash, phone, firstname, lastname, logo_img_url, enabled)
        VALUES (NULL, '" . $this->request->getParam('email') . "', '" . password_hash($this->request->getParam('psw'), PASSWORD_DEFAULT) . "', '" . $this->request->getParam('phone') . "', '" . $this->request->getParam('firstname') . "', '" . $this->request->getParam('lastname') . "', '" . $this->request->getParam('logo_img_url') . "', '1')";
        if ($connection->query($sqlCommand) === TRUE) {
            $this->user->addMessage("You were successfully registered. Please login");
            $this->response->setRedirect("main", true, Response::CODE_FOUND);
        } else {
            $this->user->addMessage("Error: " . $sqlCommand . "<br>" . $connection->error . "<br>");
            $this->response->setRedirect("registration");
        }
        $connection->close();
    }
}
