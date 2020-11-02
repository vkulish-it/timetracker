<?php
/** GET GENERAL DATA */
// define root dir path (project root directory)
define('ROOT_DIR', realpath(__DIR__ . '/..'));
// include library
include_once ROOT_DIR . "/App/Models/Config.php";
// create object sample of type App\Models\Config
$config = new \App\Models\Config();

/** CREATE DATABASE */

// Create connection
$connection = new \mysqli($config->getDBHost(), $config->getDBUserName(), $config->getDBUserPassword());
// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
// Create database
$sqlCommand = "CREATE DATABASE " . $config->getDBName();
if ($connection->query($sqlCommand) === TRUE) {
    echo "Database " . $config->getDBName() . " created successfully" . "\r\n";
} else {
    echo "Error creating database: " . $connection->error . "\r\n";
}
$connection->close();

/** CREATE TABLES */

// Create connection
$conn = new \mysqli($config->getDBHost(), $config->getDBUserName(), $config->getDBUserPassword(), $config->getDBName());
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/** create user table */

$sqlCommand = "CREATE TABLE Users (
id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Id',
email VARCHAR(50) NOT NULL UNIQUE COMMENT 'Email',
passwd_hash CHAR(72) NOT NULL COMMENT 'Password hash',
phone VARCHAR(15)NOT NULL COMMENT 'Phone number',
firstname VARCHAR(50) NOT NULL COMMENT 'Fist name',
lastname VARCHAR(50) NOT NULL COMMENT 'Last name',
logo_img_url TINYTEXT NOT NULL COMMENT 'Logo of user',
enabled BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Enabled',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of creating',
update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time of updating'
)
COMMENT='Table of users'";

if ($conn->query($sqlCommand) === TRUE) {
    echo "Table Users created successfully" . "\r\n";
} else {
    echo "Error creating table Users: " . $conn->error . "\r\n";
}

/** create Settings table */
$sqlCommand = "CREATE TABLE Settings (
id INT(10) UNSIGNED AUTO_INCREMENT COMMENT 'Id',
user_id INT(10) UNSIGNED NOT NULL COMMENT 'User Id',
bkg_color VARCHAR(10) NOT NULL COMMENT 'Background color',
main_bkg_color VARCHAR(10) NOT NULL COMMENT 'Main background color',
prep_task_color VARCHAR(10) NOT NULL COMMENT 'Preparation task color',
border_color VARCHAR(10) NOT NULL COMMENT 'Border color',
font_color VARCHAR(10) NOT NULL COMMENT 'Font color',
prep_task_name VARCHAR(10) NOT NULL COMMENT 'Preparation task name',
wkg_hour_day VARCHAR(10) NOT NULL COMMENT 'Working hours per day',
wkg_hour_week VARCHAR(10) NOT NULL COMMENT 'Working hours per week',
wkg_hour_month VARCHAR(10) NOT NULL COMMENT 'Working hours per month',
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE 
)
COMMENT='Table of settings'";

if ($conn->query($sqlCommand) === TRUE) {
    echo "Table Settings created successfully" . "\r\n";
} else {
    echo "Error creating table Settings: " . $conn->error . "\r\n";
}

/** create Tasks table */
$sqlCommand = "CREATE TABLE Tasks (
id INT(10) UNSIGNED AUTO_INCREMENT COMMENT 'Id',
user_id INT(10) UNSIGNED COMMENT 'User Id',
name VARCHAR(20) NOT NULL COMMENT 'Name of the task',
time_start TIMESTAMP COMMENT 'Time starting of the task',
time_finish TIMESTAMP COMMENT 'Time finishing of the task',
duration TIME COMMENT 'Duration of the task',
state_active BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'State of activity',
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE 
)
COMMENT='Table of tasks'";

if ($conn->query($sqlCommand) === TRUE) {
    echo "Table Tasks created successfully" . "\r\n";
} else {
    echo "Error creating table Tasks: " . $conn->error . "\r\n";
}

$conn->close();
