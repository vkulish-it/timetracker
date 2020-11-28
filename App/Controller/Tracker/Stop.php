<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxController;

class Stop extends AjaxController
{
    protected $inputParams = ['id', 'name', 'time_finish', 'duration','state_active'];

    public function run() {
        $taskId = (int)$this->request->getParam('id');

        $name = $this->request->getParam('name');
        $timeFinish = $this->request->getParam('time_finish');
        $durationTask = $this->request->getParam('duration');
        $stateActive = $this->request->getParam('state_active');


        $this->validateTaskUser($taskId);

        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "UPDATE tasks SET name = ?, time_finish = ?, duration = ?, state_active = ? WHERE tasks.id = ?;";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('sssii',  $name, $timeFinish, $durationTask, $stateActive, $taskId);
        $r = $stmt->execute();
        $n = $connection->affected_rows;
        $connection->close();

        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "SELECT * FROM tasks WHERE id = " . $taskId .";";
        $result = $connection->query($sqlCommand);
        $userData = $result->fetch_object();
        $connection->close();

        if ($r && $n > 0) {
            $this->responseData['data'] = $userData;

            $this->setAjaxStatus(self::RESPONSE_CODE_OK);
        } else {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_GENERAL);
        }
        $this->sendAjaxResponse();
    }
}
