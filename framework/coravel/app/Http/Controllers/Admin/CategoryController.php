<?php
namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Http\Controllers\Controller;
use \Request;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderBy('position','asc')->get();
        return view('admin.catalog.category.form',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMenu(){
        $categories = Category::orderBy('position','asc')->get();
        $cat = array();
        foreach ($categories as $cat2) {
            $cat[]=['id'=>$cat2->id,'text'=>$cat2->name,'a_attr'=>['href'=>'?id='.$cat2->ukey],'parent'=>($cat2->parent)?$cat2->parent:'#'];
        }
      
        return $this->json($cat);
    }
    public function create($data)
    {
        //
        // $this->validate($data,[
        //         'name'=>'required|max:191',
        // ]);
        // return Category::create([
        //     'name' -> $data->name,
        //     'title' -> $data->title,
        //     'image' -> $data->ascimage,
        //     'keywords' -> $data->keywords,
        //     'description' -> $data->description,
        //     'status' -> $data->status,
        // ]);
    }
    public function select($category){
        $category = Category::where('ukey',$category)->firstOrfail();
        return view('admin.catalog.category.form',compact('category'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // return Request::all();
        $validate = $this->validate(Request::all(),[
            'name'=>'required|max:191|unique:product_category',
        ]);
        if ($validate) {
             return $this->json(['errors'=>$validate],500);
        }
        $category = new Category();
        $category->name = Request::input('name');        
        $category->title = Request::input('title');
        $category->keywords = Request::input('keywords');
        $category->description = Request::input('description');
        $category->ukey = str_random(15);
        $category->parent = (Request::input('parent'))?Request::input('parent'):null;
        $category->status = Request::input('status');
          
        if($category->save()){
            return  $this->json(['status'=>'OK','message'=>'Category Created successfully.']);
        }
        return $this->json(['status'=>'error','message'=>'There was an problem to creating category.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }
    public function arrange(Request $request)
    {
        //
        $category = Category::find(Request::input('id'));
        $category->parent = (Request::input('parent') == '#')?null:Request::input('parent');
        $category->position = Request::input('position');
        $category->save();
        // $allCate = Category::count()-1;

        // $cat3 = Category::where('position',$request->position)->first()->id;
        // $cat2 = Category::count()-$cat3;
        // for($i=$cat3; $i<=$cat2; $i++){
        //     $cat4 = Category::find($i);
        //     $data[]= ['sn'=>$i,'name'=>$cat4->name,'id'=>$cat4->id];
        // }
        
        //     return $datas;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Product\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($ukey)
    {
        //
        $catEdit = Category::where('ukey',$ukey)->firstOrFail();
        return view('admin.catalog.category.form',compact('catEdit'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Product\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
        // return $category;
        $category = Category::find(Request::input('id'));
        if (!$category) {
           return  $this->json(['status'=>'error','message'=>'Category not found']);
        }
        $category->name = Request::input('name');
        $category->title = Request::input('title');
        $category->keywords = Request::input('keywords');
        $category->description = Request::input('description');
        $category->status = Request::input('status');
        if($category->save()){
            return  $this->json(['status'=>'OK','message'=>'Category updated']);
        }
        return  $this->json(['status'=>'error','message'=>'There was an problem to updating category.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}