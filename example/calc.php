<?php
session_start();

function getSessionCount() {
    $countKeyName = "cou";
    $count = 0;
    if (isset($_SESSION[$countKeyName])) {
        $count = $_SESSION[$countKeyName];
    }
    $count++;
    $_SESSION[$countKeyName] = $count;

    return $count;
}

function getCalcResult() {
    $params = [];
    $request = $_REQUEST;

    if (array_key_exists('number-1', $request)) {
        $params["1"] = $request['number-1'];
    } else {
        return "";
    }

    if (array_key_exists('number-2', $request)) {
        $params["2"] = $request['number-2'];
    } else {
        return "";
    }

    $config = include_once "config.php";

    switch ($config['operation']) {
        case "+":
            $result = $params["1"] + $params["2"];
            break;
        case '-':
            $result = $params["1"] - $params["2"];
            break;
        case '*':
            $result = $params["1"] * $params["2"];
            break;
        case '/':
            if ($params["2"] != 0) {
                $result = $params["1"] / $params["2"];
            } else {
                return "Param 2 must not be 0 on division";
            }
            break;
        default:
            return "Invalid input config param";
    };

    return $result;
}

include_once "header.php";

echo "<h2>" . getCalcResult() . "</h2>";
echo "<h2>Session count:" . getSessionCount() . "</h2>";

include_once "form.php";
