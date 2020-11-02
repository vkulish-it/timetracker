<?php

namespace App\Models;

class Config
{
    private $db;
    private $defaultValue;
    private $baseUrl;

    public function __construct()
    {
        $this->db = include ROOT_DIR . "/App/config/db.php";
        $setting = include_once ROOT_DIR . "/App/config/setting.php";
        $this->defaultValue = $setting["default"];
        $this->baseUrl = $setting["base_url"];
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getDbConf()
    {
        return $this->db;
    }

    public function getDBHost()
    {
        return $this->db['db_host'];
    }

    public function getDBName()
    {
        return $this->db['db_name'];
    }

    public function getDBUserName()
    {
        return $this->db['db_user_name'];
    }

    public function getDBUserPassword()
    {
        return $this->db['db_user_password'];
    }

    public function getFontSize()
    {
        return $this->defaultValue["font_size"];
    }
}
