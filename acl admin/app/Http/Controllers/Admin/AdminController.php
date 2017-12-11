<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Role;
use core\Auth;
use Illuminate\Http\Request;
class AdminController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();
        return view('admin.admin.list',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles=\App\Model\Role::get(); 
        return view('admin.admin.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $validate = $this->validate(request()->all(),[
        'name' => 'required|unique:admins',
        'email'=> 'required|unique:admins',
        'mobile' =>'required|numeric',
        'password' =>'required',
         'role'    =>'required',      
        ]);
        if ($validate) {
           return  redirect()->back()->with($validate)->withInput(request()->all());
        }
       $admin=new Admin;

       $admin->name=$request->name;
       $admin->email=$request->email;   
       $admin->contact=$request->mobile;
       $admin->password=bcrypt($request->password);
       $admin->role_id=$request->role;
       if($admin->save())
       {
          return redirect()->back()->with(['status'=>'success','message'=>'successfully Added']);
       }
       else{
         return redirect()->back()->with(['status'=>'error','message'=>'something went wrong']);
       }
      

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    
        $roles=Role::all(); 
        $admin = Admin::find($id);
        return view('admin.admin.edit',compact('admin','roles'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $user = Admin::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->contact=$request->mobile;
        if ($request->password) {
             $user->password=bcrypt(request('password'));
        }
        $user->role_id=$request->role;
        if($user->save())
        {
            return redirect()->back()->with(['status'=>'success','message'=>'successfully updated']);
        

        }
        else{
            return redirect()->back()->with(['status'=>'error','message'=>'something went wrong']);
            
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect()->back()->with(['status'=>'success','message'=>'admin deleted successfully']);
        
    }

}