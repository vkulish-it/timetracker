<?php

namespace App\Models;

class HomePageConfig
{
    const CONFIG_FILE_PATH = "App/config/home.txt";

    private $configJson;
    private $config;

    public function __construct()
    {
        $this->configJson = file_get_contents(ROOT_DIR . DIRECTORY_SEPARATOR . self::CONFIG_FILE_PATH, FILE_USE_INCLUDE_PATH);
        $this->config = json_decode($this->configJson, true);
    }

    public function getSliderItems()
    {
        return $this->config['sliders'];
    }

    public function getDescription()
    {
        return $this->config['description'];
    }
}

