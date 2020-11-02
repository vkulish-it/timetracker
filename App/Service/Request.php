<?php

namespace App\Service;

class Request
{

    public function getControllerPath() {
        return trim($_SERVER['REDIRECT_URL'], '/');
    }
    /**
     * @return array
     */
    public function getParams()
    {
        if (isset($_REQUEST)) {
            return $_REQUEST;
        }

        return [];
    }

    /**
     *  @TODO validate request data
     *
     * @param string $name
     * @return null|string|array
     */
    public function getParam(string $name)
    {
        if (isset($_REQUEST) && array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }

        return null;
    }
}