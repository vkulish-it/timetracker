<?php
namespace App\Controller\Tracker;

use App\Controller\AjaxTrackerController;

class LoadMore extends AjaxTrackerController
{
    protected $inputParams = ['date'];

    public function run() {
        $lastDate = $this->request->getParam('date');
        $tasks = $this->tracker->getLoadMoreTasks($lastDate);
        if ($tasks === false) {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_MYSQL);
        } else {
            $this->responseData['data'] = $tasks;
            $this->setAjaxStatus(self::RESPONSE_CODE_OK);
        }
        $this->sendAjaxResponse();
    }
}
