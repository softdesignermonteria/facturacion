<?php

/**
 * Kumbia Enterprise Framework
 *
 * LICENSE
 *
 * This source file is subject to the New BSD License that is bundled
 * with this package in the file docs/LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kumbia@kumbia.org so we can send you a copy immediately.
 *
 * @category 	Kumbia
 * @package 	Bootstrap
 * @copyright	Copyright (c) 2008-2011 Louder Technology COL. (http://www.loudertechnology.com)
 * @license 	New BSD License
 * @version 	$Id: index.php,v c4aa3976a4aa 2011/08/03 06:05:30 andres $
 */

//Path donde la aplicación está instalada

//define('KEF_ABS_PATH', getcwd().'/');
if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){ 
	define('KEF_ABS_PATH', $_SERVER['DOCUMENT_ROOT']."/facturacion/");
}else{
	define('KEF_ABS_PATH', $_SERVER['DOCUMENT_ROOT']."/");
}

//Indica si se debe usar el framework rápido ó el de debug
define('KEF_OFAST', false);
