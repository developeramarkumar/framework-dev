<?php
// Route::group(['middleware' => 'user'], function() {
//    Route::get('user', function() {
//      echo "string";
//   });
// });

Route::group(['namespace' => 'App\Http\Controllers'], function() {
   
    Route::group(['middleware' => ['App\Http\Middleware\User\RedirectIfNotAuthenticated','App\Http\Middleware\TrimStrings']], function() {
        Route::post('/profile', 'User\ProfileController@profiledetail');
        // Route::post('/profile-submit', 'User\ProfileController@bankdetail');
        Route::post('/changepwd', 'User\ProfileController@changepwd');
        Route::post('/changetran', 'User\ProfileController@changetran');
        Route::post('/kyc-submit', 'User\ProfileController@kyc');
        Route::group(['prefix' => 'user'], function() {

            Route::post('add-bank-details', 'User\ProfileController@bankdetail');
            Route::post('frenchies-request', 'User\HelpDeskController@frenchiesRequest');
            Route::post('profile/picture', 'User\ProfileController@picture');


        });
        Route::post('/btc', 'User\ProfileController@btc');
        Route::post('/coinexchange', 'User\ProfileController@coinexchange');

        Route::post('add-benificery', 'User\DmtController@addBenificry');
        Route::post('add-benificery-verify', 'User\DmtController@benificryVerify');

        Route::get('get-benificry-detail/{id}', function($id) {
            return $ben = \App\Model\Beneficiary::find($id);
        });

        Route::post('transfer-mony', 'User\DmtController@dmtTransfer');

        Route::post('transfer-blue-to-coin', 'User\BlueToCoinController@SendOtp');

        Route::post('coin-user-to-user', 'User\UserToUserCoinController@SendOtp');
        Route::post('referral', 'User\ReferralController@get_direct_referral');
        Route::post('user-to-user-coin-verify', 'User\UserToUserCoinController@veryPassword');

        Route::post('user/bank-withdraw', 'User\BankWithDrawController@transfer');

        Route::post('top-up-wallet', 'User\topUpController@SendOtp');
        Route::post('top-up-wallet-verify', 'User\topUpController@veryPassword');


        Route::post('invite-friend', 'User\InviteFriendController@InviteFriend');
        Route::get('kyc-delete/{kyc_id}/{kyc_type}', function($kyc_id,$kyc_type){
            $data = \App\Model\Kyc::find($kyc_id);
            $data->$kyc_type = '';
            $data->save();
            return Redirect::back();
        });


        Route::get('self-generate', 'User\SelfGenerateController@selfGenerate');

        Route::post('user-helo-desk', 'User\HelpDeskController@store');

        Route::post('transfer-blue-to-coin-verify-otp', 'User\BlueToCoinController@veryPassword');

        Route::get('get-username/{id}', function($id) {
           $data = $data = \App\Model\User::where('refer_id',$id)->first();
           if ($data) {
               return json_encode(['status'=>200,'message'=>'success','data'=>$data->name]);
           }else{
            return json_encode(['status'=>400,'message'=>'User not found']);
           }
        });
        Route::group(['prefix' => 'user'], function() {
            Route::post('bank-deposite-slip', 'User\BankDepositeController@store');
        });

        Route::get('user/logout', function() {
            \Core\Auth::guard('user')->logout();
            return \Redirect::to('../login');
        });

    });

    Route::get('cart-add-product/{id}', 'User\CartController@add');

    Route::get('remove-cart-item/{id}', 'User\CartController@remove');


 Route::group(['middleware' => ['App\Http\Middleware\Admin\RedirectIfNotAuthenticated','App\Http\Middleware\TrimStrings']], function() {

    Route::get('admin-user-list', function() {
        return \App\Model\User::all();
    });

    Route::get('admin-contact-delete/{id}', function($id) {

        $d = \App\Model\Contact::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });
  Route::get('admin-product-delete/{id}', function($id) {

        $d = \App\Model\Product::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });
    Route::get('admin-franchisee-delete/{id}', function($id) {

        $d = \App\Model\Franchisee::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });


    Route::get('admin-news-delete/{id}', function($id) {

        $d = \App\Model\News::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });
    Route::get('admin-transaction-delete/{id}', function($id) {

        $d = \App\Model\Transaction::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });

    Route::get('admin-contact-alldelete', function() {

        $d = \App\Model\Contact::truncate();
        return Redirect::back();
        
    });

    Route::get('admin-contact-delete/{id}', function($id) {

        $d = \App\Model\Contact::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });

    Route::get('admin-support-alldelete', function() {

        $d = \App\Model\Support::truncate();
        return Redirect::back();
        
    });

    Route::get('admin-support-delete/{id}', function($id) {

        $d = \App\Model\Support::where(['id'=>$id])->first();
        $d->delete();
        return Redirect::back();
        
    });



    Route::resource('admin-user-list', "Admin\UserController@getList");


    Route::get('admin-user-status/{id}', function($id) {
        $data = \App\Model\User::find($id);
        if ($data->status=='1') {
          $data->status = '0';
        } else {
          $data->status = '1';
        }
        $data->save();
        return Redirect::back();

    });

    Route::post('get-user', function() {

        $id = \Request::input('id');
        $usr = \App\Model\User::select(['name','email','mobile'])->where('refer_id',$id)->first();
        if ($usr) {
            return array('status'=>200,'data'=>$usr);
        } else {
            return array('status'=>200,'message'=>'User not found');
        }
        
    });




    Route::post('admin-self-generate', 'Admin\SelfGenerateController@selfGenerate');

    Route::post('admin-user-detail-update/{id}', 'Admin\UserController@Update');

    Route::post('admin-add-coin-to-user', 'Admin\AddCoinController@addCoin');
    
    Route::get('admin-login/{id}', 'Admin\UserController@UserLogin');

    Route::post('admin-create-product', 'Admin\ProductController@create');

    Route::post('admin-product-image', 'Admin\ProductController@uploadImage');



    Route::post('admin-update-product', 'Admin\ProductController@update');
    Route::get('get-subcategory/{id}', function($id) {
        $dat = \App\Model\Category::where('parent',$id)->get();

        $result=array();
        if ($dat) {
            foreach ($dat as $key) {
               $result[]=['value'=>$key->id,'name'=>$key->name]; 
            }

            return $result;
        } else {
            return array('value'=>null,'name'=>'Not Found! ');
        }
        
    });

    Route::get('saksham-test/{id}', function($id) {
        // return \App\Model\User::find(55)->shoppingWallet;
      return \App\Http\Library\Common::SelfGenTime(2);
      // return \App\Model\Redeem::all();
      // return rand(1000000000,9999999999);
      return \Crypt::encrypt($id);
    });


    Route::get('admin-support-delete/{id}', 'User\HelpDeskController@destroy');

    Route::post('admin-live-rate', 'Admin\LiveController@LiveRate');
    Route::post('admin-eth-businus', 'Admin\LiveController@EthBussinus');
    Route::post('admin-bth-bussinus', 'Admin\LiveController@BthBussinus');
    Route::post('admin-inr-bussinus', 'Admin\LiveController@InrBussinus');
    Route::post('ico-extra-rate', 'Admin\LiveController@IcoExtra');

        // Route::group(['prefix' => 'service'], function() {
        //      Route::get('payment','User\Service\PaymentController@pay');
        // });
    });


});



