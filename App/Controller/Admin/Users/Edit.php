<?php
namespace App\Controller\Admin\Users;

use App\Controller\Admin\AdminHttpController;

class Edit extends AdminHttpController
{
    public function run()
    {
        $this->saveUser();
        $this->response->setRedirect('admin/users');
    }

    /**
     * update user params: firstname, lastname, phone, user-logo, enabled
     */
    private function saveUser()
    {
        $userId = (int)$this->request->getParam('id');
        $firstname = $this->request->getParam('firstname');
        $lastname = $this->request->getParam('lastname');
        $phone = $this->request->getParam('phone');
        $enabled = $this->request->getParam('enabled') ? 1 : 0;
        $removeLogoQuery = "";
        if ($this->request->getParam('remove-user-logo') !== null && $this->request->getParam('remove-user-logo')) {
            $removeLogoQuery = ", logo_img_url=''";
        }
        if ($userId !== null && $firstname && $lastname && $phone) {
            $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
            $sqlCommand = "UPDATE users SET firstname='" . $firstname . "', lastname='" . $lastname  . "', phone = '" . $phone  . "', enabled = " . $enabled . $removeLogoQuery . " WHERE users.id=$userId;";
            if ($connection->query($sqlCommand) === TRUE) {
                $this->user->addMessage("User with Id=$userId was updated");
            } else {
                $this->user->addMessage("Error: " . $sqlCommand . "<br>" . $connection->error . "<br>");
            }
            $connection->close();
        } else {
            $this->user->addMessage("Some of required fields are empty or not exists");
        }
    }
}
