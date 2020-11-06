<?php
namespace App\Controller\Account;

use App\Controller\HttpController;

class Design extends HttpController
{
    public function run()
    {
        $this->saveDesign();
        $this->response->setRedirect('main');
    }

    /**
     * update user params: bkg_color, main_bkg_color, prep_task_color, border_color, font_color, font_size
     * id, user_id
     */
    private function saveDesign()
    {
        $bkgColor = $this->request->getParam('bkg_color');
        $mainBkgColor = $this->request->getParam('main_bkg_color');
        $prepTaskColor = $this->request->getParam('prep_task_color');
        $borderColor = $this->request->getParam('border_color');
        $fontColor = $this->request->getParam('font_color');
        $fontSize = $this->request->getParam('font_size');

        if ($bkgColor && $mainBkgColor && $prepTaskColor && $borderColor && $fontColor && $fontSize) {
            $userId = $this->user->getAccountData('id');

            $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());

            $isSettingsExists = false;
            $sqlCommand = "SELECT * FROM settings WHERE user_id='" . $userId . "';";
            if ($result = $connection->query($sqlCommand)) {
                $userData = $result->fetch_object();
                if ($userData) {
                    $isSettingsExists = true;
                }
            } else {
                $this->user->addMessage("Error while checking user settings");
            }

            if ($isSettingsExists) {
                $sqlCommand = "UPDATE settings SET bkg_color='" . $bkgColor . "',  main_bkg_color='" . $mainBkgColor . "', prep_task_color = '" . $prepTaskColor . "', bkg_color='" . $borderColor . "',  font_color='" . $fontColor . "', font_size = '" . $fontSize . "' WHERE settings.user_id='" . $userId . "';";
            } else {
                $sqlCommand = "INSERT INTO settings (id, user_id, bkg_color, main_bkg_color, prep_task_color, border_color, font_color, font_size)
        VALUES (NULL, '" . $userId . "', '" . $bkgColor . "', '" . $mainBkgColor . "', '" . $prepTaskColor . "', '" . $borderColor . "', '" . $fontColor . "', " . $fontSize . ");";
            }
            if ($connection->query($sqlCommand) === TRUE) {
                $this->user->updateSession();
            } else {
                $this->user->addMessage("Error while saving data to data base");
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