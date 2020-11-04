<?php
namespace App\Controller\User;

use App\Models\Config;
use App\Service\Request;
use App\Service\Response;
use App\Models\User;

class Login
{
    private $request;
    private $response;
    private $user;
    private $config;

    public function __construct() {
        $this->response = new Response();
        $this->request = new Request();
        $this->user = new User();
        $this->config = new Config();
    }

    public function run() {
        $login = $this->request->getParam("email");
        $passwd = $this->request->getParam("psw");
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "SELECT * FROM users WHERE email ='" . $login . "';";
        if ($result = $connection->query($sqlCommand)) {
            $userData = $result->fetch_object();
            if ($userData && password_verify($passwd, $userData->passwd_hash)) {
                $userDataArray = get_object_vars($userData);
                $this->user->login($userDataArray);
                $this->response->setRedirect("");
            } else {
                // @TODO add message invalid email or password
                $this->response->setRedirect("login");
            }
        } else {
            // @TODO add mySQL error message
            $this->response->setRedirect("login");
        }
        $connection->close();
    }
}
