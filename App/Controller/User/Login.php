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

    public function __construct() {
        include_once ROOT_DIR . "/App/Service/Response.php";
        include_once ROOT_DIR . "/App/Service/Request.php";
        include_once ROOT_DIR . "/App/Models/User.php";
        $this->response = new Response();
        $this->request = new Request();
        $this->user = new User();
    }

    public function run() {
        $login = $this->request->getParam("email");
        $passwd = $this->request->getParam("psw");
        include_once ROOT_DIR . "/App/Models/Config.php";
        $config = new Config();
        $connection = new \mysqli($config->getDBHost(), $config->getDBUserName(), $config->getDBUserPassword(), $config->getDBName());
        $sqlCommand = "SELECT * FROM users WHERE email ='" . $login . "';";
        if ($result = $connection->query($sqlCommand)) {
            $userData = $result->fetch_object();
            if ($userData && password_verify($passwd, $userData->passwd_hash)) {
                $userDataArray = get_object_vars($userData);
                $this->user->login($userDataArray);
                $this->response->setRedirect("");
            } else {
                // @TODO add message invalid email or password
                $this->response->setRedirect("login.php");
            }
        } else {
            // @TODO add mySQL error message
            $this->response->setRedirect("login.php");
        }
        $connection->close();
    }
}
