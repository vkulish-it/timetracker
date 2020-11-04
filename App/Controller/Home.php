<?php

namespace App\Controller;

use App\Models\User;

class Home
{
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function run() {
        if ($this->user->isLoggedIn()) {
            // @todo fix after tracker page is done
            include_once ROOT_DIR . "/tracker.php";
        } else {
            include_once ROOT_DIR . "/templates/login/page.php";
        }
    }
}
