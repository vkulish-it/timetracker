<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxController;

class Create extends AjaxController
{
    protected $inputParams = ['name', 'time_start', 'state_active'];

    public function run() {
        $userId = $this->user->getId();
        $name = $this->request->getParam('name');
        $timeStart = $this->request->getParam('time_start');
        $stateActive = $this->request->getParam('state_active');

        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = " INSERT INTO tasks (id, user_id, name, time_start, time_finish, duration, state_active) VALUES (NULL, ?, ?, ?, NULL, NULL, ?)";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('issi', $userId, $name, $timeStart, $stateActive);
        $stmt->execute();
        if ($connection->errno === 0) {
            $this->responseData['data']['id'] = $connection->insert_id;
            $this->setAjaxStatus(self::RESPONSE_CODE_OK);
        } else {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_MYSQL);
        }
        $connection->close();

        $this->sendAjaxResponse();
    }
}
