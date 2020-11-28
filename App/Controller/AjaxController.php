<?php
namespace App\Controller;

use App\Controller\HttpController;

abstract class AjaxController extends HttpController
{
    const RESPONSE_CODE_OK = 1;
    const RESPONSE_CODE_ERROR_GENERAL = 2;
    const RESPONSE_CODE_ERROR_INPUT_PARAMS = 3;
    const RESPONSE_CODE_ERROR_LOGIN = 4;
    const RESPONSE_CODE_ERROR_MYSQL = 5;


    const RESPONSE_ERROR_MESSAGES = [
        self::RESPONSE_CODE_OK => "",
        self::RESPONSE_CODE_ERROR_GENERAL => "Something went wrong",
        self::RESPONSE_CODE_ERROR_INPUT_PARAMS => "Invalid input params",
        self::RESPONSE_CODE_ERROR_LOGIN => "Invalid input params list",
        self::RESPONSE_CODE_ERROR_MYSQL => "Error while saving to db"
    ];

    /**
     * @var array $inputParams
     */
    protected $inputParams = [];


    protected $responseData = [
        'data' => [],
        'status' => [
            'code' => 0,
            'message' => ""
        ]
    ];

    public function __construct() {
        $this->validateInputParams();
        parent::__construct();
    }

    protected function validateInputParams() {
        foreach ($this->inputParams as $paramName) {
            if (!$_REQUEST || !isset($_REQUEST[$paramName])) {
                $this->responseData['status']['code'] = self::RESPONSE_CODE_ERROR_INPUT_PARAMS;
                $this->responseData['status']['message'] = self::RESPONSE_ERROR_MESSAGES[self::RESPONSE_CODE_ERROR_INPUT_PARAMS];
                $this->sendResponse();
            }
        }
    }

    protected function checkLogin()
    {
        if (!$this->user->isLoggedIn()) {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_LOGIN);
            $this->sendAjaxResponse();
            exit;
        }
    }

    protected function validateTaskUser($id) {
        $userId = $this->user->getId();

        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "SELECT COUNT(id) AS qty FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $qty = (int)$data['qty'];
        $connection->close();

        if ($qty < 1) {
            $this->setAjaxStatus(self::RESPONSE_CODE_ERROR_INPUT_PARAMS);
            $this->sendAjaxResponse();
        }
    }

    protected function setAjaxStatus($statusCode) {
        $this->responseData['status']['code'] = $statusCode;
        $this->responseData['status']['message'] = self::RESPONSE_ERROR_MESSAGES[$statusCode];
    }

    protected function sendAjaxResponse() {
        echo json_encode($this->responseData);
        exit;
    }
}
