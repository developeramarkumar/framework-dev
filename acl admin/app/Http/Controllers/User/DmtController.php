<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use \Request;
use App\Model\User;
use App\Model\BankList;
use App\Model\Beneficiary;
use App\Model\DmtRecord;

use Redirect;
use Core\Session;
use Core\Auth;

class DmtController extends Controller
{	
	private function postMan($url,$data){
        $additional_headers = array(                                      
           'Accept: application/json',
           'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjkxZDA3MTRjOGY3MWU4MGQ2M2RmZjhhNmI0MmJjZmM2NmI3OGE3ODliYjI4MzMyZTBmZGQ0ODYxNDFiZGNiZmFkNDBlM2UwNmIwNDM4MTU0In0.eyJhdWQiOiI1IiwianRpIjoiOTFkMDcxNGM4ZjcxZTgwZDYzZGZmOGE2YjQyYmNmYzY2Yjc4YTc4OWJiMjgzMzJlMGZkZDQ4NjE0MWJkY2JmYWQ0MGUzZTA2YjA0MzgxNTQiLCJpYXQiOjE1MDcyODcxMzYsIm5iZiI6MTUwNzI4NzEzNiwiZXhwIjoxNTM4ODIzMTM2LCJzdWIiOiI3MDgzIiwic2NvcGVzIjpbXX0.padOCnXu_l3pwCEX-oHdWjtQAQ0UkfQiGVBQAGHLtOfC9EJ5mHGaD732X9qR7lYvCumml2gLYZ0moMPGq2x0jLUzkIQd-zKLnTv3m6WWhbv5hXwunoxKNgUFNOcYhmeDv37og_BG2gKjJeRBE4B_UA8SvPjy6u_nioMVzAnwFAOCqQPjpRyD_UKXs10IG-YLN7Fxh7pCuDPDTREtcOsn3MQRWPT8YkX35OSO_GPscYiPcxrK8luPkHt3TFaT3LzE009mUUGMDJKscdCLT32n8XoWEU92FFwRl47wcxxcKTpZzfSiDhnVrdY3Nxi3cNYpjnJp3QiZXJ4It8zM65Ynpsr3pA5uy19d_RmsMju9cJ0INZ4RsIVzECmY0CtlfwVQx__HWXUQZDR5i6M6bZxK1xHgf7a5hYgO0uBAThGrOJlmM8LO8xhIwtsn_dm4d-LrMQg9z-RB8q2f4-2GEVy8srk_thTJqVRD3qDu_skeh2EkkJXzz71yu7i0Cc4GuG9S8_22je_mvMw-DL05mjjWi9zTVJRri0_9c9g1w6343vlFkikkzKq_9Tinu0nphcyoy28qEvZq3sQYN3U8Nqt42CH3PxF4BPgGws0FqpKB9jZWXqqp73-LO27ic9-XK7oicqWfkVC3cyMT7zmgitcl8-Vl-JoEs_ZRoiD2mlozGXk',
        );
        $ch = curl_init($url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                       
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                         
        curl_setopt($ch, CURLOPT_HTTPHEADER, $additional_headers); 
        return  json_decode(curl_exec ($ch));
    }

    public function dmtTransfer(Request $request){
    	$inpt = \Request::all();

    	$user = User::find(Auth::guard('user')->user()->id);

    	$rechWall = $user->rechargeWallet;

    	// return array('status'=>200,'message'=>'You have not suficient amoutn to transfer!');

    	if (!$rechWall) {
    		return array('status'=>400,'message'=>'You have not recharge wallet amoutn to transfer!');
    	} elseif($rechWall->amount < $inpt['amount']) {

    		return array('status'=>400,'message'=>'You have not suficient amoutn to transfer!');
    		
    	}else{

    		$url = 'https://www.pay2all.in/api/dmr/v2/transfer';

	    	$ben = Beneficiary::find($inpt['benificery']);

	    	$data = array('mobile_number'=>Auth::guard('user')->user()->mobile,'beneficiaryid'=>$ben->beneficiaryid,'senderid'=>$ben->sender_id,'account'=>$ben->account,'amount'=>$inpt['amount'],'channel'=>$inpt['chinal'],'client_id'=>rand(10000,99999));

	    	$send = $this->postMan($url,$data);

	    	if ($send->status == 0) {

	    		$dmt = new DmtRecord;
	    		$dmt->user_id = Auth::guard('user')->user()->id;
	    		$dmt->payid = $send->payid;
	    		$dmt->message = $send->message;
	    		$dmt->orderid = $send->orderid;
	    		$dmt->txnid = $send->txnid;
	    		$dmt->utr = $send->utr;
	    		$dmt->amount = $send->amount;
	    		$dmt->transaction_date = $send->transaction_date;

	    		$dmt->save();

	    		$rechWall->amount = $rechWall->amount-$inpt['amount'];
	    		$rechWall->save();



	    		return array('status'=>200,'message'=>'Transferd successfully!');

	    	}else{
	    		return array('status'=>400,'message'=>$send->message);
	    	}
    	}
    	

    	


    	// print_r($send);
    }

    public function benificryVerify(Request $request){
    	$inpt = \Request::all();

    	$url = 'https://www.pay2all.in/api/dmr/v2/add_beneficiary_confirm';

    	$ben = Beneficiary::find($inpt['ben_id']);

    	$data = array('mobile_number'=>Auth::guard('user')->user()->mobile,'otp'=>$inpt['otp'],'senderid'=>$ben->sender_id,'beneficiaryid'=>$ben->beneficiaryid);

    	$otp_very = $this->postMan($url,$data);

    	// print_r($otp_very);
    	if ($otp_very->status == '0') {
    		$ben->status = '1';
    		$ben->save();
    		return array('status'=>200,'message'=>'Successfull verified!');
    	}else{
    		return array('status'=>400,'message'=>$otp_very->message);
    	}
    }

	public function addBenificry(Request $request){
        // sleep(10);
        // exit();
		$inpt = \Request::all();
		$user = User::find(Auth::guard('user')->user()->id);

		$benificry = array('mobile'=>$user->mobile);

		$verify = $this->verify($benificry);
		// print_r($verify);
		// exit();
		if ($verify->status == '274') {
			$sender = array('mobile'=>$user->mobile,'fname'=>$user->name,'lname'=>$user->name);

			$add_sender = $this->addSender($sender);

			if ($add_sender->status == '0') {
				$sender_id = $add_sender->senderid;
			}

		}elseif($verify->status == '0'){
			$sender_id = $verify->senderid;
		}else{
			return array('status'=>400,'message'=>'Sender not found');
		}

		// return $sender_id;
        $bank = BankList::where('bank_code',$inpt['bank_code'])->first();
        $url = 'https://www.pay2all.in/api/dmr/v2/add_beneficiary';
        $data = ['mobile_number'=>$user->mobile,'account'=>$inpt['account'],'ifsc'=>$inpt['ifse'],'bankcode'=>$bank->bank_code,'senderid'=>$sender_id,'name'=>$inpt['name'],'vendor_id'=>3];     
        $response =  $this->postMan($url,$data);
        // print_r($response);
        // exit();

        if ($response->status == 0) {

            if (Beneficiary::where(['sender_id'=>$sender_id,'beneficiaryid'=>$response->beneficiaryid])->first()) {
               return array('status'=>400,'message'=>'Benificry already exist!');
            }
            $ben = Beneficiary::firstOrNew(['sender_id'=>$sender_id,'beneficiaryid'=>$response->beneficiaryid]);


            $ben->user_id = $user->id;
            $ben->name = $inpt['name'];
            $ben->bank_name = $bank->bank_name;
            $ben->bank_code = $bank->bank_code;
            $ben->status = '0';
            $ben->ifsc = $inpt['ifse'];
            $ben->account = $inpt['account'];
            $ben->save();

            // $ben = Beneficiary::create([
            //     'user_id'=>$user->id,
            //     'sender_id'=>$sender_id,
            //     'name'=>$inpt['name'],
            //     'bank_name'=>$bank->bank_name,
            //     'bank_code'=>$bank->bank_code,
            //     'status'=>0,
            //     'ifsc'=>$inpt['ifse'],
            //     'beneficiaryid'=>$response->beneficiaryid,
            //     'account'=>$inpt['account']
            // ]);
            return array('status'=>200,'message'=>$response->message,'ben_id'=>$ben->id);
        }
        return array('status'=>400,'message'=>$response->message);
	}


	protected function verify(array $data){
		// return $data['mobile'];
		$url = 'https://www.pay2all.in/api/dmr/v2/verification';
		$data = array('mobile_number'=>$data['mobile']);
        return $this->postMan($url,$data);
    }

    protected function addSender(array $data){
    	$url = 'https://www.pay2all.in/api/dmr/v2/add_sender';
		$data = array('mobile_number'=>$data['mobile'],'first_name'=>$data['fname'],'last_name'=>$data['lname']);
        return $this->postMan($url,$data);
    }
}
