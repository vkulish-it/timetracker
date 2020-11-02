<?php

namespace App\Service;

use App\Models\Config;

class Response
{
    const CODE_FOUND = 302;

    private $config;

    public function __construct()
    {
        include_once ROOT_DIR . "/App/Models/Config.php";
        $this->config = new Config();
    }

    public function setRedirect($newUrl, $replace = true, $code = self::CODE_FOUND)
    {
        $url = $this->config->getBaseUrl() . "/" . $newUrl;
        header("Location: " . $url, $replace, $code);
    }
}