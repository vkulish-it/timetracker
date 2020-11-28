<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxTrackerController;

class Create extends AjaxTrackerController
{
    protected $inputParams = ['name', 'time_start', 'state_active'];

    public function run() {
        $userId = $this->user->getId();
        $name = $this->request->getParam('name');
        $timeStart = $this->request->getParam('time_start');
        $stateActive = $this->request->getParam('state_active');

        $createdTaskId = $this->tracker->createNewTask($userId, $name, $timeStart, $stateActive);
        if ($createdTaskId === null) {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_MYSQL);
        } else {
            $this->responseData['data']['id'] = $createdTaskId;
            $this->setAjaxStatus(self::RESPONSE_CODE_OK);
        }

        $this->sendAjaxResponse();
    }
}
