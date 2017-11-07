<?php
namespace Core;

use Core\Session;

class Redirect 
{
	private $_url = null;
	private $_with = null;

	public function __construct($url=null){
		$this->_url = $url;
	}
	public static function  back(){
		$url = $_SERVER['HTTP_REFERER'];
		return new Redirect($url);
	}
	public static function  url($url){
		return new Redirect($url);
	}
	public static function  to($url){
		return new Redirect($url);
	}
	public function  with($array){
		foreach ($array as $key => $value) {
			Session::flash($key,$value);
		}
		
		return $this;
	}

	public function __destruct(){
		header('location:'.$this->_url);
	}
}