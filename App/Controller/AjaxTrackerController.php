<?php
namespace App\Controller;

use App\Factory;
use App\Models\Tracker;

abstract class AjaxTrackerController extends AjaxController
{
    /** @var Tracker $tracker */
    protected $tracker;

    public function __construct() {
        parent::__construct();
        $this->tracker = Factory::getSingleton(Tracker::class);
    }

    protected function validateTaskUser($taskId) {
        if (!$this->tracker->validateTaskUser($taskId)) {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_INPUT_PARAMS);
            $this->sendAjaxResponse();
        }
    }
}
