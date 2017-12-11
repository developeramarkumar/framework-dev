<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $page = array_first(explode('.', \Route::currentRouteName()));
        
        if(\App\Model\Permission::where('name','read_'.$page)->whereHas('permission',function($query){ $query->where('role_id',\Core\Auth::guard('admin')->user()->role_id);})->count())
         {
             return true;
        }
         return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
