<?php
/***********************************************************************+
 * Ocrend-Framework
 * Licence MIT
 */

namespace Ocrend\Core\Kernel;
use Ocrend\Core\Kernel\Helpers\Strings;
use Ocrend\Core\Kernel\Helpers\Arrays;

# Seguridad

defined('INDEX_DIR') OR exit('Ocrend software says .i.');

//------------------------------------------------

class Router {

  //------------------------------------------------


  public $dir = __ROOT__;
  private $defaultController = 'homeController';
  private $url = null;
  private $controller = null;
  private $method = null;
  private $id = null;

  //------------------------------------------------

  private $routes = array(
    '/controller' => 'alphanumeric',
    '/method' => 'none',
    '/id' => 'int'
  );

  //------------------------------------------------

  /**
    * Constructor, inicializa las Rutas y obtiene los parámetros principales pasados por la URL de forma amigable.
    * Todas las variables pasadas por la URL de forma amigable, son independientes y no forman parte del arreglo $_GET
    *
    * @param string $DATABASE, se pasa de forma opcional una base de datos distinta a la definida en DATABASE['name'] para conectar
    *
    * @return void
  */
  public function __construct() {

    Helper::load('strings');
    $this->url = $this->resolveUrl();
    if(!empty($this->url) and $this->url != $this->dir) {
      $this->url = explode('/',$this->url);
      $this->controller = $this->resolveController();
      $this->method = $this->resolveMethod();
      $this->id = $this->resolveId();
    } else {
      $this->controller = $this->defaultController;
    }

  }

  /**
   * @return string
   */
  private function resolveController()
  {
    return Strings::alphanumeric($this->url[0]) ? strtolower( $this->url[0] ) . 'Controller' : 'homeController';
  }

  /**
   * @return string
   */
  private function resolveMethod()
  {
    return array_key_exists(1,$this->url) ? $this->url[1] : null;
  }

  /**
   * @return string
   */
  private function resolveId()
  {
    return array_key_exists(2,$this->url) ? $this->url[2] : null;
  }

  /**
   * @return array|string
   */
  private function resolveUrl()
  {
    $url = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    if($this->dir == '/' and strlen($url) >= strlen($this->dir)) {
      $url[0] = '';
    } else {
      $url = explode($this->dir,$this->url);
      $url = $url[1];
    }
    return $url;
  }

  //------------------------------------------------

  /**
    * Retorna el controlador actual
  */
  public function getController() : string {
    return trim($this->controller);
  }

  //------------------------------------------------

  /**
    * Retorna el método actual
  */
  public function getMethod() {
    return $this->method;
  }

  //------------------------------------------------

  /**
   * Retorna el id actual
  */
  public function getId() {
    return $this->id;
  }

  //------------------------------------------------

  /**
    * Crea una ruta de URL Amigable
    *
    * @param string $name: Nombre de la ruta
    * @param string $type: Regla de filtrado de la ruta
    *
  */
  public function setRoute(string $name, string $type = 'alphanumeric') {
    if(!in_array($type,['letters','int','float','none'])) {
      $type = 'alphanumeric';
    }
    $this->routes[$name] = $type;
  }

  //------------------------------------------------

  /**
    * Devuelve el valor que esté entrando por la ruta seleccionada, creada con setRoute()
    *
    * @param string $name: Nombre de la ruta
    *
    * @return string contenido de la ruta o null si no cumple la regla definida con setRoute() para esa ruta
  */
  public function getRoute(string $name) {

    $index = Arrays::get_key_by_index($name,$this->routes);

    if(!is_array($this->url)) $this->url = array();

    if($index >= 3 and array_key_exists($index,$this->url)) {
      switch ($this->routes[$name]) {
        case 'alphanumeric':
          return Strings::alphanumeric($this->url[$index]) ? strtolower($this->url[$index]) : null;
        break;
        case 'letters':
          return Strings::only_letters($this->url[$index]) ? strtolower($this->url[$index]) : null;
        break;
        case 'int':
          return is_numeric($this->url[$index]) ? (int) $this->url[$index] : null;
        break;
        case 'float':
          return is_numeric($this->url[$index]) ? (float) $this->url[$index] : null;
        break;
        default:
          return $this->url[$index];
        break;
      }
    }

    return null;
  }

}


