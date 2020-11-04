<?php

use App\Service\Request;

// define root dir path (project root directory)
define('ROOT_DIR', realpath(__DIR__));
/** include file (library) with class description by class name */
spl_autoload_register(function ($className) {
        include ROOT_DIR . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    }
);
session_start();
$classes = [
    '' => "App\Controller\Home",
    'home' => "App\Controller\Home",
    'index.php' => "App\Controller\Home",
    'login' => "App\Controller\LoginForm",
    'user/login' => "App\Controller\User\Login",
    'logout' => "App\Controller\User\Logout",
    'contact-us' => "App\Controller\ContactUsForm",
    'not-found' => "App\Controller\NotFound",
    'registration' => "App\Controller\RegistrationForm",
    'user/registration' => "App\Controller\User\Registration",
    // @todo describe other controllers
];
$controllerClass = "App\Controller\NotFound";
$request = new Request();
$controllerPath = $request->getControllerPath();
if (array_key_exists($controllerPath, $classes)) {
    $controllerClass = $classes[$controllerPath];
}
$controller = new $controllerClass();
$controller->run();
