<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxTrackerController;

class Delete extends AjaxTrackerController
{
    protected $inputParams = ['id'];

    public function run() {
        $taskId = (int)$this->request->getParam('id');
        $this->validateTaskUser($taskId);

        if ($this->tracker->deleteById($taskId) === true) {
            $this->setAjaxStatus(self::RESPONSE_CODE_OK);
        } else {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_MYSQL);
        }

        $this->sendAjaxResponse();
    }
}
