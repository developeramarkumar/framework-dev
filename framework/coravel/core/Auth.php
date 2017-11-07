<?php
namespace Core;
use \App\Model\User;
use \App\Model\Admin;
use \Core\Session;
use \Request;
class Auth
{
	protected $_auth = false; 
	protected $_user = null;
	protected $_guard = null;
	function __construct($guard)
	{
		$this->_guard = $guard;
		if (Session::getAuth($guard)) {
			if ($guard == 'user') {
				$this->_auth = true;
				$this->_user = User::find(Session::getAuth($guard)['id']);
			}	
			if ($guard == 'admin') {
				$this->_auth = true;
				$this->_user = Admin::find(Session::getAuth($guard)['id']);
			}			
		}	
		
	}
	
	public function user(){
		return $this->_user;
	}
	public static function guard($guard){
		return new Auth($guard);
	}
	public function check(){
		if ($this->_user) {
			return count($this->_user);
		}
		Session::deleteAuth($this->_guard);
		return false;
		

	}
	public function attempt($response){
		foreach ($response as $key => $value) {
			$array = array($key=>$value);
			break;
		}
		$user = User::where($array);
		if ($user->count()) {
			if (\Hash::check($response['password'], $user->first()->password)) {
				return $this->login($user->first());
			}		
		}
		
			
	}
	public function login(User $user){
		if ($user) {
			Session::setAuth($this->_guard,$user->id);
			$this->_auth = true;
			$this->_user = $user;			
		}
		return $this->_user;
	}
	public function loginById($userid){
			if ($this->_guard == 'user') {
				$user = User::find($userid);
			}	
			if ($this->_guard == 'admin') {
				$user = Admin::find($userid);
			}	
		if ($user) {
			Session::setAuth($this->_guard,$user->id);
			$this->_auth = true;
			$this->_user = $user;			
		}
		return $this->_auth;
	}
	public static function api(){

		if (\Request::input('user_id')) {
			@$id = \Crypt::decrypt(\Request::input('user_id'));
			if ($id) {
				return User::find($id);
			}
		}
	}
	public function logout(){
		Session::deleteAuth($this->_guard);
		return true;
	}
}