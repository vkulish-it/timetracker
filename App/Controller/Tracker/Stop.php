<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxTrackerController;

class Stop extends AjaxTrackerController
{
    protected $inputParams = ['id', 'name', 'time_finish', 'duration','state_active'];

    public function run() {
        $taskId = (int)$this->request->getParam('id');
        $this->validateTaskUser($taskId);

        $name = $this->request->getParam('name');
        $timeFinish = $this->request->getParam('time_finish');
        $durationTask = (int)$this->request->getParam('duration');
        $stateActive = $this->request->getParam('state_active');

        if ($this->tracker->updateOnStop($taskId, $name, $timeFinish, $durationTask, $stateActive)) {
            $this->setAjaxStatus(self::RESPONSE_CODE_OK);
        } else {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_MYSQL);
        }

        $this->sendAjaxResponse();
    }
}
