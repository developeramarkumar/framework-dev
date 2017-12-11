<?php 
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Container\Container;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Auth\Access\Gate;
if (! function_exists('action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true)
    {
        return app('url')->action($name, $parameters, $absolute);
    }
}

// if (! function_exists('app')) {
//     function app($abstract, $data=null){
//         return \Illuminate\Support\Facades\Facade::getFacadeApplication()->$abstract;
//     }
// }
if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $abstract
     * @param  array   $parameters
     * @return mixed|\Illuminate\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
    	// return Container:
    	// return  \Illuminate\Support\Facades\Facade::getFacadeApplication();
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return empty($parameters)
            ? Container::getInstance()->make($abstract)
            : Container::getInstance()->makeWith($abstract, $parameters);
    }
}

function base_url($path=null){
    return $_SERVER["HTTP_HOST"].$path;
}

function old($value){
  return Core\Session::old($value);
}
function error($value){
  return Core\Session::error($value);
}

// $errors = Collect(@$_SESSION['_flash']);
if (! function_exists('extend')) {
    function extend($layout){
        require_once __DIR__.'/../'.$layout;
    }
}
if (! function_exists('view')) {
    function view($page, array $data=array()){
        extract($data);
        $page = str_replace('.', __DS__, $page).'.php';
        // dd($page);
        try {
          return include_once __DIR__.'/../view/'.$page;
        } catch (Exception $e) {
          if ($e) {
             return response('<h1 style="text-align:center; margin-top:300px; font-weight:200; color:#888">Sorry, the page you are looking for could not be found. </h1>',$status = 400 );
          }
        }
        
    }
}
if (! function_exists('can')) {
    function can($action){
       return app($action)->authorize();
    }
}
if (! function_exists('gate')) {
    function gate($action){
        $request2 = app($action);
        $request2->setContainer(app());
        return $request2->validate();
    }
}
if (! function_exists('policy')) {
    /**
     * Get a policy instance for a given class.
     *
     * @param  object|string  $class
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    function policy($class)
    {
        return app(Gate::class)->getPolicyFor($class);
    }
}
if (! function_exists('load')) {
    function load($page){
        $page = str_replace('.', __DS__, $page).'.php';
        include_once __DIR__.'/../view/'.$page;
    }
}
if (! function_exists('assets')) {
    function assets($page){
        // $page = str_replace('.', __DS__, $page);
        return base_url($page);
    }
}
if (! function_exists('url')) {
    function url($url){
      return base_url($url);
    }
}
// if (! function_exists('request')) {
//     function request($request=null){
//         if ($request) {
//             return $session =  \Illuminate\Http\Request::createFromGlobals()->$request;
//         }
//         return $session =  \Illuminate\Http\Request::createFromGlobals();
      
//     }
// }
// if (! function_exists('redirect')) {
//     function redirect($url=null){
//        return new Core\Redirect($url);      
//     }
// }
if (! function_exists('redirect')) {
    /**
      * Get an instance of the redirector.
      *
      * @param  string|null  $to
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
     
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {

        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (! function_exists('response')) {
  function response($content = '', $status = 200, array $headers = array()){
        return new Response($content, $status, $headers);
    }
}

if (! function_exists('json')) {
     function json($data = array(), $status = 200, array $headers = array(), $options = 0)
    {
        if ($data instanceof ArrayableInterface)
        {
            $data = $data->toArray();
        }
        return new JsonResponse($data, $status, $headers, $options);
    }
  }
if (! function_exists('action')) {
     function action($user, $params=null, array $action=array())
    {
       $user = Core\Auth::guard('admin')->user();
       foreach ($user->permissions as $permission) {
          
       }
       return response();
        
    }
}
if (! function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}
if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        return app('url')->route($name, $parameters, $absolute);
    }
}
if (! function_exists('back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int    $status
     * @param  array  $headers
     * @param  mixed  $fallback
     * @return \Illuminate\Http\RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false)
    {
        return app('redirect')->back($status, $headers, $fallback);
    }
}
if (! function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}
if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}
if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @return string
     */
    function decrypt($value)
    {
        return app('encrypter')->decrypt($value);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @return string
     */
    function encrypt($value)
    {
        return app('encrypter')->encrypt($value);
    }
}
if (! function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    function event(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

if (! function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param  string  $method
     * @return \Illuminate\Support\HtmlString
     */
    function method_field($method)
    {
        return '<input type="hidden" name="_method" value="'.$method.'">';
    }
}

if (! function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return app('request')->old($key, $default);
    }
}
if (! function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}
if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        return data_get(app('request')->all(), $key, $default);
    }
}
if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param  string  $content
     * @param  int     $status
     * @param  array   $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = app(ResponseFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}
if (! function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}
if (! function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = app(ValidationFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}
