<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxController;

class Delete extends AjaxController
{
    protected $inputParams = ['id'];

    public function run() {
        $taskId = (int)$this->request->getParam('id');
        $this->validateTaskUser($taskId);
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "DELETE FROM tasks WHERE id = " . $taskId .";";
        if ($connection->query($sqlCommand) === FALSE) {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_MYSQL);
        };
        $connection->close();
        $this->sendAjaxResponse();
    }
}
