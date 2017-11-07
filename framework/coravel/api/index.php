<?php 
include_once(__DIR__.'/../bootstrap/autoload.php');
include_once (__DIR__.'/../routes/api.php');

$response = $ioc['router']->dispatch($request);
$response->send();