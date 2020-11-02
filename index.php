<?php

use App\Service\Request;

// define root dir path (project root directory)
define('ROOT_DIR', realpath(__DIR__));

$classes = [
    '' => "App\Controller\Home",
    'home' => "App\Controller\Home",
    'index.php' => "App\Controller\Home",
    'login' => "App\Controller\LoginForm",
    'user/login' => "App\Controller\User\Login",
    'logout' => "App\Controller\User\Logout",
    'not-found' => "App\Controller\NotFound",

    // @todo describe other controllers
];
$controllerClass = "App\Controller\NotFound";

include_once ROOT_DIR . "/App/Service/Request.php";
$request = new Request();
$controllerPath = $request->getControllerPath();

if (array_key_exists($controllerPath, $classes)) {
    $controllerClass = $classes[$controllerPath];
}

// get class Path by its name
// App\Controller\NotFound => /App/Controller/NotFound.php
$path = ROOT_DIR . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $controllerClass) . '.php';
//

include_once $path;
$controller = new $controllerClass();
$controller->run();
