<?php
namespace App\Controller\Account;

use App\Controller\HttpController;

class Time extends HttpController
{
    public function run()
    {
        $this->saveTime();
        $this->response->setRedirect('main');
    }

    /**
     * update user params:prep_task_name, wkg_hour_day, wkg_hour_week, wkg_hour_month
     * id, user_id
     */
    private function saveTime()
    {
        $prepTask = $this->request->getParam('prep_task_name');
        $wkgHDay = $this->request->getParam('wkg_hour_day');
        $wkgHWeek = $this->request->getParam('wkg_hour_week');
        $wkgHMonth = $this->request->getParam('wkg_hour_month');

        if ($prepTask && $wkgHDay && $wkgHWeek && $wkgHMonth) {
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
                $sqlCommand = "UPDATE settings SET prep_task_name='" . $prepTask . "', wkg_hour_day='" . $wkgHDay . "', wkg_hour_week='" . $wkgHWeek . "', wkg_hour_month= '" . $wkgHMonth . "';";
            } else {
                $sqlCommand = "INSERT INTO settings (id, user_id, prep_task_name, wkg_hour_day, wkg_hour_week, wkg_hour_month)
        VALUES (NULL, '" . $userId . "','" . $prepTask . "', '" . $wkgHDay  . "', '" . $wkgHWeek . "', '" . $wkgHMonth . "');";
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
}