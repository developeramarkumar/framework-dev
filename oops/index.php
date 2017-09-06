<?php	

define('_ROOT_', $_SERVER['DOCUMENT_ROOT'].'/oops/');

require_once (_ROOT_. 'bootstrap/autoload.php');



use \data\DB;
use \data\Mail;
$query = DB::table('users')->get();


var_dump($query);

// Mail::send();