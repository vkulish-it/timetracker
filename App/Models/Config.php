<?php

namespace App\Models;

class Config
{
    private $db;
    private $defaultValue;
    private $baseUrl;
    private $admin;

    public function __construct()
    {
        $this->db = include ROOT_DIR . "/App/config/db.php";
        $setting = include ROOT_DIR . "/App/config/setting.php";
        $this->defaultValue = $setting["default"];
        $this->baseUrl = $setting["base_url"];
        $this->admin = include ROOT_DIR . "/App/config/admin.php";
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

    public function getDefaultAccountSettings()
    {
        return $this->defaultValue;
    }

    public function getAdminLogin()
    {
        return $this->admin['login'];
    }

    public function getAdminPassword()
    {
        return $this->admin['password'];
    }
}
