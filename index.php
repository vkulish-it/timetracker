<?php

use App\Service\Request;

// define root dir path (project root directory)
define('ROOT_DIR', realpath(__DIR__));
/** include file (library) with class description by class name */
spl_autoload_register(function ($className) {
        include_once ROOT_DIR . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    }
);
session_start();
$classes = [
    '' => "App\Controller\Home",
    '/' => "App\Controller\Home",
    'home' => "App\Controller\Home",
    'index.php' => "App\Controller\Home",
    'admin/login' => "App\Controller\Admin\LoginForm",
    'admin/account/login' => "App\Controller\Admin\AccountLogin",
    'login' => "App\Controller\LoginForm",
    'user/login' => "App\Controller\User\Login",
    'logout' => "App\Controller\User\Logout",
    'contact-us' => "App\Controller\ContactUsForm",
    'not-found' => "App\Controller\NotFound",
    'registration' => "App\Controller\RegistrationForm",
    'user/registration' => "App\Controller\User\Registration",
    'main' => "App\Controller\Account\Main",
    'main/account/edit' => "App\Controller\Account\Edit",
    'main/account/design' => "App\Controller\Account\Design",
    'main/account/time' => "App\Controller\Account\Time",
    'main/account/delete' => "App\Controller\Account\Delete",
    'tracker/create' => "App\Controller\Tracker\Create",

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
