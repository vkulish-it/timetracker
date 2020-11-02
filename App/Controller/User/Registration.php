<?php
namespace App\Controller\User;

use App\Models\Config;
use App\Service\Response;

class Registration
{
    public function run() {
        $request = $_REQUEST;
        include_once ROOT_DIR . "/App/Service/Response.php";
        $response = new Response();
// @TODO check all request data
        include_once ROOT_DIR . "/App/Models/Config.php";
        $config = new Config();
        $connection = new \mysqli($config->getDBHost(), $config->getDBUserName(), $config->getDBUserPassword(),$config->getDBName());
        $sqlCommand = "INSERT INTO Users (id, email, passwd_hash, phone, firstname, lastname, logo_img_url, enabled)
        VALUES (NULL, '" . $request['email'] . "', '" . password_hash($request['psw'], PASSWORD_DEFAULT) . "', '" . $request['phone'] . "', '" . $request['firstname'] . "', '" . $request['lastname'] . "', '" . $request['logo_img_url'] . "', '1')";
        if ($connection->query($sqlCommand) === TRUE) {
// @TODO login user           $userId = $connection->insert_id;
            $response->setRedirect("", true, Response::CODE_FOUND);
        } else {
// @TODO           echo "Error: " . $sqlCommand . "<br>" . $connection->error;
            $response->setRedirect("registration.php");
        }
        $connection->close();
    }
}
