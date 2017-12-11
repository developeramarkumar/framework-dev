<?php
namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use \Request;
use \Redirect;
use App\Model\Operator;
use App\Model\Cyberplat;
use App\Model\CyberplatError;
class OperatorController extends Controller
{

    public function index(){
        $operators = Operator::where('service_id',Request::input('service_id'))->get();
        if ($operators) {
            return $this->json(['data'=>$operators,'class'=>'success','message'=>'operator found']);
        }
        return $this->json(['class'=>'error','message'=>'operator not found'],500);
    } 
    public function show(){
        $operator = Operator::select(['id','name','lableMsg','act_label_msg','min','max','amount'])->where('id',Request::input('operator_id'))->first();
        if ($operator) {
           return  $this->json(['data'=>$operator,'class'=>'success','message'=>'operator found']);
        }
        return $this->json(['class'=>'error','message'=>'operator not found'],500);
    }   
    public function validateOpt (){
        // return Request::all();
        $operator = Operator::where(['id'=>Request::input('operator')])->first();                                         
        $cyberplat_Verification_url = $operator->cyberplat_Verification_url;
        $cyberplat_Payment_url = $operator->cyberplat_Payment_url;
        $cyberplat_Status_url = $operator->cyberplat_Status_url;
        $Cyberplat = Cyberplat::where(['id'=>1])->first();
        $SD = $Cyberplat->SD;
        $AP = $Cyberplat->AP;
        $OP = $Cyberplat->OP;
        $CERT_key = $Cyberplat->CERT_key;
        $CP_PASSWORD = $Cyberplat->CP_PASSWORD;

        $check_url  = trim($cyberplat_Verification_url);                                //"https://in.cyberplat.com/cgi-bin/rjio/rjio_pay_check.cgi";   
        define('CERT', $CERT_key);  //our testing key 
        define('CP_PASSWORD', $CP_PASSWORD); //our pwd 
        $pay_url    = trim($cyberplat_Payment_url);                                       //"https://in.cyberplat.com/cgi-bin/rjio/rjio_pay.cgi";
        $verify_url = trim($cyberplat_Status_url);  
        $phNbr = Request::input('number');
        $ACCOUNT = Request::input('account');
        $amount = (Request::input('amount'))?Request::input('amount'):1;
        $REQ_TYPE= ''; 
        $authenticate = $operator->auth;
        $path = base_url();
        $secKey = file_get_contents("".$path."workingkey/private.key");
        $passwd = CP_PASSWORD;
        $serverCert = file_get_contents("".$path."workingkey/mycert.pem");

        $sessPrefix = rand(100, 300);
        $sess = $sessPrefix.$phNbr.time();
        $sess = substr($sess,-20);
         $querString="CERT=".CERT."\r\nSD=$SD\r\nAP=$AP\r\nOP=$OP\r\nSESSION=$sess\r\nNUMBER=$phNbr\r\nACCOUNT=$ACCOUNT\r\nAMOUNT=$amount\r\nREQ_TYPE=$REQ_TYPE\r\nAMOUNT_ALL=$amount\r\nAuthenticator3=$authenticate\r\nCOMMENT=Test recharge";

        // make SHA1RSA signature
        $pkeyid = openssl_pkey_get_private($secKey, $passwd);
        openssl_sign($querString, $signature, $pkeyid, OPENSSL_ALGO_SHA1);
        openssl_free_key($pkeyid);

        $encoded = base64_encode($signature);
        $encoded = chunk_split($encoded, 76, "\r\n");

        $signInMsg = "BEGIN\r\n" . $querString . "\r\nEND\r\nBEGIN SIGNATURE\r\n" . $encoded . "END SIGNATURE\r\n";
        //print "Signed request:\n$signInMsg\n";

        // send request to Cyberplat
        //echo "\n==============Phone Validation Response===================\n";
        function removeSpecialChar($data){
            return str_replace('<', '',str_replace('>', '',$data));
        }
        $resData=array();
        $response = $this->get_query_result($signInMsg, $check_url);
        $resData['DATE'] = (preg_match('/DATE=(.*)/', $response, $matches))?$matches[1]:'';
        $resData['ADDINFO'] = (preg_match('/ADDINFO=(.*)/', $response, $matches))?urldecode($matches[1]):'';
        $resData['PRICE'] = (preg_match('/PRICE=(.*)/', $response, $matches))?$matches[1]:'';
        $resData['SESSION'] = (preg_match('/SESSION=(.*)/', $response, $matches))?$matches[1]:'';
        $resData['ERROR_CODE'] = (preg_match('/ERROR=(.*)/', $response, $matches))?$matches[1]:'';
        $resData['RESULT'] = (preg_match('/RESULT=(.*)/', $response, $matches))?$matches[1]:'';
        $resData['TRANSID'] = (preg_match('/TRANSID=(.*)/',$response, $matches))?$matches[1]:'';
        $resData['ERRMSG'] = (preg_match('#ERRMSG=(.*)#', $response, $matches))?$matches[1]:'';
        $resData['AUTHCODE'] = (preg_match('/AUTHCODE=(.*)/',$response, $matches))?$matches[1]:'';
        $resData['TRNXSTATUS'] = (preg_match('/TRNXSTATUS=(.*)/', $response, $matches))?$matches[1]:'';
        $data = array();
        if ($resData['ADDINFO']) {
            $info =   explode(' ',$resData['ADDINFO']);
            $infoData['Bill Number']=(removeSpecialChar($info[0])=='NA')?'':removeSpecialChar($info[0]);
            $infoData['Bill Date']=(removeSpecialChar($info[1])=='NA')?'':date('d-M-Y',strtotime(removeSpecialChar($info[1])));
            $infoData['Bill Due Date']=(removeSpecialChar($info[2])=='NA')?'':date('d-M-Y',strtotime(removeSpecialChar($info[2])));
            $infoData['Amount']=(removeSpecialChar($info[3])=='NA')?'':removeSpecialChar($info[3]);
            // $infoData['Partial Bill']=(removeSpecialChar($info[4])=='NA')?'':removeSpecialChar($info[4]);
            $infoData['Name']= (string) array_last(explode('!', removeSpecialChar($info[5])));
            $infoData['Name'] = ($infoData['Name'] == 'NA')?'':$infoData['Name'];
            foreach ($infoData as $key => $value) {
                if ($value != '' && $value != "NA") {
                    $data[$key]=$value;
                }
               
            }
        }
        
        if ($resData['ERRMSG'] == '' && $resData['ERROR_CODE'] > 0) {
            // return $resData['ERROR_CODE'] ;
            $resData['ERRMSG'] = \App\Model\CyberplatError::where(['error_code'=>(int)$resData['ERROR_CODE']])->first()->descriptions_as_per_api;
        }
        
        if($resData['ERROR_CODE'] == 7 || $resData['ERROR_CODE'] == 0){
            return $this->json(['class' => 'success', 'message' =>'Number is valid','data'=>$data]);
        } else{   
            return $this->json(['class' => 'danger', 'error_number'=> $resData['ERROR_CODE'], 'message' =>$resData['ERRMSG']],500);
        }


    }
    protected function get_query_result($qs, $url){
        $opts = array( 
            'http'=>array(
                'method'=>"POST",
                'header'=>array("Content-type: application/x-www-form-urlencoded\r\n".
                                "X-CyberPlat-Proto: SHA1RSA\r\n"),
                'content' => "inputmessage=".urlencode($qs)
            )
        ); 
        $context = stream_context_create($opts);    
        return file_get_contents($url, false, $context);
    }

    protected function check_signature($response, $serverCert) {
        $fields = preg_split("/END\r\nBEGIN SIGNATURE\r\n|END SIGNATURE\r\n|BEGIN\r\n/", $response, NULL, PREG_SPLIT_NO_EMPTY);
        if (count($fields) != 2) {
            print "Bad response\n";
            return;
        }

        $pubkeyid = openssl_pkey_get_public($serverCert);
        $ok = openssl_verify(trim($fields[0]), base64_decode($fields[1]), $pubkeyid);
       
        openssl_free_key($pubkeyid);
    }
    
}
