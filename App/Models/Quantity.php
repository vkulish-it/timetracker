<?php
namespace App\Models;

use App\Factory;

/** @todo move to tracker model */
class Quantity
{
    protected $config;

    public function run()
    {
        $this->getQuantity();
    }

    public function __construct()
    {
        $this->config = Factory::getSingleton(Config::class);
    }

    public function getQuantity()
    {
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());

        $sqlCommand = "SELECT COUNT(*) AS count FROM users";
        if ($result = $connection->query($sqlCommand)) {
            $userData = $result->fetch_object();
            return $userData->count;
        } else {
            return 0;
        }
}
}