<?php
namespace App\Controller\User;

use App\Models\Config;
use App\Models\User;
use App\Service\Request;
use App\Service\Response;

class Registration
{
    private $request;
    private $response;
    private $config;

    public function __construct() {
        $this->response = new Response();
        $this->request = new Request();
        $this->config = new Config();
    }

    public function run() {
// @TODO check all request data
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "INSERT INTO Users (id, email, passwd_hash, phone, firstname, lastname, logo_img_url, enabled)
        VALUES (NULL, '" . $this->request->getParam('email') . "', '" . password_hash($this->request->getParam('psw'), PASSWORD_DEFAULT) . "', '" . $this->request->getParam('phone') . "', '" . $this->request->getParam('firstname') . "', '" . $this->request->getParam('lastname') . "', '" . $this->request->getParam('logo_img_url') . "', '1')";
        if ($connection->query($sqlCommand) === TRUE) {
// @TODO login user           $userId = $connection->insert_id;
            $this->response->setRedirect("", true, Response::CODE_FOUND);
        } else {
// @TODO           echo "Error: " . $sqlCommand . "<br>" . $connection->error;
            $this->response->setRedirect("registration");
        }
        $connection->close();
    }
}
