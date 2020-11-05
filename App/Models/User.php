<?php
namespace App\Models;

class User
{
    const SESSION_KEY_USER_DATA = 'user';
    const SESSION_KEY_USER_IS_LOGGED_IN = 'is_logged_in';

    public $defaultImageUrl = 'media/user-logo.png';

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

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->isLoggedIn()) {
            $lastName = $_SESSION[self::SESSION_KEY_USER_DATA]['lastname'];
            $firstName = $_SESSION[self::SESSION_KEY_USER_DATA]['firstname'];
            return $firstName . " " . $lastName;
        } else {
            return "";
        }
    }

    /**
     * @param string $fieldName
     * @return string|null
     */
    public function getAccountData(string $fieldName)
    {
        return $_SESSION[self::SESSION_KEY_USER_DATA][$fieldName];
    }

    /**
     * @return string
     */
    public function getLogoUrl()
    {
        $url = $this->defaultImageUrl;

        if ($this->isLoggedIn()) {
            $userLogoUrl = $_SESSION[self::SESSION_KEY_USER_DATA]['logo_img_url'];
            if ($userLogoUrl) {
                $url = $userLogoUrl;
            }
        }

        $config = new Config();
        $baseUrl = $config->getBaseUrl();

        return $baseUrl . '/' . $url;
    }
}