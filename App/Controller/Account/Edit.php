<?php
namespace App\Controller\Account;

use App\Controller\HttpController;

class Edit extends HttpController
{
    public function run()
    {
        $this->saveUser();
        $this->response->setRedirect('main');
    }

    /**
     * update user params: firstname, lastname, phone, user-logo
     */
    private function saveUser()
    {
        $firstname = $this->request->getParam('firstname');
        $lastname = $this->request->getParam('lastname');
        $phone = $this->request->getParam('phone');
        if ($firstname && $lastname && $phone) {
            $logoRelativeUrl = $this->updateImage();
            if ($logoRelativeUrl === null) {
                $logoRelativeUrl = $this->user->getAccountData('logo_img_url');
            }
            $userId = $this->user->getAccountData('id');
            $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
            $sqlCommand = "UPDATE users SET firstname='" . $firstname . "',  lastname='" . $lastname . "', phone = '" . $phone . "', logo_img_url='" . $logoRelativeUrl . "' WHERE users.id='" . $userId . "';";
            if ($connection->query($sqlCommand) === TRUE) {
                $this->user->updateSession();
            } else {
                $this->user->addMessage("Error: " . $sqlCommand . "<br>" . $connection->error . "<br>");
            }
            $connection->close();
        } else {
            $this->user->addMessage("Some of required fields are empty or not exists");
        }
    }

    /**
     * Upload logo image and save it to project user logo folder
     *
     * @return string|null
     */
    private function updateImage() {
        if (!$_FILES || !array_key_exists("user-logo", $_FILES) || !$_FILES["user-logo"] || $_FILES["user-logo"]['error'] != 0) {
            return null;
        }

        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/media/img/user-logo/";
        $filename = $this->user->getAccountData('id') . "-" . $_FILES["user-logo"]["name"];
        $relativeUrl = "/media/img/user-logo/" . $filename; // will be returned and saved to data base
        $targetFile = $targetDir . basename($filename);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["user-logo"]["tmp_name"]);
            if ($check === false) {
                $this->user->addMessage("Can't get image.");
                $uploadOk = 0;
            }
        }
    // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $this->user->addMessage("Sorry, your file was not uploaded.");
    // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["user-logo"]["tmp_name"], $targetFile)) {
                return $relativeUrl;
            } else {
                $this->user->addMessage("Sorry, there was an error uploading your file.");
            }
        }
        return null;
    }
}