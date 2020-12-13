<?php
namespace App\Controller\Admin\Users;

use App\Controller\Admin\AdminHttpController;

class EditForm extends AdminHttpController
{
    public function run()
    {
        include_once ROOT_DIR . "/templates/admin/users/edit/page.php";
    }
}
