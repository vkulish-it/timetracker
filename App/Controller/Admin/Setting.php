<?php
namespace App\Controller\Admin;

class Setting extends AdminHttpController
{
    public function run()
    {
        include_once ROOT_DIR . "/templates/admin/setting/page.php";
    }
}
