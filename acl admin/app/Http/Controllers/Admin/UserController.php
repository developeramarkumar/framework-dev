<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\User;
use core\Auth;
use Illuminate\Http\Request;
class UserController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        gate('read');
        $users = User::all();
        return view('admin.user.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        gate('create');
        return view('admin.menu.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($table)
    {
        gate('create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        gate('read');
        $user = User::find($id);
        return view('admin.user.view',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        gate('update');
        $user = User::find($id);
        return view('admin.user.edit',compact('user'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        gate('update');
        $user = User::find($id);
        $user->fname=request('fname');
        $user->lname=request('lname');
        $user->mobile=request('mobile');
        $user->email=request('email');
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
        gate('delete');
        User::destroy($id);
        return redirect()->back();
        
    }

}