<?php 
include_once(__DIR__.'/../bootstrap/autoload.php');
include_once (__DIR__.'/../routes/web.php');

$response = $ioc['router']->dispatch($request);
$response->send();