<?php	

define('DS', DIRECTORY_SEPARATOR);
define('_ROOT_', $_SERVER['DOCUMENT_ROOT'].'/oops/');
require_once (_ROOT_. 'bootstrap/bootstrap.php');
ini_set('error_log',_ROOT_.'tmp'.DS.'logs'.DS.'error.log');
