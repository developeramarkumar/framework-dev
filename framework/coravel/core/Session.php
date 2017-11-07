<?php
/**
 * Created by Chris on 9/29/2014 3:56 PM.
 */
namespace Core;

class Session {
   
    public static function exists($name) {
        @session_start(); 
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function put($name, $value) {
        @session_start(); 
        return @$_SESSION[$name] = $value;
    }

    public static function get($name) {
        @session_start(); 
        return @$_SESSION[$name];
    }

  
    public static function withFlash ($name, $string = 'null') {  
        @session_start(); 
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $_SESSION['_flash'][$key]=$value;
            }
        }
        else{
            $_SESSION['_flash']=[$name => $string];
        }
            
    }
    public static function flash($name) {  
        @session_start();
        
        if(@$_SESSION['_flash'][$name]){
            $flash = $_SESSION['_flash'][$name];  
            if (isset($_SESSION['_flash'])) {
                unset($_SESSION['_flash'][$name]);
            }                   
            return $flash;
        }
    }
    public static function checkFlash ($name) {  
        @session_start();
        if(@$_SESSION['_flash'][$name]){
            return true;
        }
    }
   public static function setAuth($guard,$id){
        @session_start();
        @$_SESSION['_auth']=[$guard=>['id'=>$id,'url'=>\Config::get('app.url')]];
        return true;
    }
    public static function getAuth($guard){
        @session_start();
        if (@$_SESSION['_auth'][$guard]['url'] == \Config::get('app.url')) {
           return @$_SESSION['_auth'][$guard];
        }
        
    }
    public static function deleteAuth($guard){
        @session_start();
        unset($_SESSION['_auth'][$guard]);
        return true;
    }
    public static function delete($name){
        @session_start(); 
        if(is_array($name)) {
            foreach ($name as $key => $value) {
               if (@$_SESSION['_'.$value]) {
                 unset($_SESSION['_'.$value]);
               }
            }
        }
        else{
            if (self::exists($name)) {
                unset($_SESSION['_'.$value]);
            }
        }
    }
    public static function withError($name,$string=null){
        @session_start(); 
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $_SESSION['_error'][$key]=$value;
            }
        }
        else{
            $_SESSION['_errors']=[$name => $string];
        }
    }
    public static function error($key){
        @session_start();
        if (isset($_SESSION['_error'][$key])) {
            $value = $_SESSION['_error'][$key];
            return $value;
        }
        return '';
    }
    public static function withInput($name,$string=null){
        @session_start();         
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $_SESSION['_old'][$key]=$value;
            }
        }
        else{
            $_SESSION['_old']=[$name => $string];
        }
    }
    public static function old($key){
        @session_start();
        if (isset($_SESSION['_old'][$key])) {
            $value = $_SESSION['_old'][$key];
            return $value;
        }
        return '';
    }


}