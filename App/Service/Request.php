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

    /**
     * @return array
     */
    public function getFiles()
    {
        if (isset($_FILES)) {
            return $_FILES;
        }

        return [];
    }

    /**
     * @param string $key name of request parameter
     * @return array
     */
    public function getFile(string $key)
    {
        if (isset($_FILES) && array_key_exists($key, $_FILES)) {
            return $_FILES[$key];
        }

        return null;
    }
}
