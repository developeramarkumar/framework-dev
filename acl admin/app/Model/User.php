<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
	// use SoftDeletes;

	protected $table = 'users';
	protected $fillable = ['id','email','mobile','password','demo_password'];
	// protected $hidden = ['password'];
	// public function getNameAttribute($value){
	// 	$val = explode(' ', $value);

 //        return ucwords($val[0]);
 //    }

	public function userRefers(){
		return $this->hasMany('App\Model\User','used_refer','used_refer')->limit(30)->has('usedRefUser')->select(['id']);
	}

	public function directRefer(){
		return $this->hasMany(User::class,'used_refer','id');
	}

	public function userDetail(){
		return $this->belongsTo(Userdetail::class,'id','user_id');
	}

	public function greenWallet(){
		return $this->belongsTo(GreenWallet::class,'id','user_id');
	}

	public function blueWallet(){
		return $this->belongsTo(BlueWallet::class,'id','user_id');
	}

	public function fixedSafe(){
		return $this->belongsTo(FixedSafe::class,'id','user_id');
	}

	public function redWallet(){
		return $this->belongsTo(RedWallet::class,'id','user_id');
	}

	public function kyc(){
		return $this->belongsTo(Kyc::class,'id','user_id');
	}
	public function bank(){
		return $this->belongsTo(Bank::class,'id','user_id');
	}

	public function transcation(){
		return $this->hasMany(Transaction::class,'credit','id');
	}
	public function transaction(){
		return $this->hasMany(Transaction::class,'credit','id');
	}

	public function topUp(){
		return $this->hasMany(Ibtopup::class,'to_id','id');
	}

	public function selfGenerate(){
		return $this->belongsTo(SelfGenerate::class,'id','user_id');
	}

	public function refUser(){
		return $this->belongsTo(User::class,'used_refer','id');
	}

	public function packageUser(){
		return $this->belongsTo(UserPackage::class,'id','user_id');
	}
	public function packageUserDirect(){
		return $this->belongsTo(UserPackage::class,'id','user_id');
	}

	public function parent() {
        return $this->belongsTo(User::class,'used_refer','id') ;
    }

    public function shoppingWallet(){
    	return $this->belongsTo(ShoppingWallet::class,'id','user_id');
    }

    public function rechargeWallet(){
    	return $this->belongsTo(RechargeWallet::class,'id','user_id');
    }

    public function monyTranWallet(){
    	return $this->belongsTo(MonyTransWallet::class,'id','user_id');
    }

    public function logins(){
    	return $this->hasMany(UserLogin::class,'user_id','id');
    }

  
	 public function myplan(){
		return $this->belongsTo(Myplan::class,'id','user_id');
	}
	

}
