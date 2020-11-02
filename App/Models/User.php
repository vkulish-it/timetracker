<?php
namespace App\Models;

class User
{
    const SESSION_KEY_USER_DATA = 'user';
    const SESSION_KEY_USER_IS_LOGGED_IN = 'is_logged_in';

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($_SESSION && $_SESSION[self::SESSION_KEY_USER_IS_LOGGED_IN] === true) {
            return true;
        }

        return false;
    }

    /**
     * @param array $userData
     * @return $this
     */
    public function login(array $userData)
    {
        $_SESSION[self::SESSION_KEY_USER_DATA] = $userData;
        $_SESSION[self::SESSION_KEY_USER_IS_LOGGED_IN] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function logout()
    {
        if ($_SESSION && array_key_exists(self::SESSION_KEY_USER_DATA, $_SESSION)) {
            unset($_SESSION[self::SESSION_KEY_USER_DATA]);
        }
        if ($_SESSION && array_key_exists(self::SESSION_KEY_USER_IS_LOGGED_IN, $_SESSION)) {
            unset($_SESSION[self::SESSION_KEY_USER_IS_LOGGED_IN]);
        }

        return $this;
    }
}