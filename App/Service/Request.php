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
     * @param string $key name of request parameter
     * @return null|string|array
     */
    public function getParam(string $key)
    {
        if (isset($_REQUEST) && array_key_exists($key, $_REQUEST)) {
            return $_REQUEST[$key];
        }

        return null;
    }
}