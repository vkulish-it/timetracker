<?php
/** GET GENERAL DATA */
// define root dir path (project root directory)
define('ROOT_DIR', realpath(__DIR__ . '/..'));
// include library
include_once ROOT_DIR . "/App/Controller/User/Registration.php";

$controller = new \App\Controller\User\Registration();
$controller->run();
