<?php
namespace App\Controller;

use App\Controller\HttpController;

class ContactUsForm extends HttpController
{
    public function run() {
        include ROOT_DIR . "/templates/contact-us/page.php";
    }
}
