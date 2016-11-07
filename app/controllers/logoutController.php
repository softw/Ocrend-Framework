<?php
namespace App\Controllers;
use Ocrend\Core\Kernel\Controllers;
use Ocrend\Core\Models\Sessions;
use Ocrend\Core\Models\Func;
# Seguridad
defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class logoutController extends Controllers {

  public function __construct() {
    parent::__construct();
    if(DB_SESSION) {
      (new Sessions)->check_life(true);
    } else {
      unset($_SESSION[SESS_APP_ID]);
      session_write_close();
      session_unset();
    }
    Func::redir();
  }

}

