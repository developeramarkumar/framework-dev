<?php
error_reporting( 0 );
ini_set('display_errors', 0);
require_once __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/illuminate/support/helpers.php';
require_once __DIR__.'/../core/helpers.php';
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
	try {
        include_once  __DIR__.'/../'.$class.'.php';
      } catch (Exception $e) {
        
      }    
});
date_default_timezone_set('Asia/Kolkata');

if ($app['debug']==true) {
    error_reporting( E_ALL );
    ini_set('display_errors', 1);
}
if ($app['env'] == 'local') {
  $whoops = new \Whoops\Run;
  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
  $whoops->register();
}

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Contracts\ArrayableInterface;

$request = Illuminate\Http\Request::createFromGlobals();

$ioc = new \Illuminate\Container\Container;
$ioc->singleton('config', 'Illuminate\Config\Repository');
// $ioc['app'] = $app;
$ioc->instance('app', $ioc); // optional

$ioc['env'] = $app['env'];
$ioc['config']->set($config);
$ioc['config']['app'] = $app;
$ioc['config']['auth'] = $auth;


// $ioc->bind('app', $ioc); // optional
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
$cookie = new  Symfony\Component\HttpFoundation\Cookie(
    $ioc['session']->getName(),
    $ioc['session']->getId(),
    time() + ($ioc['config']['session.lifetime'] * 60),
    '/',
    null,
    false
);
setcookie(
    $cookie->getName(),
    $cookie->getValue(),
    $cookie->getExpiresTime(),
    $cookie->getPath(),
    $cookie->getDomain()
);
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
$ioc->instance('request', \Illuminate\Http\Request::capture());
$ioc->instance('FormRequest', Illuminate\Foundation\Http\FormRequest::capture());
$ioc->instance('UpdateRequest', App\Http\Requests\UpdateRequest::capture());
// $ioc->singleton()
$ioc->singleton(
    Illuminate\Contracts\Filesystem\Factory::class,
    function ($app) {
        return new Illuminate\Filesystem\FilesystemManager($app);
    }
);
 $ioc->singleton('filesystem', function ($app) {
            return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider');
});
// $ioc->resolving(function (Illuminate\Foundation\Http\FormRequest $request, $ioc) {
//         // [.... custom functions to call on request here ....]
//     });
$ioc->alias('filesystem', 'Illuminate\Filesystem\FilesystemManager');
$ioc->alias('request', 'Illuminate\Http\Request');
$ioc->alias('request', 'Symfony\Component\HttpFoundation\Request');
$ioc->alias('FormRequest', 'Illuminate\Foundation\Http\FormRequest');
$ioc->alias('UpdateRequest', 'App\Http\Requests\UpdateRequest');
$ioc->alias('app', 'Illuminate\Foundation\Application');
$ioc->alias('app', 'Illuminate\Contracts\Container\Container');
$ioc->alias('app', 'Illuminate\Contracts\Foundation\Application"');
$ioc->alias('app', 'Psr\Container\ContainerInterface"');
$ioc->alias('config', 'Illuminate\Config\Repository');
$ioc->alias('config', 'Illuminate\Contracts\Config\Repository');
$ioc->alias('encrypter', 'Illuminate\Contracts\Encryption\Encrypter');
$ioc->alias('files', 'Illuminate\Filesystem\Filesystem');
$ioc->alias('redirect', 'Illuminate\Routing\Redirector');
$ioc->alias('router', 'Illuminate\Routing\Router');
$ioc->alias('router', 'Illuminate\Contracts\Routing\Registrar');
$ioc->alias('router', 'Illuminate\Contracts\Routing\BindingRegistrar');
$ioc->alias('url', 'Illuminate\Contracts\Routing\UrlGenerator');
$ioc->alias('validator', 'Illuminate\Validation\Factory');
$ioc->alias('validator', 'Illuminate\Contracts\Validation\Factory');
$ioc->alias('session', 'Illuminate\Validation\Factory');
$ioc->alias('session.store', 'Illuminate\Session\Store');
$ioc->alias('session.store', 'Illuminate\Contracts\Session\Session');
$ioc->alias('auth', 'Illuminate\Auth\AuthManager');
$ioc->alias('auth', 'Illuminate\Contracts\Auth\Factory');
$ioc->alias('auth.driver', 'Illuminate\Contracts\Auth\Guard');
$ioc->alias('auth.driver', 'Illuminate\Contracts\Auth\Factory');
$ioc->alias('config', 'Illuminate\Config\Repository');
$ioc->alias('config', 'Illuminate\Contracts\Config\Repository'); 
$ioc->alias('cookie', 'Illuminate\Cookie\CookieJar'); 
$ioc->alias('cookie', 'Illuminate\Contracts\Cookie\Factory'); 
$ioc->alias('cookie', 'Illuminate\Contracts\Cookie\QueueingFactory'); 
$ioc->alias('db', 'Illuminate\Database\DatabaseManager'); 
$ioc->alias('db.connection', 'Illuminate\Database\Connection'); 
$ioc->alias('db.connection', 'Illuminate\Database\ConnectionInterface'); 
with (App\Providers\AppServiceProvider::class);
with(\Illuminate\Foundation\Providers\FormRequestServiceProvider::class);
with(\Illuminate\Foundation\Providers\FoundationServiceProvider::class);
with(Intervention\Image\ImageServiceProvider::class);


// with(Overtrue\LaravelShoppingCart\ServiceProvider::class);
with (new \App\Providers\AppServiceProvider($ioc))->register();
with (new \Illuminate\Events\EventServiceProvider($ioc))->register();
with (new \Illuminate\Routing\RoutingServiceProvider($ioc))->register();
with (new Illuminate\Translation\TranslationServiceProvider($ioc))->register();
with (new \Illuminate\Validation\ValidationServiceProvider($ioc))->register();
with (new \Illuminate\Encryption\EncryptionServiceProvider($ioc))->register();
with (new \Illuminate\Filesystem\FilesystemServiceProvider($ioc))->register();
with(new Illuminate\Hashing\HashServiceProvider($ioc))->register();
with (new \Illuminate\Auth\AuthServiceProvider($ioc))->register();
\Illuminate\Foundation\AliasLoader::getInstance($app['aliases'])->register();
\Illuminate\Support\Facades\Facade::setFacadeApplication($ioc);

Illuminate\Container\Container::setInstance($ioc['app']);
$_SERVER["HTTP_HOST"] =$app['url'];
