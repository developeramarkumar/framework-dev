<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use Core\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{
	public function login(Request $request){
		// dd(bcrypt('123456'));
		$validator = $this->validate(request()->all(),[
			'username' => 'required',
			'password'=>'required',
		]);
		if ($validator) {
			// $request->session()->flash('key', '$value');
			return redirect()->back()->with($validator)->withInput(request()->all());
		}
		
        if(Auth::guard('admin')->attempt(['email'=>request('username'),'password'=>request('password')])){

        	return redirect()->route('dashboard.index');
        }	
        return redirect()->back()->with(['username'=>'Invalid username or password'])->withInput(request()->all());
	}
	public function showLoginForm(){		
		return view('admin.login');
	}
	
	public function logout(){
		Auth::guard('admin')->logout();
		return redirect()->route('admin.login.form');
	}
	
	private function username(){
		if(is_numeric(Request::input('username'))){
			return 'email';
		}
		// return 'refer_id';
	}
	
    
}
