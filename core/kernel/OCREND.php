<?php
/***********************************************
 * Write by: eduardo Date: 7/11/16 Time: 03:25 PM
 * Ocrend-Framework
 */

namespace Ocrend\Core\Kernel;


interface OCREND
{
    /**
     * Interfaz implementada para TODOS los modelos sin excepción, que requieran interacción
     * alguna con la base de datos
     */
    public function __construct();
    public function __destruct();


}