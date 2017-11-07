<?php 
include_once(__DIR__.'/../bootstrap/autoload.php');
include_once (__DIR__.'/../routes/admin.php');
// $guard = Config::get('auth')['guards']['user'];
// $provider = \Config::get('auth')['providers'][Config::get('auth')['guards']['user']['provider']];
// dd($provider['model']);
$response = $ioc['router']->dispatch($request);
$response->send();