<?php

namespace App\Service;

use App\Factory;
use App\Models\Config;

class Response
{
    const CODE_FOUND = 302;

    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->config = Factory::getSingleton(Config::class);
    }

    public function setRedirect($newUrl, $replace = true, $code = self::CODE_FOUND)
    {
        $url = $this->config->getBaseUrl() . "/" . $newUrl;
        header("Location: " . $url, $replace, $code);
    }
}