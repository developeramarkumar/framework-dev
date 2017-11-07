<?php 
Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::post('/user/login', 'Api\Auth\LoginController@login');
    Route::get('/user/logout', 'Api\Auth\LoginController@logout');
    Route::post('/user/logout', 'Api\Auth\LoginController@logout');
    Route::post('/register', 'Api\Auth\RegisterController@register');
    Route::post('/register/verify', 'Api\Auth\RegisterController@verifyOtp');
    Route::post('/register/resendOtp', 'Api\Auth\RegisterController@resend_otp');
    Route::post('/contact', 'Api\ContactController@store');
    Route::post('/franchisee','Api\FranchiseeController@franchisee');
    Route::post('/user/resendOtp', 'Api\Auth\LoginController@resend_otp');
	
	Route::post('hotel','Api\HotelController@hotelListing');

    Route::group(['prefix' => 'user'], function() {
        
        Route::post('forget-password/sendotp', 'Api\Auth\ForgetPasswordController@sendOtp');
        Route::post('forget-password/changepassword', 'Api\Auth\ForgetPasswordController@change');
        Route::group(['middleware' => ['App\Http\Middleware\Api\RedirectIfNotAuthenticated','App\Http\Middleware\TrimStrings']], function() {
			
            Route::post('rupees-wallet/transfer', 'Api\RedWalletController@transfer');
			Route::post('rupees-wallet/transferApi', 'Api\RedWalletController@transferApi');
            Route::post('wallet/transfer', 'Api\WalletController@transfer');
			Route::post('wallet/transferApi', 'Api\WalletController@transferApi');			
            Route::post('coin-wallet/transfer', 'Api\CoinWalletController@transfer');

            Route::post('topup','Api\TopupController@topup');

            Route::post('get-name', 'Api\UserController@getName');
			 Route::post('get-name-api', 'Api\UserController@getNameApi');
			 Route::post('get-planName', 'Api\UserController@getAllPlan');

            Route::post('blue-wallet/transfer', 'Api\BlueWalletController@transfer');

            Route::post('transact-history/free-income', 'Api\TransactionHistoryController@free_income');

            Route::post('transact-history/rupees-wallet-statement', 'Api\TransactionHistoryController@rupee_wallet_statement');

            Route::post('transact-history/receive-coin-report', 'Api\TransactionHistoryController@receive_coin_report');

            Route::post('transact-history/transfer-coin-report', 'Api\TransactionHistoryController@transfer_coin_report');

            Route::post('transact-history/coin-wallet-statement', 'Api\TransactionHistoryController@coin_wallet_statement');

            Route::post('transact-history/coin-wallet-summary', 'Api\TransactionHistoryController@coin_wallet_summary');

            Route::post('transact-history/withdraw-statement', 'Api\TransactionHistoryController@withdraw_statement');

            

            Route::post('blue-wallet/sendOtp', 'Api\BlueWalletController@sendOtp');

            Route::post('fixed-safe-transfer', 'User\FixedSafeController@transfer');
            Route::post('fixed-safe-validate', 'User\FixedSafeController@fixsafevalidate');
			 Route::post('user-wallet-details', 'Api\UserWalletDetailsController@userWalletDetails');
             Route::post('Get-Category', 'Api\GetCategoryController@GetCategory');
			 Route::post('Get-Sub-Category', 'Api\GetSubCategoryController@GetSubCategory');
			
			
			
        });

    });
	
    Route::group(['prefix' => 'service'], function() {
        Route::post('operator/get','Api\Service\OperatorController@index');
        Route::post('operator/details','Api\Service\OperatorController@show');
        Route::post('operator/validate','Api\Service\OperatorController@validateOpt');
        Route::post('payment','Api\Service\PaymentController@pay');
        
    });
   




    
});

