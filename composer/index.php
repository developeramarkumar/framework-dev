<?php	

define('__ROOT__', $_SERVER['DOCUMENT_ROOT'].'/composer/');

require_once (__DIR__.'/bootstrap/autoload.php');
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'icaps',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->bootEloquent();


use \model\User;
use Illuminate\Http\Request;
foreach (User::all() as $data) {
	echo @$data->abc->user_id;
}
$request = Request::capture();
if ($request->isMethod('post')) {
	var_dump($request->all());
}

?>
<form method="post" enctype="multipart/form-data">
	<input type="text" name="asd"  value="">
	<input type="file" name="amar">
	<button>sfds</button>
</form>
// echo Request::fullUrl();

