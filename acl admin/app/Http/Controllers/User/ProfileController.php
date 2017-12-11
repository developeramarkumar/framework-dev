<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use \Request;
use App\Model\User;
use App\Model\Userdetail;
use App\Model\Bank;
use App\Model\Kyc;
use Redirect;
use Core\Session;
use Core\Auth;
// use \Image;
use Intervention\Image\ImageManagerStatic as Image;
class ProfileController extends Controller
{

      public function editprofile(\Request $request) {
  
  $user = User::where('id',Auth::guard('user')->user()->id)->Update(['fname'=> \Request::input('fname'),'lname'=> \Request::input('lname'),'address'=> \Request::input('address'),'country'=>\Request::input('country'),'state'=>\Request::input('state')]);
 
  if($user)
  {
  Session::withFlash(['message'=>'BTC detail added successfully!','class'=>'success']); 
  }
  else
  {
  Session::withFlash(['message'=>'BTC detail added successfully!','class'=>'danger']); 
  }
 return Redirect::back();
   }

     public function changepwd(\Request $request)
   {

    // return Request::input();
   $validate = $this->validate(Request::all(),[
      'old_password' => 'required|min:6',
      'password'=>'required|confirmed|min:6',
      'password_confirmation'=>'required|min:6',
      ]);
  if ($validate) 
  {
  
      Session::withInput(Request::all());
      Session::withError($validate);
      Session::withFlash(['message'=>'Try again','class'=>'error']); 
      return Redirect::back();
    }

  $pass = \Hash::check(Request::input('old_password'),Auth::guard('user')->user()->password);
  // dd($pass);
  if($pass){
      $userpwd = User::find(Auth::guard('user')->user()->id);
      $userpwd->password = \Hash::make(Request::input('password'));
      $userpwd->demo_password =\Crypt::encrypt(\Request::input('password'));
      $userpwd->save();
      if($userpwd) {
        Session::withFlash(['message'=>'Password updated successfully!','class'=>'success']); 
      }
      
  } else {
    Session::withFlash(['message'=>'Try again','class'=>'error']); 
    // Session::withFlash(['message'=>'Try again!','class'=>'danger']); 
  }
    
  //return redirect()->back()->with('message', 'Bank detail added successfully!');
  return Redirect::back();

   }



    // update and insert Personal detail of user
  public function profiledetail() {
    // dd(Request::all());
    $validate = $this->validate(Request::Input(),[
      
        'dob'=>'date'
        ]);
    if ($validate) {
        Session::withInput(Request::all());
        Session::withError($validate);
        // @session_start();
        // dd($_SESSION);
        return Redirect::back();
    }

    // if($_FILES['profile_photo']['name']!="")
    // {
    //   $profile=rand().$_FILES['profile_photo']['name'];
    // }
    // else {
    //   $profile=$_REQUEST['image1'];
    // }
        
    // $con=strtolower(trim($profile));
    // $string=str_replace(" ","-",$con);
    // $string =preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $string);
    // $filename=preg_replace('/-+/', '-', $string);

    // $file_tmp=$_FILES['profile_photo']['tmp_name'];

    // $folderpath = "../dashboard/profile/";

    // $path=$folderpath.$filename;
    // $folder=Auth::guard('user')->user()->id;
    // $folderPath = "../dashboard/profile/$folder/";
    //  $dir=mkdir("$folderPath");

    //     chmod("$folderPath", 777);

    //    $path=$folderPath.$filename;
    // move_uploaded_file($file_tmp,$path);
    $profile_photo='';
    $userprofile = Userdetail::firstOrNew(['user_id' =>Auth::guard('user')->user()->id]);

    if (Request::file('profile_photo')) {
        $file_tmp=$_FILES['profile_photo']['tmp_name'];
        $folderpath = "../dashboard/profile/photo/";
        $profile_photo = str_random(50).'.'.Request::file('profile_photo')->extension();
        $path=$folderpath.$profile_photo;
        $pan = move_uploaded_file($file_tmp,$path);
        $userprofile->profile_photo=$profile_photo;
    }

    
    $userprofile->gender = \Request::input('gender');
    $userprofile->dob = \Request::input('date');
    $userprofile->city=\Request::input('city');
    $userprofile->state = \Request::input('state');
    $userprofile->postal_code=\Request::input('postal_code');
    
    $userprofile->whats_number=\Request::input('phone');
  
   if($userprofile->save())
   {
    Session::withFlash(['message'=>'Your Profile updated successfully!','class'=>'success']);
   }
   else{ // $validate = $this->validate(Request::Input(),[
    //     'username' => 'required',
    //     'password'=>'required',
    //     ]);
    // if ($validate) {
    //     Session::withInput(Request::all());
    //     Session::withError($validate);
    //     // @session_start();
    //     // dd($_SESSION);
    //     return Redirect::back();
    // }
    Session::withFlash(['message'=>'There was problem to updating profile!','class'=>'error']);
   }

      // Session::withFlash(['message'=>'Your Profile updated successfully!','class'=>'success']);
  	//$userdetail =  User::orderBy('id','desc')->first();
  	return Redirect::to('../dashboard/profile.php');


  }
    public function picture(){
        $validate = $this->validate(Request::all(),[
            'image' => 'required',
           ]);
        if ($validate) {
            Session::withInput(Request::all());
            Session::withError($validate);
            return Redirect::back();
        } 
        // Image::configure(array('driver' => 'GD'));
       
        $user=Userdetail::firstOrNew(['user_id'=>Auth::guard('user')->user()->id]);
        if (@$user->profile_photo) {
            unlink('../dashboard/profile/photo/'.$user->profile_photo);
        }
        if (Request::file('image')) {
              $image = str_random(50).'.'.Request::file('image')->extension();
              Image::make(Request::file('image'))->resize(100, 100)->save('../dashboard/profile/photo/'.$image);
            
            $user->profile_photo = $image;
            if($user->save()){
              Session::withFlash(['message'=>'profile updated sucessfully.','class'=>'success']);
            }
            else{
              Session::withFlash(['message'=>'There was problem to updating profile!','class'=>'error']);
            }
            return Redirect::back();
        }
        

    }
          // update and insert Bank detail of user
    public function bankdetail(\Request $request) {
  $validate = $this->validate(Request::Input(),[
        'bankName' => 'required',
        'branchName'=>'required',
        'accountHolderName'=>'required',
        'accountNo'=>'required',
        'ifsccode'=>'required',
        ]);
    if ($validate) {
        Session::withInput(Request::all());
        Session::withError($validate);
        return Redirect::back();
    }
	$userbank = Bank::firstOrNew(['user_id' =>Auth::guard('user')->user()->id]);
	$userbank->bankname = \Request::input('bankName');
  $userbank->branchname = \Request::input('branchName');
  $userbank->account_holder_name=\Request::input('accountHolderName');
  $userbank->account_no = \Request::input('accountNo');
  $userbank->ifsc_code=\Request::input('ifsccode');
  if($userbank->save())
  {
  Session::withFlash(['message'=>'Bank detail added successfully!','class'=>'success']); 
  }
  else
  {
  Session::withFlash(['message'=>'Bank detail added successfully!','class'=>'danger']); 
  }

  //return redirect()->back()->with('message', 'Bank detail added successfully!');
  return Redirect::to('../dashboard/bank-details');
   }

   // update and insert Btc detail of user
    public function btc(\Request $request) {
  
  $userbtc = Userdetail::where('user_id',Auth::guard('user')->user()->id)->Update(['btc_address'=> \Request::input('btc')]);
 
  if($userbtc)
  {
  Session::withFlash(['message'=>'BTC detail added successfully!','class'=>'success']); 
  }
  else
  {
  Session::withFlash(['message'=>'BTC detail added successfully!','class'=>'danger']); 
  }
 return Redirect::back();
   }

  public function coinexchange(\Request $request) {

  $validate = $this->validate(Request::all(),[
      'coin_exchange' => 'required'
    
      ]);
  if ($validate) 
  {
    // dd($validate);
      Session::withInput(Request::all());
      Session::withError($validate);
      Session::withFlash(['message'=>'Try again','class'=>'error']); 
      return Redirect::back();
    }


  
  $coinexchange = Userdetail::where('user_id',Auth::guard('user')->user()->id)->Update(['coin_exchange_id'=>\Request::input('coin_exchange')]);
 
  if($coinexchange)
  {
  Session::withFlash(['message'=>'coinexchange Id added successfully!','class'=>'success']); 
  }
  else
  {
  Session::withFlash(['message'=>'Coinexchange Id added successfully!','class'=>'error']); 
  }

 //return redirect()->back()->with('message', 'Bank detail added successfully!');

  return Redirect::back();
   }

// update and insert kyc detail

    public function kyc(Request $request){

      // return \Request::all();
        // $validate = $this->validate(Request::all(),[
        //     'pancard' => 'nullable|image',
        //     'aadhar'  => 'nullable|image'
        // ]);

        // if ($validate) {
        //     Session::withFlash($validate);
        //     return Redirect::back();
        // }
        $userkyc = Kyc::firstOrNew(['user_id' =>Auth::guard('user')->user()->id]);
        if (Request::file('pancard')) {
            $file_tmp=$_FILES['pancard']['tmp_name'];
            $folderpath = "../dashboard/profile/kyc/";
            $pancard = str_random(50).'.'.Request::file('pancard')->extension();
            $path=$folderpath.$pancard;
            $pan = move_uploaded_file($file_tmp,$path);
            $userkyc->pancard= $pancard;
        }
        if (Request::file('aadhar')) {
            $aadhar_tmp=$_FILES['aadhar']['tmp_name'];
            $folderpath = "../dashboard/profile/kyc/";
            $aadhar = str_random(50).'.'.Request::file('aadhar')->extension();
            $aadharpath=$folderpath.$aadhar;
            move_uploaded_file( $aadhar_tmp,$aadharpath);
            $userkyc->aadharcard=$aadhar;  
        }

        if (Request::file('bank_proof')) {
            $aadhar_tmp=$_FILES['bank_proof']['tmp_name'];
            $folderpath = "../dashboard/profile/kyc/";
            $bank_proof = str_random(50).'.'.Request::file('bank_proof')->extension();
            $aadharpath=$folderpath.$bank_proof;
            move_uploaded_file( $aadhar_tmp,$aadharpath);
            $userkyc->bank_proof=$bank_proof;
        }

        if (Request::file('photo')) {
            $aadhar_tmp=$_FILES['photo']['tmp_name'];
            $folderpath = "../dashboard/profile/kyc/";
            $photo = str_random(50).'.'.Request::file('photo')->extension();
            $aadharpath=$folderpath.$photo;
            move_uploaded_file( $aadhar_tmp,$aadharpath);
            $userkyc->image=$photo;
        }


               
        if($userkyc->save()){
            Session::withFlash(['message'=>'Kyc detail added successfully!','class'=>'success']);
        }
        else{
            Session::withFlash(['message'=>'Kyc detail added successfully!','class'=>'danger']);
        }
        return Redirect::back();

    }

  


 
   public function changetran(Request $request){

        $validate = $this->validate(Request::all(),[
          'transaction_password' => 'required|min:6',
        ]);
        if ($validate) 
        {
          Session::withInput(Request::all());
          Session::withError($validate);
          Session::withFlash(['message'=>'Try again','class'=>'error','status'=>'active']); 
          return Redirect::back();
        }
        
        $inp = Request::input();
        $user = User::find(Auth::guard('user')->user()->id);
        $user->transaction_pass=\Request::input('transaction_password');
        $user->save();
        if($user) {
            Session::withFlash(['message'=>'Password updated successfully!','class'=>'success','status'=>'active']); 
        }else{
            Session::withFlash(['message'=>'Try again','class'=>'error','status'=>'active']); 
        }
        return Redirect::back();
   }

}
