<?php

namespace App\Models;

class Config
{
    private $db;
    public $rootDir;
    private $defaultValue;

    public function __construct()
    {
        $this->rootDir = realpath(__DIR__ . '/../..');
        $this->db = include_once $this->rootDir . "/App/config/db.php";
        $this->defaultValue = include_once $this->rootDir . "/App/config/default.php";
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
