<?php
namespace App\Controller\Account;

use App\Controller\HttpController;

class Delete extends HttpController
{
    public function run()
    {
        $this->deleteUser();
        $this->response->setRedirect('');
    }

    /**
     * update user params: firstname, lastname, phone, user-logo
     */
    private function deleteUser()
    {
        $userId = $this->user->getAccountData('id');
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "DELETE FROM users WHERE users.id =" . $userId . ";";
        if ($connection->query($sqlCommand) === TRUE) {
            $this->user->addMessage("Your account was deleted successfully");
            $this->user->updateSession();
        } else {
            $this->user->addMessage("Error: " . $sqlCommand . "<br>" . $connection->error . "<br>");
        }
        $connection->close();
    }
}