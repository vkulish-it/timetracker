<?php
namespace App\Controller\Admin;

class Users extends AdminHttpController
{
    public function run()
    {
        include_once ROOT_DIR . "/templates/admin/users/page.php";
    }
}
