<?php
namespace App\Controller;

use App\Factory;
use \App\Models\Config;
use \App\Service\Request;
use \App\Service\Response;
use \App\Models\User;
use \App\Interfaces\Controller as ControllerInterface;

abstract class HttpController implements ControllerInterface
{
    /** @var User */
    protected $user;

    /** @var Config */
    protected $config;

    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /**
     * @var bool $checkForLoggedIn
     */
    protected $checkForLoggedIn = true;

    public function __construct() {
        $this->user = Factory::getSingleton(User::class);
        $this->response = Factory::getSingleton(Response::class);
        $this->config = Factory::getSingleton(Config::class);
        $this->request = Factory::getSingleton(Request::class);

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
