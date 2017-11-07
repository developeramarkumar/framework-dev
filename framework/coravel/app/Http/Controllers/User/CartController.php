<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;

use App\Model\Transaction;
use Redirect;
use Core\Session;
use App\Model\User;
use App\Model\Support;
use Core\Auth;
use App\Model\Product;
// use Darryldecode\Cart;
use App\Http\Library\Cart;


class CartController extends Controller
{
    public function add($id){

      $pro = Product::find($id);



      $cart = new Cart;

      // return $cart->getItemById(array('id' => $id));
      // return $cart->destroy();

      // if ($cart->getItemById(array('id'=>$id))) {
      //   return 'ok';
      // }else{
      //   return 'false';
      // }

      // return $cart->contents();

      $cart->insert(array(
        'id'    =>  $pro->id,
        'name'  =>  $pro->name,
        'price' =>  $pro->price,
        'qty'   =>  1,
      ));

      return $cart->contents();

    }

    public function update(Request $request){
      return \Request::all();

      Cart::update(456, array(
        'name' => 'New Item Name', // new item name
        'price' => 98.67, // new item price, price can also be a string format like so: '98.67'
      ));


    }

    public function remove($id){

      $cart = new Cart;

      $cart->remove($id);

      return \Redirect::back();

    }
}
