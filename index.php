<?php
// @TODO Homepage
//session_start();

$config = include_once $_SERVER['DOCUMENT_ROOT'] . "config.php";

if ($config['logged in'] === true) {
    include_once "tracker.php";
} else {
    include_once  "login.php";
}
