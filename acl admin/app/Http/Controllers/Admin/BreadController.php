<?php
namespace App\Http\Controllers\Admin;

use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables =  \DB::select('SHOW TABLES');
        $menus = Menu::all();
        return  view('admin.menu.list',compact('menus','tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($table)
    {
        return view('admin.bread.create',compact('table'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($table)
    {
        $menu = Menu::firstOrNew(['table_name'=>$table]);
        $menu->name =  request('name');
        $menu->table_name =  $table;
        $menu->icon =  request('icon');
        $menu->slug =  request('slug');
        $menu->controller =  request('controller');
        $menu->save();
        $i=0;
        foreach (['create',  'read', 'update', 'delete'] as $key=>$value) {
            $permission = new Permission;
            $permission->menu_id = $menu->id;
            $permission->name = $value.'_'.str_replace(' ', '_', strtolower($menu->slug));
            $permission->table_name = $table;
            $permission->menu_name = $menu->name;
            $permission->save();
        }
        return redirect()->route('dashboard.index')->with(['status'=>'success','message'=>'Successfully created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($table)
    {
        $menu = Menu::where(['table_name'=>$table])->first();
        return view('admin.bread.edit',compact('menu'));
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
        
        $bread=Menu::find($id);
        $bread->name=$request->name;
        $bread->slug=$request->slug;
        $bread->icon=$request->icon;
        $bread->controller=$request->controller;
        if($bread->save())
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
    public function destroy($table)
    {
        $menu = Menu::where('table_name',$table);
        if ($menu->count()) {
           
            $permission = Permission::where('menu_id',$menu->first()->id);
            foreach ($permission->get() as $perm) {
                PermissionRole::where('permission_id',$perm->id)->delete();
            }
            $permission->delete();
            $menu->delete();
        }
        return redirect()->back()->with(['status'=>'success','message'=>'Bread deleted successfully']);
        
    }

}
