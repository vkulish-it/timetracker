<?php
namespace App\Controller\User;

class Create
{
    public function run() {
        // get request data to array
        $request = $_REQUEST;
        // check all request data
// @TODO
        // create connection with data base
        include_once ROOT_DIR . "/App/Models/Config.php";
        $config = new \App\Models\Config();
        $connection = new \mysqli($config->getDBHost(), $config->getDBUserName(), $config->getDBUserPassword(),$config->getDBName());
        // send query to add user request data to data base table Users
        $sqlCommand = "INSERT INTO Users (id, email, passwd_hash, phone, firstname, lastname, logo_img_url, enabled)
        VALUES (NULL, '" . $request['email'] . "', '" . password_hash($request['psw'], PASSWORD_DEFAULT) . "', '" . $request['phone'] . "', '" . $request['firstname'] . "', '" . $request['lastname'] . "', '" . $request['logo_img_url'] . "', '1')";
        // save user Id to variable
        if ($connection->query($sqlCommand) === TRUE) {
            $userId = $connection->insert_id;
            echo "New record created successfully. User inserted ID is: " . $userId;
        } else {
            echo "Error: " . $sqlCommand . "<br>" . $connection->error;
        }
        // close connection
        $connection->close();
        // create redirect (for now just echo new user Id)
        echo "123";
    }
}
