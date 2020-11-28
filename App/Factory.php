<?php

namespace App;

final class Factory
{
    private static $classes = [];

    public static function getSingleton($className) {
        if (array_key_exists($className, static::$classes)) {
            return static::$classes[$className];
        } else {
            return static::getNewClass($className);
        }
    }

    public static function getNewClass($className) {
        $newClass = new $className();
        if (!array_key_exists($className, static::$classes)) {
           static::$classes[$className] = $newClass;
        }
        return $newClass;
    }
}
