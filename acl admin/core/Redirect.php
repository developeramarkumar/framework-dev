<?php 
namespace Core;

use Core\Session;
use \Redirect as illuminateRedirect;
class Redirect 
{
	private $url = ''; 
	public function __construct($url = null){
		error_reporting(0);
		ini_set('display_error', 0);
		$this->url=url($url);
	}
	public function back(){
		$this->url=$_SERVER['HTTP_REFERER'];
		return $this;
	}
	public function to($url){	
		$this->url=url($url);	
		return $this;
	}
	public function with($key,$value=null){	
		if (is_array($key)) {
			@session_start();
			$_SESSION['_error']=$key;
		}
		else{
			$_SESSION['_error'][$key]=$value;
		}
		return $this;
		
	}
	public function withInput($key,$value=null){	
		if (is_array($key)) {
			@session_start();
			$_SESSION['_old']=$key;
		}
		else{
			$_SESSION['_old'][$key]=$value;
		}
		return $this;
		
	}
	public function __destruct(){

		// ob_start(); //this should be first line of your page
		 echo @app('redirect')->to($this->url, $status = 302, $headers = [], $secure = null);
		// ob_end_flush(); //this should be last line of your page
		// die();
		// illuminateRedirect::to($this->url);
		// return $this->redirect();
		
	}
	private function redirect(){
		// header('location : '.$this->url)
		  return @app('redirect')->to($this->url, $status = 302, $headers = [], $secure = null);
		// return ;
	}
}