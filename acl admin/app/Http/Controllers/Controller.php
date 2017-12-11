<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Contracts\ArrayableInterface;
use \Redirect;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // public function validate($data,$rules){
    // 	$loader = new \Illuminate\Translation\FileLoader(new \Illuminate\Filesystem\Filesystem, './lang/');
	   //  $translator = new \Illuminate\Translation\Translator($loader, 'en');
	   //  $presence = new \Illuminate\Validation\DatabasePresenceVerifier($ioc['db']->getDatabaseManager());
	   //  $validation = new \Illuminate\Validation\Factory($translator, $ioc);
	   //  $validation->setPresenceVerifier($presence);	   
	   //  $validator = $validation->make($data, $rules);
	   //  if ($validator->fails()) {
    //     	return $errors = new Illuminate\Support\Fluent($errors->messages());;
    // 	}
	    
    // }
    public function validate($data,$rules,array $message=array()){
    	$validator = \Validator::make($data,$rules,$message);
	    if ($validator->fails()) {
        	 $validateErrors = $validator->errors()->messages();
        	 foreach ($validateErrors as $key => $value) {
        	 	$errors[$key]=$value[0];
        	 }
        	 return $errors;
        	// dd($errors->all());
    	}
    }
    public function response($content = '', $status = 200, array $headers = array()){
        return new Response($content, $status, $headers);
    }
    public function json($data = array(), $status = 200, array $headers = array(), $options = 0)
    {
        if ($data instanceof ArrayableInterface)
        {
            $data = $data->toArray();
        }
        return new JsonResponse($data, $status, $headers, $options);
    }

}
