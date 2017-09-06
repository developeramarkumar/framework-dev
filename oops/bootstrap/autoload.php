<?php
namespace bootstrap;
define('_DS_', DIRECTORY_SEPARATOR);
ini_set('error_log',_ROOT_.'storage'._DS_.'logs'._DS_.'error.log');

require_once __DIR__.'/../vendor/autoload.php';
spl_autoload_register(function($class) {
		require_once  $class.'.php';    
});