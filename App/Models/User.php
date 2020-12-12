<?php
namespace App\Models;

use App\Factory;
use \App\Models\Config;

class User
{
    const SESSION_KEY_USER_DATA = 'user';
    const SESSION_KEY_USER_IS_LOGGED_IN = 'is_logged_in';
    const SESSION_KEY_USER_MESSAGES = 'messages';
    const SESSION_KEY_USER_SETTINGS = 'settings';
    const SESSION_KEY_ADMIN_IS_LOGGED_IN = 'admin_is_logged_in';

    public $defaultImageUrl = 'media/img/user-logo-default.png';

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
        $this->updateSession();
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
        if ($_SESSION && array_key_exists(self::SESSION_KEY_USER_SETTINGS, $_SESSION)) {
            unset($_SESSION[self::SESSION_KEY_USER_SETTINGS]);
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
     * @return int|null
     */
    public function getId()
    {
        if ($this->isLoggedIn()) {
            return (int)$_SESSION[self::SESSION_KEY_USER_DATA]['id'];
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
     * @param string $fieldName
     * @return string|null
     */
    public function getAccountSettings(string $fieldName)
    {
        return $_SESSION[self::SESSION_KEY_USER_SETTINGS][$fieldName];
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

        $config = Factory::getSingleton(Config::class);
        $baseUrl = $config->getBaseUrl();

        return $baseUrl . '/' . trim($url, '/');
    }

    /**
     * Add message to user session
     *
     * @param string $message
     */
    public function addMessage(string $message)
    {
        if (array_key_exists(self::SESSION_KEY_USER_MESSAGES, $_SESSION)) {
            $_SESSION[self::SESSION_KEY_USER_MESSAGES][] = $message;
        } else {
            $_SESSION[self::SESSION_KEY_USER_MESSAGES] = [$message];
        }
    }

    /**
     * Return string with all messages together
     *
     * @return string
     */
    public function getMessage() {
        if (array_key_exists(self::SESSION_KEY_USER_MESSAGES, $_SESSION) && $_SESSION[self::SESSION_KEY_USER_MESSAGES]) {
            $messages = implode('. ', $_SESSION[self::SESSION_KEY_USER_MESSAGES]);
            unset($_SESSION[self::SESSION_KEY_USER_MESSAGES]);
            return $messages;
        } else {
            return "";
        }
    }

    public function updateSession() {
        $config = Factory::getSingleton(Config::class);
        $connection = new \mysqli($config->getDBHost(), $config->getDBUserName(), $config->getDBUserPassword(), $config->getDBName());
        $sqlCommand = "SELECT * FROM users WHERE users.id='" . $this->getAccountData('id') . "';";
        if ($result = $connection->query($sqlCommand)) {
            $userData = $result->fetch_object();
            if ($userData) {
                $_SESSION[self::SESSION_KEY_USER_DATA] = get_object_vars($userData);
            } else {
                $this->logout();
            }
        } else {
            $this->addMessage("Error while getting user data");
        }

        $sqlCommand = "SELECT * FROM settings WHERE settings.user_id='" . $this->getAccountData('id') . "';";
        if ($result = $connection->query($sqlCommand)) {
            $userSettings = $result->fetch_object();
            if ($userSettings) {
                $_SESSION[self::SESSION_KEY_USER_SETTINGS] = get_object_vars($userSettings);
            } else {
                $defaultSettings = $config->getDefaultAccountSettings();
                $defaultSettings['id'] = null;
                $defaultSettings['user_id'] = $this->getAccountData('id');
                $_SESSION[self::SESSION_KEY_USER_SETTINGS] = $defaultSettings;
            }
        } else {
            $this->addMessage("Error while getting user settings");
        }

        $connection->close();
    }

    /**
     * @return bool
     */
    public function isLoggedInAdmin()
    {
        if ($_SESSION && $_SESSION[self::SESSION_KEY_ADMIN_IS_LOGGED_IN] === true) {
            return true;
        }

        return false;
    }

    /**
     * @return $this
     */
    public function loginAdmin()
    {
        $_SESSION[self::SESSION_KEY_ADMIN_IS_LOGGED_IN] = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function logoutAdmin()
    {
        if ($_SESSION && array_key_exists(self::SESSION_KEY_ADMIN_IS_LOGGED_IN, $_SESSION)) {
            unset($_SESSION[self::SESSION_KEY_ADMIN_IS_LOGGED_IN]);
        }
        return $this;
    }

}