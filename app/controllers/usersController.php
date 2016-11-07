<?php

namespace App\Controllers;
use Ocrend\Core\Kernel\Controllers;

class usersController extends Controllers
{

    public function __construct() {
        parent::__construct();
        echo $this->template->render('user/show');
    }
}