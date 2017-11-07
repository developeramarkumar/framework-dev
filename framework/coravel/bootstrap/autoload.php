<?php
error_reporting( 0 );
ini_set('display_errors', 0);
require_once __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/illuminate/support/helpers.php';
$rootpath = $_SERVER["DOCUMENT_ROOT"].'/cashcoin';

$config = include_once(__DIR__.'/../config/database.php');
$app = include_once(__DIR__.'/../config/app.php');
$auth = include_once(__DIR__.'/../config/auth.php');
$session = include_once(__DIR__.'/../config/session.php');
$filesystem = include_once(__DIR__.'/../config/filesystems.php');
$basePath = str_finish(dirname(__FILE__), '/');

define('__DS__', DIRECTORY_SEPARATOR);

spl_autoload_register(function($class) {
	$class = str_replace("\\", __DS__, $class);
    $class = str_replace("/", __DS__, $class);
	require_once  __DIR__.'/../'.$class.'.php';    
});
date_default_timezone_set('Asia/Kolkata');

$request = Illuminate\Http\Request::createFromGlobals();


$request = \Illuminate\Http\Request::createFromGlobals();
$ioc = new \Illuminate\Container\Container;
$ioc->singleton('config', 'Illuminate\Config\Repository');
$ioc['app'] = $app;

$ioc['env'] = $ioc['app']['env'];
$ioc['config']->set($config);
$ioc['config']['app'] = $ioc['app'];
$ioc['config']['auth'] = $auth;

if ($ioc->env == 'local') {
   error_reporting( E_ALL );
  ini_set('display_errors', 1);
  $whoops = new \Whoops\Run;
  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
  $whoops->register();
}
$ioc['files'] = new \Illuminate\Filesystem\Filesystem;
$ioc['config']['filesystem'] = $filesystem;
$ioc['config']['session'] = $session;
// Now we need to fire up the session manager
$sessionManager = new Illuminate\Session\SessionManager($ioc);
$ioc['session.store'] = $sessionManager->driver();
$ioc['session'] = $sessionManager;
// session ID from the supplied cookie
$cookieName = $ioc['session']->getName();
if (isset($_COOKIE[$cookieName])) {
    if ($sessionId = $_COOKIE[$cookieName]) {
        $ioc['session']->setId($sessionId);
    }
}
// Boot the session
$ioc['session']->start();

$ioc['path'] = $rootpath;
$ioc->singleton('db',function() use($ioc){
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($ioc['config']['connections'][$ioc['config']['default']]);
    return $capsule;
});
$ioc['path.lang']=$ioc['path'].'/lang/';
$ioc['db']->setEventDispatcher(new \Illuminate\Events\Dispatcher($ioc));
$ioc['db']->setAsGlobal();
$ioc['db']->bootEloquent();
$ioc['db'] = $ioc['db']->getDatabaseManager(); 
$ioc->instance('request', \Illuminate\Http\Request::createFromGlobals());
$ioc->singleton(
    Illuminate\Contracts\Filesystem\Factory::class,
    function ($app) {
        return new Illuminate\Filesystem\FilesystemManager($app);
    }
);
 $ioc->singleton('filesystem', function ($app) {
            return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider');
});

$ioc->alias('filesystem', 'Illuminate\Filesystem\FilesystemManager');
with (App\Providers\AppServiceProvider::class);
with(\Illuminate\Foundation\Providers\FoundationServiceProvider::class);
with(Intervention\Image\ImageServiceProvider::class);
with(Darryldecode\Cart\CartServiceProvider::class);

// with(Overtrue\LaravelShoppingCart\ServiceProvider::class);
with (new \App\Providers\AppServiceProvider($ioc))->register();
with (new \Illuminate\Events\EventServiceProvider($ioc))->register();
with (new \Illuminate\Routing\RoutingServiceProvider($ioc))->register();
with (new Illuminate\Translation\TranslationServiceProvider($ioc))->register();
with (new \Illuminate\Validation\ValidationServiceProvider($ioc))->register();
with (new \Illuminate\Encryption\EncryptionServiceProvider($ioc))->register();
with (new \Illuminate\Filesystem\FilesystemServiceProvider($ioc))->register();
// with(new \Illuminate\Foundation\Providers\FoundationServiceProvider($ioc))->register();
// with(new \Illuminate\Encryption\EncryptionServiceProvider($ioc))->register();
with(new Illuminate\Hashing\HashServiceProvider($ioc))->register();
with (new \Illuminate\Auth\AuthServiceProvider($ioc))->register();
// with (new \App\Providers\RouteServiceProvider($ioc))->register();
\Illuminate\Foundation\AliasLoader::getInstance($app['aliases'])->register();
\Illuminate\Support\Facades\Facade::setFacadeApplication($ioc);
// dd($ioc->config['app.debug']);

$_SERVER["HTTP_HOST"] = $ioc->config['app.url'];
function base_url($path=null){
    return $_SERVER["HTTP_HOST"].$path;
}
function encrypt($value) {
  return  \Crypt::encrypt($value);;
};
function decrypt($value){
  return \Crypt::decrypt($value);
}
function old($value){
  return Core\Session::old($value);
}
function error($value){
  return Core\Session::error($value);
}
// dd($ioc['config']['filesystem']);
// dd(encrypt('hgfhf'));

// new \Illuminate\Encryption\Encrypter(\Config::get('app.key'));

// \CoinGate\CoinGate::config(array(
//   'environment' => 'sandbox', // sandbox OR live
//   'app_id'      => 'YOUR_APP_ID', 
//   'api_key'     => 'YOUR_API_KEY', 
//   'api_secret'  => 'YOUR_API_SECRET'
// ));

