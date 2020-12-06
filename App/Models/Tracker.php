<?php

namespace App\Models;

use App\Factory;

class Tracker
{
    /** @var User */
    private $user;

    /** @var Config */
    private $config;

    public function __construct() {
        $this->user = Factory::getSingleton(User::class);
        $this->config = Factory::getSingleton(Config::class);
    }

    public function validateTaskUser($taskId) {
        $userId = $this->user->getId();
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "SELECT COUNT(id) AS qty FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('ii', $taskId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $qty = (int)$data['qty'];
        $connection->close();
        return ($qty > 0);
    }

    public function createNewTask($userId, $name, $timeStart, $stateActive) {
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "INSERT INTO tasks (id, user_id, name, time_start, time_finish, duration, state_active) VALUES (NULL, ?, ?, ?, NULL, NULL, ?)";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('issi', $userId, $name, $timeStart, $stateActive);
        $stmt->execute();
        if ($connection->errno === 0) {
            $taskId = $connection->insert_id;
            $connection->close();
            return $taskId;
        }
        return null;
    }

    public function updateOnStop($taskId, $name, $timeFinish, $durationTask, $stateActive) {
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "UPDATE tasks SET name = ?, time_finish = ?, duration = ?, state_active = ? WHERE tasks.id = ?;";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('sssii',  $name, $timeFinish, $durationTask, $stateActive, $taskId);
        $r = $stmt->execute();
        $n = $connection->affected_rows;
        $connection->close();
        if ($r && $n > 0) {
            return true;
        }
        return false;
    }

    public function deleteById($taskId) {
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "DELETE FROM tasks WHERE id = " . $taskId .";";
        if ($connection->query($sqlCommand) === FALSE) {
            return false;
        }
        return true;
    }

    public function getTaskById($id) {

    }

    public function getJsonUserTasks() {
        $userId = $this->user->getId();
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $twoDaysAgo = date( 'Y-m-d', strtotime(' -2 day'));
        $sqlCommand = "SELECT * FROM tasks WHERE user_id = ? AND time_start > ?";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('is', $userId, $twoDaysAgo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $connection->close();
        return json_encode($data);
    }

    /**
     * @param string $lastDate
     * @return array|false
     */
    public function getLoadMoreTasks($lastDate) {
        $userId = $this->user->getId();

        // get previous working date
        $lastDateString = $lastDate . " 00:00:00";
        $connection = new \mysqli($this->config->getDBHost(), $this->config->getDBUserName(), $this->config->getDBUserPassword(), $this->config->getDBName());
        $sqlCommand = "SELECT time_start FROM tasks WHERE user_id = ? AND time_start < ? ORDER BY time_start DESC LIMIT 1";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('is', $userId, $lastDateString);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result === false) {
            $connection->close();
            return false;
        }
        $data = $result->fetch_assoc();
        if (!$data || !isset($data['time_start']) || !$data['time_start']) {
           return [];
        }
        $dateTime = $data['time_start']; // 2020-12-03 21:10:01
        $date = substr($dateTime, 0 ,10); // 2020-12-03

        // get tasks for previous working date
        $minDateTime = $date . " 00:00:00";
        $maxDateTime = $date . " 23:59:59";
        $sqlCommand = "SELECT * FROM tasks WHERE user_id = ? AND time_start >= ? AND time_start <= ?";
        $stmt = $connection->prepare($sqlCommand);
        /** i - Integer; d - Double; s - String; b - Blob */
        $stmt->bind_param('iss', $userId, $minDateTime, $maxDateTime);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result === false) {
            $connection->close();
            return false;
        }
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $connection->close();

        return $data;
    }

}
