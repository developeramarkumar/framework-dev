<?php
namespace App\Http\Controllers\Admin;

use App\Model\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        gate('read');
        if ($request->ajax()) {
            $datas = Contact::select(['id','name','email','mobile','created_at']);
            $totaldata = $datas->count();
            if ($request->order[0] && $_GET['columns'][$_GET['order'][0]['column']]['name'] != 'action') {
                $datas->orderBy($_GET['columns'][$_GET['order'][0]['column']]['name'] ,$_GET['order'][0]['dir']);
            }
            $search = request('search')['value'];
            if ($search) {
                $datas->where('id', 'like', '%'.$search.'%')->orWhere('name', 'like', '%'.$search.'%')->orWhere('created_at', 'like', '%'.$search.'%');
            }
            # set datatable parameter 
            $result["length"]= $request->length;
            $result["recordsTotal"]= $totaldata;
            $result["recordsFiltered"]= $datas->count();
             $records = $datas->limit($request->length)->offset($request->start)->get();
            # fetch table records 
            $result['data'] = [];
            foreach ($records as $data) {
                 $result['data'][] =[$data->id,$data->name,$data->email,$data->mobile,$data->id];               
            }        
            return $result;                      
        }        
        return view('admin.contact.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        gate('read');
        return iew('admin.menu.add');
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        gate('delete');
        Contact::destroy($id);
        return json(['status'=>'success','message'=>'deleted sucessfully']);
        
    }

}
