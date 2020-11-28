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
}
