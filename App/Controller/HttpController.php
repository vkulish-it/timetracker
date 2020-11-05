<?php
namespace App\Controller;

use \App\Models\Config;
use \App\Service\Request;
use \App\Service\Response;
use \App\Models\User;
use \App\Interfaces\Controller as ControllerInterface;

abstract class HttpController implements ControllerInterface
{
    protected $user;
    protected $config;
    protected $request;
    protected $response;

    /**
     * @var bool $checkForLoggedIn
     */
    protected $checkForLoggedIn = true;

    public function __construct() {
        $this->user = new User();
        $this->response = new Response();
        $this->config = new Config();
        $this->request = new Request();

        if ($this->checkForLoggedIn === true) {
            $this->checkLogin();
        }
    }

    protected function checkLogin() {
        if (!$this->user->isLoggedIn()) {
            $this->response->setRedirect('login');
            exit();
        }
    }
}
