<?php

namespace App\Http\Controllers\Admin;

use App\Model\Role;
use App\Model\Permission;
use App\Model\PermissionRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use \Auth;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       gate('read');
       $roles = Role::all();
       return view('admin.role.list',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        gate('create');
        return view('admin.role.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        gate('create');
        $validate = $this->validate(request()->all(),[
        'name' => 'required|unique:roles',
        
        ]);
        if ($validate) {
            redirect()->back()->with($validator)->withInput(request()->all());
        }
        $role = new Role;
        $role->name = request('name');
        if ($role->save()) {
            
            return redirect()->back()->with(['status'=>'success','message'=>'successfully Added']);
            
        }
        else{
            return redirect()->back()->with(['status'=>'error','message'=>'something went wrong']);
          
        }  
        
        //Role::create(['name'=>$request('name')]) ; 
       // dd(request()->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        gate('read');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        gate('update');
        $role = Role::find($id);
        $permissions = Permission::all()->groupBy('table_name');
        return view('admin.role.edit',compact('role','permissions'));
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
        gate('update');
        $role = Role::find($id);
        PermissionRole::where(['role_id'=>$role->id])->delete();
        if (request('permissions')) {
            foreach (request('permissions') as $key => $value) {
                $permission = new PermissionRole;
                $permission->role_id = $id;
                $permission->permission_id=$value;
                $permission->save();
            }
        }
        return redirect()->back()->with(['status'=>'success','message'=>'Permission alined success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        gate('delete');
    }
}
