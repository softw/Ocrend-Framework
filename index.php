<?php

/**
  * Ocrend Framework - MVC Architecture for Web Applications
  * PHP Version 7
  * @package Ocrend Framework
  * @version v1.1.2
  * @author Brayan Narváez (Prinick) <prinick@ocrend.com> <youtube.com/user/prinick96>
  * @copyright 2016 - Ocrend Software
  * @license	http://opensource.org/licenses/MIT	MIT License
  * @link http://framework.ocrend.com/
*/

//------------------------------------------------
require('vendor/autoload.php');
# Carga del núcleo
define('INDEX_DIR',true);
require('core/app_core.php');

//------------------------------------------------

# Detección del controlador actual
$controller = $router->getController();

//------------------------------------------------

# Identificación del controlador en el sistema
if(!is_readable('app/controllers/' . $controller . '.php')) {
  $controller = 'errorController';
}

$controllerName = CONTROLLERS_NS.$controller;
new $controllerName();

//------------------------------------------------

# Modo debug
!DEBUG ?: new Debug($startime);

