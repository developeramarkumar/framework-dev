<?php 
namespace App\Http\Library;

use \App\Model\Order;
use \App\Model\Userdetail;
use \App\Model\HotelCountry;
use \App\Model\HotelCity;
use \App\Model\HotelSearchResult;
use \App\Model\HotelInfo;
use \App\Model\HotelsRoom;
/**
* All function for hotel
*/
class Hotel
{   use Validation,Cryption;
	
	public $msg='';
	protected $form='';
	protected $paymentForm='';
	protected $hotelInfoForm='';


         // $externalContent = file_get_contents('http://checkip.dyndns.com/');
         // preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
         // $myIp=$m[1];
         protected static $ClientId = "ApiIntegrationNew";
	     protected static $UserName="HAPPINESS EASY";
	     protected static $Password="HAPPY@123";
	     protected static $EndUserIp="203.122.11.211";
	     protected static $TokenId="59343ec0-fc1e-4355-b660-3fa5810ce6df";
         protected static $MemberId=46098;
         protected static $AgencyId=46104;
         protected static $TraceId=0;

		 protected static $getTokenIdUrl="http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate";
		 protected static $getCountryListUrl="http://api.tektravels.com/SharedServices/SharedData.svc/rest/CountryList";
		 protected static $getCityListUrl="http://api.tektravels.com/SharedServices/SharedData.svc/rest/DestinationCityList";
		 protected static $getErrorStatus_TokenId="";

		 protected static $getSrchHtlUrl="http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelResult/";
		 protected static $getErrorStatus_SrchHtl="HotelSearchResult";

		 protected static $getHtlInfoUrl="http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelInfo/";
		 protected static $getErrorStatus_HtlInfo="HotelInfoResult";

		 protected static $getHtlRoomUrl="http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/GetHotelRoom/";
		 protected static $getErrorStatus_HtlRoom="GetHotelRoomResult";

		 protected static $getBlckRoomUrl="http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/BlockRoom/";
		 protected static $getErrorStatus_SBlckRoom="BlockRoomResult";


         protected static $authenticateArr='';

         public $parametersHtlSrch="{}";

	public function __construct(){}

	protected function curlHitApi($parameters,$url,$checkStatus=null)
    {
		
		
		//echo '<br/>$url='.$url;
		//echo '<pre>';print_r($parameters);
        $header = ["Cache-Control:no-cache","Content-Type:application/json"];
        $method = 'POST';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
        $this->curlResponse=$response;
		//$this->curlHitApi=$response;

        //echo '<pre>';print_r($response);die;
        // $response=$this->staticDataHotSrch();
        $response=json_decode($response);
        
        
        
        if($checkStatus==null)
        {
            $errorStatus=$response->Status;

        }
        else
        {
        	//echo '<pre>';print_r($response);die;
            $response=$response->{$checkStatus};

        	$errorStatus=$response->ResponseStatus;
        	//echo $checkStatus.'<pre>';print_r($response);
        }


		if((int)$errorStatus===1)
		{

			$this->curlHitApi=$response;
            $this->curlHitApiCount=1;
		}
		else
		{
            $this->curlHitApi=$response->Error->ErrorMessage;
            $this->curlHitApiCount=0;
		 }
		
		//ECHO '$this->curlHitApi=====<pre>';print_r($this->curlHitApi);
	}

 //    public static function getTokenId($hotel)
	// {
	// 	$parameters=array(
	// 	  	"ClientId"=>Self::$ClientId,
	// 	    "UserName"=>Self::$UserName,
	// 	    "Password"=>Self::$Password,
	// 	    "EndUserIp"=> Self::$EndUserIp
	// 	);
	//     $parameters=json_encode($parameters);
 //        $hotel->curlHitApi($parameters,self::$getTokenIdUrl);
 //        self::$authenticateArr=$hotel->curlHitApi;
      
	// }
	// public function getCountryList()
	// {

 //        $parameters=array(
	// 	  	"ClientId"=>self::$ClientId,
	// 	    "TokenId"=>self::$authenticateArr->TokenId, 
	// 	    "EndUserIp"=> self::$EndUserIp
	// 	);
	//      $parameters=json_encode($parameters);
 //         $this->curlHitApi($parameters,self::$getCountryListUrl);

 //        if($this->curlHitApiCount>0)
 //        {
 //          	$xml = simplexml_load_string($this->curlHitApi->CountryList);
 //          	$json = json_encode($xml);
 //            $country = json_decode($json,TRUE);
 //            $country=$country['Country'];
 //        }
 //        else
 //        {
 //             $country=array();
 //        }
	// }

 //    public function getCityList($countryCode)
	// {
		
 //        $parameters=array(
	// 	  	"ClientId"=>self::$ClientId,
	// 	    "TokenId"=>self::$authenticateArr->TokenId, 
	// 	    "EndUserIp"=> self::$EndUserIp,
	// 	    "CountryCode"=> $countryCode
	// 	);
	//     $parameters=json_encode($parameters);

 //        $this->curlHitApi($parameters,self::$getCityListUrl);
 //        if($this->curlHitApiCount>0)
 //        {
 //          	$xml = simplexml_load_string($this->curlHitApi->DestinationCityList);
 //          	$json = json_encode($xml);
 //            $city = json_decode($json,TRUE);
 //            $city=$city['City'];
 //        }
 //        else
 //        {
 //            $city=array();
 //        }
	// }

	public function getHotelList($post)
	{

        $post['no_guests']=json_decode($post['no_guests'],true);
		$this->getHotelList='';
       
		$parameters=array(
		  	
		    "BookingMode"=> 5,
            "CheckInDate"=>$post['check-in'],
            "NoOfNights"=>(int)$post['no_nights'],
            "CountryCode"=>$post['CtyCode'],
            
            "ResultCount"=> 0,
            "PreferredCurrency"=>"INR",
            "GuestNationality"=>$post['NatCode'],
            "NoOfRooms"=> (int)$post['no_of_rooms'],
            "PreferredHotel"=> "",
            "MaxRating"=> 5,
		    "MinRating"=> 1,
		    "ReviewScore"=> 0,
		    "IsNearBySearchAllowed"=> false,

		    "EndUserIp"=> self::$EndUserIp,
		    "TokenId"=>self::$TokenId, 
		    
            "CityId"=>(int)$post['CtyId'],
		    "RoomGuests"=>$post['no_guests']
		   
		);
		
		//$parameters1['RoomGuests']=$no_guests;
		// echo '<pre>';print_r($parameters);
        
		// echo '<pre>';print_r($parameters1);die;
	    $parameters=json_encode($parameters);
	    $parameters=stripslashes($parameters);
	    $this->parametersHtlSrch=$parameters;
        //echo '<pre>';print_r($parameters);die;
        $HotelSearchResult=\App\Model\HotelSearchResult::where(array('city_id'=>(int)$post['CtyId']))->first();
        $dataExists=count($HotelSearchResult);
        //dd($HotelSearchResult->data);
        // echo '<pre>';print_r($HotelSearchResult);die;
        if($dataExists==0)
        {
            $this->curlHitApi($parameters,self::$getSrchHtlUrl,self::$getErrorStatus_SrchHtl);
            if($this->curlHitApi->ResponseStatus==1)
            {
                $HotelSearchResult= new \App\Model\HotelSearchResult;
                
                $HotelSearchResult->data=$this->curlResponse;
                $HotelSearchResult->city=$post['CityId'];
                $HotelSearchResult->city_id=$post['CtyId'];
               
                $HotelSearchResult->created_at=date("Y-m-d H:i:s");
                $HotelSearchResult->updated_at=date("Y-m-d H:i:s");
                //ECHO '<PRE>';print_r($HotelSearchResult->data);die;
                $HotelSearchResult->save();
            }
           // echo 'SAVE';DIE;
        }
        else
        {
            $this->curlHitApiCount=1;
            $this->curlHitApi=json_decode($HotelSearchResult->data)->{self::$getErrorStatus_SrchHtl};
        }
        //echo '<pre>';print_r($this->curlHitApi);die;
        if($this->curlHitApiCount>0)
        {
        	self::$TraceId=$this->curlHitApi->TraceId;

            $this->hotelSearchHtml($this->curlHitApi->HotelResults);
            $this->getHotelList=$this->hotelSearchHtml;
        }
        else
        {
        	$this->getHotelList=$this->curlHitApi;
           
        }
        //echo '<pre>QQQQ';print_r($srcHotel);die;
        
        //echo '<pre>QQQQ';print_r($srcHotel);die;
	}
    public function getHotelListAjax($post)
	{
		$this->getHotelListAjax='';
        $post['loadPostData']=json_decode($post['loadPostData'],true);
        $post['loadPostData']['MaxRating']=$post['rating'];
        $post['loadPostData']['MinRating']=$post['rating'];
        //$minPrice=$post['minPrice'];$maxPrice=$post['maxPrice'];
        $parameters=$post['loadPostData'];
        
	    $parameters=json_encode($parameters);
	    $parameters=stripslashes($parameters);
	    $this->parametersHtlSrch=$parameters;
        //echo '<pre>';print_r($parameters);die;
        //$this->curlHitApi($parameters,self::$getSrchHtlUrl,self::$getErrorStatus_SrchHtl);
        //echo '<pre>';print_r($post['loadPostData']['CityId']);die;

        $HotelSearchResult=\App\Model\HotelSearchResult::where(array('city_id'=>(int)$post['loadPostData']['CityId']))->first();
        $dataExists=count($HotelSearchResult);
        //dd($HotelSearchResult->data);
        // echo '<pre>';print_r($HotelSearchResult);die;
        if($dataExists==0)
        {
            $this->curlHitApi($parameters,self::$getSrchHtlUrl,self::$getErrorStatus_SrchHtl);
        }
        else
        {
            $this->curlHitApiCount=1;
            $this->curlHitApi=json_decode($HotelSearchResult->data)->{self::$getErrorStatus_SrchHtl};
        }



        if($this->curlHitApiCount>0)
        {
        	self::$TraceId=$this->curlHitApi->TraceId;
        	     //echo '<pre>';print_r($this->curlHitApi->HotelResults);die;
            $this->hotelSearchHtmlAjax($this->curlHitApi->HotelResults,$post);

            $this->getHotelListAjax=$this->hotelSearchHtmlAjax;
        }
        else
        {
        	$this->getHotelListAjax=$this->curlHitApi;
           
        }

        //echo '<pre>QQQQ';print_r($srcHotel);die;
        
        //echo '<pre>QQQQ';print_r($srcHotel);die;
	}
	public function getHotelListSort($post)
	{
		$this->getHotelListSort='';
        $post['loadPostData']=json_decode($post['loadPostData'],true);

        //$minPrice=$post['minPrice'];$maxPrice=$post['maxPrice'];
        $parameters=$post['loadPostData'];
        
	    $parameters=json_encode($parameters);
	    $parameters=stripslashes($parameters);
	    $this->parametersHtlSrch=$parameters;
        //echo '<pre>';print_r($parameters);die;
       
        //echo '<pre>';print_r($this->curlHitApi);die;

        $HotelSearchResult=\App\Model\HotelSearchResult::where(array('city_id'=>(int)$post['loadPostData']['CityId']))->first();
        $dataExists=count($HotelSearchResult);
        //dd($HotelSearchResult->data);
        // echo '<pre>';print_r($HotelSearchResult);die;
        if($dataExists==0)
        {
            $this->curlHitApi($parameters,self::$getSrchHtlUrl,self::$getErrorStatus_SrchHtl);
        }
        else
        {
            $this->curlHitApiCount=1;
            $this->curlHitApi=json_decode($HotelSearchResult->data)->{self::$getErrorStatus_SrchHtl};
        }

        if($this->curlHitApiCount>0)
        {
        	self::$TraceId=$this->curlHitApi->TraceId;
        	     //echo '<pre>';print_r($this->curlHitApi->HotelResults);die;
            $this->hotelSearchHtmlSort($this->curlHitApi->HotelResults,$post);

            $this->getHotelListSort=$this->hotelSearchHtmlSort;
        }
        else
        {
        	$this->getHotelListSort=$this->curlHitApi;
           
        }

        //echo '<pre>QQQQ';print_r($srcHotel);die;
        
        //echo '<pre>QQQQ';print_r($srcHotel);die;
	}
	public function getHotelInfo($get)
	{

		$this->getHotelInfo='';
        $parametersArr=array(
	        "AgencyId"=>self::$AgencyId,
	        "ClientId"=>self::$ClientId,
	        "EndUserIp"=>self::$EndUserIp,
	        "TokenAgencyId"=>self::$AgencyId,
	        "TokenId"=>self::$TokenId,
	        "TokenMemberId"=> self::$MemberId,

	        "ResultIndex"=>$get['a'],
	        "HotelCode"=>$get['b'],
	        "HotelName"=>$get['c'],
	        "TraceId"=>$get['d']
		);
	    $parameters=json_encode($parametersArr);
        //echo '<pre>';print_r($parameters);die;
        
        
        $HotelSearchResult=\App\Model\HotelInfo::where(array('hotel_code'=>(int)$parametersArr['HotelCode']))->first();
        $dataExists=count($HotelSearchResult);
        //dd($HotelSearchResult->data);
        // echo '<pre>';print_r($HotelSearchResult);die;
        if($dataExists==0)
        {
            $this->curlHitApi($parameters,self::$getHtlInfoUrl,self::$getErrorStatus_HtlInfo);
            //echo '<pre>';print_r($this->curlHitApi);die;
            if($this->curlHitApi->ResponseStatus==1)
            {
                $HotelSearchResult= new \App\Model\HotelInfo;
                
                $HotelSearchResult->data=$this->curlResponse;
                $HotelSearchResult->hotel_code=$parametersArr['HotelCode'];
                $HotelSearchResult->hotel=$parametersArr['HotelName'];
               
                $HotelSearchResult->created_at=date("Y-m-d H:i:s");
                $HotelSearchResult->updated_at=date("Y-m-d H:i:s");
                //ECHO '<PRE>';print_r($HotelSearchResult->data);die;
                $HotelSearchResult->save();
            }
           // echo 'SAVE';DIE;
        }
        else
        {
            $this->curlHitApiCount=1;
            $this->curlHitApi=json_decode($HotelSearchResult->data)->{self::$getErrorStatus_HtlInfo};
        }
//echo '<pre>';print_r($this->curlHitApi);die;
        if($this->curlHitApiCount>0)
        {
            self::$TraceId=$this->curlHitApi->TraceId;
        	     //echo '<pre>';print_r($this->curlHitApi->HotelResults);die;
            $this->getHtlDetailsHtml($this->curlHitApi->HotelDetails,$get);

            $this->getHotelInfo=$this->getHtlDetailsHtml;

        }
        else
        {
            $this->getHotelInfo='<img src="'.base_url().'assets/images/norecord.png" > </img>';
        }

       // echo '<pre>QQQQ';print_r($this->getHotelInfo);die;

       
	}
    public function getHtlDetailsHtml($hotelsData,$get)
    {
       $this->getHtlDetailsHtml='';
       // echo '<pre>';print_r($hotelsData);die;
       $this->getHtlDetailsHtml.='<div class="row">
                        <div class="col-md-8 col-sm-8">
<div id="wowslider-container7" class="ws_gestures" style="font-size: 10px;">
<div class="ws_images" style="overflow: visible;">
<div style="position: relative; width: 100%; font-size: 0px; line-height: 0; max-height: 100%; overflow: hidden;">
<img src="http://10.107.4.8/cashcoin/assets/images/hotel_slider/1.jpg" alt="hotel-750x420-1" title="hotel-750x420-1" id="wows7_0" style="width: 100%; visibility: hidden;"></div>

<div style="position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; overflow: hidden;"><div class="ws_list" style="position: absolute; top: 0px; height: 100%; width: 1000%; left: -79.834%; transform: translate3d(0px, 0px, 0px);">

<div class="ws_swipe_left" style="position: absolute; top: 0px; height: 100%; overflow: hidden; width: 10%; left: -10%;"><img src="http://10.107.4.8/cashcoin/assets/images/hotel_slider/6.jpg" alt="hotel-750x420-6" title="hotel-750x420-6" id="wows7_5" style="visibility: visible;">
</div>';

$this->getHtlDetailsHtml.='<ul style="width: 100%;">';
 $a="";
 $loopcounter=0;
 //echo '<pre>';print_r($hotelsData);die;
    foreach($hotelsData->Images as $imagePath)
    {
        ++$loopcounter;
        $class="";
        if($loopcounter==2)
        {
            $class.="ws_selbull";
        }    

        $a.="<a href='javascript:void(0);' title='slider ".$loopcounter."' class='".$class."'><span>".$loopcounter."</span></a>";

		$this->getHtlDetailsHtml.='<li style="width: 10%;">
				<img src="'.$imagePath.'" alt="hotel-750x420-1" title="hotel-750x420-1" id="wows7_0" style="visibility: visible;">
				</li>';
    }
    
    $this->getHtlDetailsHtml.='</ul>';
	$this->getHtlDetailsHtml.='<div class="ws_swipe_right" style="position: absolute; top: 0px; height: 100%; overflow: hidden; width: 10%; left: 60%;"><img src="http://10.107.4.8/cashcoin/assets/images/hotel_slider/1.jpg" alt="hotel-750x420-1" title="hotel-750x420-1" id="wows7_0" style="visibility: visible;"></div></div></div><div class="ws_cover" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 10; background: rgb(255, 255, 255) none repeat scroll 0% 0%; opacity: 0;"><a href="javascript:void(0);" style="display:none;position:absolute;left:0;top:0;width:100%;height:100%"></a></div><div style="position: absolute; padding: 0px; z-index: 56; right: 15px; bottom: 15px;"><a href="http://wowslider.com" style="position: relative; display: block; font-size: 15px; width: auto; height: auto; font-family: Arial; font-weight: normal; font-style: normal; padding: 1px 5px; margin: 0px; border-radius: 10px; outline: medium none currentcolor;" target="_blank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div><div class="ws_controls">

	<div class="ws_bullets">
    <div>'.$a.'</div>
    </div>
     <a href="javascript:void(0);" class="ws_next"><span><i></i><b></b></span></a><a href="javascript:void(0);" class="ws_prev"><span><i></i><b></b></span></a></div></div>
	
<div class="ws_shadow"></div>
</div>	

                            <h3>Description</h3>
                            <p>'.$hotelsData->Description.'</p>';
    if(count($hotelsData->HotelFacilities)) 
    {
        $this->getHtlDetailsHtml.='<div class="row">';
        $facilityCounter=0;
        foreach($hotelsData->HotelFacilities as $facility)
        {
            ++$facilityCounter;
            if($facilityCounter==1)
            {
               $this->getHtlDetailsHtml.='<div class="col-md-4 col-sm-4">
                                    <ul class="list-ok">'; 
            }
            
            $this->getHtlDetailsHtml.='<li>'.$facility.'</li>';
            if($facilityCounter%4==0)
            {                         
                 $this->getHtlDetailsHtml.='</ul></div>';
                 $this->getHtlDetailsHtml.='<div class="col-md-4 col-sm-4">
                                    <ul class="list-ok">'; 
            }
        }
        $this->getHtlDetailsHtml.='</ul></div></div>';

    }                       

$this->getHtlDetailsHtml.='<div class="separator"></div>
                            
                           
                           
                        </div>
                        <div class="col-md-4  col-sm-4">
                           <div class="sp-page-r">
			<div class="h-detail-r">
				<div class="h-detail-lbl">
					<div class="h-detail-lbl-a">'.$hotelsData->HotelName.' </div>
					<div class="h-detail-lbl-b">'.$hotelsData->Address.'</div>
				</div>
				<div class="h-detail-stars">
					<ul class="h-stars-list">
						<li><a href="javascript:void(0);"><img alt="'.$hotelsData->HotelName.'" src="'.base_url().'img/'.($hotelsData->StarRating>0?"hd-star-b.png":"hd-star-a.png").'"></a></li>
						<li><a href="javascript:void(0);"><img alt="'.$hotelsData->HotelName.'" src="'.base_url().'img/'.($hotelsData->StarRating>1?"hd-star-b.png":"hd-star-a.png").'"></a></li>
						<li><a href="javascript:void(0);"><img alt="'.$hotelsData->HotelName.'" src="'.base_url().'img/'.($hotelsData->StarRating>2?"hd-star-b.png":"hd-star-a.png").'"></a></li>
                        <li><a href="javascript:void(0);"><img alt="'.$hotelsData->HotelName.'" src="'.base_url().'img/'.($hotelsData->StarRating>3?"hd-star-b.png":"hd-star-a.png").'"></a></li>
                        <li><a href="javascript:void(0);"><img alt="'.$hotelsData->HotelName.'" src="'.base_url().'img/'.($hotelsData->StarRating>4?"hd-star-b.png":"hd-star-a.png").'"></a></li>
					</ul>
					<div class="clear"></div>
				</div>';
                if(strlen($hotelsData->HotelPolicy)>6)
                $this->getHtlDetailsHtml.='
					<div class="h-help-lbl">Hotel Policy</div>
				      <div class="h-details-text">
					  <p>'.$hotelsData->HotelPolicy.'</p>
				      </div>
			        </div>';

			
			$this->getHtlDetailsHtml.='<div class="h-help">
				<div class="h-help-lbl">Need Sparrow Help?</div>
				<div class="h-help-lbl-a">We would be happy to help you!</div>
				<div class="h-help-phone">'.$hotelsData->HotelContactNo.'</div>
				
			</div>
			
			
			
		</div>
                        </div>
                    </div>';

    }
	public function getHotelRoom($get)
	{
		
         $parametersArr=array(
            "AgencyId"=>self::$AgencyId,
            "ClientId"=>self::$ClientId,
            "EndUserIp"=>self::$EndUserIp,
            "TokenAgencyId"=>self::$AgencyId,
            "TokenId"=>self::$TokenId,
            "TokenMemberId"=> self::$MemberId,

            "ResultIndex"=>$get['a'],
            "HotelCode"=>$get['b'],
            "HotelName"=>$get['c'],
            "TraceId"=>$get['d']
        );
	    $parameters=json_encode($parametersArr);
       //echo '<pre>';print_r($parameters);die;
        
         
        $HotelSearchResult=\App\Model\HotelsRoom::where(array('hotel_code'=>(int)$parametersArr['HotelCode']))->first();
        $dataExists=count($HotelSearchResult);
        //dd($HotelSearchResult->data);
        // echo '<pre>';print_r($HotelSearchResult);die;
        if($dataExists==0)
        {
            $this->curlHitApi($parameters,self::$getHtlRoomUrl,self::$getErrorStatus_HtlRoom);
            if($this->curlHitApi->ResponseStatus==1)
            {
                $HotelSearchResult= new \App\Model\HotelsRoom;
                
                $HotelSearchResult->data=$this->curlResponse;
                $HotelSearchResult->hotel_code=$parametersArr['HotelCode'];
                $HotelSearchResult->hotel=$parametersArr['HotelName'];
               
                $HotelSearchResult->created_at=date("Y-m-d H:i:s");
                $HotelSearchResult->updated_at=date("Y-m-d H:i:s");
                //ECHO '<PRE>';print_r($HotelSearchResult->data);die;
                $HotelSearchResult->save();
            }
           // echo 'SAVE';DIE;
        }
        else
        {
            $this->curlHitApiCount=1;
            $this->curlHitApi=json_decode($HotelSearchResult->data)->{self::$getErrorStatus_HtlRoom};
        }

        if($this->curlHitApiCount>0)
        {
            //$this->hotelRoom=$this->curlHitApi->HotelRoomsDetails;
            $this->hotelRoomHtml($this->curlHitApi->HotelRoomsDetails,$get);
            $this->getHotelRoom=$this->hotelRoomHtml;
        }
        else
        {
            $this->getHotelRoom='<img src="'.base_url().'assets/images/norecord.png" > </img>';
        }

       // echo '<pre>QQQQ';print_r($this->hotelRoom);die;
	}
    public function hotelRoomHtml($hotelsData,$get)
    {
        $user= \Core\Auth::guard('user')->user();

        $is_login=count($user)==0?0:1;
        //echo $is_login.'<pre>';print_r($user);die;
        $this->hotelRoomHtml='';
        $this->hotelRoomHtmlCount=0;
        if(count($hotelsData))
        {
            //echo count($hotelsData);echo '<pre>';print_r($hotelsData); die;         
            foreach($hotelsData as $roomDetail)
            {
                ++$this->hotelRoomHtmlCount;
                $this->hotelRoomHtml.='<div class="row luxe_row">
                
                <div class="col-md-6">
                   <div class="luxe_room">
                        <h4><a href="hotels-booking">'.$roomDetail->RoomTypeName.'</a></h4>
                       
                        <p>'.$roomDetail->CancellationPolicy.'</p>
                   </div>
                </div>
           
                <div class="col-md-3">
                    <div class="luxe_right">
                       <h4>Rs '.$roomDetail->Price->OfferedPriceRoundedOff.'</h4>
                       <p>per night</p>';
      if($is_login==0)
      {
        $this->hotelRoomHtml.='<a href="'.base_url().'login">Book Now</a>';
      }
      else
      {
        $this->hotelRoomHtml.='<a href="javascript:void(0);" >Book Now</a>';
      }
    $this->hotelRoomHtml.='
                    </div>
                </div>
            </div>'; 
            }
         
        }
        else
        {

        }
        if($this->hotelRoomHtmlCount==0)
        {
           $this->hotelRoomHtml.='<img src="'.base_url().'assets/images/norecord.png" > </img>';

        }
        
    }
	public function getBlockRoom()
	{
		
        $parameters=array(
		    "ResultIndex"=> "1",
		    "HotelCode"=> "1386",
		    "HotelName"=>"The Leela",
		    "GuestNationality"=>"IN",
		    "NoOfRooms"=>"2",
		    "ClientReferenceNo"=> "0",
		    "IsVoucherBooking"=> "true",
		    "HotelRoomsDetails"=> 
		    array(
		    	array(
		    
		            "RoomIndex"=> "37",
	                "RoomTypeCode"=> "1589_abc001589^T210^MT0^OR0^RN1^BB1^M0_2^^1589_abc001589^T210^MT0^OR0^RN1^BB1^M0_2_~!:~7~!:~2",
	                "RoomTypeName"=>"Deluxe Room-Doublebed",
	                "RatePlanCode"=> "1589_abc001589^T210^MT0^OR0^RN1^BB1^M0_2_",
		            "BedTypeCode"=> null,
		            "SmokingPreference"=> 0,
		            "Supplements"=> null,
		            "Price"=>
		            array(
	                    "CurrencyCode"=> "INR",
	                    "RoomPrice"=> 157.54,
	                    "Tax"=> 23.63,
	                    "ExtraGuestCharge"=> 0,
	                    "ChildCharge"=> 0,
	                    "OtherCharges"=> 0,
	                    "Discount"=> 0,
	                    "PublishedPrice"=> 181.18,
	                    "PublishedPriceRoundedOff"=> 181,
	                    "OfferedPrice"=> 181.18,
	                    "OfferedPriceRoundedOff"=> 181,
	                    "AgentCommission"=> 0,
	                    "AgentMarkUp"=> 0,
	                    "ServiceTax"=> 10.87,
	                    "TDS"=> 0
		            )
		        ),
		        array(
		           "RoomIndex"=>39,
					"RoomTypeCode"=>"1589_abc001589^T210^MT0^OR0^RN1^BB1^M0_2^^1589_abc001589^T210^MT0^OR0^RN1^BB1^M0_2_~!:~7~!:~2",
					"RoomTypeName"=>"Deluxe Room-Doublebed",
					"RatePlanCode"=>"1589_abc001589^T210^MT0^OR0^RN1^BB1^M0_2_",
		            "BedTypeCode"=> null,
		            "SmokingPreference"=> 0,
		            "Supplements"=> null,
		            "Price"=> array(
	                    "CurrencyCode"=> "INR",
	                    "RoomPrice"=> 157.54,
	                    "Tax"=> 23.63,
	                    "ExtraGuestCharge"=> 0,
	                    "ChildCharge"=> 0,
	                    "OtherCharges"=> 0,
	                    "Discount"=> 0,
	                    "PublishedPrice"=> 181.18,
	                    "PublishedPriceRoundedOff"=> 181,
	                    "OfferedPrice"=> 181.18,
	                    "OfferedPriceRoundedOff"=> 181,
	                    "AgentCommission"=> 0,
	                    "AgentMarkUp"=> 0,
	                    "ServiceTax"=> 10.87,
	                    "TDS"=> 0
		            )
		        )
		    ),
		    "EndUserIp"=> "203.122.11.211",
		    "TokenId"=> "7800974f-998e-4a41-ac13-f26c13cb1761",
		    "TraceId"=>"a7c2b6cc-3987-4fda-8c37-da3bd07f08e5"
	    );
	    $parameters=json_encode($parameters);
	   //echo '<pre>';print_r($parameters);die;
	    $this->curlHitApi($parameters,self::$getBlckRoomUrl,self::$getErrorStatus_SBlckRoom);
	    //echo '<pre>';print_r($this->curlHitApi);die;
	    if($this->curlHitApiCount>0)
	    {
	        $hotelRoom=$this->curlHitApi;
	    }
	    else
	    {
	        $hotelRoom='<img src="'.base_url().'assets/images/norecord.png" > </img>';
	    }

	    echo '<pre>QQQQ';print_r($hotelRoom);die;
	}


	//////////////////////////Html start functions//////////////////////////////////////////////////////////

    public function hotelSearchHtml($hotelsData)
    {
       // echo '<pre>';print_r($hotelsData);die;
       $this->hotelSearchHtml="";
       $this->htlPostData=array();
       foreach($hotelsData as $key=>$hotelData)
       {
 

            $this->hotelSearchHtml.='<div class="col-md-4 col-sm-6 col-xs-12 grid-item hotelList" style="display:none">
                                        <div class="grid-item-inner">
                                            <div class="grid-img-thumb">
                                                <a class="htl_" target="__blank" href="'.base_url().'hotel/hotels-details?a='.$hotelData->ResultIndex.'&b='.$hotelData->HotelCode.'&c='.$hotelData->HotelName.'&d='.self::$TraceId.'" id="htl_'.$hotelData->ResultIndex.'"><img src="'.$hotelData->HotelPicture.'" alt="'.$hotelData->HotelName.'" class="img-responsive" /></a>
                                            </div>
                                            <div class="grid-content">
                                                <div class="grid-price text-right">
                                                    Only <span><sub>Rs</sub>'.$hotelData->Price->OfferedPriceRoundedOff.'</span>
                                                </div>
                                                <div class="grid-text">
                                                    <div class="place-name">'.$hotelData->HotelName.'</div>
                                                    <div class="travel-times">
                                                        <h4 class="pull-left">'.$hotelData->HotelName.'</h4>
                                                        <span class="pull-right">
                                                            <i class="fa '.(4<$hotelData->StarRating?'fa-star':'fa-star-o').'" ></i>
                                                            <i class="fa '.(3<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(2<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(1<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(0<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        //}
      }
    }
    public function hotelSearchHtmlAjax($hotelsData,$post)
    {
       // echo '<pre>';print_r($hotelsData);die;
       $this->hotelSearchHtmlAjax="";
       $this->hotelSearchHtmlAjaxCount=0;
       $post['maxPrice']=str_replace(' ', '', $post['maxPrice']);
       $post['minPrice']=str_replace(' ', '', $post['minPrice']);
       //echo '<pre>';print_r($post['maxPrice']);die;
       foreach($hotelsData as $key=>$hotelData)
       {
       	     //echo '<pre>';print_r($post);die;
            
            if($hotelData->Price->OfferedPriceRoundedOff >= $post['minPrice'] && $hotelData->Price->OfferedPriceRoundedOff<=$post['maxPrice'] && $hotelData->StarRating==$post['rating'])
            {
               ++$this->hotelSearchHtmlAjaxCount;
            $this->hotelSearchHtmlAjax.='<div class="col-md-4 col-sm-6 col-xs-12 grid-item hotelList" style="display:none">
                                        <div class="grid-item-inner">
                                            <div class="grid-img-thumb">
                                                <a class="htl_" target="__blank" href="'.base_url().'hotel/hotels-details?a='.$hotelData->ResultIndex.'&b='.$hotelData->HotelCode.'&c='.$hotelData->HotelName.'&d='.self::$TraceId.'" id="htl_'.$hotelData->ResultIndex.'"><img src="'.$hotelData->HotelPicture.'" alt="'.$hotelData->HotelName.'" class="img-responsive" /></a>
                                            </div>
                                            <div class="grid-content">
                                                <div class="grid-price text-right">
                                                    Only <span><sub>Rs</sub>'.$hotelData->Price->OfferedPriceRoundedOff.'</span>
                                                </div>
                                                <div class="grid-text">
                                                    <div class="place-name">'.$hotelData->HotelName.'</div>
                                                    <div class="travel-times">
                                                        <h4 class="pull-left">'.$hotelData->HotelName.'</h4>
                                                        <span class="pull-right">
                                                            <i class="fa '.(4<$hotelData->StarRating?'fa-star':'fa-star-o').'" ></i>
                                                            <i class="fa '.(3<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(2<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(1<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(0<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        }
      }
      if($this->hotelSearchHtmlAjaxCount==0)
       {
           $this->hotelSearchHtmlAjax.='<img src="'.base_url().'assets/images/norecord.png" > </img>';

       }
    }

    public function hotelSearchHtmlSort($hotelsData,$post)
    {
       // echo '<pre>';print_r($hotelsData);die;
       $this->hotelSearchHtmlSort="";
       $this->hotelSearchHtmlSortCount=0;
       // $post['maxPrice']=str_replace(' ', '', $post['maxPrice']);
       // $post['minPrice']=str_replace(' ', '', $post['minPrice']);
       //echo '<pre>';print_r($post['maxPrice']);die;
       $sortLoop=array();
       if($post['sort']==1)
       {
	       foreach($hotelsData as $key=>$hotelData)
	       {
	           $sortLoop[$hotelData->Price->OfferedPriceRoundedOff]=$hotelData;
	       }
	        
	       if($post['sortBy']==1)
	       {
	           ksort($sortLoop);
	       }
	       else
	       {
	        	krsort($sortLoop);
	       }

	        foreach($sortLoop as $key=>$hotelData)
            {
       	     //echo '<pre>';print_r($post);die;
           
            
               ++$this->hotelSearchHtmlSortCount;
                $this->hotelSearchHtmlSort.='<div class="col-md-4 col-sm-6 col-xs-12 grid-item hotelList" style="display:none">
                                        <div class="grid-item-inner">
                                            <div class="grid-img-thumb">
                                                <a class="htl_" target="__blank" href="'.base_url().'hotel/hotels-details?a='.$hotelData->ResultIndex.'&b='.$hotelData->HotelCode.'&c='.$hotelData->HotelName.'&d='.self::$TraceId.'" id="htl_'.$hotelData->ResultIndex.'"><img src="'.$hotelData->HotelPicture.'" alt="'.$hotelData->HotelName.'" class="img-responsive" /></a>
                                            </div>
                                            <div class="grid-content">
                                                <div class="grid-price text-right">
                                                    Only <span><sub>Rs</sub>'.$hotelData->Price->OfferedPriceRoundedOff.'</span>
                                                </div>
                                                <div class="grid-text">
                                                    <div class="place-name">'.$hotelData->HotelName.'</div>
                                                    <div class="travel-times">
                                                        <h4 class="pull-left">'.$hotelData->HotelName.'</h4>
                                                        <span class="pull-right">
                                                            <i class="fa '.(4<$hotelData->StarRating?'fa-star':'fa-star-o').'" ></i>
                                                            <i class="fa '.(3<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(2<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(1<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                            <i class="fa '.(0<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        
            }
	            if($this->hotelSearchHtmlSortCount==0)
	            {
	             $this->hotelSearchHtmlSort.='<img src="'.base_url().'assets/images/norecord.png" > </img>';

	            }
	   }
	   if($post['sort']==2)
       {
	       foreach($hotelsData as $key=>$hotelData)
	       {
	           $sortLoop[$hotelData->StarRating][]=$hotelData;
	       }
	        
	       if($post['sortBy']==3)
	       {
	            ksort($sortLoop);
	       }
	       else
	       {
	        	krsort($sortLoop);
	       }

            foreach($sortLoop as $key=>$hotelDatas)
            {
       	     //echo '<pre>';print_r($post);die;
                foreach($hotelDatas as $key=>$hotelData)
                {
            
	               ++$this->hotelSearchHtmlSortCount;
	               $this->hotelSearchHtmlSort.='<div class="col-md-4 col-sm-6 col-xs-12 grid-item hotelList" style="display:none">
	                                        <div class="grid-item-inner">
	                                            <div class="grid-img-thumb">
	                                                <a class="htl_" target="__blank" href="'.base_url().'hotel/hotels-details?a='.$hotelData->ResultIndex.'&b='.$hotelData->HotelCode.'&c='.$hotelData->HotelName.'&d='.self::$TraceId.'" id="htl_'.$hotelData->ResultIndex.'"><img src="'.$hotelData->HotelPicture.'" alt="'.$hotelData->HotelName.'" class="img-responsive" /></a>
	                                            </div>
	                                            <div class="grid-content">
	                                                <div class="grid-price text-right">
	                                                    Only <span><sub>Rs</sub>'.$hotelData->Price->OfferedPriceRoundedOff.'</span>
	                                                </div>
	                                                <div class="grid-text">
	                                                    <div class="place-name">'.$hotelData->HotelName.'</div>
	                                                    <div class="travel-times">
	                                                        <h4 class="pull-left">'.$hotelData->HotelName.'</h4>
	                                                        <span class="pull-right">
	                                                            <i class="fa '.(4<$hotelData->StarRating?'fa-star':'fa-star-o').'" ></i>
	                                                            <i class="fa '.(3<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
	                                                            <i class="fa '.(2<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
	                                                            <i class="fa '.(1<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
	                                                            <i class="fa '.(0<$hotelData->StarRating?'fa-star':'fa-star-o').'"></i>
	                                                        </span>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>';
                }
  			}
		    if($this->hotelSearchHtmlSortCount==0)
		    {
		           $this->hotelSearchHtmlSort.='<img src="'.base_url().'assets/images/norecord.png" > </img>';

		    }

	   }
//echo '<pre>';print_r($sortLoop);die;
      
    }
	//////////////////////////Html end functions//////////////////////////////////////////////////////////
	protected function staticDataHotSrch($key)
	{
      

      $array['getHotelRoom']='{
        "GetHotelRoomResult": {
        "ResponseStatus": 1,
        "Error": {
            "ErrorCode": 0,
            "ErrorMessage": ""
        },
        "TraceId": "d6dab17d-f1ef-47fa-9a4e-08cf290da92f",
        "IsUnderCancellationAllowed": true,
        "IsPolicyPerStay": false,
        "HotelRoomsDetails": [
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 1,
                "RoomTypeCode": "3509387|7cbbe94c-0501-d9f4-dbe9-c19e94d4ac8a|1^^1^^494838|144846822|7cbbe94c-0501-d9f4-dbe9-c19e94d4ac8a~!:~1~!:~1",
                "RoomTypeName": "Deluxe Room",
                "RatePlanCode": "494838|144846822|7cbbe94c-0501-d9f4-dbe9-c19e94d4ac8a",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~1",
                "DayRates": [
                    {
                        "Amount": 791.9,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 791.9,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 1903.59,
                    "Tax": 548.22,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 2451.82,
                    "PublishedPriceRoundedOff": 2452,
                    "OfferedPrice": 2451.82,
                    "OfferedPriceRoundedOff": 2452,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 147.11,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [],
                "Amenity": [],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 1226,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Deluxe Room#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 1226.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": []
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 6,
                "RoomTypeCode": "3509387|7cbbe94c-0501-d9f4-dbe9-c19e94d4ac8a|2^^2^^494838|144846822|7cbbe94c-0501-d9f4-dbe9-c19e94d4ac8a~!:~2~!:~2",
                "RoomTypeName": "Deluxe Room",
                "RatePlanCode": "494838|144846822|7cbbe94c-0501-d9f4-dbe9-c19e94d4ac8a",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~1",
                "DayRates": [
                    {
                        "Amount": 791.9,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 791.9,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 1903.59,
                    "Tax": 548.22,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 2451.82,
                    "PublishedPriceRoundedOff": 2452,
                    "OfferedPrice": 2451.82,
                    "OfferedPriceRoundedOff": 2452,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 147.11,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [],
                "Amenity": [],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 1226,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Deluxe Room#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 1226.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": []
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 2,
                "RoomTypeCode": "3509387|9aab5409-c03d-55c7-3081-da5e526d3053|1^^1^^494837|144846822|9aab5409-c03d-55c7-3081-da5e526d3053~!:~3~!:~1",
                "RoomTypeName": "Deluxe Room",
                "RatePlanCode": "494837|144846822|9aab5409-c03d-55c7-3081-da5e526d3053",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~2",
                "DayRates": [
                    {
                        "Amount": 1055.97,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1055.97,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 2538.47,
                    "Tax": 731.56,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3270.03,
                    "PublishedPriceRoundedOff": 3270,
                    "OfferedPrice": 3270.03,
                    "OfferedPriceRoundedOff": 3270,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 196.2,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [
                    "Breakfast"
                ],
                "Amenity": [
                    "Breakfast"
                ],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 1635,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Deluxe Room#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 1635.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": [
                    "Breakfast"
                ]
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 7,
                "RoomTypeCode": "3509387|9aab5409-c03d-55c7-3081-da5e526d3053|2^^2^^494837|144846822|9aab5409-c03d-55c7-3081-da5e526d3053~!:~4~!:~2",
                "RoomTypeName": "Deluxe Room",
                "RatePlanCode": "494837|144846822|9aab5409-c03d-55c7-3081-da5e526d3053",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~2",
                "DayRates": [
                    {
                        "Amount": 1055.97,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1055.97,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 2538.47,
                    "Tax": 731.56,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3270.03,
                    "PublishedPriceRoundedOff": 3270,
                    "OfferedPrice": 3270.03,
                    "OfferedPriceRoundedOff": 3270,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 196.2,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [
                    "Breakfast"
                ],
                "Amenity": [
                    "Breakfast"
                ],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 1635,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Deluxe Room#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 1635.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": [
                    "Breakfast"
                ]
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 3,
                "RoomTypeCode": "3509388|28c8e3b8-2f7b-cdc3-d130-93539da1f886|1^^1^^494838|144846822|28c8e3b8-2f7b-cdc3-d130-93539da1f886~!:~5~!:~1",
                "RoomTypeName": "Executive Room",
                "RatePlanCode": "494838|144846822|28c8e3b8-2f7b-cdc3-d130-93539da1f886",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~3",
                "DayRates": [
                    {
                        "Amount": 1124.36,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1124.36,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 2702.88,
                    "Tax": 778.92,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3481.79,
                    "PublishedPriceRoundedOff": 3482,
                    "OfferedPrice": 3481.79,
                    "OfferedPriceRoundedOff": 3482,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 208.91,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [],
                "Amenity": [],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 1741,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Executive Room#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 1741.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": []
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 8,
                "RoomTypeCode": "3509388|28c8e3b8-2f7b-cdc3-d130-93539da1f886|2^^2^^494838|144846822|28c8e3b8-2f7b-cdc3-d130-93539da1f886~!:~6~!:~2",
                "RoomTypeName": "Executive Room",
                "RatePlanCode": "494838|144846822|28c8e3b8-2f7b-cdc3-d130-93539da1f886",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~3",
                "DayRates": [
                    {
                        "Amount": 1124.36,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1124.36,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 2702.88,
                    "Tax": 778.92,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3481.79,
                    "PublishedPriceRoundedOff": 3482,
                    "OfferedPrice": 3481.79,
                    "OfferedPriceRoundedOff": 3482,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 208.91,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [],
                "Amenity": [],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 1741,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Executive Room#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 1741.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": []
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 4,
                "RoomTypeCode": "3509390|71503ec4-ee1a-67b7-59f5-330c74f9cddd|1^^1^^494838|144846822|71503ec4-ee1a-67b7-59f5-330c74f9cddd~!:~7~!:~1",
                "RoomTypeName": "Family Suite",
                "RatePlanCode": "494838|144846822|71503ec4-ee1a-67b7-59f5-330c74f9cddd",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~4",
                "DayRates": [
                    {
                        "Amount": 1574.34,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1574.34,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 3784.44,
                    "Tax": 1089.79,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 4874.23,
                    "PublishedPriceRoundedOff": 4874,
                    "OfferedPrice": 4874.23,
                    "OfferedPriceRoundedOff": 4874,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 292.45,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [],
                "Amenity": [],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 2437,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Family Suite#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 2437.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": []
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 9,
                "RoomTypeCode": "3509390|71503ec4-ee1a-67b7-59f5-330c74f9cddd|2^^2^^494838|144846822|71503ec4-ee1a-67b7-59f5-330c74f9cddd~!:~8~!:~2",
                "RoomTypeName": "Family Suite",
                "RatePlanCode": "494838|144846822|71503ec4-ee1a-67b7-59f5-330c74f9cddd",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~4",
                "DayRates": [
                    {
                        "Amount": 1574.34,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1574.34,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 3784.44,
                    "Tax": 1089.79,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 4874.23,
                    "PublishedPriceRoundedOff": 4874,
                    "OfferedPrice": 4874.23,
                    "OfferedPriceRoundedOff": 4874,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 292.45,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [],
                "Amenity": [],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 2437,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Family Suite#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 2437.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": []
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 5,
                "RoomTypeCode": "3509390|d1f3fc65-1b34-e0cc-2386-e37774488ba9|1^^1^^494837|144846822|d1f3fc65-1b34-e0cc-2386-e37774488ba9~!:~9~!:~1",
                "RoomTypeName": "Family Suite",
                "RatePlanCode": "494837|144846822|d1f3fc65-1b34-e0cc-2386-e37774488ba9",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~5",
                "DayRates": [
                    {
                        "Amount": 1799.37,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1799.37,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4325.22,
                    "Tax": 1244.41,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5569.64,
                    "PublishedPriceRoundedOff": 5570,
                    "OfferedPrice": 5569.64,
                    "OfferedPriceRoundedOff": 5570,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 334.18,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [
                    "Breakfast"
                ],
                "Amenity": [
                    "Breakfast"
                ],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 2785,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Family Suite#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 2785.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": [
                    "Breakfast"
                ]
            },
            {
                "ChildCount": 0,
                "RequireAllPaxDetails": false,
                "RoomId": 0,
                "RoomStatus": 0,
                "RoomIndex": 10,
                "RoomTypeCode": "3509390|d1f3fc65-1b34-e0cc-2386-e37774488ba9|2^^2^^494837|144846822|d1f3fc65-1b34-e0cc-2386-e37774488ba9~!:~10~!:~2",
                "RoomTypeName": "Family Suite",
                "RatePlanCode": "494837|144846822|d1f3fc65-1b34-e0cc-2386-e37774488ba9",
                "RatePlan": 0,
                "InfoSource": "FixedCombination",
                "SequenceNo": "TH~~1083367~5",
                "DayRates": [
                    {
                        "Amount": 1799.37,
                        "Date": "2017-12-18T00:00:00"
                    },
                    {
                        "Amount": 1799.37,
                        "Date": "2017-12-19T00:00:00"
                    }
                ],
                "SupplierPrice": null,
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4325.22,
                    "Tax": 1244.41,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5569.64,
                    "PublishedPriceRoundedOff": 5570,
                    "OfferedPrice": 5569.64,
                    "OfferedPriceRoundedOff": 5570,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 334.18,
                    "TDS": 0
                },
                "RoomPromotion": "Limited time offer. Rate includes 5% discount!|",
                "Amenities": [
                    "Breakfast"
                ],
                "Amenity": [
                    "Breakfast"
                ],
                "SmokingPreference": "NoPreference",
                "BedTypes": [],
                "HotelSupplements": [],
                "LastCancellationDate": "2017-12-12T23:59:59",
                "CancellationPolicies": [
                    {
                        "Charge": 0,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-07T00:00:00",
                        "ToDate": "2017-12-12T23:59:59"
                    },
                    {
                        "Charge": 2785,
                        "ChargeType": 1,
                        "Currency": "INR",
                        "FromDate": "2017-12-13T00:00:00",
                        "ToDate": "2017-12-16T23:59:59"
                    },
                    {
                        "Charge": 100,
                        "ChargeType": 2,
                        "Currency": "INR",
                        "FromDate": "2017-12-17T00:00:00",
                        "ToDate": "2017-12-20T23:59:59"
                    }
                ],
                "CancellationPolicy": "Family Suite#^#No cancellation charge, If cancelled between 07-Dec-2017 00:00:00 and 12-Dec-2017 23:59:59.|INR 2785.00 will be charged, If cancelled between 13-Dec-2017 00:00:00 and 16-Dec-2017 23:59:59.|100.00% of total amount will be charged, If cancelled between 17-Dec-2017 00:00:00 and 20-Dec-2017 23:59:59.|#!#",
                "Inclusion": [
                    "Breakfast"
                ]
            }
        ],
        "RoomCombinations": {
            "InfoSource": "FixedCombination",
            "IsPolicyPerStay": false,
            "RoomCombination": [
                {
                    "RoomIndex": [
                        1,
                        6
                    ]
                },
                {
                    "RoomIndex": [
                        2,
                        7
                    ]
                },
                {
                    "RoomIndex": [
                        3,
                        8
                    ]
                },
                {
                    "RoomIndex": [
                        4,
                        9
                    ]
                },
                {
                    "RoomIndex": [
                        5,
                        10
                    ]
                }
            ]
        }
    }
}';





      $array['getHotelInfo']= '{
    "HotelInfoResult": {
        "ResponseStatus": 1,
        "Error": {
            "ErrorCode": 0,
            "ErrorMessage": ""
        },
        "TraceId": "a712b0ac-55a2-4be9-ad0f-ef4f2fae4b6e",
        "HotelDetails": {
            "HotelCode": "339532",
            "HotelName": "Radisson Blu Hotel Greater Noida",
            "StarRating": 5,
            "HotelURL": null,
            "Description": "Set in a prime location of New Delhi and NCR, Radisson Blu Hotel Greater Noida puts everything the city has to offer just outside your doorstep. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. Take advantage of the hotel\'s 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, fireplace. Some of the well-appointed guestrooms feature television LCD/plasma screen, carpeting, free welcome drink, linens, locker. Recuperate from a full day of sightseeing in the comfort of your room or take advantage of the hotel\'s recreational facilities, including hot tub, fitness center, sauna, outdoor pool, spa. Radisson Blu Hotel Greater Noida is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.&nbsp;<br/><b>Disclaimer notification: Amenities are subject to availability and may be chargeable as per the hotel policy.</b>&nbsp; <br />",
            "Attractions": null,
            "HotelFacilities": [
                "24-hour front desk",
                "24-hour room service",
                "airport transfer",
                "bar",
                "car hire",
                "car park",
                "coffee shop",
                "concierge",
                "currency exchange",
                "designated smoking area",
                "dry cleaning",
                "elevator",
                "express check-in/check-out",
                "facilities for disabled guests",
                "family room",
                "free Wi-Fi in all rooms",
                "laundry service",
                "lockers",
                "luggage storage",
                "newspapers",
                "restaurant",
                "room service",
                "safety deposit boxes",
                "salon",
                "shops",
                "tours",
                "valet parking",
                "Wi-Fi in public areas",
                "air conditioning",
                "bathrobes",
                "blackout curtains",
                "coffee/tea maker",
                "daily newspaper",
                "flat screen television",
                "mini bar",
                "shower",
                "soundproofing",
                "telephone",
                "television",
                "toiletries",
                "trouser press",
                "fitness center",
                "hot tub",
                "massage",
                "outdoor pool",
                "pool (kids)",
                "sauna",
                "spa",
                "steamroom"
            ],
            "HotelPolicy": "Remark :Smoking is only allowed in designated areas. Guests smoking elsewhere are responsible for all costs, damages, and liabilities caused by smoking.\r\nInfant 1 year(s) : Stay for free if using existing bedding. Note, if you need a cot there may be an extra charge.Children 2 - 7 year(s) : Stay for free if using existing bedding.| | Early check out will attract full cancellation charge unless otherwise specified.|Remark :Smoking is only allowed in designated areas. Guests smoking elsewhere are responsible for all costs, damages, and liabilities caused by smoking.\r\nInfant 1 year(s) : Stay for free if using existing bedding. Note, if you need a cot there may be an extra charge.Children 2 - 7 year(s) : Stay for free if using existing bedding.||",
            "SpecialInstructions": null,
            "HotelPicture": null,
            "Images": [
                "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YkpH7GRV/CIdxIYR+jt/Seo1RqBnwPglTpAy9P/k7wb9AK9ywykPsIdEyZ6OeXncM/Nw4hfeUAGscoKnPFqmxkM5nizMD3/lUNK3bQUFaUFyLKWKXVsUQkY=",
                "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUMB6jxbEKVoF68bzZRiRVmpl4Sug1ePiLaf7Y/9UcroArwG0eyPP3aUrikIYWS8IfAYmhda5qrqWbDFasiY0nO6Nh55fx0ESaARjBpLkRrcSz5CP4AtOIFo=",
                "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxP+npyDe1rQDrQaGHDvZUi0D+3AfAdmBjV10HQ3QQB9ML9+Ladjz+7+42IlIoHbrop5K3jgXHQtLx2KFw/zPypjtfp8ipE2K1w==",
                "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YkpH7GRV/CIdxIYR+jt/Seo1RqBnwPglTvAKxihfEvD9hiW/hDfCgvS+UAJDqlaLbQRVNSxK/z+UyGuqJKbIHmtG4Cfji4+ruA==",
                "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02Hjx+sXrdj3VLjOdDWpLC8HRembCs6Wrsf9pKiRULo/ub6dRy4xcIO6Mxuh2nGeSD1X0Ff0Ic9IDPfOhj54uJ33cIFv6mHt/tg=="
            ],
            "Address": "C-8, Site-4,2nd Cross Avenue Road, Kasna Greater Noida, New Delhi and NCR, India, ",
            "CountryName": "",
            "PinCode": "201306",
            "HotelContactNo": null,
            "FaxNumber": null,
            "Email": null,
            "Latitude": "28.45013",
            "Longitude": "77.52972",
            "RoomData": null,
            "RoomFacilities": null,
            "Services": null
        }
    }
}';





      $array['getHotelSrch']= '{
    "HotelSearchResult": {
        "ResponseStatus": 1,
        "Error": {
            "ErrorCode": 0,
            "ErrorMessage": ""
        },
        "TraceId": "a7c2b6cc-3987-4fda-8c37-da3bd07f08e5",
        "CityId": "10409",
        "CheckInDate": "2017-12-18",
        "CheckOutDate": "2017-12-20",
        "PreferredCurrency": "INR",
        "NoOfRooms": 2,
        "RoomGuests": [
            {
                "NoOfAdults": 1,
                "NoOfChild": 1,
                "ChildAge": [
                    7
                ]
            },
            {
                "NoOfAdults": 1,
                "NoOfChild": 0,
                "ChildAge": []
            }
        ],
        "HotelResults": [
            {
                "ResultIndex": 1,
                "HotelCode": "1386",
                "HotelName": "The Leela",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "12   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 396.73,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 396.73,
                    "PublishedPriceRoundedOff": 397,
                    "OfferedPrice": 396.73,
                    "OfferedPriceRoundedOff": 397,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEfM8uIePElhECnAgAl7HcG+Eiznyjv2UJ6fYM7YFGbIC2r4EAw4rc/YVTzpjOqb85UBZxw9WL2RPphLvjC0W/IPAfCjqrAV7BUp2rVxNPUM6Vzyt44GE6ozxu1SSRMSEmcocjS/EoyCXBIBJhqIY7/+DWb7gn+6IxKXdDZP30o8PQ==",
                "HotelAddress": "4 th, Delhi, , 895465, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 2,
                "HotelCode": "1476",
                "HotelName": "City Centre",
                "HotelCategory": "",
                "StarRating": 2,
                "HotelDescription": "rew   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 500.5,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 500.5,
                    "PublishedPriceRoundedOff": 500,
                    "OfferedPrice": 500.5,
                    "OfferedPriceRoundedOff": 500,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEfM8uIePElhECnAgAl7HcG+Eiznyjv2UJ6fYM7YFGbIC2r4EAw4rc/YVTzpjOqb85UBZxw9WL2RPhO+vQXaEMtWM3o8IwuXThBgoGplaiNCegtBS8v86AN0gFKqV0xGy8sYC/AaG08mw9FqK/jaGJhbex7Ko8IshtHBpgkdzCD4Qw==",
                "HotelAddress": "rt, Delhi, , 56546546, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 96,
                "HotelCode": "1341686",
                "HotelName": "Treebo Natraj Yes Please",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 3500.68,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3522.68,
                    "PublishedPriceRoundedOff": 3523,
                    "OfferedPrice": 3500.68,
                    "OfferedPriceRoundedOff": 3501,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB582/neiLd5LNkLqeJh5x0j/t20K8Voze/ZUb6FeljKTmK3s+7NhI4SX3cZICeCjFY=",
                "HotelAddress": "1750, Chuna Mandi, Opposite Imperial Cinema.,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.640774",
                "Longitude": "77.2094",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 3,
                "HotelCode": "291165",
                "HotelName": "Hotel Sai International",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in Pahar Ganj, Hotel Sai International is a perfect starting point from which to explore New Delhi and NCR. Both business travelers and tourists can enjoy the hotel\'s facilities and services. Take advantage of the hotel\'s 24-hour room service, free Wi-Fi in all rooms, 24-hour front desk, facilities for disabled guests, luggage storage. Guestrooms are fitted with all the amenities you need for a good night\'s sleep. In some of the rooms, guests can find television LCD/plasma screen, air conditioning, telephone, fan, satellite/cable TV. The hotel offers various recreational opportunities. A welcoming atmosphere and excellent service are what you can expect during your stay at Hotel Sai International.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 3581.35,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3581.35,
                    "PublishedPriceRoundedOff": 3581,
                    "OfferedPrice": 3581.35,
                    "OfferedPriceRoundedOff": 3581,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YuPOOvoR3oi41p7IJJgwnqdVVsSt9BrqXCmB3Ts4ohF75EO80qbVABf5FQAl+iy2/MAHbk93E5qZ+CbXXTrcxrFXHZLRtIeMpwghIFJreWO8Ov9ZHjkXI4g=",
                "HotelAddress": "7864,Arakashan Road, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 4,
                "HotelCode": "239424",
                "HotelName": "Hotel Airport Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Set in a prime location of New Delhi and NCR, Hotel Airport Inn puts everything the city has to offer just outside your doorstep. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. Free Wi-Fi in all rooms, 24-hour security, 24-hour front desk, 24-hour room service, Wi-Fi in public areas are on the list of things guests can enjoy. Designed for comfort, selected guestrooms offer television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), non smoking rooms, air conditioning to ensure a restful night. The hotel offers various recreational opportunities. For reliable service and professional staff, Hotel Airport Inn caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 3700.26,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3700.26,
                    "PublishedPriceRoundedOff": 3700,
                    "OfferedPrice": 3700.26,
                    "OfferedPriceRoundedOff": 3700,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUJqKg5BZpC9KhPoc/nFQZHyJdnAUMebnQ8BuHTxYxbD0j7taB2NN0yWOrqs7NghlgvbqMIIUsmjxqIiY4l43ysnqHFnduQmu5A==",
                "HotelAddress": "A-7, Street No.1, Mahipalpur extn. Near IGI Airport, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 5,
                "HotelCode": "566846",
                "HotelName": "Smyle Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Smyle Inn is conveniently located in the popular Pahar Ganj area. Both business travelers and tourists can enjoy the hotel\'s facilities and services. Facilities like 24-hour room service, free Wi-Fi in all rooms, 24-hour front desk, express check-in/check-out, luggage storage are readily available for you to enjoy. Designed for comfort, selected guestrooms offer television LCD/plasma screen, additional bathroom, additional toilet, air purifier, free welcome drink to ensure a restful night. Recuperate from a full day of sightseeing in the comfort of your room or take advantage of the hotel\'s recreational facilities, including games room. Smyle Inn combines warm hospitality with a lovely ambiance to make your stay in New Delhi and NCR unforgettable.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 3940.79,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 3940.79,
                    "PublishedPriceRoundedOff": 3941,
                    "OfferedPrice": 3940.79,
                    "OfferedPriceRoundedOff": 3941,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxIffNMZMxVxJbEoDIxzklCOpLut6jqqh2Oky4L467HjFhfSe6+0CCsMrJ05j66Gl3yK46nNxQM0QR0AmZ11gRsSvfnGj4ag21DlDFaulYJaR2N6JngtRWLg=",
                "HotelAddress": "916, Gali Chandi Wali, Main Bazaar, Delhi National Territory, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 6,
                "HotelCode": "271687",
                "HotelName": "Hotel Amax Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The 3-star Hotel Amax Inn offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, photocopying are there for guest\'s enjoyment. Comfortable guestrooms ensure a good night\'s sleep with some rooms featuring facilities such as television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), non smoking rooms, air conditioning. Take a break from a long day and make use of massage. A welcoming atmosphere and excellent service are what you can expect during your stay at Hotel Amax Inn.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4033.21,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 4033.21,
                    "PublishedPriceRoundedOff": 4033,
                    "OfferedPrice": 4033.21,
                    "OfferedPriceRoundedOff": 4033,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02P6fAcgap6JiTTveBDZUU6llGuSgMBjtBRo/B1xyHIU3sYEC++Rp+SBiHeF5aL57SYTOdqFmn2j1NU6Arz3m6Z/qyU5IiNuUpvauYwXYl/JbH0e4wadCHyE=",
                "HotelAddress": "8145/6, Arakashan Road, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 7,
                "HotelCode": "1185185",
                "HotelName": "The Raj",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Hotel The Raj, you&apos;ll be centrally located in New Delhi, convenient to Ramakrishna Mission and Red Fort.  This hotel is wi  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4518.58,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 4518.58,
                    "PublishedPriceRoundedOff": 4519,
                    "OfferedPrice": 4518.58,
                    "OfferedPriceRoundedOff": 4519,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1fgKqm3E2zkBFCSxHU14YtAveN6XcciugsN5c8Sy3Tl7ZnReYJCNMbTsoWQLq+xbfGP5ohbhcQd//xehRauR2P2wgU8UN/Pg/w==",
                "HotelAddress": "8495 Arakahan Road Paharganj , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 8,
                "HotelCode": "1083367",
                "HotelName": "Hotel Airport City",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location Centrally located in New Delhi, Hotel Airport City is convenient to DLF Emporio Vasant Kunj and Jawaharlal Nehru University.  This luxury hote  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4941.79,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 4941.79,
                    "PublishedPriceRoundedOff": 4942,
                    "OfferedPrice": 4941.79,
                    "OfferedPriceRoundedOff": 4942,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1fgKqm3E2zkBFCSxHU14YtA9aqgEk7Z0Vfcw/2zTlC3fg2RvytBaoeC5ByWwz0H9RJSp1Jpv3LLq89OYNy5uWrmp+9jZumqPmw==",
                "HotelAddress": "A-11 Road no-1 N.H. -8 Near I.G.I Airport Mahipalpur Extn , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 97,
                "HotelCode": "327859",
                "HotelName": "Hotel Hari Piorko",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Hari Piorko is a 3 star hotel close to the New Delhi Railway Station offering free Wi-Fi to the guests. It is very near to the shopping district in Central Delhi making it an ideal abode for leisure travelers. Elegant interiors & teak wood furniture makes it more appealing.\n\n<b>Location</b>\nThe hotel is situated in the Pahar Ganj area - Central Delhi. It enjoys close proximity to popular tourist locations such as Connaught Place (approx 3 km), Jantar Mantar (approx 3 km), India Gate (approx 5 km). Other places of interest are Gole Market, Parliament of India and Birla Mandir Temple.\n\nDistance from Indira Gandhi Airport: Approx 16 km\nDistance from New Delhi Railway Station: Approx 2 km\nDistance from Nizzamuddin Railway Station: Approx 10 km\n\n<b>Hotel Features</b>\nHotel Hari Piorko provides array of facilities to guarantee a satisfying travel experience. It has an in-house dining hall and restaurant. German Panickel Bakery offers earth oven baked fresh bakery products. 24 hour room service is available for added convenience. Therapeutic health massage is also available in the hotel. Other services include laundry service, travel desk, money exchange, safety deposit and doctor on call.\n\n<b>Rooms</b>\nThe hotel rooms are divided into Executive and Deluxe categories. Rooms are well equipped with amenities such as air-conditioning, LCD TVs, refrigerators, private bathrooms and hot and cold water. A wooden wardrobe serves the purpose of storage. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4532.3,
                    "Tax": 546.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5100.82,
                    "PublishedPriceRoundedOff": 5101,
                    "OfferedPrice": 5078.82,
                    "OfferedPriceRoundedOff": 5079,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5nOqoN6hN2KbZjmq3E3Dlb2HYPpManWpsyeqJ4XePNif66j0Z8WfOW",
                "HotelAddress": "4775, Main Bazaar, Near 6 Tooti Chowk, Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.6411057",
                "Longitude": "77.2142836",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 98,
                "HotelCode": "311620",
                "HotelName": "Le Roi Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Indulge in first class hospitality with the Hotel Le Roi that offers lavishness and comfort at its very best. Thanks to the great business facilities and a fine dining experience, this amazing boutique hotel serves best for business and leisure travelers.\n\n<b>Location:</b>\nThis affluent hotel is located at Chuna Mandi near Paharganj, New Delhi. Famous commercial centers like  Jantar Mantar (approx 3 km), Jama Masjid (approx 4 km), India Gate (approx 5 km) are at close proximity to the hotel, thereby giving a chance for the travelers to roam around and shop. Other famous tourist spots like  Connaught Place ,Karol Baugh ,Red Fort and Chandni Chowk are very close to the hotel too.\n\nDistance from Indira Gandhi Airport: Approx. 15 km\nDistance from New Delhi Railway Station: 3 km\nDistance from Nizzamuddin Railway Station: Approx. 9 km\n\n<b>Hotel Features:</b> \nHotel Le Roi offers facilities like laundry, Wi-Fi access, sound proof windows, elevators, uninterrupted power supply and also doctor on-call service in case of emergencies. The guests can contact the 24-hour travel desk for optimising travel itineraries. \n\n<b>Rooms:</b> \nThe rooms of this hotel feature amazing decor with chic and sophisticated designs. Well-furnished and appointed with good modern facilities, each room is equipped with air conditioning, internet facilities, LCD television, well-stocked mini bar and a tea/coffee maker machine. ",
                "HotelPromotion": "Book now and save 45%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4595.8,
                    "Tax": 554.14,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 3778.2,
                    "PublishedPrice": 5171.94,
                    "PublishedPriceRoundedOff": 5172,
                    "OfferedPrice": 5149.94,
                    "OfferedPriceRoundedOff": 5150,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5EscsPJxI71N2GOKY6jUzcphqu7kvOuDPzacJpSn2g53qasIB8AsbK",
                "HotelAddress": "2206, Chuna Mandi, Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.64427",
                "Longitude": "77.210664",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 99,
                "HotelCode": "724497",
                "HotelName": "Hotel Global Radiance",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Global Radiance, New Delhi, is synonymous with modern dcor, bright interiors and warm ambience. Centrally located in the city, the hotel is well-suited for leisure as well as business travellers. The well-appointed rooms with a peaceful ambience ensure a pleasant stay.\n\n<b>Location:</b>\nHotel Global Radiance is situated at road No-6, Mahipalpur Extension. Remarkable tourist destinations like Aravalli Biodiversity Park (Approx. 5km) and Fellowship Of Believer\'s Abundant Life Church (Approx. 2km) are located in the vicinity of the hotel. Other destinations that can also be visited are Qutub Minar, Connaught Place and India Gate. Situated on Raj Path, India Gate is a tall gate with an eternal flame called \'Amar Jawan Jyoti\' that pays tribute to the soldiers who died during the Indian Independence. \nIt is also surrounded by beautiful fountains and gardens.\n\nDistance from Indira Gandhi International Airport: 5 km (approx.)\nDistance from New Delhi Railway Station: 21 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel ensures complete relaxation and comfort by rendering all the essential amenities to the patrons. Radical services include air conditioning, room service, front desk, travel desk, internet, non smoking rooms, elevators, guests lift to all floors, parking, security and doctor-on-call. The board room, meeting rooms with audio visual equipment, meeting facilities and business services satiate the needs of business travellers during the stay. With the banqueting facilities, patrons can celebrate social events with ease. Gorge on mouth-watering dishes and drinks at the multi-cuisine restaurant of the hotel.\n\n<b>Rooms:</b>\nPatrons can choose to stay in standard room, deluxe room, super deluxe room or executive double room of the hotel. Guests are sure to feel at ease in these rooms which exude contemporary elegance and warmth. The in-room amenities include air conditioning, flat screen television, tea/coffee maker, safe, internet access, writing desk and telephone. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4728,
                    "Tax": 570,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5320,
                    "PublishedPriceRoundedOff": 5320,
                    "OfferedPrice": 5298,
                    "OfferedPriceRoundedOff": 5298,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4iJkjhnsf4bggGNq8sNkhcK2h9UsG9l/60vFob3GYrn0tW+Q9Y44Y5",
                "HotelAddress": "A-250, Road No-6,\t\t \nMahipalpur Extension,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.542281",
                "Longitude": "77.124015",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 100,
                "HotelCode": "308420",
                "HotelName": "Airport Hotel Ambrosia",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Ambrosia is one of the leading hotels in New Delhi, ideal for business and leisure travellers. Guests find the hotel to be value for money with friendly staff and delicious food.\n\n<b>Location:</b>\nHotel Ambrosia is located at Mahipalpur, International Airport Zone, New Delhi. Some of the famous sight-seeing attractions at proximity from the hotel are Qutab Minar, Jantar Mantar, Chattarpur Mandir, and Lotus Temple. Other major attractions at a distance from the hotel are India Gate, Red Fort, Chandni Chowk and Humayun\'s tomb.\n\nDistance from Indira Gandhi Airport: Approx. 3km\nDistance from New Delhi Railway Station: Approx. 12km\nDistance from Nizzamuddin Railway Station: Approx. 15km\n\n<b>Hotel Features:</b>\nHotel Ambrosia is a well-designed hotel offering modern amenities to its guests including Wi-Fi, 24 hour front desk, non-smoking rooms, travel desk, doctor-on-call, security and conference hall. The hotel also houses a fine dining restaurant. The restaurant serves authentic Indian, Chinese and continental cuisine in a comfortable ambience and perfect-setting for spending an evening with your loved ones.\n\n<b>Rooms:</b>\nHotel Ambrosia offers spacious accommodation of rooms which are well-designed with quality furnishing and elegant interiors. Each room is well equipped with AC, internet, flat-screen TV, direct dial, tea/coffee maker, writing desk and safe. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 4778,
                    "Tax": 576,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5376,
                    "PublishedPriceRoundedOff": 5376,
                    "OfferedPrice": 5354,
                    "OfferedPriceRoundedOff": 5354,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4O29ZWImShGQ3zVYkxDBOMpSC8O6H9ikPoe5niodj3yz+xew40LUtu",
                "HotelAddress": "A 217, KH No.416, Road No.-6, Mahipalpur Extension,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.550381",
                "Longitude": "77.131619",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 9,
                "HotelCode": "1475064",
                "HotelName": "Persona International - Demo",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Demo -    ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5561.75,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5561.75,
                    "PublishedPriceRoundedOff": 5562,
                    "OfferedPrice": 5561.75,
                    "OfferedPriceRoundedOff": 5562,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEemnIyiBY3pXWioEU6GJUEJliFiVw/YX7SxlJat8q+hioAc1JH7OfYwPBsHLN3xWHDzUIoh7ZnKmX4q6gnMzSz7FQnN/49IKDmCYf2SGNFhxICSLWJhMa7K858ATXHvxiHq9fN/ZxqEgHU3TQXSkZsYg8royU2pxzBCeNm1AxCOlQ==",
                "HotelAddress": "Demo - 12A17 Saraswati Marg, Karol Bagh New Delhi  110005, New Delhi, , , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 101,
                "HotelCode": "318305",
                "HotelName": "The White Klove",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The White Klove Hotel, New Delhi is a contemporary style hotel with refined luxury, superior amenities and services. With 3 dining outlets, recreational facilities and relaxed ambience, the property ensures a wonderful stay to the travelers. \n\n<b>Location</b>\nWhite Klove Hotel is located in Paharganj area in Central Delhi. It is located just 5 minutes from Connaught place and 15 minutes away from the Red Fort and Jama Masjid. Many popular tourist locations such as Jantar Mantar (approx 3 km), India Gate (approx 5 km), Humayuns Tomb (approx 9 km) are situated nearby.\n\nDistance from Indira Gandhi Airport: Approx 15km\nDistance from New Delhi Railway Station: Approx 3 km\nDistance from Nizzamuddin Railway Station: Approx 9 km\n\n<b>Hotel Features</b>\nThe White Klove hotel provides exclusive boutique services such as a spa, swimming pool, gym and salon. Moti Mahal Rajputana restaurant has a great ambience and caters to a variety of tastes. The roof top restaurant offers multi-cuisine food and overlooks the hotels beautifully manicured gardens. The hotel also has a cafe in the form of Cafe holic, which serves a wide range or teas, coffees and desserts. For the business travelers, conference and meeting facility is also available in the hotel. \n\n<b>Rooms</b>\nThe White Klove hotel has 10 Executive Rooms, 10 Club Rooms and 7 Nirvana Suite rooms. Complimentary wireless internet access and a writing desk are available for the business guests. Apart from the standard amenities Executive and Premium rooms come fitted with a bathtub. ",
                "HotelPromotion": "Seasonal Special 70% Discount!!!",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5018,
                    "Tax": 604.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 11760,
                    "PublishedPrice": 5644.8,
                    "PublishedPriceRoundedOff": 5645,
                    "OfferedPrice": 5622.8,
                    "OfferedPriceRoundedOff": 5623,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5W9ptiLcrrkGh1XFSP8TY7C132oJD24/ShaF+nMqXrf0jp+a00ghGoT4PsloTKdo8MROLE2DPErg==",
                "HotelAddress": "1563, Laxmi Narayan Street, Near Imperial Cinema, Behind Rama Krishna Metro Station, Punchkuian Road,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.641223",
                "Longitude": "77.21184",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 10,
                "HotelCode": "1083269",
                "HotelName": "Hotel Hari Piorko",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location A stay at Hotel Hari Piorko places you in the heart of New Delhi, minutes from Ramakrishna Mission and close to Red Fort.  This hotel is withi  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5688.76,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5688.76,
                    "PublishedPriceRoundedOff": 5689,
                    "OfferedPrice": 5688.76,
                    "OfferedPriceRoundedOff": 5689,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1VbldshbyoCVTJbNEp36GcybnMHdE/7DkjieYwM0pGPUx5iEFbSpn5VTwoXWkzoN2jD/Aq7131rtf3x/htNvM41ZqlqzUp4ip0BS4pYhmvbfBZO54VU65P8=",
                "HotelAddress": "4775, Main Bazar, Near 6 Tooti Chowk  , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 102,
                "HotelCode": "714147",
                "HotelName": "Hotel Blue Pearl - Paharganj",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Blue Pearl Paharganj is a 3 star hotel strategically located in Paharganj very close to New Delhi railway station. It is an economical property with the city\'s popular attractions in close vicinity. Elegantly furnished rooms equipped with necessary amenities make this hotel an ideal stay for the guests.\n\n<b>Location</b>\nThe property is located in Paharganj area of Central Delhi. Connaught Place (3 km approx.), India Gate (5 km approx.), Red Fort (6 km approx.), Birla Mandir (3 km approx.), Jama Masjid (3 kms approx.) are nearby places of interest.\n\nDistance from New Delhi Railway Station: 2 km (approx.)\nDistance from IGI Airport: 16 km (approx.)\n\n<b>Features</b>\nHotel Blue Pearl Paharganj is has pleasant interiors and provides basic facilities to the travelers. The property offers Wi-Fi, 24-hour front desk, 24-hour room service, Parking, Non-smoking rooms and Doctor on call facility.\n\n<b>Rooms</b>\nThe rooms at Hotel Blue Pearl are well-furnished and spacious. Private bathroom, air conditioning, color television,   internet access, newspaper is provided in each room. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5178,
                    "Tax": 624,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5824,
                    "PublishedPriceRoundedOff": 5824,
                    "OfferedPrice": 5802,
                    "OfferedPriceRoundedOff": 5802,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5JmBDdYnzCoXIn5p5oiAJG+GSdInKXc91u9Zysq+khebOdmmD0vUp+",
                "HotelAddress": "Arakashan Road, Near New Delhi Railway Station, Behind Ajanta Hotel,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645563",
                "Longitude": "77.216073",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 103,
                "HotelCode": "41945",
                "HotelName": "Hotel Le Heritage",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Le Heritage, New Delhi is an elegant business offering in the city. The personalized services and cordial hospitality make guests experience pleasurable. The delightful cuisine entices foodies and the conference facilities make the hotel perfect for business travellers. \n\n<b>Location: </b>\nHotel Le Heritage is located near telephone exchange, Karol Bagh. Lakshmi Narayan Mandir (Approx. 5km) and Jama Masjid (Approx. 6km) are proximate places from the hotel. Jama Masjid was commissioned by the Mughal Emperor Shah Jahan and is considered to be one of the biggest mosques in Old Delhi. Other distant places which can also be toured are Iron Pillar, Nehru Park and Red Fort. \n\nDistance from Indira Gandhi Airport: Approx. 15kms\nDistance from New Delhi Railway Station: Approx. 5kms\nDistance from Nizzamuddin Railway Station: Approx. 12kms\n\n<b>Hotel Features: </b>\nHotel Le Heritage features thoughtful amenities to ensure an unparalleled sense of comfort. The basic facilities rendered by the hotel are front desk, parking, travel desk, room service, security and doctor-on-call. Guests can request for airport shuttle to enjoy convince of travelling. Business clients can hire the conference hall which is equipped by audio visual equipment and LCD/projector. \n\n<b>Rooms: </b>\nThe hotel provides well-maintained rooms which are categorized as suite, deluxe, economy and standard. The simplistic decor of the each room offers complete relaxation. Some of the common in-room amenities are TV, safe, phone and refrigerator. Guests can enjoy tea/coffee in the comfort of their rooms. Additionally, in-room menu and temperature control are some of the contemporary amenities available in each room. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5278,
                    "Tax": 636,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 5936,
                    "PublishedPriceRoundedOff": 5936,
                    "OfferedPrice": 5914,
                    "OfferedPriceRoundedOff": 5914,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4sYCI5j2Q1xyl1gUlOf2fuLE483wiGYreOzWqawRVHVpo/rKhUPOSs",
                "HotelAddress": "8A/3, W. E. A. Pusa Road, Near Metro Pillar No 131, Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.643658",
                "Longitude": "77.182511",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 11,
                "HotelCode": "2194021",
                "HotelName": "Hotel RnB Qube",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel RnB Qube is conveniently located in the popular Greater Noida area. Both business travelers and tourists can enjoy the hotel\'s facilities and services. Service-minded staff will welcome and guide you at the Hotel RnB Qube. Guestrooms are designed to provide an optimal level of comfort with welcoming decor and some offering convenient amenities like television LCD/plasma screen, clothes rack, complimentary instant coffee, complimentary tea, linens. The hotel offers various recreational opportunities. Hotel RnB Qube is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6028.74,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6028.74,
                    "PublishedPriceRoundedOff": 6029,
                    "OfferedPrice": 6028.74,
                    "OfferedPriceRoundedOff": 6029,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG58KfqAgvvNQ+amkNV7xwZx9ShxjYIFMf8hWjNeP2SMsZDfbkgu3ge6RxtLVmuHy/ucdcsJtDYuYQiKCo80Hs+5LKIG0YQmsa1L5/rblmupxnPt1pnqpD2YI=",
                "HotelAddress": "Plot No. 9, Knowledge Park 3, Greater Noida, Delhi National Territory, New Delhi and NCR, India, , , 201306, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 12,
                "HotelCode": "960344",
                "HotelName": "Hotel Kings Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in Karol Bagh, Hotel Kings Inn is a perfect starting point from which to explore New Delhi and NCR. The hotel offers a wide range of amenities and perks to ensure you have a great time. All the necessary facilities, including 24-hour room service, free Wi-Fi in all rooms, 24-hour security, shrine, daily housekeeping, are at hand. Comfortable guestrooms ensure a good night\'s sleep with some rooms featuring facilities such as television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), air conditioning, heating. The hotel offers various recreational opportunities. Friendly staff, great facilities and close proximity to all that New Delhi and NCR has to offer are three great reasons you should stay at Hotel Kings Inn.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6069.27,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6069.27,
                    "PublishedPriceRoundedOff": 6069,
                    "OfferedPrice": 6069.27,
                    "OfferedPriceRoundedOff": 6069,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02L2wfhrw1xfZQYJghNlazih+uo0HyEl7cBOH7RXWawbCpgvhf2lXC2ndhLgrwIwE4Sc6jQrJtgd1cF8N/1wEJfGNZNUbrTS84Lpcf4Vm05jpqDHBMMPbRqk=",
                "HotelAddress": "14A/7 Wea Channa Market opposite to kalyan jewellers, Delhi National Territory, New Delhi and NCR, India, , , 110005, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 13,
                "HotelCode": "568493",
                "HotelName": "Hotel Cabana",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The 3-star Hotel Cabana offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. The hotel has everything you need for a comfortable stay. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, postal service are on the list of things guests can enjoy. Designed for comfort, selected guestrooms offer locker, towels, carpeting, slippers, television LCD/plasma screen to ensure a restful night. The hotel offers various recreational opportunities. Convenience and comfort makes Hotel Cabana the perfect choice for your stay in New Delhi and NCR.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6157.38,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6157.38,
                    "PublishedPriceRoundedOff": 6157,
                    "OfferedPrice": 6157.38,
                    "OfferedPriceRoundedOff": 6157,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+Yi950Q9Bgki1ObkE9TpRuSraUacBw0fNbjeYfN76DeQ+V7NT4LUFFIXw/uPxldCLS9peNqvTutGDcs8rYQXRWZfOKFrsW0YGlaxSoLma8OqBmUSxhy7u4SI=",
                "HotelAddress": "2313 Behind Imperial Cinema | Near RK Ashram Metro Station, Delhi National Territory, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 104,
                "HotelCode": "715134",
                "HotelName": "Cottage Ganga Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Cottage Ganga Inn, New Delhi, is a centrally located accommodation. Well connected to the city centre, shopping malls, business hubs and important landmarks of the city, the hotel is well suited for business and leisure travellers. Explore the capital city and its culture at this government approved hotel.\n\n<b>Location:</b>\nHotel Cottage Ganga Inn is located at Bazar Sangtrashan, behind Khanna Cinema Paharganj. Red Fort (Approx. 4km) and National Museum (Approx. 5km) are places of popular interest among tourists near this hotel. Also known as the Lal Qilla, the Red Fort is a heritage structure built by the Emperor Shah Jahan. It took 10 years to complete and is known for its architecture and grandeur. One can also visit Akshardham Temple, Humayun\'s Tomb and Lotus temple when in the city.\n\nDistance from Indira Gandhi International Airport: 17 km (approx.)\nDistance from New Delhi Railway Station: 2 km (approx.)\n\n<b>Hotel Features:</b>\nFor a truly comforting stay, the hotel offers an array of quality services. Some of the basic services include air conditioning, room service, front desk, non smoking rooms, parking, security and doctor-on-call. Business centre truly satisfies the corporate travellers. In-house restaurant serves delicious meals.\n\n<b>Rooms:</b>\nGuests can opt to stay in standard non A/c, deluxe A/c or family rooms of the hotel. The room decor is simple and appealing. The in-room amenities include colour television, internet access, refrigerator, writing desk and telephone. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5510,
                    "Tax": 663.84,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6195.84,
                    "PublishedPriceRoundedOff": 6196,
                    "OfferedPrice": 6173.84,
                    "OfferedPriceRoundedOff": 6174,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7OjouNoyegn9Wxgo/58OR+UhmTBaBnvc3ut7/4qWucNOBqbDboYr0c",
                "HotelAddress": "1532,Bazar Sangtrashan,(Behind Khanna Cinema)\nPaharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.641768",
                "Longitude": "77.212632",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 14,
                "HotelCode": "446327",
                "HotelName": "Hotel Shelton",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Set in a prime location of New Delhi and NCR, Hotel Shelton puts everything the city has to offer just outside your doorstep. Both business travelers and tourists can enjoy the hotel\'s facilities and services. Service-minded staff will welcome and guide you at the Hotel Shelton. Guestrooms are fitted with all the amenities you need for a good night\'s sleep. In some of the rooms, guests can find television LCD/plasma screen, carpeting, locker, mirror, towels. The hotel offers various recreational opportunities. Discover all New Delhi and NCR has to offer by making Hotel Shelton your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6402.22,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6402.22,
                    "PublishedPriceRoundedOff": 6402,
                    "OfferedPrice": 6402.22,
                    "OfferedPriceRoundedOff": 6402,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG5zJ9+XL6YapEdfo1gxPOWYcxAd9mne592lFvlzPlWeegQr3JBhmfdogWGqqsEN5S6yzp45SXngeMHfhC26HUSdlXDc6Z5XpEr0Xh1KKvUJRqRyRXRacciok=",
                "HotelAddress": "5043 -Main Bazaar, 6- Tooti Chowk, Near New Delhi Railway Station, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 105,
                "HotelCode": "315437",
                "HotelName": "Ginger-Vivek Vihar",
                "HotelCategory": "Ginger hotels",
                "StarRating": 3,
                "HotelDescription": "Ginger Vivek Vihar hotel is a great travel destination for both business and leisure travelers. Perfect for business meetings, the hotel has a well-equipped waiting room that can accommodate 10 people easily. \n\n<b>Location:</b>\nGinger Vivek Vihar Hotel is situated in the DDA community center at Vivek Vihar, Delhi. Excellent placement and ease of accessibility make it a favourable choice for the business class as well as tourists. While guests stay here, they can visit the famous Surajmal Park (Approx 1km), Bhaubali Enclave (Approx 1km), Jama Masjid (Approx 10km)\n\nDistance from Indira Gandhi Airport: Approx. 26km\nDistance from New Delhi Railway Station:  Approx. 12km\nDistance from Nizzamuddin Railway Station:  Approx. 15km\n\n<b>Hotel Features:</b>\nThe hotel offers a number of business services including a meeting room that can accommodate up to 10 people.  The internet facility helps workaholics keep in touch with the world. Travelers can enjoy city guide facilities on request as well as taxi and van service. The hotel\'s in-house restaurant offers freshly cooked multi-cuisine dishes that will leave patrons craving for more.Ginger Vivek Vihar Hotel offers in-room dining facility to those who would love to dine privately in the comfort of their room. \n\n<b>Rooms:</b>\nThe Ginger Vivek Vihar Hotel has well-styled rooms that offer convenience at its best. Guests can avail of amenities like air conditioning, refrigerator, flat-screen TV, telephone and tea/coffee maker. The rooms are extremely cozy and equipped to make guests feel at home. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5854.08,
                    "Tax": 705.13,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6581.21,
                    "PublishedPriceRoundedOff": 6581,
                    "OfferedPrice": 6559.21,
                    "OfferedPriceRoundedOff": 6559,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5/7rhPhmKuhCRZiAxiv83MYMABed+MJm0gjzUqcwy00G5k46QrgMMx",
                "HotelAddress": "DDA Community Center (Technopark), Opp. Eastend Club, Vivek Vihar,Delhi,India, , , 110095",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.665772",
                "Longitude": "77.304155",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 106,
                "HotelCode": "732081",
                "HotelName": "Hotel Sai Miracle",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Sai Miracle is an artistically designed hotel that mesmerizes its guests with a range of comforting facilities.A 3 Star property offers In-house restaurant which serves a wide array of authentic Indian and global cuisines to pamper the taste-buds. The hotel is well-suited to both business and leisure travelers.\n\nLocation\nHotel Sai Miracle is located at a walking distance from New Delhi Railway Station, 5 Minutes drive from Connaught Place and 5 minutes walking from Ramakrishna Ashram Metro Station. Hotel offers good connectivity from Indira Gandhi International Airport which is at a distance of around 21 km. \nThe hotel is in close proximity to India Gate (5 km), Hazrat Nijamuddin Railway Station (7km), Pragati Maidan (4.5 km), Akshardham Mandir (6.5 km), \n\nHotel Sai Miracle provides a range of services for the comfortable stay of guests. The hotel pampers its guests with other eminent amenities like high-speed wireless connectivity of internet, travel desk, 24 hour room service, 24 power backup, doctor on call, Laundry & Dry Cleaning Service, local tour and travel desk, Free Dialing telephone and Airport pick up and drop facility (Surcharge).\n\nThe hotel possesses 22 enormous rooms which are available in three variants, Deluxe Room, Executive Room and Suite Room. All the rooms offer amenities like private bathrooms with rainfall shower heads and well designed toiletries with regular supply of hot and cold running water. Free Wi-Fi and LCD television are available in the rooms. Additional amenities include ceiling fan, Mini Bar (chargeable basis), a safe, working desk, direct dial telephone service and full length mirror. Hair Dryer and Tea Coffee Maker on Request ",
                "HotelPromotion": "Last minute deal 40% off...",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5956.4,
                    "Tax": 717.41,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 3985.6,
                    "PublishedPrice": 6695.81,
                    "PublishedPriceRoundedOff": 6696,
                    "OfferedPrice": 6673.81,
                    "OfferedPriceRoundedOff": 6674,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5XEHJQkx3M7gDNKCcqJX/Eo8wE/QrHLOFiDtJtlHvQUPfWy37pxtpd",
                "HotelAddress": "2532, Street No.11, Chuna Mandir, Pahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.6430786",
                "Longitude": "77.2101487",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 107,
                "HotelCode": "320252",
                "HotelName": "Hotel Delhi Pride",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Perfectly suited to business travelers, Hotel Delhi Pride ensures complete comfort and convenience with great facilities such as car rental services, currency exchange, fax, email, internet, photocopy and courier services.\n\n<b>Location:</b>\n Standing amidst Asia\'s largest shopping area, Karol Bagh.Hotel Delhi Pride offers easy accessibility to all parts of the city. Guests wishing to take in the sights of the capital city can visit Connaught Place (approx 4 Km), Red Fort (approx 7 Km) , India Gate (approx 8 Km).\n\nDistance from IGI Airport:- Approx. 16 Km\nDistance from New delhi Railway Station: Approx. 5Km\nDistance from Nizzamuddin railway station: Approx. 12Km\n\n<b>Hotel Features:</b> \nBusiness travelers would find Hotel Delhi Pride to be a great accommodation option because of the state-of-the-art facilities on offer. Guests can rent cars as well as get foreign currency exchanged within the hotel premises. Documents can be faxed, emailed, photocopied or couriered. Complete guests convenience is ensured via 24-hour room service. The rooftop of this hotel can be used for arranging small-scale parties and events. The hotel houses a dining hall and a cafeteria. Hotel Delhi Pride allows its guests to dine in-house at its restaurant that serves up a variety of mouth-watering dishes from Indian, Chinese and Continental cuisines. In-room dining is also allowed.\n\n<b>Rooms:</b> \nGuests can choose from among Executive, Premium and Deluxe rooms available at the Hotel Delhi Pride. With a pristine city view available, each room is furnished with kind-size beds. Daily newspaper service, laundry and complimentary Wi-Fi access is also available. ",
                "HotelPromotion": "Book Now and save 25%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 5963,
                    "Tax": 718.2,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 2100,
                    "PublishedPrice": 6703.2,
                    "PublishedPriceRoundedOff": 6703,
                    "OfferedPrice": 6681.2,
                    "OfferedPriceRoundedOff": 6681,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6znvyntiuhrFpDmpljDq0v3W+eFYyvxlk3q/rYpZgJNSITV9n2X7yB",
                "HotelAddress": "12A/28,West Extension Area ,Saraswati Marg, ( Near Karol Bagh Metro Station) Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.647749",
                "Longitude": "77.187181",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 108,
                "HotelCode": "382230",
                "HotelName": "Balsons Continental-Patel Nagar",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in the heart of West Delhi and close to the prime shopping area named Karol Bagh, Hotel Balsons Continental is a 3-star hotel surrounded by some of the popular shopping spots. The hotel is ideally based for leisure and business travelers.\n\n<b>Location</b>\nThe hotel is located in East Patel Nagar. Karol bagh is at a distance of 2 kilometer, Connaught Place is 5 kilometer away and Rajendra Place centre is just 2 minutes drive from the hotel. city\'s best restaurants and pulsating nightlife destinations are also close to the hotel. Beautiful and popular monuments like Lotus Temple(21 km), Qutub Minar(19 km), Lal Quila(11 km) and India Gate(9 km) are located near the hotel too.\n\nIGI Airport: 10 km/ 25 min\nNizamuddin railway station: 16 km/ 35 min\n\n<b>Features</b>\nHotel Balsons Continental houses a restaurant and internet access is also provided to the guests for better connectivity. A well-equipped Conference Hall, Travel Desk, parking and doctor on call are other services available for the guests.\n\n<b>Rooms</b>\nWell furnished, contemporary styled rooms are offered by the hotel. Every room is air-conditioned and features a color television, telephone, Wi-Fi access and a private washroom. All rooms are spacious and cozy. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6115,
                    "Tax": 736.44,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6873.44,
                    "PublishedPriceRoundedOff": 6873,
                    "OfferedPrice": 6851.44,
                    "OfferedPriceRoundedOff": 6851,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB68AgRKn/DGBV1lMI9bKjShaBGNUXsV9BuS3B0ipK0IFQ==",
                "HotelAddress": "8/1 East Patel Nagar,Delhi,India, , , 110008",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.644141",
                "Longitude": "77.172557",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 109,
                "HotelCode": "707467",
                "HotelName": "Paradise Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Paradise Inn is a business class luxury hotel ideal for both corporate and leisure travellers. Guests find the hotel to be value for money with good restaurant and friendly staff.\n\n<b>Location:</b>\nParadise Inn is centrally located in the business district and shopping hubs of Karol Baugh, New Delhi. The hotel stands close to Karol Baugh Metro Station and Business Centre. Some of the major tourist attractions at a proximity from the hotel are Jama Masjid (Approx. 6km), Chandni Chowk, Red Fort, Gandhi Smriti, and India Gate. Other major attractions include Humayun\'s Tomb and Lodi Gardens.\n\nDistance from Indira Gandhi Airport: Approx. 15km\nDistance from New Delhi Railway Station: Approx. 5km\nDistance from Nizzamuddin Railway Station: Approx. 11km\n\n<b>Hotel Features:</b>\nParadise Inn features stylish interiors and friendly staff for the comfort of the guests. The hotel offers a wide range of amenities including 24 hour front desk and room service, AC, parking, travel desk, laundry, security and doctor-on-call. Guests can enjoy fine dining at the Cafeteria located at the Paradise Inn. Patrons can enjoy authentic Indian, Chinese and continental cuisine along with a delicious cup of coffee at the rooftop of the hotel.\n\n<b>Rooms:</b>\nParadise Inn is a hotel offering well-decorated and furnished rooms. Each room is equipped with AC, internet access, tea/coffee maker, writing desk, flat-screen TV, luggage space, and table lamp. ",
                "HotelPromotion": "Book Now And Save 25% Per Night",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6119.75,
                    "Tax": 737.01,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 2155,
                    "PublishedPrice": 6878.76,
                    "PublishedPriceRoundedOff": 6879,
                    "OfferedPrice": 6856.76,
                    "OfferedPriceRoundedOff": 6857,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7s7FIAl+vD+JQlqA5yKpBoMvb4Yu8VMuHV0okfvpJ16j1s+yXepR8P",
                "HotelAddress": "15A/49 W.E.A Ajmal Khan Road, Karol Bagh, Near New Jesaram Hospital,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.650371",
                "Longitude": "77.192339",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 110,
                "HotelCode": "715857",
                "HotelName": "Hotel De Aura",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Airport Hotel De Aura is a contemporary property whose primary aim is to please patrons with its warm and friendly hospitality. The hotel offers impeccable range of services that ensures a memorable and comfortable stay. The well-appointed accommodations with peaceful ambience beckon the guests for a pleasant stay. \n\n<b>Location:</b>\nAirport Hotel De Aura is located at Mahipalpur Extn., near IGI Airport. Being the capital of India, New Delhi has many historical sites that tourists can enjoy during their stay. Some nearby places are Fellowship of Believer\'s Abundant Life Church (Approx. 3km) and Aravalli Biodiversity Park (Approx. 6km). The park is a lush green area right in the centre of Delhi and a great place to spend evenings with family and friends. Akshardham Temple, Connaught Place and Lotus temple are interesting destinations to explore in the City. \n\nDistance from Indira Gandhi International Airport: 2 km (approx.)\nDistance from New Delhi Railway Station: 21 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel provides a host of amenities to make the stay hassle-free. The basic amenities offered to the guests include room service, internet access, front desk, air conditioning, non-smoking rooms, travel desk and 24-hour security.\n\n<b>Rooms:</b>\nThe accommodations at the Airport Hotel De Aura are peaceful and comfortable ensuring a relaxing experience. Elegantly appointed rooms with dark brown coloured furnishings truly assure a comfortable stay. The room amenities that patrons can avail are air conditioning, satellite television, internet access, telephone, in-room menu and writing desk. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6174,
                    "Tax": 743.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 6939.52,
                    "PublishedPriceRoundedOff": 6940,
                    "OfferedPrice": 6917.52,
                    "OfferedPriceRoundedOff": 6918,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6YuCU+IMmjeE5Gzb2jmomZqDQkmNVq90jVr4kCkQPsVGu8sh8RaroW",
                "HotelAddress": "Block -L -10,N.H-8,Mahipalpur Extn,Near IGI Airport,,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.546131",
                "Longitude": "77.123704",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 111,
                "HotelCode": "957034",
                "HotelName": "Treebo Rockwell Plaza",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6349.96,
                    "Tax": 764.63,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7136.59,
                    "PublishedPriceRoundedOff": 7137,
                    "OfferedPrice": 7114.59,
                    "OfferedPriceRoundedOff": 7115,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB775Tm9AeBMw2VUo00qa3OqPlFpc4Jc2z/In14SMPzlgVAtDfxtPq1vsedLhfAnFMY=",
                "HotelAddress": "5/14 , WEA , Ajmal Khan Road.,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.64709",
                "Longitude": "77.189434",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 15,
                "HotelCode": "159199",
                "HotelName": "Hotel Le Roi",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Stop at Hotel Le Roi to discover the wonders of New Delhi and NCR. The hotel offers a high standard of service and amenities to suit the individual needs of all travelers. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, 24-hour front desk, express check-in/check-out. Guestrooms are fitted with all the amenities you need for a good night\'s sleep. In some of the rooms, guests can find television LCD/plasma screen, carpeting, clothes rack, complimentary instant coffee, complimentary tea. Enjoy the hotel\'s recreational facilities, including garden, before retiring to your room for a well-deserved rest. Hotel Le Roi is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7308.1,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7308.1,
                    "PublishedPriceRoundedOff": 7308,
                    "OfferedPrice": 7308.1,
                    "OfferedPriceRoundedOff": 7308,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02FU0SSKKgIDqGZGQsI+vMX3tawNk9qK2//idNkw8LMdaPxiyXWX9dmSdNgCk8G8Kf8n1yOIxErYsPIsKUayhNGQepWlrVkiGcv4GPKXSHRXR1EX8UqJXYuM=",
                "HotelAddress": "2206, Raj Guru Road, Desh Bandu Gupta Road, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 112,
                "HotelCode": "725698",
                "HotelName": "Hotel Persona International",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Persona International, New Delhi, with a prime location and warm hospitality, in the capital city attracts lots of leisure travellers. With the plush Connaught Palace for bargain hunters and monuments like Red Fort and Jantar Mantar nearby, this elegantly decorated hotel offers tastefully decorated rooms for a memorable stay.\n\n<b>Location:</b>\nHotel Persona International is situated at W.E.A, Saraswati Marg, Karol Bagh. Sight-seeing destinations like Gurudwara Bangla Sahib (Approx. 5km) and Connaught Place (Approx. 5km) surround the hotel. Situated in Connaught Palace, Gurudwara Bangla Sahib is a holy place of Sikh worship with an art gallery, library, hospital, a Baba Baghel Singh Museum and a higher secondary school for girls. Visitors can also catch a glimpse of Humayun\'s Tomb, Lotus Temple and India Gate.\n\nDistance from Indira Gandhi International Airport: 16 km (approx.)\nDistance from New Delhi Railway Station: 7 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel provides ample of facilities for guests comfort and convenience. Some of the basic services offered are air-conditioning, room service, front desk, travel desk, non smoking rooms, internet, security and doctor-on-call. The coffee-shop is an ideal venue to savour tasty snacks with beverages. Relish on lip-smacking food and drinks at the beautifully decorated in-house restaurant. \n\n<b>Rooms:</b>\nThe lodging options at the hotel include deluxe room, premium room and executive rooms. The beautifully decorated rooms with chic interiors are equipped with contemporary amenities. The rooms feature attractive furnishings with an inviting ambience. Amenities like air conditioning, flat screen television, writing desk, hair dryer and internet access enhance the comfort quotient of the patrons. ",
                "HotelPromotion": "Book Now Get 25% Off on Per Night",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6575,
                    "Tax": 791.64,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 2199,
                    "PublishedPrice": 7388.64,
                    "PublishedPriceRoundedOff": 7389,
                    "OfferedPrice": 7366.64,
                    "OfferedPriceRoundedOff": 7367,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6Dd55cIJe+GkVQkPdvSBr6PM3KWUEltQQaZ9Q43ecAaJURQ5z/opMA",
                "HotelAddress": "12A/17, W.E.A ,Saraswati Marg, Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.646631",
                "Longitude": "77.188479",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 113,
                "HotelCode": "717403",
                "HotelName": "Hotel Green Lotus-Dwarka",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Anand Lok, New Delhi, is situated near the Delhi Metro station. Surrounded by business hubs , shopping malls and prominent landmarks of the city, the hotel is well suited for every traveller. Guests can sit back and enjoy the warm hospitality and peaceful ambience of the hotel. \n\n<b>Location:</b> \nHotel Anand Lok is located at Pochanpur, near Shyam Baba Mandir, Sector-23, Dwarka. Places of attraction like St. Pius X Church (Approx. 1km) and Bharat Vandana (Approx. 2km) are located at a stone throw away\'s distance from the hotel. Make vacations memorable by visiting other distant places like Red Fort, Jama Masjid and Chandani Chowk.\n\nDistance from Indira Gandhi International Airport: 14 km (approx.)\nDistance from New Delhi Railway Station: 29 km (approx.)\n\n<b>Hotel Features:</b>\nFor a truly relaxing stay, the hotel caters to the detailed needs of patrons. Some of the basic services include air conditioning, room service, front desk, wheel chair access, internet, non smoking rooms, elevators, parking, security and doctor-on-call. The multi-cuisine restaurant serves an array of dishes from across the world for a filling experience.\n\n<b>Rooms:</b>\nDepending on the requirement, guests can stay in deluxe or superior rooms. All the rooms are air- conditioned and simple for a peaceful stay. The in-room amenities include air conditioning, flat screen television, internet access, safe, in room menu and telephone. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6624.2,
                    "Tax": 797.54,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7443.74,
                    "PublishedPriceRoundedOff": 7444,
                    "OfferedPrice": 7421.74,
                    "OfferedPriceRoundedOff": 7422,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4ynmYa36RkH3YqUhJ1RYnPxHNDOf33GinTP4J9mm4aNA==",
                "HotelAddress": "Opposite Shyam Baba Mandir, Sector-23, Dwarka Link Road, Near Sector -21 Metro station,Delhi,India, , , 110077",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.561767",
                "Longitude": "77.051045",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 114,
                "HotelCode": "334561",
                "HotelName": "Hotel Green Lotus",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Green Lotus is a 3-Star Property. The hotel is situated near to the Indira Gandhi International Airport (5.8 KM), Dwarka Sec.-21 Metro Station (05 KM) and Bijwasan Railway Station (5.4 KM). The location of the hotel is close to some of the various sightseeing areas like Jantar Mantar (20 KM), Qutub Minar (12 KM), DLF Cyber City (5 KM), DLF Cyber Hub (5.5 KM) and Ambience Mall (4.5 KM). The Hotel has well-furnished 42 Rooms divided into 4 categories Deluxe, Super Deluxe, Suites & Family Room. All the Room have the basic amenities like Air Conditioner, Cable 32 LCT TV, Intercom Facility, Mini Bar, Tea/Coffee Maker, Mineral Water and Attached Bathrooms making the stay memorable for the guests. The Hotel also offers facilities like Taxi Services and Parking. Internet Facility available at the Hotel enables the guest to stay connected to the outside world. Iron/Ironing Board & Hair Dryer is also available on request. The Hotel is fully equipped with communications systems, presentation facilities and conference hall to ensure the running of your business events. The property also has an in-house Multi-Cuisine Restaurant serving wide range of delicacies satisfying your palates. The Hotel houses a coffee shop were you can freshen up by trying the beverages served. With a favorable location and modern facilities, the Hotel is suited for both business and leisure guests.\n\nLocation\nIGI Airport  5.8 KM\nAmbience Mall - 3.5 KM\nDLF Cyber City - 5.0 km\nSahara Mall - 5.8 km\nDwarka Sec.-21 Metro Station  5.0 KM\nBijwasan Railway Station  5.4 KM\nUdyog Vihar (Gurgaon)  2.5 KM\n\nFeatures\nOne can pamper his or her pallet with the hotel\'s Restaurant aptly named \"AMPA\", open 24x7 for all its guests. Be it a business lunch or an enjoyable dinner meal, the restaurant\'s multi cuisine menu is destined to treat everybodys taste buds. To assist the jet set corporate traveler, an in-house conference facility for 7 - 20 persons is also available. The hotel\'s strategic location and ability to offer a wide array of services, has made it become a preferred destination for the corporate traveler visiting the nation\'s capital.\n\nRooms\nContemporary designed guest rooms with large comfortable bedding are intended to rejuvenate all travelers during their stay. All rooms come equipped with 32\" flat screen TV, tea / coffee machine, business center, 24 hours dining and complimentary Wi-Fi. \n\nAmenities\nParking Facilities Available\nLift / Elevator\nLaundry Service Available\n24 Hours Front Desk\nRoom Service (24 Hours)\nFree WIFI\nInternet / Fax (Reception area only)\n24 Hours Power Backup ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6634.4,
                    "Tax": 798.77,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7455.17,
                    "PublishedPriceRoundedOff": 7455,
                    "OfferedPrice": 7433.17,
                    "OfferedPriceRoundedOff": 7433,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6C8T+dgcGGHq357YASYcZly82M8KDEmediDSXB0wr+5Q==",
                "HotelAddress": "91, Kapashera, Bijwasan Road,  Near IGI Airport Aerocity, NH-8,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.5309154",
                "Longitude": "77.0858107",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 16,
                "HotelCode": "MAJ|DEL",
                "HotelName": "Majestic Palace",
                "HotelCategory": "",
                "StarRating": 2,
                "HotelDescription": "Indra Gandhi International Airport is 16 kms away,New Delhi Railway Station is 3 kms,Karol Bagh Metro station is 5 mins, Central distance to connaught Place is 3 kms.Pragati Maidan Trade fair ground is 4 kms. WHR02F 10/11 Rooms have the availability of both Twin and Double beds, it has all Television with satellite Cable, hair dryer and Tea and Coffee maker. Hotel has the dinning area. It is modern brick building. It has very small lobby.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7491.33,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7491.33,
                    "PublishedPriceRoundedOff": 7491,
                    "OfferedPrice": 7491.33,
                    "OfferedPriceRoundedOff": 7491,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mg+LzXtJGxrsAoIl2LnDG4si4OHqm9eIbkiaf94sHOt3",
                "HotelAddress": "4/35  W.E.A  SARASWATI MARG KAROL BAGH  NEW DELHI 110005 INDIA, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 115,
                "HotelCode": "375240",
                "HotelName": "Hotel Tourist",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Tourist is an excellent choice if you are looking for a homey and warm atmosphere, and the convenience of being close to public transport spots. Situated in the center of New Delhi near Connaught Place, this hotel is a stone\'s throw away from the major commercial and shopping areas of the city.\n\n<b>Location:</b>\n Located in Ram Nagar near Connaught Place, Hotel Tourist is in close proximity to the metro station and provides quick and easy transport to places of cultural importance like Akshardham Temple, Cathedral Church of Redemption and many others. Besides these, it lies close to important places like Sadar Bazar, Karol Bagh and Chandni Chowk, Connaught place(approx. 2 km), Jantar Mantar (approx 3 km), Jama Masjid (approx 4 km).\n\nDistance from Indira Gandhi Airport:Approx 16km\nDistance from New Delhi Railway Station:Approx 3km\nDistance from Nizzamuddin Railway Station:Approx 9km\n\n<b>Hotel features:</b>\nHotel Tourist is a contemporary and appealing structure with well-groomed and aesthetically designed interiors. Featuring a terrace garden, conference facilities, travel desk and doctor on call, this hotel is an exceptional choice to spend an enjoyable holiday in Delhi. Offering delectable vegetarian fare, Hotel Suvidha is a multi-cuisine restaurant. With contemporary ambience and pleasant appeal, the expanded menu includes fresh preparations by the chef every day. Guests can also celebrate special personal events like birthdays, kitty parties and anniversaries here. \n\n<b>Rooms:</b>\n Featuring  luxuriously furnished rooms, Hotel Tourist ensures its patrons a relaxed and pleasant stay. With amenities like tea/coffeemakers, minibars, flat-screen televisions and Wi-Fi in every room, this hotel blends luxury with ease of convenience. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6728,
                    "Tax": 810,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7560,
                    "PublishedPriceRoundedOff": 7560,
                    "OfferedPrice": 7538,
                    "OfferedPriceRoundedOff": 7538,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4aBqwNsKy0SRQeUDTzmtxAOLi5zMzOb29HRinsQvXKng==",
                "HotelAddress": "7361, Ram Nagar, Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.649225",
                "Longitude": "77.208769",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 116,
                "HotelCode": "333519",
                "HotelName": "Hotel Delhi Aerocity @ Airport Highway",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 6774,
                    "Tax": 815.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7611.52,
                    "PublishedPriceRoundedOff": 7612,
                    "OfferedPrice": 7589.52,
                    "OfferedPriceRoundedOff": 7590,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB63XYjv3DWyLBwY/UEN/IqiNB5QSEgLfZ4J9PMKmRbj3b1ufk764Bro",
                "HotelAddress": "104/2/2 M. R. Complex, Near IGI Airport, Rangpuri,New Delhi,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.542073",
                "Longitude": "77.118398",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 17,
                "HotelCode": "1199084",
                "HotelName": "Hotel Good Palace",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location A stay at Hotel Good Palace places you in the heart of New Delhi, convenient to Ramakrishna Mission and Red Fort.  This hotel is within close   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7631.86,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7631.86,
                    "PublishedPriceRoundedOff": 7632,
                    "OfferedPrice": 7631.86,
                    "OfferedPriceRoundedOff": 7632,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1fgKqm3E2zkBFCSxHU14YtB1AMoZk5LUxadqgOaieqh1HsVmS7MeBkn7b2tmPGHv9heJP/qlU3PshGxpNHLuW+GcGT2pGNlJMbUYtYKKOtZXvcuuILruvhQ=",
                "HotelAddress": "15A/63 Ajmal Khan Road W E A , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 18,
                "HotelCode": "362034",
                "HotelName": "Metropolis Tourist Home",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Metropolis Tourist Home is a popular choice amongst travelers in New Delhi and NCR, whether exploring or just passing through. Both business travelers and tourists can enjoy the hotel\'s facilities and services. Facilities like free Wi-Fi in all rooms, 24-hour front desk, express check-in/check-out, luggage storage, Wi-Fi in public areas are readily available for you to enjoy. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), non smoking rooms, air conditioning. The hotel offers various recreational opportunities. For reliable service and professional staff, Metropolis Tourist Home caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7700.5,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 7700.5,
                    "PublishedPriceRoundedOff": 7700,
                    "OfferedPrice": 7700.5,
                    "OfferedPriceRoundedOff": 7700,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YsqSyycuF02nL5gL6bolfLZsPP1VQrJENTmTavZhgJ6O3z5vMY1HqN+ik6nVZ4zU+HWbRKXrnQQiDwxh2yulSiAfiJrtRkAPn6QxkdhylzDLUEl+zK4ZGKc=",
                "HotelAddress": "1634-35,Ist Floor, Main Bazar, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 117,
                "HotelCode": "1327646",
                "HotelName": "Treebo Singh Sons Karol Bagh",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7144.27,
                    "Tax": 859.95,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8026.22,
                    "PublishedPriceRoundedOff": 8026,
                    "OfferedPrice": 8004.22,
                    "OfferedPriceRoundedOff": 8004,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7cQvVqb0LPjJz/64YIpfRB0wd9nlXoD9LAEUrAQwSYsh5FER410nbItxjkeZw7oYoqTp7UPLSDqQ==",
                "HotelAddress": "7A/10, W.E.A Channa Market, Near Channa Market- Second Circle.,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.646571",
                "Longitude": "77.186315",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 118,
                "HotelCode": "741724",
                "HotelName": "Hotel Nirmal Mahal",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Nirmal Mahal is an artistically designed hotel that mesmerizes its guests with a range of comforting facilities.A 3 Star property offers In-house restaurant which serves a wide array of authentic Indian and global cuisines to pamper the taste-buds. \n\nHotel Nirmal Mahal is located at a walking distance from New Delhi Railway Station, 5 Minutes drive from Connaught Place and 5 minutes walking from Ramakrishna Ashram Metro Station. \nThe hotel is in close proximity to India Gate (5 km), Hazrat Nijamuddin Railway Station (7km), Pragati Maidan (4.5 km) and Akshardham Mandir (6.5 km), \n \nDistance from Indira Gandhi International Airport: 21km (approx.)\nDistance from New Delhi Railway Station: 0.6 km (approx.)\n\nHotel Nirmal Mahal provides a range of services for the comfortable stay of guests. The hotel pampers its guests with other eminent amenities like high-speed wireless connectivity of internet, 24 hour room service, 24 power backup, doctor on call, Laundry & Dry Cleaning Service (surcharge), local tour and travel desk, Free Dialing telephone and Airport pick up and drop facility (Surcharge).\n\nThe rooms provided by the hotel are of various types, such as Deluxe rooms, Super Deluxe Rooms and Deluxe Triple Rooms. Well-designed wallpapers, comfortable bedding with other contemporary fittings lures guests for a relaxed stay. The in-room amenities include air conditioning, LCD Tv, Internet access, minibar on chargeable basis, safe in room, free Guest toiletries, in-room menu, Tea/coffee maker(Chargeable basis) and Hair Dryer (Chargeable basis).\n\nThe hotel is well-suited to both business and leisure travelers!!! ",
                "HotelPromotion": "Book now and get 40% discount",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7175,
                    "Tax": 863.64,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 2399,
                    "PublishedPrice": 8060.64,
                    "PublishedPriceRoundedOff": 8061,
                    "OfferedPrice": 8038.64,
                    "OfferedPriceRoundedOff": 8039,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6SZsz9IOv+qM785w7nmPUB3vLFqMoUhynFEGmGzIQxRVeVRAZWQ65FdG8f745Rjgw=",
                "HotelAddress": "1519, Sangatrashan Street,Near Behind Khanna Cinema,Pahar Ganj, Main Bazar Rd,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.641224",
                "Longitude": "77.212626",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 119,
                "HotelCode": "726373",
                "HotelName": "Hotel Toronto New Delhi",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Toronto, New Delhi, welcome guests in an abode of comfort with its innate warmth, prompt service and essential amenities. Tastefully appointed rooms, state-of-the-art facilities are complemented with gratifying hospitality. Foodies can savour an array of delectable cuisines at the in-house restaurant. \n\n<b>Location: </b>\nHotel Toronto is located at Multani Dhanda, Paharganj. Connaught Place (Approx. 4km) and Red fort (Approx. 5km) are two beautiful sites that surround the hotel. The popular Connaught Place which is known for its distinct Victorian style of architecture serves as a major commercial and shopping centre in New Delhi. Other interesting sites to explore while touring the capital city are Lotus temple, Humayun\'s tomb and Qutub Minar.\n\nDistance from Indira Gandhi International Airport: 15 km (approx.)\nDistance from New Delhi Railway Station: 3 km (approx.)\n\n<b>Hotel Features: </b>\nThe hotel has modern facilities to assure convenience and comfort to the guests. The various basic facilities ensured to the patrons are room service, internet, 24-hour front desk, air conditioning, elevators, non-smoking rooms, parking, travel desk, and 24-hour security. For organizing corporate meetings, the resort also provides state-of-the-art business centre. In addition, foodies can relish mouth-watering food at the in-house restaurant.\n\n<b>Rooms: </b>\nStandard room, executive room, triple bedded room and family quad bedded room are different accommodations provided to the guests. Rooms are spacious and well-furnished. The lighting of the room creates a soothing effect and creates a calm ambience around. Modern room amenities present in each room are air conditioning, internet access, safe, telephone, in-room menu and satellite TV. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7178,
                    "Tax": 864,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8064,
                    "PublishedPriceRoundedOff": 8064,
                    "OfferedPrice": 8042,
                    "OfferedPriceRoundedOff": 8042,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB58qt6kTkqT5j1fwQuXBkOMDkpauHDMrGn7hXq4mU2d42kIfCZxlHavxmoaoEcnCNQ=",
                "HotelAddress": "Street No 1 Opposite Pahar Ganj Police Station,\nNext to Maharashtra Bhawan,\nPahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.646172",
                "Longitude": "77.209454",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 120,
                "HotelCode": "735586",
                "HotelName": "Hotel Port View",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Offering quality accommodation, Hotel Port View, New Delhi forms an ideal lodging option for budget travelers. Well designed and spaciously set hotel showcases elegance in its interiors. Its service package comprises of all major amenities required for a hassle free journey. Thus, easy on pocket rates coupled with customary amenities and fine accommodation choices assure a memorable vacation to the tourists. \n\n<b>Location</b>\nThe hotel is located in Mahabalipur, New Delhi. Some famous tourist attractions well connected to the hotel are Red Fort 21 km (approx.), Jama Masjid 20 km (approx.), Chandni Chowk 20 km (approx.), Swaminarayan Akshardham 25 km (approx.), Humayun\'s Tomb 20 km (approx.), Lodi Gardens 17 km (approx.), Qutab Minar 12 km (approx.), India Gate 17 km (approx.) and Bahai (Lotus) Temple 20 km (approx.).\n\nIndira Gandhi International Airport- 3 km (approx.)\nPalam Railway Station- 10 km (approx.)\nMahipalpur Bus Stop- 1 km (approx.)\n\n<b>Facilities</b>\nPower back-up, lift access, foreign exchange assistance, front desk and dining facility are some of the noteworthy conveniences offered to the travelers residing in-house. Hotels in-house restaurant is designed to provide right ambience for dining. Some delectable delicacies are served at the eatery to enrich the vacation experience at the hotel. Front desk staff is present round the clock to take care of every minute need of the guests. \n\n<b>Rooms</b>\nAccommodation choices offered to the travelers at Hotel Port View are Suite, Standard and Premium. The rooms are spaciously set up and are modest in its furnishing. They seem attractive with subtle dcor and soothing combination of colors. They provide home like convenience with wardrobe facility, newspaper availability, refrigerator and TV. Some more amenities provided in the rooms include room service, complimentary bed tea, iron with ironing board, mini bar, AC and wakeup call facility. The bathrooms have 24 hour supply of hot and cold water for convenience. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7378,
                    "Tax": 888,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8288,
                    "PublishedPriceRoundedOff": 8288,
                    "OfferedPrice": 8266,
                    "OfferedPriceRoundedOff": 8266,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB69GLqNunEe6T4islkpgWnDbxoqoCRPlP2wFx3RP6NtTjYewYIFExHK",
                "HotelAddress": "L-384, Main Road NH-8, Mahipalpur Extension,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.54630550360053",
                "Longitude": "77.12397297844291",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 121,
                "HotelCode": "714393",
                "HotelName": "Hotel Surya International",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Surya International, New Delhi, is one of the finest value for money accommodation in town with lavish interiors and a central location. It offers excellent facilities and the best in comfort to ensure that guests have a memorable stay. \n\n<b>Location:</b>\nHotel Surya International is located at W.E.A. Saraswati Marg, Karol Bagh. The nearby places to visit include Jama Masjid (Approx. 6km) and Red Fort (Approx. 8km). Jama Masjid is one of the biggest mosques in Old Delhi with its floor covered with white and black marbles resembling a prayer mat in Mohammedan culture. Guests can also visit interesting destinations such as Akshardham Temple, Humayun\'s Tomb and Lotus Temple.\n\nDistance from Indira Gandhi International Airport: 19 km (approx.)\nDistance from New Delhi Railway Station: 7 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel is perfect for vacationers who would like to relax and enjoy in the lap of luxury. For business meetings, it has an indoor air conditioned restaurant that serves tempting food. If romance is in the air try their exquisitely designed open terrace coffee shop that comes with subdued lighting and soft romantic music. The basic amenities offered at the hotel include room service, access to the internet, front desk, air conditioning, non-smoking rooms, travel desk, doctor-on-call and round the clock security. In addition, the hotel provides business services and conference facilities for corporate travellers. The bar is an ideal destination to relax with quality drinks after a hard day at work or sight-seeing, \n\n<b>Rooms: </b>\nThe accommodation on offer at Hotel Surya International includes premium rooms and executive rooms. The rooms are beautifully appointed and equipped with all the modern comforts and conveniences. All the rooms are air conditioned and furnished with elegant furnishings, offering basic amenities such as flat screen television, internet access, telephone, in-room menu and writing desk. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7418,
                    "Tax": 892.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8332.8,
                    "PublishedPriceRoundedOff": 8333,
                    "OfferedPrice": 8310.8,
                    "OfferedPriceRoundedOff": 8311,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7olb8DoeWiSejC9thtOGH878BPUWE/GWMAV3g1CAI7Zw==",
                "HotelAddress": "4/33,W.E.A. Saraswati Marg, Karol Bagh,,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.6478045",
                "Longitude": "77.1885601",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 122,
                "HotelCode": "314542",
                "HotelName": "The Pearl A Royal Residency",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The Pearl - A Royal Residency is a 4-star boutique-style hotel that provides luxurious accommodation to go with contemporary decor. It also offers a conference room for business meets.\n\n<b>Location: </b>\nSituated at the heart of New Delhi in Karol Baugh, this 4-star property is in close proximity to commercial, financial and entertainment hubs. The famed Metro rail stations are just a few minutes away, as is the downtown city centre. guests can have a gala time shopping at the Karol Baugh Market- the Main Ajmal Khan Road. Popular sightseeing locations around the hotel include Connaught place (Approx. 5km), Jama Masjid (Approx. 6km), India Gate (Approx. 9km), Raj Ghat and Jantar Mantar.\n\nDistance from Indira Gandhi Airport: Approx. 16km\nDistance from New Delhi Railway Station: Approx. 6km\nDistance from Nizzamuddin Railway Station: Approx. 12km\n\n<b>Hotel features: </b>\nThis boutique hotel pampers its guests with concierge services. Travel itineraries can be confirmed and optimised at the in-house travel desk. The conference room at The Pearl - A Royal Residency hotel can seat up to 50 people at one time making it perfect for small-scale business events and meetings. The hotel also has an in-house restaurant. The in-house restaurant serves indian, chinese, and continental cusines.\n\n<b>Rooms: </b>\nEvery air-conditioned room at the hotel is furnished with a colour TV, safe. Spread over 4 floors, these rooms also provide business travellers the facility of direct dialling from in-room telephones or web access using the free Wi-Fi service. ",
                "HotelPromotion": "Book 5 to 365 days before check-in and save 15%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7458,
                    "Tax": 897.6,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 1320,
                    "PublishedPrice": 8377.6,
                    "PublishedPriceRoundedOff": 8378,
                    "OfferedPrice": 8355.6,
                    "OfferedPriceRoundedOff": 8356,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7WYZbJvI3WdKfDNnoY3c3+Sk6JZMYIRhMfSI831OvMDHhRLt+Airz7xeG/RJ7YCxI4/QkLUcqfFA==",
                "HotelAddress": "7A/43, W.E.A, Karol Bagh, Near Metro Pillar No. 123,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645708",
                "Longitude": "77.183651",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 123,
                "HotelCode": "387833",
                "HotelName": "Mehra Residency - Dwarka",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Mehra Residency Dwarka, New Delhi has become the cynosure of all eyes by offering world class facilities at amazingly reasonable prices. In a short span, this hotel has become peerless in the hospitality industry and attracts both family and business travellers alike.\n\n<b>Location</b>\nThis budget hotel is strategically located in the sub city of Delhi, Dwarka, amidst large commercial complexes, shopping arcades and other important destinations. It is few minutes drive to Domestic and IGI International Airport Delhi, making it comfortable for the travellers to commute.\n\nIGI Airport New Delhi: 15-18 Kms Approx New Delhi Railway Station: 23-27 Kms  \n\n<b>Feature</b>\nThe interiors and exteriors of the hotel have been spectacularly designed keeping in mind international standards. The hotel is well equipped to tender to the needs of every guest, irrespective of the purpose of their visit. The services and facilities provided includes; Elevator facilities, 24 Hour Room Service, Picnics/Parties on request, Laundry service, Fax facilities, Wi-Fi Internet facilities, Doctor on call, Restaurant etc. Airport Pick up Service, Train Ticket Booking and Money exchange is available on request.\n\n<b>Rooms</b>\nEach room is provided with separate air-conditioning systems to ensure optimum level of comfort to guests according to their preference. The attached bathrooms are decorated according to the modern taste and interior designing trends. Bathrooms are equipped with geyser to ensure running hot and cold water round the clock.\n\n<b> Note from the hotel </b>\n\nAll guests are requested to carry valid identification which needs to be produced at the time of check-in. Please note that Delhi NCR IDs are not accepted. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7578,
                    "Tax": 912,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8512,
                    "PublishedPriceRoundedOff": 8512,
                    "OfferedPrice": 8490,
                    "OfferedPriceRoundedOff": 8490,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4pST73ZZjtsOei51xHwkEK2p2gYGiAK8D8eEOKjg7wdA==",
                "HotelAddress": "A-45, Sector 19, Behind Vardhman Crown Mall, Dwarka, New Delhi,Delhi,India, , , 110075",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.575402",
                "Longitude": "77.051053",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 19,
                "HotelCode": "1429",
                "HotelName": "Crowne Plaza New Delhi Rohini",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Set 1.6 km from Humayun\'s Tomb, this luxury hotel is also 2.3 km from India Gate and 9 km from the Red Fort. \n\nThe modern rooms have flat-screen TVs, DVD players, minibars and paid Wi-Fi. Suites add living/dining rooms and/or kitchenettes. Room service is available.\n\nAmenities include 3 chic restaurants featuring international fare, and a breakfast buffet is available (fee). There\'s also a bar, a gym and an outdoor pool, as well as a full-service spa.   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8627.46,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8627.46,
                    "PublishedPriceRoundedOff": 8627,
                    "OfferedPrice": 8627.46,
                    "OfferedPriceRoundedOff": 8627,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEfM8uIePElhECnAgAl7HcG+Eiznyjv2UJ6fYM7YFGbIC2r4EAw4rc/YVTzpjOqb85UBZxw9WL2RPhqm5ArwgAIOFnrTmF6YsUeS9rDk5ifrVTCVyGluNOghjVhzV/bwrPZKcIBL+LvX8yHQvo3Le1HcQBBhCgYNb2AfyJ7Se7mcqw==",
                "HotelAddress": "Dr. Zakir Hussain Marg, Delhi, , 110003, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 124,
                "HotelCode": "41162",
                "HotelName": "Hotel Lohias @ Airport Highway",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Lohias Airport Hotel is an ideal destination for both leisure and business travellers. Guests find the hotel to be value for money with friendly staff and well-maintained meeting room.\n\n\n<b>Location:</b>\nLohias Airport Hotel is an economy hotel located on National Highway 8 near Airport Zone. The hotel is ranked as one of the finest hotels in Delhi. Some of the major destinations at a close proximity from the hotel include Qutab Minar, Nehru Park, Lotus Temple, and Raj Ghat. Other tourist spots at a distance from the hotel are Red fort, Connaught Place, India Gate, and Jama Masjid. Vasant Kunj is approximately 5km from the hotel, Rajokri Protected Forest is about 7km away, and Aravalli Biodiversity Park is approximately 9km by road.\n\nDistance from Indira Gandhi Airport: Approx. 6km\nDistance from New Delhi Railway Station: Approx. 18km\nDistance from Nizzamuddin Railway Station: Approx. 21km\n\n\n<b>Hotel Features:</b>\nLohias Airport Hotel features a well-maintained and fully equipped conference hall. The hotel offers a range of amenities like 24 hour security, doctor-on-call, parking and health club. The hotel also houses a multi-cuisine restaurant, Seasons, that serves authentic Indian, Chinese and continental cuisine.\n\n<b>Rooms:</b>\nThe rooms at Lohias Airport Hotel are classy, stylish and elegant with vital modern amenities. Each rooms is well-equipped with TV, and 24 hour menu facility. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7772,
                    "Tax": 935.28,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8729.28,
                    "PublishedPriceRoundedOff": 8729,
                    "OfferedPrice": 8707.28,
                    "OfferedPriceRoundedOff": 8707,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7TC04bEEMLYRMWXEE/R9Md0+KSAodx1E5PKVQiWbQ+Kw==",
                "HotelAddress": "A-53, Mahipalpur Extn. National Highway No-8, Near IGI Airport,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.550475",
                "Longitude": "77.129374",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 125,
                "HotelCode": "358584",
                "HotelName": "Hotel Sarthak Palace",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7774,
                    "Tax": 935.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8731.52,
                    "PublishedPriceRoundedOff": 8732,
                    "OfferedPrice": 8709.52,
                    "OfferedPriceRoundedOff": 8710,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4suhxIXd9EL+CAI5qzqRCELjACo3H2KbQ545UY0uLWPA==",
                "HotelAddress": "14A/34, WEA, Channa Market, Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.644496",
                "Longitude": "77.187841",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 20,
                "HotelCode": "254017",
                "HotelName": "Hotel Classic Diplomat",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Hotel Classic Diplomat is conveniently located in the popular Indira Gandhi Int\'l Airport area. The hotel offers a wide range of amenities and perks to ensure you have a great time. Facilities like 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service are readily available for you to enjoy. Comfortable guestrooms ensure a good night\'s sleep with some rooms featuring facilities such as television LCD/plasma screen, bathroom phone, clothes rack, linens, locker. The hotel offers various recreational opportunities. Discover all New Delhi and NCR has to offer by making Hotel Classic Diplomat your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8853.93,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8853.93,
                    "PublishedPriceRoundedOff": 8854,
                    "OfferedPrice": 8853.93,
                    "OfferedPriceRoundedOff": 8854,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YlVcUKpfznymuANIC+pUaGq21Ym4s7tnf6YF2Xrggi1k+LkZ1i8lTSpVylTnanZYRwtyHZgDrlOlbA6ZrZKDP+tHmBAsUoQ/E7Gik3qJdyk1bl9ya59S1Uk=",
                "HotelAddress": "A-4, Mahipalpur NH-8, Near IGI Airport T3, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 126,
                "HotelCode": "723346",
                "HotelName": "S and B East Inn-Patel Nagar",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "S&B East Inn, New Delhi, is a comfortably designed hotel that strives to provide every level of comfort and satisfaction to its guests. It ensures a memorable and pleasant stay in a peaceful and relaxed ambience. It is one of the most suited places for business as well as leisure travellers in the capital city.\n\n<b>Location:</b>\nS&B East India is located at East Patel Nagar. The National Museum (Approx. 8km) and India Gate (Approx. 8km) are nearby places that can be explored easily from the hotel. The historically famous India Gate is situated on the Raj Path and is 42 metres tall surrounded with beautiful gardens and fountains. Other places of interest include Humayun\'s Tomb, Qutab Minar and Lodi Garden.\n\nDistance from Indira Gandhi International Airport: 18 km (approx.)\nDistance from New Delhi Railway Station: 8 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel with its luxurious and contemporary interiors offers the utmost in comfort, hospitality and peace. For a pleasant and memorable stay it offers basic amenities like room service, internet access, front desk, air conditioning, parking, travel desk, security and doctor-on-call. Additionally, it offers non-smoking rooms, jacuzzi and lounge for recreation. For business travellers it provides a business centre with business services, audio visual equipment, and meeting rooms. Delicious food is served at the modern in-house restaurant.\n\n<b>Rooms:</b>\nThe accommodation at S&B East Inn offers a luxurious lifestyle. The rooms are divided into deluxe and executive. These rooms are air conditioned, spacious, elegantly furnished and well-equipped with modern amenities. Some of which include colour television, internet access, well-stocked mini bar, telephone, in-room menu and writing desk. In addition, the hotel provides guests with temperature control. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 7974,
                    "Tax": 959.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8955.52,
                    "PublishedPriceRoundedOff": 8956,
                    "OfferedPrice": 8933.52,
                    "OfferedPriceRoundedOff": 8934,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5PW28EniH9ruODf3sTtJpbfZH6MK0F5kKeqNbKb9pbvJVc83GBmSjb",
                "HotelAddress": "30/26 East Patel Nagar,Delhi,India, , , 110008",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.644319",
                "Longitude": "77.176315",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 21,
                "HotelCode": "1297762",
                "HotelName": "Balaji Deluxe",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at The Prime Balaji Deluxe @ New Delhi Railway Station in New Delhi (Paharganj), you&apos;ll be convenient to Ramakrishna Mission   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8956.09,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 8956.09,
                    "PublishedPriceRoundedOff": 8956,
                    "OfferedPrice": 8956.09,
                    "OfferedPriceRoundedOff": 8956,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1eA0nkBbmmqFVfp+vpvJQTRX2O5lk10aJpwfkZHGej5qsjw/n1Jk+0F+23zyEQjUHNbQSkUIAO3lUwJeoLqr9QMHQ7t0qhnyuOfIYtnT6m6oq9OLBjuE/u8=",
                "HotelAddress": "8574, Arakashan Road New Delhi 110055 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 22,
                "HotelCode": "1234696",
                "HotelName": "Sunstar Grand",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Hotel Sunstar Grand in New Delhi (Karol Bagh), you&apos;ll be close to Laxminarayan Temple and Jantar Mantar.  This hotel is wi  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9063.64,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9063.64,
                    "PublishedPriceRoundedOff": 9064,
                    "OfferedPrice": 9063.64,
                    "OfferedPriceRoundedOff": 9064,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyNgVcab9hOO0APURgDW4bW+WH0uUDWBDElzBMJsOsVnYfR7PLYo5gUXmGdPxAxFtjJDw1UxbXlEdd+KVSRrmENqHHgXUd8O4Qo=",
                "HotelAddress": "7A/17 WEA Channa Market Karol Bagh , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 23,
                "HotelCode": "185995",
                "HotelName": "Hotel Grand Godwin",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Grand Godwin is conveniently located in the popular Pahar Ganj area. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. Take advantage of the hotel\'s 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, postal service. Some of the well-appointed guestrooms feature television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), non smoking rooms, air conditioning. Access to the hotel\'s massage will further enhance your satisfying stay. Friendly staff, great facilities and close proximity to all that New Delhi and NCR has to offer are three great reasons you should stay at Hotel Grand Godwin.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9108.51,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9108.51,
                    "PublishedPriceRoundedOff": 9109,
                    "OfferedPrice": 9108.51,
                    "OfferedPriceRoundedOff": 9109,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUO3bV+tbAgm2iBc30Ecg7V9fW2suOoTDWOw3F2PuLXDvv3sjlXROwy/0EA1xwGFkgsPp2+ai3E5r5gMyo1W3JajU93M+2u7yW23Hyyg0/eXOCrSbhqEduE8=",
                "HotelAddress": "8502/42 Arakashan road, Ram Nagar, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 127,
                "HotelCode": "725339",
                "HotelName": "Hotel All Iz Well",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel All Iz Well is an artistically designed hotel that mesmerizes its guests with a range of comforting facilities.A 3 Star property offers In-house restaurant serves a wide array of authentic Indian and global cuisines to pamper the taste-buds. The hotel is well-suited to both business and leisure travelers.\n\nLocation\n\nHotel All Iz Well is located at a walking distance from New Delhi Railway Station, 5 Minutes drive from Connaught Place and 10 minutes walking from Ramakrishna Ashram Metro Station. Hotel offers good connectivity from Indira Gandhi International Airport which is at the distance of around 21 km. \n The hotel is in close proximity to India Gate (5 km), Hazrat Nizamuddin Railway Station (7km), Pragati Maidan (4.5 km), Akshardham Mandir (6.5 km), \n\nFacility\n\nHotel All Iz Well provides a range of services for the comfortable stay of guests. The hotel pampers its guests with other eminent amenities like high-speed wireless connectivity of internet, travel desk,24 room service, 24 power backup doctor on call, Laundry & Dry Cleaning Service, local tour and travel desk, Free Dialing telephone and Airport pickup and drop facility (Surcharge).\n\nRooms\nThe hotel possesses 50 enormous rooms which are available in four variants, Deluxe Room, Super Deluxe, Deluxe Triple Room and Family room. All the rooms offer amenities like private bathrooms with rainfall shower heads and well designed toiletries with regular supply of hot and cold running water. Free Wi-Fi and LCD television are available in the rooms. Additional amenities include ceiling fan, Mini Bar (chargeable basis), a safe, working desk, direct dial telephone service and full length mirror.Hair Dryer and Tea Coffee Maker available on request. ",
                "HotelPromotion": "Book Now And Save 40% Per Night",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8135.6,
                    "Tax": 978.91,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 5438.4,
                    "PublishedPrice": 9136.51,
                    "PublishedPriceRoundedOff": 9137,
                    "OfferedPrice": 9114.51,
                    "OfferedPriceRoundedOff": 9115,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB75VZdQ4rTWLAA10Z7qR5s+4k0gTNNRRaRsD4ZzYuiYBS9AG175j+NquC5veGFRgFfFZ4J4KdeuQA==",
                "HotelAddress": "4781, Main Bazar, Pahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.641253",
                "Longitude": "77.214064",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 24,
                "HotelCode": "253995",
                "HotelName": "Amby Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The 3-star Amby Inn offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. The property features a wide range of facilities to make your stay a pleasant experience. Free Wi-Fi in all rooms, 24-hour room service, facilities for disabled guests, Wi-Fi in public areas, valet parking are on the list of things guests can enjoy. Comfortable guestrooms ensure a good night\'s sleep with some rooms featuring facilities such as television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), air conditioning, desk. The hotel offers various recreational opportunities. Discover all New Delhi and NCR has to offer by making Amby Inn your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9165.8,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9165.8,
                    "PublishedPriceRoundedOff": 9166,
                    "OfferedPrice": 9165.8,
                    "OfferedPriceRoundedOff": 9166,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02NeGgzn2svrHGpOAqRWQwnXLVcxSZNieeqdD/A5/qQzeWMnfgturizS1y+T9ls56N1e1LEkVQALlp6gdu+0UO9ri72zwvSxTQHnlrVeZ7BVrDrDsmV4Rj0I=",
                "HotelAddress": "M-13, Lajpat Nagar Part 2, New Delhi and NCR, India, , , 110024, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 25,
                "HotelCode": "802841",
                "HotelName": "New Delhi YMCA Tourist Hostel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "New Delhi YMCA Tourist Hostel is perfectly located for both business and leisure guests in New Delhi and NCR. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. To be found at the hotel are 24-hour front desk, 24-hour room service, facilities for disabled guests, luggage storage, Wi-Fi in public areas. Some of the well-appointed guestrooms feature television LCD/plasma screen, internet access – wireless, non smoking rooms, air conditioning, heating. The hotel\'s peaceful atmosphere extends to its recreational facilities which include fitness center, outdoor pool, children\'s playground, garden. Discover all New Delhi and NCR has to offer by making New Delhi YMCA Tourist Hostel your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9263.09,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9263.09,
                    "PublishedPriceRoundedOff": 9263,
                    "OfferedPrice": 9263.09,
                    "OfferedPriceRoundedOff": 9263,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YoRdoIg9qw9r1YsOXWyHUaTjoG1YQYNqcana8Xk1G6ZF7xjKMnrzCraldlp5P6xvAwoUI5gDc5840V8meDAYua2kCJ4gnATn2/kPiFVGTzgOudDtxyi/HIY=",
                "HotelAddress": "1, Gate no 1,Jai Singh Road, Connaught Place, Delhi National Territory, New Delhi and NCR, India, , , 110001, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 128,
                "HotelCode": "1328440",
                "HotelName": "Hotel Aman Continental",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "Flat 50% Discount !!!!",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8373.79,
                    "Tax": 1007.49,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 8395.79,
                    "PublishedPrice": 9403.28,
                    "PublishedPriceRoundedOff": 9403,
                    "OfferedPrice": 9381.28,
                    "OfferedPriceRoundedOff": 9381,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5/2ofxennbzivJzblMkHq13jEEDbdzBG6NlVvok7aHnvB6+4tdXCPD/QK9s2dDhUw=",
                "HotelAddress": "1048, Main Bazar, Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.643077",
                "Longitude": "77.212644",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 26,
                "HotelCode": "BON|DEL",
                "HotelName": "Bonlon Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Indira Gandhi International Airport is 16 kms away,New Delhi Railway station 3 kms Karol Bagh metro station is 5 mins away,Pragati Maidan Trade Fair ground is 4 kms. Rooms have the availability of both twin and double beds with all modern amenities like TV with satellite cable, Hair dryer and Tea and Coffee maker. WHR02F 10/11 It has multi cuisine restaurant which serves indian, Mughlai, Chinese and Continental. It is traditional building with modern extention with glass and concrete construction. It has very small lobby.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9393.89,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9393.89,
                    "PublishedPriceRoundedOff": 9394,
                    "OfferedPrice": 9393.89,
                    "OfferedPriceRoundedOff": 9394,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mpWoz/K6DwrvCcr/IpwjX10bNUfu+EJI1EoxyY/9yqZC",
                "HotelAddress": "7A/39  W.E.A, CHANNA MARKET KAROL BAGH  NEW DELHI 110005 INDIA, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 27,
                "HotelCode": "CHI|DEL",
                "HotelName": "China Town",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Indra gandhi International Airport is 16 kms away,New Delhi Railway Station 3 kms away,Karol Bagh Metro station is 5 kms away.Pragati maidan Trade Fair Ground is 4 kms away.Centre distance is 3 kms away from the hotel. Rooms are well eqipped with modern amenities,availability of double  and Twin beds,It has Television with cable, Hairdryer,Tea coffee maker. It has small dining are and serves multi cuisine breakfast. It is modern brick building It has very small lobby.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9393.89,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9393.89,
                    "PublishedPriceRoundedOff": 9394,
                    "OfferedPrice": 9393.89,
                    "OfferedPriceRoundedOff": 9394,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mi3cwpHDaNFr8STJ6f0mCpBQByuj1j1JehM8rY8hACVG",
                "HotelAddress": "12A/15  W.E.A, SARASWATI MARG KAROL BAGH NEW DELHI 110005 INDIA, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 129,
                "HotelCode": "41939",
                "HotelName": "Hotel Grand Park Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Situated a short distance away from Delhi\'s commercial and business hub, i.e. Connaught Place, Hotel Grand Park Inn is ideal for both the business and luxury traveler. This gorgeous boutique hotel is close to the New Delhi railway station and Karol Bagh metro station, making it a convenient place for your Delhi stay.\n\n<b>Location</b>\nNestled in the bustling Karol Bagh area, close to Connaught Place and public transport spots, Hotel Grand Park Inn is in the vicinity from places of attraction like Nehru Place (approx 2 km),  Okhla (approx 5 km), India Gate (approx 11 km), Sansad Bhawan, Jantar Mantar, Red Fort, India Gate, and Purana Quila.\n\nIndira Gandhi International Airport-15 km (approx.) \nNew Delhi Railway Station-5 km (approx.)\nKarol Bagh Metro Station- 1 km (approx.)    \n    \n<b>Facilities</b>\nHotel Grand Park Inn flaunts a well-lit and beautifully designed lobby and hallways, complete with snazzy colors that will remind you of the exuberant Delhi culture. With complimentary Wi-Fi and a business center you can arrange your important meetings and conferences here. The roof-top dining area offers lip-smacking delicacies  that will make your stay a pleasurable one.\n\n<b>Rooms: </b>\nHotel Grand Park Inn features  exceptionally clean and well-kept rooms, with simple yet beautiful decor and comfortable furnishings. Every room has amenities like an LCD Television, Wi-Fi, wheel chair access, 24 hours room service etc, ensuring an enjoyable and relaxed stay. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8462,
                    "Tax": 1018.08,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9502.08,
                    "PublishedPriceRoundedOff": 9502,
                    "OfferedPrice": 9480.08,
                    "OfferedPriceRoundedOff": 9480,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5DUqOjyzaasQJNc7icylitAKSOA4kf1nPzBvOWG7kVm4850xQiiSfgL68hSM2y6pI=",
                "HotelAddress": "1041/17, Abdul Rahman Road, Naiwalan, Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.646918",
                "Longitude": "77.195777",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 28,
                "HotelCode": "1297786",
                "HotelName": "Delhi City Centre",
                "HotelCategory": "",
                "StarRating": 2,
                "HotelDescription": "Property Location With a stay at Hotel Delhi City Centre, you&apos;ll be centrally located in New Delhi, convenient to Ramakrishna Mission and Red Fort.  This h  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9631.71,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9631.71,
                    "PublishedPriceRoundedOff": 9632,
                    "OfferedPrice": 9631.71,
                    "OfferedPriceRoundedOff": 9632,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMRJT17sIZ32epil8lZs9ZyHyo7IQTw918rnmfMvKGh78FPEWnkoKzpu61UooNPPwDDLOfghY2DM/tz03U9gdV1P6D9qOTgeoBVYo6ptE/U16e1RYAbALd6gWL5z8a7+hD4zFe8PIuYVOG89xwjh9e1",
                "HotelAddress": "8633-45 Arakashan Road Ram Nagar , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 130,
                "HotelCode": "50736",
                "HotelName": "Hotel Singh Empire Dx",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The Hotel Singh Empire Dx offers excellent facilities for both business and leisure. It is conveniently located in the commercial hub of New Delhi. The hotel has apt resources for holding business conferences and is also located close to numerous popular tourist hotspots.\n\n<b>Location:</b>\nThe hotel is in close proximity to popular tourist locations such as the  Jama Masjid (approx 2km) , India Gate (approx 5 km) , Humayun\'s Tomb (approx 8km). It is also conveniently located near the New Delhi Railway Station. \n\nDistance from Indira Gandhi Airport:  Approx 16 km\nDistance from New Delhi Railway Station: Approx 3 km\nDistance from Nizzamuddin Railway Station: Approx 9 km\n\n<b>Hotel Features:</b>\nThe hotel has a host of features that ensure guests have a comfortable stay. Business travellers can use the conference hall for business presentations or the conference facilities and business centre for meetings. Valet parking service is available at the Hotel Singh Empire Dx. Guests can choose to dine at the in-house restaurant.The in-house restaurant at the Hotel Singh Empire Dx offers sumptuous multi-cuisine meals from Indian, Chinese and Continental cuisines.\n\n<b>Rooms:</b>\nThe hotel contains  different rooms. Guests have the option of choosing between deluxe rooms, family rooms and suites. The rooms are all air-conditioned and come with Cable TV(LCD) with more than 100 channels, attached bath with hot/cold water and other services. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8618,
                    "Tax": 1036.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9676.8,
                    "PublishedPriceRoundedOff": 9677,
                    "OfferedPrice": 9654.8,
                    "OfferedPriceRoundedOff": 9655,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5pm87Zb62yjVoNergBU4O8OaLfgGdH7LRMIeiK8H2i6Vuy07PKQZof",
                "HotelAddress": "8707-8710, D.B. Gupta Road, Pahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.644745",
                "Longitude": "77.2136777",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 29,
                "HotelCode": "1356466",
                "HotelName": "Clarks Inn Nehru Place",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location You&apos;ll be centrally located in New Delhi with a stay at Clarks Inn Nehru Place, minutes from Kalkaji Mandir and Lotus Temple.  This hotel  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9655.49,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9655.49,
                    "PublishedPriceRoundedOff": 9655,
                    "OfferedPrice": 9655.49,
                    "OfferedPriceRoundedOff": 9655,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMRJT17sIZ32YUCHzZxvGcgLe1KIQRQIOnL4zjQrtW2/7Z8KYrdrFvk6R3hRYxZNPTmIj0Qz5LuBOBvoxEOA2I93B98+6ZK1n7Mjmwu6SZc4KAmA/cgkftwx2r+8z60dHXxGp69k/pgopy+bwYR8PdD26zxxpd3/rI=",
                "HotelAddress": "CC-21 Kalkaji Nehru Place , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 30,
                "HotelCode": "1128930",
                "HotelName": "Metro Heights",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Set in a prime location of New Delhi and NCR, Hotel Metro Heights puts everything the city has to offer just outside your doorstep. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. To be found at the hotel are free Wi-Fi in all rooms, 24-hour security, daily housekeeping, fireplace, taxi service. Towels, fireplace, linens, internet access – wireless, internet access – wireless (complimentary) can be found in selected guestrooms. The hotel offers various recreational opportunities. A welcoming atmosphere and excellent service are what you can expect during your stay at Hotel Metro Heights. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9819.8,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9819.8,
                    "PublishedPriceRoundedOff": 9820,
                    "OfferedPrice": 9819.8,
                    "OfferedPriceRoundedOff": 9820,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YjpRPvcByLjNDL14j9j/q/JC+GumvCwvhFKIKohx9TL16SUFFFGJqPKde62oSu/XhW0sqWcPAuoQRfPsfBygtbL/jD9w85cQkFAP0TOo08zrUs/1YG/uxbM=",
                "HotelAddress": "8/35, W. E. A., Padam Singh Road New Delhi 110005, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 131,
                "HotelCode": "42003",
                "HotelName": "Hotel Regent Continental",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8774,
                    "Tax": 1055.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9851.52,
                    "PublishedPriceRoundedOff": 9852,
                    "OfferedPrice": 9829.52,
                    "OfferedPriceRoundedOff": 9830,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7V40mFxiF01FnZ13wAmwax/7Ze7FkBZ3EcZml7Xd0M5nVqkTGwUC8G",
                "HotelAddress": "4/73, W.E.A., Krishna Market, Saraswati Marg, Karol Bagh,Delhi,India, , , 110 005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.648439",
                "Longitude": "77.18808",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 132,
                "HotelCode": "723337",
                "HotelName": "Hotel Anand Lok Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The courteous staff of Anand Lok Inn, New Delhi, offers flawless personalized services to the patrons. The interiors are beautifully decorated, creating an ambience of relaxation and comfort. Set in one of the most distinguishing addresses in the Capital city, it is ideal for leisure as well as business travellers. \n\n<b>Location:</b>\nAnand Lok Inn is located at Lane No. 4, Mahipalpur Extension. It is in proximity to the IGI International airport and well connected to the metro station and malls. The nearby places worth visiting include the Fellowship of Believer\'s Abundant Life Church (Approx. 2km) and Aravalli Biodiversity Park (Approx. 5km). During their stay, guests can visit other famous place like the Red Fort, Jama Masjid and Lotus Temple. The Red Fort is also known as Lal Qila and represents the grandeur of the Mughal Court.\n\n\nDistance from Indira Gandhi International Airport: 3 km (approx.)\nDistance from New Delhi Railway Station: 18 km (approx.)\n\n<b>Hotel Features:</b>\nHotel Anand Lok Inn has tastefully done up interiors with attention to every detail in regards to the comfort and satisfaction of the guests. It offers basic amenities, including room service, internet access, front desk, non-smoking rooms, travel desk, doctor-on-call and round the clock security. For business executives, the hotel provides a business centre with business services, audio visual equipment, and meeting rooms. The hotel houses a multi-cuisine restaurant that serves some of the most delicious delicacies of New Delhi. \n\n<b>Rooms:</b>\nThe hotel offers deluxe rooms, executive rooms and suites for accommodation. The rooms are air-conditioned, spacious and comfortable and carry all the modern day amenities required for a pleasant and memorable stay. Some basic ones include colour television, internet access, mini bar, safe, telephone, temperature control, and writing desk. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8774,
                    "Tax": 1055.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 9851.52,
                    "PublishedPriceRoundedOff": 9852,
                    "OfferedPrice": 9829.52,
                    "OfferedPriceRoundedOff": 9830,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5PW28EniH9rkhfGXsQOUYmfyG8OhNptrsnYj1BJ8d5MEgKap54E7h2",
                "HotelAddress": "Lane No. 4, Mahipalpur Extn.,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.542933",
                "Longitude": "77.125117",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 31,
                "HotelCode": "181760",
                "HotelName": "Hotel Le Seasons",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Ideally located in the prime touristic area of Indira Gandhi Int\'l Airport, Hotel Le Seasons promises a relaxing and wonderful visit. The hotel has everything you need for a comfortable stay. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, convenience store, daily housekeeping are on the list of things guests can enjoy. Guestrooms are fitted with all the amenities you need for a good night\'s sleep. In some of the rooms, guests can find television LCD/plasma screen, complimentary instant coffee, free welcome drink, locker, scale. To enhance guests\' stay, the hotel offers recreational facilities such as fitness center. Friendly staff, great facilities and close proximity to all that New Delhi and NCR has to offer are three great reasons you should stay at Hotel Le Seasons.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10047.9,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10047.9,
                    "PublishedPriceRoundedOff": 10048,
                    "OfferedPrice": 10047.9,
                    "OfferedPriceRoundedOff": 10048,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxH7uGQ9aYa+MxAUAj72TO2wN0RFils09ldHJwtUagVbQ5kQ1fDoydoaagNSAV/rgrj9m3e6U7pki1oCJlOwtNgyF89z5pdbiJ6UHi6rEGuPDMfVW2/cC+rA=",
                "HotelAddress": "A- 1A, A Block, Mahipalpur Extension, (Opp. Aerocity Metro Station), N.H - 8, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 133,
                "HotelCode": "708861",
                "HotelName": "Hotel Fortuner",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Fortuner, Delhi, is an elegant business offering in the city. The gracious ambience and heart-warming hospitality carves a special place in the heart of the guests. The rooms are indeed comfy while the various modern amenities cater to every need of the traveller. The delightful cuisine entices foodies and the conference facilities make the hotel perfect for business travellers.\n\n<b>Location: </b>\nHotel Fortuner is situated on Main Faiz Road, Karol Bagh, quite close to the New Delhi Railway Station. Places of attraction like Hanuman Temple (Approx. 2km) and Connaught place (Approx. 3km) lie close to the hotel. Hanuman Temple in Connaught Place is an ancient Hindu temple and is believed to be one of the five temples of Mahabharata days in Delhi. Lahore Gate, Pragati Maidan and Mumtaz Mahal are some of the other prominent tourist attractions.\n\nDistance from Indira Gandhi Airport: Approx. 15km\nDistance from New Delhi Railway Station: Approx. 4km\nDistance from Nizamuddin Railway Station: Approx. 11km\n\n<b>Hotel Features: </b>\nGuests are ensured complete convenience, business facilities and culinary delight with the various hotel features. The range of amenities that make the stay comfortable include internet, 24-hour front desk, laundry, non-smoking rooms, parking, travel desk, 24-hour security and doctor-on-call. The hotel can also arrange for corporate events in the conference rooms fitted with LCD and audio-visual equipments. The hotel also has a restaurant that serves delicious food.\n\n<b>Rooms: </b>\nAccommodation is available in the form of super deluxe rooms, classic rooms and executive rooms. These rooms sport a contemporary design with modern furniture and the abstract paintings add to the pleasure element. Internet access, iron, minibar, telephone, flat-screen TV and writing desk are the features that are provided in every room for the convenience of the guests. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8974.4,
                    "Tax": 1079.57,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10075.97,
                    "PublishedPriceRoundedOff": 10076,
                    "OfferedPrice": 10053.97,
                    "OfferedPriceRoundedOff": 10054,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4VkJcsUdgkdAEGLQrzsoaQjoLMpMzyGqjrobdeDNmKU4r3vJNlXc2A",
                "HotelAddress": "943 / 3 Main Faiz Road, Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.648319",
                "Longitude": "77.198808",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 134,
                "HotelCode": "720217",
                "HotelName": "Hotel Shimla Heritage",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Shimla Heritage, New Delhi, invite patrons to experience a homely stay. This comfortable abode with a coveted location and unmatched services along with present-day conveniences, spacious rooms and tempting meals is a suitable for both leisure and business travellers.\n\n<b>Location:</b>\nHotel Shimla Heritage is located at W.E.A., Channa Market, Karol Bagh. The famous Connaught Place (Approx. 6km) and Gurudwara Bangla Sahib (Approx. 5km) are nearby places worth visiting. Connaught Place is the commercial hub of New Delhi and is popular for shopping and well planned Victorian styled architecture. Other interesting places to explore include Lotus Temple, India Gate and Qutab Minar.\n\nDistance from Indira Gandhi International Airport: 15 km (approx.)\nDistance from New Delhi Railway Station: 7 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel offers a comfortable feeling of home to both leisure and business travellers. The range of basic amenities ensured to the patrons are room service, internet access, air conditioning, non-smoking rooms, parking, travel desk, security and doctor-on-call. Business travellers can avail of the business centre that provides business services, audio visual equipment, and board rooms. The indoor multi-cuisine restaurant satiates the diversified palates of the guests with a variety of delicious fare. Patrons can also spend their leisure time with loved ones at 24 hrs Cafe lounge. \n\n<b>Rooms:</b>\nHotel Shimla Heritage offers warm and comfortable deluxe rooms, with or without balconies. These rooms features simplicity and convenience at its best. Some in-room amenities include air conditioning, colour television, internet access, well-stocked mini bar, refrigerator, telephone, bedside lamp, in-room menu and writing desk. Additionally, it also provides table lamp, tea/coffee maker and international plug points to enhance the comfort quotient of the patrons. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 8978,
                    "Tax": 1080,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10080,
                    "PublishedPriceRoundedOff": 10080,
                    "OfferedPrice": 10058,
                    "OfferedPriceRoundedOff": 10058,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB56MGrYoYwklzbba03XQCddeVsRoxvH1WFTChTCeeWisgOHsUCnRqUh",
                "HotelAddress": "7A/36, W.E.A., Channa Market, Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645845",
                "Longitude": "77.18338",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 32,
                "HotelCode": "858228",
                "HotelName": "Park Inn by Radisson New Delhi IP Extension",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Set in a prime location of New Delhi and NCR, Park Inn by Radisson New Delhi IP Extention puts everything the city has to offer just outside your doorstep. The hotel offers a high standard of service and amenities to suit the individual needs of all travelers. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service are there for guest\'s enjoyment. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, free welcome drink, linens, locker, mirror. The hotel offers various recreational opportunities. For reliable service and professional staff, Park Inn by Radisson New Delhi IP Extention caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10126.27,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10126.27,
                    "PublishedPriceRoundedOff": 10126,
                    "OfferedPrice": 10126.27,
                    "OfferedPriceRoundedOff": 10126,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02ALMQK+x0RLTA0mpOn/0XPVUFcJyGmqX70TU2SQMTcEeR1DvSpgFy+g+bwyLYIMHoikLz17iKb0dV3nHQCIJVAAHc4cu3366PTalwkU2Uqr8QxM28XEOqvE=",
                "HotelAddress": "Plot No.6A, IP Extension, Patparganj, Delhi National Territory, New Delhi and NCR, India, , , 110092, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 135,
                "HotelCode": "50727",
                "HotelName": "Hotel Grand Godwin",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9474,
                    "Tax": 1139.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10635.52,
                    "PublishedPriceRoundedOff": 10636,
                    "OfferedPrice": 10613.52,
                    "OfferedPriceRoundedOff": 10614,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4kCjzKlE9/MPL7QbLcynlgVcJM9VzrtYhlHdZ5N+hczTN0KQUik0wg",
                "HotelAddress": "8502/41 Arakashan Road, Ram Nagar, Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645852",
                "Longitude": "77.215309",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 136,
                "HotelCode": "332514",
                "HotelName": "Hotel Clark Heights-Patel Nagar",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Clark Heights is a boutique hotel offering facilities like a spa and conference halls, making it ideal for leisure tourists as well as business travellers. Its beautifully decorated interiors create a soothing and inviting ambience.\n\n<b>Location: </b>\nHotel Clark Heights is situated at West Patel Nagar in New Delhi. It is just a short distance away from the famous Karol Bagh shopping area and is 10 minutes away from Connaught Place. Popular shopping areas like Sadar Bazaar and Palika Bazaar are proximate to the hotel. Places of tourists\' interest around this hotel include Jantar Mantar (Approx. 8km), India Gate (Approx.8km), Jama Masjid (Approx. 8km) and Chandni Chowk.\n\nDistance from Indira Gandhi Airport: Approx. 16km\nDistance from New Delhi Railway Station: Approx. 7km\nDistance from Nizzamuddin Railway Station: Approx. 13km\n\n<b>Hotel Features: </b>\nHotel Clark Heights provides a range of exclusive facilities. It includes a spa that is perfect for guests to pamper themselves with body treatments and exotic massages. The conference and banquet facilities. Others include 24-hour front desk, round-the-clock room service and a multi-cuisine restaurant that serves delectable Indian, Chinese and Continental dishes.\n\n<b>Rooms: </b>\nHotel Clark Heights has air-conditioned rooms that are equipped with variety of amenities that ensure complete comfort. It includes flat-screen colour TV with satellite TV channels, ironing board, a writing desk chargeable Wi-Fi, telephone etc. ",
                "HotelPromotion": "Flat 50% Discount!!",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9574,
                    "Tax": 1151.52,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 9596,
                    "PublishedPrice": 10747.52,
                    "PublishedPriceRoundedOff": 10748,
                    "OfferedPrice": 10725.52,
                    "OfferedPriceRoundedOff": 10726,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5ON23vD8v+e/hbHDxS8WCbcz80YF7XHPq4+P7yjslOOKA/6ytttfZdH/yJ0yNp3Jo=",
                "HotelAddress": "1/1 West Patel Nagar, Opp. Metro Pillar No. 209,New Delhi,Delhi,India, , , 110008",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.648309",
                "Longitude": "77.164331",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 137,
                "HotelCode": "708512",
                "HotelName": "Hotel Shelton",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "Last minute deal 40% off...",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9575.6,
                    "Tax": 1151.71,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 6398.4,
                    "PublishedPrice": 10749.31,
                    "PublishedPriceRoundedOff": 10749,
                    "OfferedPrice": 10727.31,
                    "OfferedPriceRoundedOff": 10727,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5So99jD2k06XcXPYNGkvxmbHvIhXlKgouYx30PRxw+QN9h8dqQjABd",
                "HotelAddress": "5043, Main Bazar, Pahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.641066",
                "Longitude": "77.213415",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 33,
                "HotelCode": "1261401",
                "HotelName": "Grand Park Inn",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location A stay at Hotel Grand Park-Inn places you in the heart of New Delhi, convenient to Sadar Bazaar.  This hotel is within close proximity of Rama  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10806.76,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10806.76,
                    "PublishedPriceRoundedOff": 10807,
                    "OfferedPrice": 10806.76,
                    "OfferedPriceRoundedOff": 10807,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1eA0nkBbmmqFVfp+vpvJQTRKEHcaoTwpyIqfMOqYh0rB3TSf/sbUsgyz1AoIvAbhIDf0BrbhMVFD7BU8SWPH+vNK1tVb+f2Pqw==",
                "HotelAddress": "1041/17, Abdul Rahman Marg New Delhi 110005 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 34,
                "HotelCode": "109750",
                "HotelName": "Hotel Emperor Palms",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Emperor Palms is perfectly located for both business and leisure guests in New Delhi and NCR. Both business travelers and tourists can enjoy the hotel\'s facilities and services. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, car power charging station, daily housekeeping. Television LCD/plasma screen, clothes rack, complimentary instant coffee, complimentary tea, linens can be found in selected guestrooms. The hotel offers various recreational opportunities. Friendly staff, great facilities and close proximity to all that New Delhi and NCR has to offer are three great reasons you should stay at Hotel Emperor Palms.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10925.67,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10925.67,
                    "PublishedPriceRoundedOff": 10926,
                    "OfferedPrice": 10925.67,
                    "OfferedPriceRoundedOff": 10926,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02BXdQaeJbwsehZY2DGcP0EQufm20a+Tud/i6N5FA9oCGH91oUeWWCoHIlx7iuYvUgCPz4+wJv752MFuu4IRH9TnIy3z8IiGI/je45pze60ex8n/sK/I1xl0=",
                "HotelAddress": "15A/7 W.E.A, Puja Park, Opp. New Bloom School, Near Karol Bagh Metro Stn, New Delhi and NCR, India, , , 110005, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 35,
                "HotelCode": "1618699",
                "HotelName": "SK Crown Park Naraina",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The 3.5-star SK Crown Park Naraina offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. Both business travelers and tourists can enjoy the hotel\'s facilities and services. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, car power charging station, convenience store. Each guestroom is elegantly furnished and equipped with handy amenities. The hotel offers various recreational opportunities. SK Crown Park Naraina is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10971.61,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 10971.61,
                    "PublishedPriceRoundedOff": 10972,
                    "OfferedPrice": 10971.61,
                    "OfferedPriceRoundedOff": 10972,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxJzFVwWftAUdkmArHDTTHVUbP6OL6kD5jgX/xMFznIF8KVF/UIqv+P88m4xqhpq8rqy98O14ACt4zgfmA4FtUo4BHzuMdqHFmYPrWTzG+aIBzS122hdLwDQ=",
                "HotelAddress": "A1,Ring Rd,Block A.Naraina,New Delhi, Delhi National Territory, New Delhi and NCR, India, , , 110028, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 36,
                "HotelCode": "462890",
                "HotelName": "The Athena Hotel",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Ideally located in the prime touristic area of South Delhi, The Athena Hotel promises a relaxing and wonderful visit. The hotel offers a high standard of service and amenities to suit the individual needs of all travelers. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, postal service. Some of the well-appointed guestrooms feature television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), non smoking rooms, air conditioning. Enjoy the hotel\'s recreational facilities, including massage, garden, before retiring to your room for a well-deserved rest. For reliable service and professional staff, The Athena Hotel caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11403.47,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 11403.47,
                    "PublishedPriceRoundedOff": 11403,
                    "OfferedPrice": 11403.47,
                    "OfferedPriceRoundedOff": 11403,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+Yr9CtZzb+JWZaz5DDTnxSx1t/gsYpZQSLxmanbWxeByPlr+Z1mCnJ65P75Bo4k+VQdwYROhE5NnvZ8yJr8gFWVUSSud+nKmADUaueW8MLgHR+breAJ1QeDA=",
                "HotelAddress": "19, Eastern Avenue,Maharani Bagh,, New Delhi and NCR, India, , , 110065, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 138,
                "HotelCode": "729833",
                "HotelName": "Treebo Amber",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Treebo Amber in Sukhdev Vihar, New Delhi enjoys a nice position near many corporate offices and local attractions like Lotus Temple (4.7 km) and Humayuns tomb (6.4 km). Shopping hotspots are nearby too, making it a perfect base for all kinds of travellers visiting Delhi. Moolson Coors, Uniclean,Unitech, Hindustan Tin Works Ltd, ANK Global Health, T&K consultancy, Radiant all these companies are within 6 km of the hotel.\n\nTonnes of facilities are available at this Treebo property: Breakfast and Wi-Fi and Parking are complimentary and laundry service is available for a charge. A travel desk, ironing board and event hosting facilities are also available. The restaurant serving delicious food within the premises is a must visit. The hotel also has a cool roof top cafe that is perfect for spending time and unwinding. The hotel authorities can arrange for a spa treatment upon request.\n\nAll rooms are air-conditioned and central heating is available to comfort guests in chilly winters of Delhi. Other amenities include electric kettle, LCD TV, satellite channels and free toiletries in the bathroom. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9946.93,
                    "Tax": 1510.44,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 11479.37,
                    "PublishedPriceRoundedOff": 11479,
                    "OfferedPrice": 11457.37,
                    "OfferedPriceRoundedOff": 11457,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6yi7/iY7r2RTNq3pRh6HV3DQYD/M+5jF2orNaU0ccpRiqgVlZm0KFw",
                "HotelAddress": "198, Sukhdev Vihar, Okhla.,Delhi,India, , , 110025",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.555497",
                "Longitude": "77.275515",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 37,
                "HotelCode": "1154585",
                "HotelName": "Centaur IGI Airport",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Sitting just one kilometre from the Indira Gandhi International Airport this hotel is the perfect stop for the weary traveller. Set in a building shaped like a star with four arches pointing in the fo  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11479.68,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 11479.68,
                    "PublishedPriceRoundedOff": 11480,
                    "OfferedPrice": 11479.68,
                    "OfferedPriceRoundedOff": 11480,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMAyopOFYk9cVrP5efkvjnnYw4Mq1ZrQXfG7o1Z8gsD3sjfZvgjjVvApcVDd8yuouEBJCPLO3BuHUaNlIignGSqJYhrPxXTspxzLh2gA+srXn8oOmqQ9rDqq09kBXOA301XqAoPAzaZiore9rBH0/r4",
                "HotelAddress": "Indira Gandhi International Airport New Delhi 110037 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 139,
                "HotelCode": "317458",
                "HotelName": "Hotel La Vista",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel La Vista provides its guests a delectable blend of luxurious contemporary ambience and business facilities for hosting events and conferences complete with banqueting services.\n\n<b>Location: </b>\nIdeally located at the heart of Delhi in Karol Baugh, this hotel is proximate to the interstate bus terminus. Guests wishing to indulge in some sightseeing can visit Jama Masjid (Approx. 4km), Jantar Mantar (Approx. 6km), India Gate (Approx. 7km), Lodhi Gardens and Red Fort, or sample some local cuisine at the colourful Chandni Chowk.\n\nDistance from Indira Gandhi Airport: Approx. 15km\nDistance from New Delhi Railway Station: Approx. 4km\nDistance from Nizzamuddin Railway Station: Approx. 11km\n\n<b>Hotel features: </b>\nBusiness travellers will like the array of facilities provided to them by Hotel La Vista. It has a lounge, conference hall and also offers banquet facilities for hosting business and social events. Ample parking space is available for visitors\' vehicles. Medical emergencies are catered to by a doctor available on-call. This hotel houses a multi-cuisine restaurant that serves indian, chinese, continental cusines.\n\n<b>Rooms: </b>\nSpread over the floors, the air-conditioned rooms at the Hotel La Vista ensure complete guest comfort with creature comforts, such as colour TV, mini bar, telephone with direct dialling and Complimentary Shoe shine. All rooms are temperature controlled and come with a tea/coffee maker. Wi-Fi-based web access is also offered at an extra charge. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 9974,
                    "Tax": 1517.4,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 11513.4,
                    "PublishedPriceRoundedOff": 11513,
                    "OfferedPrice": 11491.4,
                    "OfferedPriceRoundedOff": 11491,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB68W7egSEAVntNS24dth+C+Z6kpZCnMCRrrew1rYzMeGVzlPA3tAgbD",
                "HotelAddress": "938/3, Nai Walan, Illahi Bux Road (Opp. A2Z News Office), Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.648725",
                "Longitude": "77.198524",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 38,
                "HotelCode": "1110603",
                "HotelName": "Hotel Aman Continental",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in Pahar Ganj, Hotel Aman Continental is a perfect starting point from which to explore New Delhi and NCR. The property features a wide range of facilities to make your stay a pleasant experience. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service are there for guest\'s enjoyment. Each guestroom is elegantly furnished and equipped with handy amenities. The hotel offers various recreational opportunities. Hotel Aman Continental combines warm hospitality with a lovely ambiance to make your stay in New Delhi and NCR unforgettable.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11776.95,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 11776.95,
                    "PublishedPriceRoundedOff": 11777,
                    "OfferedPrice": 11776.95,
                    "OfferedPriceRoundedOff": 11777,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02MX3z8dt1BF6DQUEVflUv3CsQccozAnMQng0iEDwiCZFdDBqTUk4jspbW/ZjP4NctWQi06B19lih0YGzYTKDOE75brgHLIdtpfBq1/RnjYiCzQPudvlRV2c=",
                "HotelAddress": "1048, Main Bazar, Paharganj, Delhi National Territory, New Delhi and NCR, India, , , 110055, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 39,
                "HotelCode": "271615",
                "HotelName": "Wood Castle Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Stop at Wood Castle Hotel to discover the wonders of New Delhi and NCR. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, daily housekeeping, taxi service, postal service. Some of the well-appointed guestrooms feature television LCD/plasma screen, clothes rack, free welcome drink, linens, locker. The hotel offers various recreational opportunities. For reliable service and professional staff, Wood Castle Hotel caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11955.32,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 11955.32,
                    "PublishedPriceRoundedOff": 11955,
                    "OfferedPrice": 11955.32,
                    "OfferedPriceRoundedOff": 11955,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG5/aTYWMhK4Xq4+09x9EZBXGfqypgyDpswW9q94PS1qP/UGOPbLiVseOH0qxL1iDHXKQUTsmb1En+nRRGb66xull5YBeub9YgkGBfo2EqEe7fEBGNPZBPYl4=",
                "HotelAddress": "8A/7G, W.E.A. Channa Market, New Delhi and NCR, India, , , 110005, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 140,
                "HotelCode": "313745",
                "HotelName": "Hotel Alpine Tree",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Whether it is corporate outing or a family get-together, Hotel Alpine Tree is best when it comes to great hospitality and luxurious stay. Equipped with all modern facilities, this hotel leaves no stone unturned to meet the requirements of its guests.\n\n<b> Location:</b> \nAlpine Tree hotel is located on the Delhi - Gurgaon expressway, near Mahipalpur that is in close proximity to the airport as well as the railway station. The industrial district of Gurgaon is just a short distance away. Famous tourist destinations like Vasant Kunj (Approx. 5km), Aravalli Biodiversity Park (Approx. 9km), Pusa Hill Forest (Approx. 10km), Qutab Minar, India Gate and Rajpath are accessible from the hotel as well.\n\nDistance from Indira Gandhi Airport: Approx. 5km\nDistance from New Delhi Railway Station: Approx. 18km\nDistance from Nizzamuddin Railway Station: Approx. 20km\n\n<b> Hotel Features: </b> \nAlpine Tree is a 3-star hotel that provides its guests amazing hospitality with a touch of elegance. The royal exterior and contemporarily designed interiors make this hotel plush. This hotel offers a conference hall to conduct official seminars and events for business travellers. Other facilities include laundry services, travel desk, currency exchange facilities and internet services too. Doctor on-call services are also available in case of medical emergencies.\n\n<b> Rooms: </b> \nThis 3-star hotel has rooms with categories of Deluxe and Executive. Each room is equipped with lavish furniture, STD/ISD, Wi-Fi , LCD TV sets and Direct Dialing facilities. A safe is provided in each room to keep the valuables of the guests protected. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10174.6,
                    "Tax": 1835.39,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 12031.99,
                    "PublishedPriceRoundedOff": 12032,
                    "OfferedPrice": 12009.99,
                    "OfferedPriceRoundedOff": 12010,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7j/2h3I89Rp1ubArbgqcUwfxeh5YPnF6+ZIRxf5P+8Bm4f2CiPHgpx",
                "HotelAddress": "A-282, Mahipalpur Extn.,National Highway-08,Near I.G.I. Airport,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.549074",
                "Longitude": "77.127243",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 40,
                "HotelCode": "186616",
                "HotelName": "Country Inn & Suites By Carlson Sahibabad",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Ideally located in the prime touristic area of Ghaziabad, Country Inn & Suites By Carlson Sahibabad promises a relaxing and wonderful visit. The hotel has everything you need for a comfortable stay. Facilities like 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service are readily available for you to enjoy. Guestrooms are fitted with all the amenities you need for a good night\'s sleep. In some of the rooms, guests can find television LCD/plasma screen, clothes rack, complimentary instant coffee, complimentary tea, linens. To enhance guests\' stay, the hotel offers recreational facilities such as hot tub, fitness center, outdoor pool, spa, massage. A welcoming atmosphere and excellent service are what you can expect during your stay at Country Inn & Suites By Carlson Sahibabad.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12214.22,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 12214.22,
                    "PublishedPriceRoundedOff": 12214,
                    "OfferedPrice": 12214.22,
                    "OfferedPriceRoundedOff": 12214,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUPWePHAw7XEXVh6GXrVEKsgFxgxS4NouBlkLoSfQISJ+pa3asypMftYbv21VMWiCb3Aif8LetBmBnkZXkW0AJ224XlqetgFgRh3leru/gAiGof3eiOIiyI0=",
                "HotelAddress": "64/6, Site IV, Industrial Area, Sahibabad, New Delhi and NCR, India, , , 201010, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 141,
                "HotelCode": "718177",
                "HotelName": "Hotel Cabana",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Cabana, New Delhi is a elegant boutique property with one of the most ideal central locations in town. It offers a luxurious and comfortable environment to soothe mind and body. Its warm and friendly hospitality attracts corporate business travellers as well as domestic and international travellers. \n\n<b>Location:</b>\nHotel Cabana is located at R.K. Ashram road, Paharganj. It is in proximity to many historical sightseeing places, including Connaught Place (Approx. 3km) and National Museum (Approx. 5km). Connaught Place with its Victorian styled architecture is the commercial hub of the capital and very popular with tourists and shopping. Other interesting places include Jama Masjid, Qutab Minar and Lotus Temple.\n\nDistance from Indira Gandhi International Airport: 17 km (approx.)\nDistance from New Delhi Railway Station: 2 km (approx.)\n\n<b>Hotel Features:</b>\nHotel Cabana\'s main feature is its spacious terrace where guests can spend their evenings with tea and snacks. The hotel offers some basic amenities like room service, internet access, front desk, air conditioning, travel desk, indoor parking and round the clock security. In addition, it offers doctor-on-call, non-smoking rooms and guest lift to all floors. For corporate or social functions, guests can use the hotel\'s business services, audio visual equipment, meeting facilities and conference equipment. The in-house restaurant is the best place to savour Indian, Chinese and European delights.\n\n<b>Rooms:</b>\nThe accommodation offered at the hotel includes the Deluxe A/c rooms, super deluxe A/c rooms and family triple rooms. All rooms are air conditioned and have basic amenities such as colour television, hair dryer, internet access, mini bar, telephone, bedside lamp and in-room menu. In addition, the guests are provided with ceiling fan, tea/coffee maker, temperature control, Wi-Fi access, international plug points and writing desk. ",
                "HotelPromotion": "Flash Sale 50% Off...",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10376,
                    "Tax": 1871.64,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 10398,
                    "PublishedPrice": 12269.64,
                    "PublishedPriceRoundedOff": 12270,
                    "OfferedPrice": 12247.64,
                    "OfferedPriceRoundedOff": 12248,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7SdRD6VwSisR8q8RF+CJrMtJhv8mRGs2oKiy3mjm2NWY6hmPahWiQ3",
                "HotelAddress": "2313, R.K Ashram Road (Behind Imperial Cinema), Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.6411123",
                "Longitude": "77.2114861",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 41,
                "HotelCode": "AJA3|DEL",
                "HotelName": "Ajanta",
                "HotelCategory": "",
                "StarRating": 2,
                "HotelDescription": "Located in the Ram Nagar district of New Delhi, near the main railway station, serving destinations throughout India. Standard rooms are basically furnished and in good condition. These rooms have neither air-conditioning nor windows, and the shower area in the bathroom is not separate from the basin/toilet area. A bustling coffee shop is located just off the lobby, and a rooftop restaurant is currently under construction. A modern brick building. The reception area offers standing space, with seating in the adjacent coffee shop. An in-house travel agency assists travellers in planning their journey around New Delhi and India. A good quality budget property, ideally located near the train station. Independent travellers will appreciate the in-house travel agency. TO/0407  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12247.73,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 12247.73,
                    "PublishedPriceRoundedOff": 12248,
                    "OfferedPrice": 12247.73,
                    "OfferedPriceRoundedOff": 12248,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mq7S+N7TfHiu+U7nEsUFx+6R0WxRVbf6RYuafg6zSy/O",
                "HotelAddress": "36 ARAKASHAN ROAD - RAM NAGAR OPPOSITE NEW DELHI RAILWAY STATION NEW DELHI INDIA, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 142,
                "HotelCode": "870088",
                "HotelName": "Hotel Freesia-Defence Colony",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "Book now and get 49% discount per night",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10565.6,
                    "Tax": 1905.77,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 12940.4,
                    "PublishedPrice": 12493.37,
                    "PublishedPriceRoundedOff": 12493,
                    "OfferedPrice": 12471.37,
                    "OfferedPriceRoundedOff": 12471,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB58aR8U5lXJicb5RXWYPHtyra3Bs27sKGYCAKNoV+zlDoo/9Jos50As",
                "HotelAddress": "E-19, Main Ring Road, Defence Colony,Delhi,India, , , 110024",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.5672882",
                "Longitude": "77.2280361",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 143,
                "HotelCode": "713623",
                "HotelName": "Hotel Le Benz",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Le Benz is an artistically designed hotel that mesmerizes its guests with a range of comforting facilities.A 3 Star property offers In-house restaurant which serves a wide array of authentic Indian and global cuisines to pamper the taste-buds. The hotel is well-suited to both business and leisure travelers.\n\nLocation\nHotel Le Benz is located at a walking distance from New Delhi Railway Station, 5 Minutes drive from Connaught Place and 5 minutes walking from Ramakrishna Ashram Metro Station. Hotel offers good connectivity from Indira Gandhi International Airport which is at a distance of around 21 km. \nThe hotel is in close proximity to India Gate (5 km), Hazrat Nijamuddin Railway Station (7km), Pragati Maidan (4.5 km), Akshardham Mandir (6.5 km).\n\nDistance from IGI Airport : 21 Km approx\nDistance from New Delhi Railway Station : 0.3 Km\nDistance from Hazrat Nizamudin Station : 3 km Approx.\n\nHotel Feature\nHotel Le Benz provides a range of services for the comfortable stay of guests. The hotel pampers its guests with other eminent amenities like high-speed wireless connectivity of internet, travel desk, 24 hour room service, 24 power backup, doctor on call, Laundry & Dry Cleaning Service, local tour and travel desk, Free Dialing telephone and Airport pick up and drop facility (Surcharge).\n\nRooms \nThe hotel possesses 22 enormous rooms which are available in three variants, Deluxe Room, Executive Room and Family Room. All the rooms offer amenities like private bathrooms with rainfall shower heads and well designed toiletries with regular supply of hot and cold running water. Free Wi-Fi and LCD television are available in the rooms. Additional amenities include ceiling fan, Mini Bar (chargeable basis), a safe, working desk, direct dial telephone service and full length mirror. Hair Dryer and Tea Coffee Maker available on Request. ",
                "HotelPromotion": "Limited period offer 30% off!!!",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 10615.2,
                    "Tax": 1914.7,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 4558.8,
                    "PublishedPrice": 12551.9,
                    "PublishedPriceRoundedOff": 12552,
                    "OfferedPrice": 12529.9,
                    "OfferedPriceRoundedOff": 12530,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4icFKQoew3nikQszUCYYdf4lRALw99kVjrdjnanT+s+P4TGqFamViq",
                "HotelAddress": "2222, Rajguru Road,\nChuna Mandi,\nPahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.64306",
                "Longitude": "77.210669",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 144,
                "HotelCode": "48412",
                "HotelName": "Treebo Conclave Riviera Greater Kailash",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "\"Treebo Conclave Riviera, situated in Delhis Kailash Colony is a one-minute walk from the Kailash Colony Metro Station and 1 km away from the corporate hub and market of Nehru Place.\nThe hotel offers room service, chargeable laundry options, business event hosting facilities and parking space on-site. Guests get to enjoy free Wi-Fi and complimentary breakfast from Treebo.\nAll the rooms are air-conditioned and equipped with a coffee table, fridge, safety locker and LCD television with cable/ DTH connection. Youll find water heating facility and Treebo toiletries in the private bathroom.\nThe hotel is 18 km from the airport, 7 km from the Hazrat Nizammudin railway station and Sarai Kale Khan ISBT bus stop.\nYoull find a lot of eateries within 2.5 km from this Treebo property. Royal China, Starbucks Caf, Fio Cookhouse & Bar are naming a few among many others in the same area.\nOther places nearby are the Lotus Temple (3.1 km), Humayuns Tomb (6.9 km), Qutub Minar (8 km), the Select City Walk Mall (6.3 km), DLF Place Saket Mall, MGF Metropolitan Mall (7 km) and the shopping streets of M Block Market GK 1 (2.3 km).\" ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11066.92,
                    "Tax": 1712.03,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 12800.95,
                    "PublishedPriceRoundedOff": 12801,
                    "OfferedPrice": 12778.95,
                    "OfferedPriceRoundedOff": 12779,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4Iwyu4ilMW0I9CWbKiUXbTFRkDd42vOW4FKkv2ssIlAtLlurG4wuuR",
                "HotelAddress": "A - 20 Kailash Colony.,Delhi,India, , , 110048",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.55553",
                "Longitude": "77.241338",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 42,
                "HotelCode": "1284751",
                "HotelName": "Deemarks",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location Located in New Delhi, Dee Marks Hotel & Resorts is in the business district and convenient to Central Mall and Worldmark.  This family-fri  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12847.14,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 12847.14,
                    "PublishedPriceRoundedOff": 12847,
                    "OfferedPrice": 12847.14,
                    "OfferedPriceRoundedOff": 12847,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMAyopOFYk9cSypIn99eldjbBba4K0BS7gQmefK0jeOPasHfjzCR0RLoRjwjfCL1lMBMljtk9uzd6lD60Xw4ljPej9dfQ1qbZXm4gZS7pRbsVw51Qv8//UodFvHQBxmpkLfqoM5sJE9wzlgzppkiPq9",
                "HotelAddress": "National Highway-8 New Delhi 110037 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 43,
                "HotelCode": "305758",
                "HotelName": "Evergreen Suites Defence Colony",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Evergreen Suites Defence Colony is a popular choice amongst travelers in New Delhi and NCR, whether exploring or just passing through. The hotel offers a high standard of service and amenities to suit the individual needs of all travelers. Service-minded staff will welcome and guide you at the Evergreen Suites Defence Colony. Guestrooms are fitted with all the amenities you need for a good night\'s sleep. In some of the rooms, guests can find television LCD/plasma screen, fireplace, free welcome drink, locker, mirror. Access to the hotel\'s golf course (within 3 km) will further enhance your satisfying stay. Evergreen Suites Defence Colony is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13070.37,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13070.37,
                    "PublishedPriceRoundedOff": 13070,
                    "OfferedPrice": 13070.37,
                    "OfferedPriceRoundedOff": 13070,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG52lpaJe9M3rgfQ7ULBAEKDLv2ExfkKKaQRl0FByDudpvF8JbEu8E8OLwlW6xd5AlfJytM8rA51mJMBM0ZeUuZznYfMH/S1+439yTEQX3gK3hcJvg0J5aq9c=",
                "HotelAddress": "A-153, Defence Colony, New Delhi and NCR, India, , , 110024, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 145,
                "HotelCode": "308780",
                "HotelName": "Hotel Forest Green",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Treehouse Forest Green is a boutique hotel ideal for business and leisure travellers. The guests find the stay satisfying and value for money. They enjoy the hospitality of the staff and the great food.\n\n<b>Location</b>\nTreehouse Forest Green is a well-designed boutique hotel located near the Embassy Area. Some of the major attractions close to the hotel include Humayun\'s Tomb (approx 6 km), Qutab Minar (approx 8 km), Sheesh mahal (approx 3 kms),India Gate (approx 8 km), Jantar Mantar(approx 10 km) and National Museum. Other major attractions at a distance from the hotel include Rashtrapati Bhawan, Old Fort, Lotus Temple, Red Fort, and Jama Masjid.\n \nDistance from Indira Gandhi Airport: Approx. 14km\nDistance from New Delhi Railway Station: Approx. 12km\nDistance from Nizzamuddin Railway Station: Approx. 7km\n\n\n<b>Hotel Features</b>\nTreehouse Forest Green features a well designed lounge and a business centre, AC, TV, tea/coffee maker, temperature control, table lamp, satellite TV, bedside lamp and in-room menu. The in house restaurant serves widest range of Indian and International delicacies. They also provide buffet breakfast with options ranging from American, Continental & Indian cuisine.\n \n<b>Rooms</b>\nTreehouse Forest Green offers rooms which are spread across the floors. Each room is well-designed with attached bathrooms. The room are equipped with tea/coffee maker, fruit platter, satellite TV, telephone and luggage space. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11178,
                    "Tax": 2016,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13216,
                    "PublishedPriceRoundedOff": 13216,
                    "OfferedPrice": 13194,
                    "OfferedPriceRoundedOff": 13194,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4v/W9/ACK5U9mV/NSpZnTsMHoindXellehgvbdXWwpiRVWUHhyMnlw",
                "HotelAddress": "Number 24, Siri Fort Road, Opp. Sri Fort Sports Complex,Delhi,India, , , 110049",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.5550072",
                "Longitude": "77.2247893",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 44,
                "HotelCode": "LER|DEL",
                "HotelName": "LE ROI",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "In the heart of the city, close to New Delhi train station and a few minutes walk from Connaught Place and 22 kms from Delhi Airport. the nearest metro station is 10 minutes from the hotel. The rooms are well appointed with all modern facilities. the rooms have a safety deposit box, tea and coffee making facilities and hair dryer. the in house restaurant serves continental, Chinese and Indian cuisine. This is a newly constructed hotel with a modern facade. The lobby is medium sized with a coffee shop attached, there is also a travel desk. ideal for business travellers and guests who want to stay in the city centre.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13199.01,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13199.01,
                    "PublishedPriceRoundedOff": 13199,
                    "OfferedPrice": 13199.01,
                    "OfferedPriceRoundedOff": 13199,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+msNgNNZMn2sM2zcskiU4QbYOvQ+xz8TAxOZmktzJyeAg",
                "HotelAddress": "2206 RAJ GURU MARG DESH BANDHU GUPTA ROAD CHUNNA MANDI PAHARGANJ NEW DELHI 110055 INDIA, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 45,
                "HotelCode": "JYO|DEL",
                "HotelName": "JYOTI MAHAL",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "it is located  22 kms away from the Indra Gandhi International airport, 1 km away from the new delhi railway station, RK ashram marg is the nearest Metro station which is 10 mins away and I km away from centre of the city cannaught place. Rooms are very spacious featured with a king size bed with quality linens.rooms are designed with taste and furnish with beautiful rich wood furnished. It has a restuarant which serves Indian cuisine,restuarant is located on the roof top and all the cuisines from the world chinese,Italian, continental and israel dishes. It is a modern brick building. It has a medium size lobby with limited sitting arrangement.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13199.01,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13199.01,
                    "PublishedPriceRoundedOff": 13199,
                    "OfferedPrice": 13199.01,
                    "OfferedPriceRoundedOff": 13199,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mkGDRzQ+4KNujQ5USuKpN7rwKkEn+cLrCDlI2SWCZD+A",
                "HotelAddress": "2488/90 NALWA STREET BEHIND IMPERIAL CINEMA New Delhi CHUNA MANDI PAHARGAN India India, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 146,
                "HotelCode": "709401",
                "HotelName": "Hotel Africa Avenue GK 1",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Africa Avenue GK 1, New Delhi is designed with modernity and features stately dcor. Plush looking, this hotel is comforting in its ambience and calm in aura. The personalized attention from the dedicated staff acts as the cherry on top delighting the travelers with value for money accommodation. \n\n<b>Location</b>\nThe hotel is located in Greater Kailash, New Delhi. Some tourist places well associated with the hotel are Red Fort 16 km (approx.), Jama Masjid 15 km (approx.), Chandni Chowk 19 km (approx.), Swaminarayan Akshardham 15 km (approx.), Humayun\'s Tomb 9 km (approx.), Lodi Gardens 10 km (approx.), Qutab Minar 9 km (approx.), India Gate 11 km (approx.) and Bahai (Lotus) Temple 3 km (approx.).\n\nIndira Gandhi International Airport- 16 km (approx.)\nOkhla Railway Station- 4 km (approx.)\nPamposh Enclave Bus stop- 1 km (approx.)\nLajpat Nagar Metro Station- 3 km (approx.)\n\n<b>Facilities</b>\nSome notable services delivered by the hotel staff are reception, laundry, valet parking, doctor on call, concierge, currency exchange, banqueting, gymnasium, dining and conferencing. The friendly concierge and reception desk attends the lodgers promptly with their 24 hour service. A well-designed restaurant featuring urbane settings treats the varied palates of the tourists with multi cuisine delicacies. The well-appointed gymnasium in the house makes sure the health conscious residents do not have to miss their workout sessions while they travel. Those seeking an ideal venue to organize corporate events or grooving parties do not have to look further as the hotel has a graciously maintained banquet cum conference hall. \n\n<b>Rooms</b>\nSuperior Room and Executive Room are the two variants in which accommodation is provided to the vacationers. Designed with parquet flooring and polished wooden work, the rooms seem refined and classy. They have snug and bouncy beddings on which the crispest and softest of linens are spread to provide comfort to the lodgers. Featuring cushioned wall at bedpost augmented with wooden work finishing, the urbane theme of the rooms look more appealing. Amenities like tea and coffee maker, telephone, round clock room service, wakeup call facility, AC and iron with ironing board back the stay. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11188,
                    "Tax": 2017.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13227.8,
                    "PublishedPriceRoundedOff": 13228,
                    "OfferedPrice": 13205.8,
                    "OfferedPriceRoundedOff": 13206,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5Acl4tHp2MI+cnWFzBVzCpQ3JV3B1cVHrm3u0b9mBR7IaYjzsOtNG+v3E6i2zBibI=",
                "HotelAddress": "B - 104, Greater Kailash - 1,Delhi,India, , , 110048",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.551832",
                "Longitude": "77.233347",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 46,
                "HotelCode": "1624005",
                "HotelName": "The Sentinel Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in South Delhi, The Sentinel Hotel is a perfect starting point from which to explore New Delhi and NCR. The property features a wide range of facilities to make your stay a pleasant experience. 24-hour room service, 24-hour security, convenience store, daily housekeeping, fireplace are there for guest\'s enjoyment. Each guestroom is elegantly furnished and equipped with handy amenities. The hotel offers various recreational opportunities. Discover all New Delhi and NCR has to offer by making The Sentinel Hotel your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13399,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13399,
                    "PublishedPriceRoundedOff": 13399,
                    "OfferedPrice": 13399,
                    "OfferedPriceRoundedOff": 13399,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YnhcM1dfzBaGk0OzmFdOw1oFhETXfcvdFygaTGER8SZ0pVLZuCv4CtnbcMGSuolfVZWLrfwUPCUlVK3CS9MQ2FDpIAjo4oM3h41k+6baPqi3IGwdf9iIU0M=",
                "HotelAddress": "Plot 47, Pocket - 1, Jasola, New Delhi, Delhi National Territory, New Delhi and NCR, India, , , 110025, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 147,
                "HotelCode": "353241",
                "HotelName": "Hotel Arpit Palace",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Arpit Palace is a business-cum-economy hotel ideal for all travellers. Guests can choose from wide variety of Rooms to suit their needs. All the Rooms are equipped with modern amenities, providing the patrons with maximum comfort at minimum price.\n\n<b>Location</b>\nHotel Arpit Palace is located near Jassa Ram Hospital, a few minutes away from the Karol Bagh main market, Delhi. It is very close to Karol Bagh Metro station as well. The hotel is in close proximity to central business district, markets and historical monuments. The places of interest close to the hotel include Red Fort, Lotus Temple, Jantar Mantar (approx 4km) and Jama Masjid. Guests can also visit Rashtrapati Bhavan (approx 5km), Qutub Minar, Dilli Haat and Old Fort.\n\nDistance from the Indira Gandhi International Airport: 19 kms / 50 minutes\nDistance from the Karol Bagh Metro station: 500 m / 5 minutes\n\n<b>Features</b>\nHotel Arpit Palace Features beautiful Rooms and lobby. It offers a range of amenities including a 24-hour front desk, travel desk, parking and doctor-on-call. The in-house Crossroads Bar & Restaurant offers multi-cuisine delicacies to the patrons.\n\n<b>Rooms</b>\nThere are 46 spacious as well as cosy Rooms, spread across 4 floors. All Rooms are well-designed and have facilities like AC, TV, Wi-Fi, tea/coffee maker, mini-bar and direct dial telephone. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11374,
                    "Tax": 2051.28,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 13447.28,
                    "PublishedPriceRoundedOff": 13447,
                    "OfferedPrice": 13425.28,
                    "OfferedPriceRoundedOff": 13425,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6++ogZDGFg9KsaE0Qq9NbU0ZuaCaVPI0d4/wlvxfIHRAHSh2f7Swnv",
                "HotelAddress": "17A/1. W.E.A. Gurudwara Road, Opp. Jessa Ram Hospital Near, Metro Station Pillar No.-99 , Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645054",
                "Longitude": "77.189716",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 148,
                "HotelCode": "41935",
                "HotelName": "Hotel Broadway",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "Early Bird Discount - Save 20%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 11498,
                    "Tax": 2073.6,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 3200,
                    "PublishedPrice": 13593.6,
                    "PublishedPriceRoundedOff": 13594,
                    "OfferedPrice": 13571.6,
                    "OfferedPriceRoundedOff": 13572,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5DUqOjyzaasbqGZ/5tGkTZbNBofY7RJ/xgnTLyZQN6WcC1bdclAW6S",
                "HotelAddress": "4/15A, Asaf Ali Road, (Near Delhi Gate),Delhi,India, , , 110002",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.641026",
                "Longitude": "77.237915",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 47,
                "HotelCode": "1083275",
                "HotelName": "bloomrooms",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at bloomrooms @ Link Road, you&apos;ll be centrally located in New Delhi, convenient to Jawaharlal Nehru Stadium and Humayun&apos;  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14024.35,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14024.35,
                    "PublishedPriceRoundedOff": 14024,
                    "OfferedPrice": 14024.35,
                    "OfferedPriceRoundedOff": 14024,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1ez/nCipA2hefQ3XIEVBGQD73W519WEln8oAGihw4kYYRDKXZvI7G+bI7jfJAta1YAl+EoZpnI+F0eXIPflrJk1y0YbwkyolJw==",
                "HotelAddress": "7, Link Road Jangupura Extension , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 48,
                "HotelCode": "293017",
                "HotelName": "Hotel Central Blue Stone",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Hotel Central Blue Stone is perfectly located for both business and leisure guests in New Delhi and NCR. The hotel has everything you need for a comfortable stay. 24-hour room service, 24-hour front desk, facilities for disabled guests, express check-in/check-out, luggage storage are there for guest\'s enjoyment. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, separate living room, internet access – wireless, internet access – wireless (complimentary), air conditioning. Take a break from a long day and make use of garden. Hotel Central Blue Stone is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14119.48,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14119.48,
                    "PublishedPriceRoundedOff": 14119,
                    "OfferedPrice": 14119.48,
                    "OfferedPriceRoundedOff": 14119,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02LSe6dCeoqPWprgUtQwNBpaSBNzlOfLi5+weY6KJ2QoN+3thQja0uho0j+s9npyx5JFnxKYf0dsBo4vavoDadiXe738WA7RR2/fU6PhslRmezoSnDWxMpDA=",
                "HotelAddress": "Plot no. 358-359,\nSector 29, City Centre, New Delhi and NCR, India, , , 122001, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 49,
                "HotelCode": "43772",
                "HotelName": "Ashok Country Resort",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The 3-star Ashok Country Resort offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. The hotel offers a wide range of amenities and perks to ensure you have a great time. Facilities like 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, grocery deliveries are readily available for you to enjoy. Each guestroom is elegantly furnished and equipped with handy amenities. Enjoy the hotel\'s recreational facilities, including hot tub, fitness center, sauna, outdoor pool, spa, before retiring to your room for a well-deserved rest. Ashok Country Resort combines warm hospitality with a lovely ambiance to make your stay in New Delhi and NCR unforgettable.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14152.45,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14152.45,
                    "PublishedPriceRoundedOff": 14152,
                    "OfferedPrice": 14152.45,
                    "OfferedPriceRoundedOff": 14152,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YmJd/7O3IH/Wyc/GOxI0dua6Z90J8ICcVdZIpI8gE1SpjMfvYiem5T0hpRHwBh1huHxWHLu6B1IZYh8vpOzqxRsguFg8c2cOQn+6ucfOrj3PuqxyicpaTu8=",
                "HotelAddress": "30, Rajokri Road, Kapashera, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 149,
                "HotelCode": "934622",
                "HotelName": "Hotel Africa Avenue - South Extension",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12138,
                    "Tax": 2188.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14348.8,
                    "PublishedPriceRoundedOff": 14349,
                    "OfferedPrice": 14326.8,
                    "OfferedPriceRoundedOff": 14327,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4H/rLyzIJnWh+7IVqLFIyS21qO8EYhD2ltWBeWhqFa65oz93CW6gdZ",
                "HotelAddress": "No-6, Near South-Ex & Siri Fort Auditorium, Anand Lok,Delhi,India, , , 110049",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.557523",
                "Longitude": "77.217708",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 150,
                "HotelCode": "377181",
                "HotelName": "Omega Residency",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "An ultra-modern establishment, Omega Residency is an excellent choice for a luxurious stay in New Delhi. Perfect for all kinds of travelers, this hotel is located close to the international airport and the railway station.\n\n<b>Location:</b>\n Nestled in the heart of Delhi in Paharganj, Omega Residency is a stone\'s throw away from Chandni Chowk and Connaught Place -two of the most bustling areas in the city. Extremely close to many tourist spots like Connaught place (approx 2 km), Jantar Mantar (approx 3 km), Humayun\'s Tomb (approx 9 km), Garden of Five Senses, Red Fort, Jama Masjid, Chandni Chowk and many others, this hotel lets you bask in the colourful glory of Delhi.\n\nDistance from Indira Gandhi Airport: Approx 16km\nDistance from New Delhi Railway Station: Approx 3km\nDistance from Nizzamuddin Railway Station: Approx 9km\n\n<b>Hotel features:</b>\nOmega Residency features a three storeyed contemporary structure which ensures a cozy and relaxing stay throughout. This hotel provides spa facilities, internet, and medical services. Their in-house restaurant serves a wide range of delicious cuisines from all over the world. Omega Residency is definitely the perfect place to stay for a family holiday.\n\n<b>Rooms:</b> \nFeaturing  well-kept, fully equipped rooms, Omega Residency will being your holiday to an all new level. With tastefully designed rooms and amenities like internet access, LCD televisions, minibars, and tea making facilities, this hotel has something in store for everyone. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12174,
                    "Tax": 2195.28,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14391.28,
                    "PublishedPriceRoundedOff": 14391,
                    "OfferedPrice": 14369.28,
                    "OfferedPriceRoundedOff": 14369,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5nhJ18uQlh1LO8eB2cyLd9KIotE8lFTbISB2TrRqKGVle1PBlYyduP",
                "HotelAddress": "37 Ram Nagar, Street no. 1, Paharganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645294",
                "Longitude": "77.213048",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 151,
                "HotelCode": "321755",
                "HotelName": "Red Maple Bed and Breakfast",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The Red Maple South Extension lets its guests revel in blissful comforts, elegance and style. The hotel comes with facilities and options that make it an ideal place for businessmen and tourists on a trip to the capital of India.\n\n<b>Location: </b>\nLocated in South Extension in New Delhi, the Red Maple boasts of a unique setting in the heart of the capital city. It provides easy access to many historical sites in New Delhi like India Gate (Approx 5km), Qutub Minar (Approx 8km), Connaught Place (Approx 9km), Siris Fort (Approx 4km), Purana Quila, Safdarjung\'s Tomb, Jantar Mantar and Rajpath. The hotel is also located close to the Birla Mandir.\n\nDistance from Indira Gandhi Airport: Approx 15km\nDistance from New Delhi Railway Station: Approx 12km\nDistance from Nizzamuddin Railway Station: Approx 7km\n\n<b>Hotel Features: </b>\nDesigned and built to meet the specific requirements of businessmen and tourists, the Red Maple offers unrivaled security, ultra luxury and a range of options and facilities.With convenient facilities like doctor-on-call, laundry service and breakfast options, the hotel ensures the guests enjoy a pleasant stay.\n\n<b>Rooms: </b>\nThe Red Maple offers  fully furnished rooms to its guests. Each room is designed with the finest furnishings and comforts that a guest would need. The air-conditioned rooms are well-designed and equipped with  a coffee/tea maker, hi-speed internet, electronic safe etc. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12178,
                    "Tax": 2196,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14396,
                    "PublishedPriceRoundedOff": 14396,
                    "OfferedPrice": 14374,
                    "OfferedPriceRoundedOff": 14374,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6UqZwCmfyY70qRhfY0DvfNDVkZw0QX4mzwiXSZOJL27OBjG5/dh3r2",
                "HotelAddress": "49, Amrit Nagar ,South Extension Part-1,Delhi,India, , , 110003",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.573635",
                "Longitude": "77.219819",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 50,
                "HotelCode": "1083377",
                "HotelName": "Madhuban Managed by Peppermint",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Madhuban Hotel in New Delhi (South Delhi), you&apos;ll be close to Kailash Colony Market and Humayun&apos;s Tomb.  This hotel is within close proximity of Ansal Plaza and Siri Fort Auditorium.Rooms Make yourself at home in one of the 41 air-conditioned rooms featuring refrigerators and LCD televisions. Complimentary wireless Internet access keeps you connected, and digital programming is available for your entertainment. Private bathrooms with showers feature hair dryers and bathrobes. Conveniences include phones, as well as safes and desks.Rec, Spa, Premium Amenities Make use of convenient amenities such as complimentary wireless Internet access, concierge services, and tour/ticket assistance.Dining Satisfy your appetite at the hotel&apos;s restaurant, which serves breakfast, lunch, and dinner. Dining is also available at a coffee shop/café, and 24-hour room service is provided.Business, Other Amenities Featured amenities include a business center, a computer station, and dry cleaning/laundry services. Event facilities at this hotel consist of a conference center and a meeting room. Free self parking is available onsite. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14473.51,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14473.51,
                    "PublishedPriceRoundedOff": 14474,
                    "OfferedPrice": 14473.51,
                    "OfferedPriceRoundedOff": 14474,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968p+KSfi8oYVU8Tj278FBz/1XkfoRZtaubj/jenFXq1xBQ0vWFDN6u2cVvHDhantdDa/rQuNnX2/ivsEb9BpihfpbnVQlQHNMSYH6mfg/XiOLVduVz2AJz/TYfTuwca/dfdl/DfXkMUv2/+SjgdOquU9uhTawxV7l+Q=",
                "HotelAddress": "72, M Block Market M Block Market Greater Kailash 1, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 152,
                "HotelCode": "381257",
                "HotelName": "Jyoti Mahal Guest House",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12478,
                    "Tax": 2250,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14750,
                    "PublishedPriceRoundedOff": 14750,
                    "OfferedPrice": 14728,
                    "OfferedPriceRoundedOff": 14728,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6fBMW/6dVNOfAp5xDAo3b/n5rzgM8zf//bChRHRSsbUKKouPk7ZLDK",
                "HotelAddress": "2488-90 Nalwa Street (Behind Imperial Cinema), Chuna Mandi - Pahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.642076",
                "Longitude": "77.21175",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 153,
                "HotelCode": "306980",
                "HotelName": "Hotel Kingston Park",
                "HotelCategory": "Kingston Hotels",
                "StarRating": 3,
                "HotelDescription": "Hotel Kingston Park, New Delhi, offers a suitable lodging for business and leisure travellers. It features blend of standard and business facilities, inviting restaurants with tempting meals and cosy guestrooms. The hotel also enjoys proximity to the business hub of Karol Bagh and other shopping spots of the city.\n\n<b>Location:</b>\nHotel Kingston Park is located W.E.A. Karol Bagh. Jama Masjid (Approx. 5km) and Connaught Place (Approx. 5km) are some of the nearby places to visit from the hotel. The Jama Masjid is one of the biggest mosques in Old Delhi with flooring covered with white and black marbles resembling a prayer mat in the Mohammedan culture. Also worth visiting destinations in the historic city are the Qutab Minar, India Gate and Red Fort.\n\nIndira Gandhi International Airport: 16 km (approx.)\nNew Delhi Railway Station: 4 km (approx.)\n\n<b>Hotel Features:</b>\nThe hotel features a state-of-the-art business centre that is equipped with audio visual equipment, conference suite, and meeting rooms. The basic amenities provided are room service, internet, front desk, travel desk, air conditioning, non-smoking rooms, parking, doctor-on-call and round the clock security. Also, there is an in-house restaurant known as Aqua that offers tempting meals to the patrons. If patrons want to savour flavoursome meals in the midst of nature with an open air sitting area can enter the terrace restaurant known as Aura.\n\n<b>Rooms:</b>\nThe accommodation at Hotel Kingston Park comprises of Kingston premium rooms and Kingston suites. These air conditioned rooms with a modern appeal are well-furnished. The air- conditioned rooms have amenities such as television, internet access, mini bar, safe, telephone, writing desk, tea/coffee maker, table lamp, temperature control and Wi-Fi access. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12570,
                    "Tax": 2266.56,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14858.56,
                    "PublishedPriceRoundedOff": 14859,
                    "OfferedPrice": 14836.56,
                    "OfferedPriceRoundedOff": 14837,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5YXFzReFrv2dX+u2CgfEIw5/Rxsz3ei1gv51NR0qTcU8Utl4p5xGMUDnaCiX40Omef7chUiQWsttpC9wkvjLKS",
                "HotelAddress": "8/5, W.E.A., Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645292",
                "Longitude": "77.192642",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 51,
                "HotelCode": "248240",
                "HotelName": "The Grand Hotel Bizzotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in Gurgaon, The Grand Hotel Bizzotel is a perfect starting point from which to explore New Delhi and NCR. The property features a wide range of facilities to make your stay a pleasant experience. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour front desk, express check-in/check-out, luggage storage. Guestrooms are designed to provide an optimal level of comfort with welcoming decor and some offering convenient amenities like television LCD/plasma screen, internet access – wireless (complimentary), non smoking rooms, air conditioning, wake-up service. The hotel offers various recreational opportunities. A welcoming atmosphere and excellent service are what you can expect during your stay at The Grand Hotel Bizzotel.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14972.93,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 14972.93,
                    "PublishedPriceRoundedOff": 14973,
                    "OfferedPrice": 14972.93,
                    "OfferedPriceRoundedOff": 14973,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02DKx/ztCIdJ6XVJ3IhaKJdcxlB4aWTJKE8/YbIqJh/u3RZxtU1wJ5j/L+s7hbN/amk5HAbCN6RG8nX5z76tNfg9LQA1WrneRoPSIvu3XLUViIXQ5G6+BhhI=",
                "HotelAddress": "1816/2, Old Delhi-Gurgaon Road, Nr. Shyam Sweet Sec-14, New Delhi and NCR, India, , , , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 52,
                "HotelCode": "1284829",
                "HotelName": "Samrat",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Property Location With a stay at Hotel Samrat in New Delhi (Chanakyapuri), you&apos;ll be convenient to Indira Gandhi Memorial and GK Market.  This 4-star hotel  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15218.32,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15218.32,
                    "PublishedPriceRoundedOff": 15218,
                    "OfferedPrice": 15218.32,
                    "OfferedPriceRoundedOff": 15218,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1eA0nkBbmmqFVfp+vpvJQTRc9tuq9VJilI0+WXF1PK7iRjkZmsPx4QLYF/nthP2lsE0ecmOtz6DNk2lr65HjIHJ4hust98eUb0K27o/Pk0e8kuACPEuIibQ=",
                "HotelAddress": "Kautilya Marg New Delhi 110021 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 154,
                "HotelCode": "1325408",
                "HotelName": "Hotel Hill Palace",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "Book Now And Save 10 % Per Night",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 12938,
                    "Tax": 2332.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 1600,
                    "PublishedPrice": 15292.8,
                    "PublishedPriceRoundedOff": 15293,
                    "OfferedPrice": 15270.8,
                    "OfferedPriceRoundedOff": 15271,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6wmcoKLXVfA3zbh5jYxg7nBlxSlRUhTt6kWK/LzrHZ/XXqVB9UbRi2",
                "HotelAddress": "14 A/32 Wea Channa Market Karol Bagh,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.6457349",
                "Longitude": "77.1864853",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 53,
                "HotelCode": "1033816",
                "HotelName": "Park Plaza Delhi Cbd Shahdara",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Property Location Located in New Delhi (Shahdara), Park Plaza Delhi CBD Shahdara is close to Yamuna Sports Complex and Max Super Speciality Hospital.  This 4.5-  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15325.34,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15325.34,
                    "PublishedPriceRoundedOff": 15325,
                    "OfferedPrice": 15325.34,
                    "OfferedPriceRoundedOff": 15325,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMRJT17sIZ32Xl7MNnictJO9b3PK2Ia7v6rqA6e49aLJIBGqH3ub/+lJRBZIBALD6yuE/uhf+3EHw==",
                "HotelAddress": "Plot 32 Central Business District , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 54,
                "HotelCode": "2599397",
                "HotelName": "Glitz Westend Inn",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15426.41,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15426.41,
                    "PublishedPriceRoundedOff": 15426,
                    "OfferedPrice": 15426.41,
                    "OfferedPriceRoundedOff": 15426,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxOR/CHWWabF7FAekR7Z+4EGh87WAdD5Leh8Nw/xSEfgyh0VjXsL6AhYfkUyVXLl0vvPiHWdGwG5LafM3vW/4k13pL2wMJmoCqa7B+Z8CT1S0WMZJp5ro0Ws=",
                "HotelAddress": "NH - 8 Adjoining Shiv Murti Rangpuri Mahipalpur, Delhi National Territory, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 155,
                "HotelCode": "314146",
                "HotelName": "FabHotel Anutham Saket",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Strategically located at a walking distance from PVR Saket, FabHotel Anutham Saket offers well-appointed studio apartments at reasonable pricing. A perfect blend of premium accommodation and warm hospitality, this 3-star hotel is known to provide a choice of essential amenities required for a relaxing stay.\n\n<b>Location: </b>\nConveniently positioned in the heart of South Delhis shopping district, FabHotel Anutham Saket is one of the preferred budget hotels in Saket. An ideal retreating zone for business and leisure travelers alike, this hotel is located close to the business hubs in Okhla, Nehru Place and Jasola (about 30-minutes drive). Further, proximity to the Max Hospital (1 km) makes it perfect for travelers seeking medical assistance.Guests can also spend their spare time exploring the Kiran Nadar Museum of Art (2 km) and the Garden of Five Senses (2 km). The hotel gives its guests enough options to shop from the nearby malls and complexes including Select City walk (1.5 km), DLF Place Saket (1.5 km), and MGF Metropolitan Mall (2 km).\n\n<b>How to reach: </b>\nTravelers can commute within the city through the Saket Metro Station, which is just 800 m away from the hotel. The hotel is well accessible from the Indira Gandhi International Airport (15 km) and New Delhi Railway Station (17 km).\n\n<b>Rooms and amenities: </b> \nApart from its prime location and magnificent surroundings, the hotel offers a range of facilities including warm and personalized service backed by efficient professional and technical support, making it an ideal stopover for the travelers.Featuring contemporary dcor, the hotel boasts 15 spacious and airy rooms equipped with LCD TV, comfortable beds with spotless linens, large wardrobes, lockers, tea/coffee maker, and hygienic washrooms. Selected rooms come with a balcony and fully functional kitchenette.Added amenities available include parking space, high-speed Wi-Fi, room service, lift, pick-up and drop (chargeable), laundry and power backup. The excellent housekeeping staff ensures cleanliness and hygiene standards. The efficient staff never fails to assist the guests at the time of any query or discomfort.\n\n<b>Wine and dine: </b>\nEvery morning, the hotel serves complimentary breakfast buffet. For in-room dining, all the rooms are provided with an extensive menu to choose from. Food lovers can satisfy their hunger at the nearby famous restaurants like Turrannt - The Spicy Treat (270 m), Pind Balluchi (120 m) and Saravana Bhawan (650 m). Also, guests can enjoy Delhis nightlife at Locale, The Bunk House, Hard Rock Caf, TGIF, and Harrys, all located within a radius of 1 km.\n\n<b>What\'s more to explore: </b>\nTravelers can visit the worlds tallest brick minaret i.e. Qutub Minar (2.5 km), which is also one of the major landmarks of the city. \nChhatarpur Temple (5 km) is one of the famous temples in the city and attracts a lot of devotees every day.\n Explore the traditional and ethnic permanent market of Delhi in INA. The Delhi Hatt (8 km) is well known for its handicrafts, junk jewelry, camel hide footwear, silk and wool fabrics, gems, stones, metal crafts and much more. Travelers can even enjoy a variety of dishes from its several eateries joints. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13137.4,
                    "Tax": 2368.69,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15528.09,
                    "PublishedPriceRoundedOff": 15528,
                    "OfferedPrice": 15506.09,
                    "OfferedPriceRoundedOff": 15506,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB44G/dzNt7CjdBVPuK/qD0/ORLb4CbZduH+pMe2G2J9Sg==",
                "HotelAddress": "E-147, Saket, Near PVR Cine Complex,Delhi,India, , , 110017",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.522465",
                "Longitude": "77.2050743",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 156,
                "HotelCode": "354943",
                "HotelName": "Hotel Godwin Deluxe",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Godwin Deluxe is a 4 storey mid-range boutique hotel offering five star services. This centrally air-conditioned hotel comes with a variety of rooms and price range to suit the budget of discerning travelers. Capsule lift access to all the floors with 100% power back is there for guest\'s convenience. \n\n<b>Location</b>\nHotel Godwin Deluxe is conveniently located close to the New Delhi Railway Station hotel and is easily accessible from Centre Delhi as well as NCR. The hotel is in the centre of the city, within a two-kilometre radius of major central government offices, Parliament House and the Presidential Palace. Some of the major tourist attractions close to the hotel include Connaught Place (approx 1km), Jama Masjid (approx 3km) and Jantar Mantar (approx 3km).\n\nDistance from Indira Gandhi Airport: Approx. 16km\nDistance from New Delhi Railway Station: Approx. 0.5 km\nDistance from Nizzamuddin Railway Station: Approx. 7km\n\n<b>Hotel features</b>\nThe hotel offers a range of amenities to its guests including 24 hours room service, laundry facility, travel desk, doctor-on-call, direct dial, business centre for corporate meetings, telephone, parking, internet and Wi-Fi access. On-site travel desk specializes in sightseeing tours of Old and New Delhi and trips to Agra, Rajasthan and North India. Complimentary copy of the city map can be availed on request. Reception, Bell Desk and Security Desk are manned 24 hours. Public areas and corridors are round the clock under cc t.v camera surveillance. Emergency exits, fire equipment and safety signs are in display on all the floors and corridors. In-house restaurant serves an array of lip-smacking cuisine and coffee shop is open to serve nice cup of tea/coffee to its guests. \n\n<b>Rooms</b>\nOffering 26 tastefully appointed rooms in Standard, Executive & Studio categories, every room has a temperature control panel and a bed side set of touch-screen switches to manage the room lights and curtains .Luggage rack, personal wardrobe, writing desk are included. 32-inches LCD televisions come with premium channels. Guests can make use of the in-room refrigerators and coffee/tea makers with snacks basket. Bathrooms include 24 hrs hot and cold running water with separate shower enclosure. Bathroom amenities include Shower mats, bathroom mats, slippers, makeup/shaving mirrors, hair-dryer etc. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13224,
                    "Tax": 2384.28,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15630.28,
                    "PublishedPriceRoundedOff": 15630,
                    "OfferedPrice": 15608.28,
                    "OfferedPriceRoundedOff": 15608,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6sWAmS5t5gSU7Jlt1EPZfehiix+hFO8rozJttx5AAojqcBZdZGCHfk",
                "HotelAddress": "8501/15, Arakashan Road, Ram Nagar, Pahar Ganj,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645835",
                "Longitude": "77.215089",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 55,
                "HotelCode": "1374751",
                "HotelName": "Ameya Suites",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Ameya Suites is conveniently located in the popular South Delhi area. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. 24-hour room service, 24-hour security, daily housekeeping, free Wi-Fi in all rooms, convenience store are there for guest\'s enjoyment. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, bathroom phone, clothes rack, linens, locker. To enhance guests\' stay, the hotel offers recreational facilities such as children\'s playground, garden. Discover all New Delhi and NCR has to offer by making Ameya Suites your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15850.7,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15850.7,
                    "PublishedPriceRoundedOff": 15851,
                    "OfferedPrice": 15850.7,
                    "OfferedPriceRoundedOff": 15851,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG52wjEHjDbe45NlFNmgtmYkxKGa97Na4g8ZiZNwOw86hQRxaaB72/BeeusBctpR02fdhT/YUHBi1zklyv2FP++sUAYSmR33wsMkOYesje+PilNiebckGFrlw=",
                "HotelAddress": "Plot No.-6, FC - 33, Jasola, New Delhi, Delhi National Territory, New Delhi and NCR, India, , , 110025, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 157,
                "HotelCode": "328759",
                "HotelName": "Sage Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Sage, New Delhi, is conveniently located in proximity to business and shopping destinations in South Delhi. The hotel is sophisticatedly done which exudes a combination of traditional and contemporary living. The hospitable staff and luxurious rooms treat guests with awe inspiring comfort.\n\n<b>Location:</b>\nHotel Sage is located at Navjeevan Vihar, near Aurbindo College, Saket Golf Course. The places of tourist attraction are the Lotus temple, New Delhi (Approx. 8km) and the St Mary\'s Orthodox Church (Approx. 3km). The Lotus temple or the Bahai Temple is situated near Kalkaji in New Delhi and is the only Bahai Temple in Asia. This Lotus Temple that is situated amidst 9 pools and huge lawns and measuring 35m in height was designed by Fariborz Sahba. The structure has a half floating lotus with white petals constructed from marble. This pristine beauty has a big prayer hall that is used for meditation. The other interesting places are the India Gate, Lodi Garden and Humayun\'s Tomb.\n\nDistance from Indira Gandhi International Airport: 13 km (approx.)\nDistance from New Delhi Railway Station: 14 km (approx.) \n\n<b>Hotel Features:</b> \nHotel Sage caters to leisure as well as business guests. The hotel offers basic features like 24-hour room service, 24-hour front desk, parking, travel desk, doctor-on-call and 24-hour security. Those who do not want to miss the workout regime may hit the gym or a health club or utilize the game room. The business guests can comfortably conduct meetings with conference facilities like audio visual equipment, LCD/Projector and other conference equipment at the business centre. The feather on the cap is additional services like wedding services, banquet facilities, catering services, concierge and guest Laundromat. One may unwind at the coffee shop over a cup of hot tea or coffee. The restaurant serves delicacies from Indian, Continental, Chinese, Thai and many more cuisines.\n\n<b>Rooms:</b>\nHotel Sage offers it guests the option to choose from superior room and executive room. The spacious rooms are intricately done with wood work and interiors that leave a soothing effect for its guests. The rooms are well equipped with amenities like air conditioning with temperature control feature, flat screen colour television, mini bar, internet access, refrigerator, safe and telephone. It offers other features like table lamp, writing desk and tea / coffee maker. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 13478,
                    "Tax": 2430,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 15930,
                    "PublishedPriceRoundedOff": 15930,
                    "OfferedPrice": 15908,
                    "OfferedPriceRoundedOff": 15908,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4L5YkfwgRsD14OS9fsTIUcKKQeWlNs+D9X2YEcQlrSbWLxQGz5IGl08rPmlGUgHME=",
                "HotelAddress": "4, Navjeevan Vihar, Near Aurbindo College, Near Saket Golf Course,Delhi,India, , , 110017",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.5323606",
                "Longitude": "77.2020704",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 56,
                "HotelCode": "304050",
                "HotelName": "Radisson Blu Hotel New Delhi Dwarka",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "The 5-star Radisson Blu Hotel New Delhi Dwarka offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, fireplace are there for guest\'s enjoyment. Some of the well-appointed guestrooms feature television LCD/plasma screen, bathroom phone, clothes rack, dressing room, free welcome drink. The hotel offers various recreational opportunities. For reliable service and professional staff, Radisson Blu Hotel New Delhi Dwarka caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 16409.58,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 16409.58,
                    "PublishedPriceRoundedOff": 16410,
                    "OfferedPrice": 16409.58,
                    "OfferedPriceRoundedOff": 16410,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YrmBGfwatQsWmiwiOtCUNEqG1t0OvREWqubzJsLpyUaS6UsLJ9QYbzMtSM8JyhdWBsslmnFEobaGwM/gbdCkAn/UWbiiDm+huimJ3fjxTWrXZACGunl3q7Y=",
                "HotelAddress": "Plot No.4, Sector 13, Near IGI Airport, New Delhi and NCR, India, , , 110075, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 57,
                "HotelCode": "1284799",
                "HotelName": "Shervani",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Shervani New Delhi, you&apos;ll be centrally located in New Delhi, convenient to Purana Qila and Humayun&apos;s Tomb.  This hot  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 16657.13,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 16657.13,
                    "PublishedPriceRoundedOff": 16657,
                    "OfferedPrice": 16657.13,
                    "OfferedPriceRoundedOff": 16657,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyPszhKk7zXewbNRoxrCuks5FkSzxsE3eT81e8DmEtu9IhfeNgSY/aPS4ivEYCMmiia7IZVA2CmbRtxffQM9ZxOQ7nTa7u1PX8YgSbHXPZD5Tk0TVVLdQPTR",
                "HotelAddress": "11, Sunder Nagar New Delhi 110003 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 158,
                "HotelCode": "389471",
                "HotelName": "bloomrooms @ New Delhi Railway Station",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14478,
                    "Tax": 2610,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17110,
                    "PublishedPriceRoundedOff": 17110,
                    "OfferedPrice": 17088,
                    "OfferedPriceRoundedOff": 17088,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB50QAIWkHMMVg0Ycc6o8LQ8hvjIX0nZpqwKcEgLftK2rCdtOM3fNMvEcWkZNrQ8DBZjdbXvTQJJ/bVRNyluKEoJovLcAaSz+fg=",
                "HotelAddress": "8591, Arakashan Road, Opp. New Delhi Railway Station,Delhi,India, , , 110055",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.645534",
                "Longitude": "77.217787",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 159,
                "HotelCode": "354699",
                "HotelName": "Private Affair- Boutique Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Private Affair offers the perfect blend of sophisticated and contemporary styles of decor. A haven for rest and relaxation, this boutique hotel makes for the ideal choice of stay for tourists and leisure travellers.\n\n<b>Location: </b>\nThe Hotel Private Affair is located in the heart of New Delhi in Greater Kailash. The hotel is situated near Asia\'s biggest IT market, Nehru Place. The hotel is an ideal place of stay for leisure travellers as it is in close proximity to Delhi\'s historical places like the Siri Fort (Approx. 2km), Connaught place (Approx. 10km), Kalindi Bird Sanctuary (Approx. 10km).\n\nDistance from Indira Gandhi Airport: Approx. 16km\nDistance from New Delhi Railway Station: Approx. 12km\nDistance from Nizzamuddin Railway Station: Approx. 7km\n\n<b>Hotel Features: </b>\nThe Hotel Private Affair offers the best and finest comforts to the guests. The hotel has a conference hall that can accommodate up to 50 people, and a business centre that is equipped with state-of-the-art paraphernalia, making the hotel one of the best venues for business meetings. The hotel offers 24 hour room service and a 24 hour front desk that makes sure guests are not left to search for their own way around the hotel. It also houses a multi-cuisine restaurant\n\n<b>Rooms: </b>\nThe Hotel Private Affair offers  well-appointed rooms spread over  floors to its guests. The air-conditioned rooms come with all the amenities that the guests would require. Each room has a refrigerator, newspaper, telephone, multi-channel television and Wi-Fi connectivity. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14703,
                    "Tax": 2650.5,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17375.5,
                    "PublishedPriceRoundedOff": 17376,
                    "OfferedPrice": 17353.5,
                    "OfferedPriceRoundedOff": 17354,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6k8jKoWWuYfsaqwPzciQZIeDlP2mfbLITH6cKRub3Awmo5Ptl9x7MZ",
                "HotelAddress": "C -2, Greater Kailash -1,Delhi,India, , , 110048",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.548745",
                "Longitude": "77.236567",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 58,
                "HotelCode": "1284796",
                "HotelName": "Shanti Palace",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Hotel Shanti Palace in New Delhi (Mahipalpur), you&apos;ll be minutes from Central Mall and close to Worldmark.  This hotel is   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 17627.33,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17627.33,
                    "PublishedPriceRoundedOff": 17627,
                    "OfferedPrice": 17627.33,
                    "OfferedPriceRoundedOff": 17627,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMAyopOFYk9cdnxZoO+9czsldHuKzbo+JD8H2NBvspZ0BCfMrGICNjgrqrcshkoeAdbTsOLnPmmSA==",
                "HotelAddress": "A-67, Mahipalpur Mehrauli Road New Delhi 110037 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 59,
                "HotelCode": "557850",
                "HotelName": "Taj Princess The Boutique Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Taj Princess The Boutique Hotel is a popular choice amongst travelers in New Delhi and NCR, whether exploring or just passing through. The hotel offers guests a range of services and amenities designed to provide comfort and convenience. Free Wi-Fi in all rooms, 24-hour security, daily housekeeping, convenience store, fireplace are on the list of things guests can enjoy. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, bathroom phone, clothes rack, free welcome drink, linens. The hotel offers various recreational opportunities. For reliable service and professional staff, Taj Princess The Boutique Hotel caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 17629.49,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17629.49,
                    "PublishedPriceRoundedOff": 17629,
                    "OfferedPrice": 17629.49,
                    "OfferedPriceRoundedOff": 17629,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YkWxdazFmQToE7FmtH+1Z1WPBJIWN1eAxLtbmuGrGSiJaSM13YR7LHf3sA6oVHC9JmHM3cA18C/sD8Sf8BOEfhT59z7a56qaZ1r6eXQJPCNvzSNm5nG0Kx0=",
                "HotelAddress": "15 A / 25, Ajmal Khan Road, Delhi National Territory, New Delhi and NCR, India, , , 110005, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 160,
                "HotelCode": "1509646",
                "HotelName": "The Muse Sarovar Portico Kapashera",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "The Muse Sarovar Portico New Delhi, Kapashera is located on Bijwasan-Najafgarh road with easy access to domestic and international Airport. Designed to cater to the discerning business & leisure travelers, it is just a 7-Km drive from Indira Gandhi International airport. This contemporary hotel is also 7-Km from Dwarka Sec. 21 metro station and 12-Km from Gurugram Sec. 3 railway station. \nAll rooms have hardwood floors. All comes with features like high speed Wi-Fi (free), flat-screens, in room safe, writing desks, mini fridge & tea/coffee kettle. Room service is offered 24X7.\n\nBreakfast is complimentary. Theres a relaxed multi cuisine restaurant, a non alcoholic bar. Other amenities include an outdoor pool, a fitness centre and meeting/event space. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 14978,
                    "Tax": 2700,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17700,
                    "PublishedPriceRoundedOff": 17700,
                    "OfferedPrice": 17678,
                    "OfferedPriceRoundedOff": 17678,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4mmNZ7i5tHgGS5JGgpjhis5xt96fQYJSOpya5Jppkze+7T+9VS6a9U",
                "HotelAddress": "88-89, Bijwasan Kapashera,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.531347",
                "Longitude": "77.085264",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 60,
                "HotelCode": "1621123",
                "HotelName": "Radisson Blu Faridabad",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Stop at Radisson Blu Hotel Faridabad By Eros Group to discover the wonders of New Delhi and NCR. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. All the necessary facilities, including Wi-Fi in public areas, are at hand. Each guestroom is elegantly furnished and equipped with handy amenities. The hotel offers various recreational opportunities. For reliable service and professional staff, Radisson Blu Hotel Faridabad By Eros Group caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 17715.43,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17715.43,
                    "PublishedPriceRoundedOff": 17715,
                    "OfferedPrice": 17715.43,
                    "OfferedPriceRoundedOff": 17715,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG52zREQLe4gAebSl09nfqcrOLnEIr/bsaBTcf26TSKTPE//uaqe9OXOzdmpn1QFSERZDbQFyvmG9c4scVU5Wl5H4UpQFiyPGUy4K0pvVDjncBRRPnLZN9w18=",
                "HotelAddress": "Sector 20B, Mathura Road, Delhi National Territory, New Delhi and NCR, India, , , 121001, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 61,
                "HotelCode": "1030256",
                "HotelName": "ATRIO - A Boutique Hotel",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "ATRIO - A Boutique Hotel is conveniently located in the popular Indira Gandhi Int\'l Airport area. The hotel has everything you need for a comfortable stay. Facilities like 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service are readily available for you to enjoy. Television LCD/plasma screen, complimentary instant coffee, mirror, towels, closet can be found in selected guestrooms. The hotel offers various recreational opportunities. Friendly staff, great facilities and close proximity to all that New Delhi and NCR has to offer are three great reasons you should stay at ATRIO - A Boutique Hotel.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 17722.45,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17722.45,
                    "PublishedPriceRoundedOff": 17722,
                    "OfferedPrice": 17722.45,
                    "OfferedPriceRoundedOff": 17722,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG52vvEt+QyrHRxunG3SpFPwZN2BoAlZMHWw/ojsgy14kXVPRwYJgxTvh01O6Fon9WwisGLdxiav9l7UOms4n5vZO83zextwVsrpnVrsQ/s4YTW8rLBMxpgKs=",
                "HotelAddress": "Rajkori - Kapashera Link Road, Near NH-8, Delhi National Territory, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 161,
                "HotelCode": "716744",
                "HotelName": "Piccadily Hotel",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "<b>Location:</b> \n\nLocated in New Delhi, Piccadily Hotel is near the airport and close to Janakpuri District Centre. \n\n<b>Hotel Features:</b>\n\n Piccadily features a coffee shop/cafe, a poolside bar, and a bar/lounge. Recreational amenities include an outdoor pool and a health club. This 5-star property has a 24-hour business center and offers small meeting rooms, secretarial services, and technology support staff. Wireless and wired high-speed Internet access is available in public areas (surcharges apply). This New Delhi property has 990 square meters of event space consisting of banquet facilities, conference/meeting rooms, and a ballroom. For a surcharge, the property offers a roundtrip airport shuttle (available on request). Business services, wedding services, and tour/ticket assistance are available. Guest parking is complimentary. Additional property amenities include a concierge desk, laundry facilities, and currency exchange. The property has designated areas for smoking.\n\n<b>Guestrooms:</b>\n\n228 air-conditioned guestrooms at Piccadily Hotel New Delhi feature minibars and laptop-compatible safes. Accommodations offer city or pool views. Beds come with Egyptian cotton sheets and premium bedding. Furnishings include desks and ergonomic chairs. Bathrooms feature shower/tub combinations with handheld showerheads. They also offer makeup/shaving mirrors, bathrobes, and slippers. Wired high-speed and wireless Internet access is available for a surcharge. In addition to complimentary newspapers, guestrooms offer multi-line phones with voice mail. 32-inch LCD televisions have cable channels and free movie channels. Also included are washers/dryers and coffee/tea makers. A nightly turndown service is offered and housekeeping is available daily. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15178,
                    "Tax": 2736,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 17936,
                    "PublishedPriceRoundedOff": 17936,
                    "OfferedPrice": 17914,
                    "OfferedPriceRoundedOff": 17914,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7XrezXb6k2YVh4E8Bo62YANgi06qI7dSTIul0aJBQBZQ==",
                "HotelAddress": "District Centre Complex, Janakpuri,Delhi,India, , , 110058",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.628902",
                "Longitude": "77.078774",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 62,
                "HotelCode": "272431",
                "HotelName": "Country Inn & Suites By Carlson Gurgaon Sector 12",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Set in a prime location of New Delhi and NCR, Country Inn & Suites By Carlson Gurgaon Sector 12 puts everything the city has to offer just outside your doorstep. The hotel offers a high standard of service and amenities to suit the individual needs of all travelers. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, wheelchair accessible. Each guestroom is elegantly furnished and equipped with handy amenities. The hotel\'s peaceful atmosphere extends to its recreational facilities which include hot tub, fitness center, sauna, outdoor pool, indoor pool. Convenience and comfort makes Country Inn & Suites By Carlson Gurgaon Sector 12 the perfect choice for your stay in New Delhi and NCR.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 18247.82,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18247.82,
                    "PublishedPriceRoundedOff": 18248,
                    "OfferedPrice": 18247.82,
                    "OfferedPriceRoundedOff": 18248,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YitSkE/eZH54DuJc2q4TxiR4RpGPQRapiGaw53xMvkV9pmHHtgtPNue+zwQzi7XkXeTl75c6j8FoITadN8uRYBAOkOf/mqX39+fbPur8AUNCUAi1OKYw5oA=",
                "HotelAddress": "Plot No. 301-302, Sector-12, Gurgaon, Haryana, New Delhi and NCR, India, , , 122002, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 63,
                "HotelCode": "186805",
                "HotelName": "Shervani Nehru Place",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Stop at Shervani Nehru Place to discover the wonders of New Delhi and NCR. The hotel offers a wide range of amenities and perks to ensure you have a great time. Take advantage of the hotel\'s 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service. Guestrooms are designed to provide an optimal level of comfort with welcoming decor and some offering convenient amenities like television LCD/plasma screen, separate living room, internet access – wireless, internet access – wireless (complimentary), non smoking rooms. The hotel offers various recreational opportunities. A welcoming atmosphere and excellent service are what you can expect during your stay at Shervani Nehru Place.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 18257.55,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18257.55,
                    "PublishedPriceRoundedOff": 18258,
                    "OfferedPrice": 18257.55,
                    "OfferedPriceRoundedOff": 18258,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG55KXb755Fl645zQOtaTmxjtpq4Qpl+Ix669H+OHxtK6OLTNJEs2q/OfNQgaXS5aSIsgWOqdJffyM8fk6wOHM6ExNqQQCeNeeBrhQKv7Nc8kmV9EoIwuQ7WU=",
                "HotelAddress": "B-20, Chirag enclave, New Delhi and NCR, India, , , , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 64,
                "HotelCode": "400258",
                "HotelName": "The Umrao",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Located in Indira Gandhi Int\'l Airport, The Umrao Hotel & Resort is a perfect starting point from which to explore New Delhi and NCR. The hotel has everything you need for a comfortable stay. 24-hour room service, free Wi-Fi in all rooms, 24-hour front desk, facilities for disabled guests, express check-in/check-out are there for guest\'s enjoyment. Some of the well-appointed guestrooms feature television LCD/plasma screen, internet access – wireless, non smoking rooms, air conditioning, wake-up service. Entertain the hotel\'s recreational facilities, including hot tub, sauna, outdoor pool, spa, pool (kids). The Umrao Hotel & Resort combines warm hospitality with a lovely ambiance to make your stay in New Delhi and NCR unforgettable.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 18433.21,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18433.21,
                    "PublishedPriceRoundedOff": 18433,
                    "OfferedPrice": 18433.21,
                    "OfferedPriceRoundedOff": 18433,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUGWzU46ple98ShDREQme8Xagc6W2d0NKaLa5XX02PzVbMSyXc5lyYJp4sw1EYVyBP70z8cxJDPiVk7MrqWEPC78KR6SRqrQDZDZ0rISJujr7JGTgtv36GBQ=",
                "HotelAddress": "N.H – 8, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 65,
                "HotelCode": "MUG|DEL",
                "HotelName": "Mughal Heritage",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Hotel is located 10 kms from the city centre and metro station is 25 mins away from it,Airport is 30 kms away. Deluxe room has a bouble size beds with all modern amenities as mini bar,wi-fi internet and 32 inch LCD. Coffee shop has the capacity to accommodate 50 guests and offers the dinning of multi cuisine. La Carte menu is available for lunch and dinner. It is modern mughal architecture building. It has a small lobby with limited sitting arrangement. A comfortable base with a selection of eateries and good facilities which include steam sauna, gym facility.It recreates the grand characteristics of mughal architecture both in the exterior finishes as well as the interior decor. Whr02f 06/11  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 18490.51,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18490.51,
                    "PublishedPriceRoundedOff": 18491,
                    "OfferedPrice": 18490.51,
                    "OfferedPriceRoundedOff": 18491,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mrq6/PEe8kxGqTii/WMyoPvqTj9P7HtVwHf3axsH5Brh",
                "HotelAddress": "A-15 NARAINA VIHAR New Delhi 110028 India India, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 66,
                "HotelCode": "1083426",
                "HotelName": "Country Inn & Suites By Carlson Delhi Saket",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location Located in New Delhi (South Delhi), Country Inn By Carlson Delhi Saket is minutes from Select Citywalk and MGF Metropolitan Mall.  This family  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 18568.88,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18568.88,
                    "PublishedPriceRoundedOff": 18569,
                    "OfferedPrice": 18568.88,
                    "OfferedPriceRoundedOff": 18569,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1fgKqm3E2zkBFCSxHU14YtADi6wsi+rbpUB4jEJapdfQAHls97r2mD7lV11ux9bqgzlJNGOtznInqCxZsAC6q0kxivYE4vqvAM+/UTlfzRYvw6jb/3yQN8g=",
                "HotelAddress": "Plot A1 DLF South Court District Centre , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 162,
                "HotelCode": "211474",
                "HotelName": "Home @ F37",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Home @ 37 is a home away from home in New Delhi. The cosy and intimate accommodations, quality services and its proximity to Lotus temple, fine dining restaurants and well-known markets, make it an ideal destination among leisure travellers.\n\n<b>Location:</b>\nHome @37 is located at East of Kailash. ISKCON Temple (Approx. 1km) and Lotus temple (Approx. 2km) are prominent attractions near the hotel. Lotus Temple is the only Bahai temple in Asia. It has a half floating lotus as the main structure along with white petals made of marble. Located amidst 9 pools and huge lawns, the entire structure is illuminated in the evening that gives it a golden glow and is a treat to watch it. Other places of attraction include Qutab Minar, Humayun\'s Tomb and India Gate.\n\nDistance from Indira Gandhi International Airport: 16 km (approx.)\nDistance from New Delhi Railway Station: 13 km (approx.)\n\n<b>Hotel Features:</b>\nGuests can avail the basic services that include room service, travel desk, internet, parking, security and doctor-on-call. Business services, meeting facilities and meeting rooms ensure guests have a hassle-free time arranging corporate events. The in- house kitchen serves a range of cuisines, including North Indian, Continental and Chinese for food lovers. \n\n<b>Rooms:</b>\nThe super deluxe rooms provide amenities such as air conditioning, colour television, internet access, refrigerator, safe, telephone and tea/coffee maker. The soothing ambience of the rooms with minimalistic decor beckons the guests for a relaxed stay. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15748,
                    "Tax": 2838.6,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18608.6,
                    "PublishedPriceRoundedOff": 18609,
                    "OfferedPrice": 18586.6,
                    "OfferedPriceRoundedOff": 18587,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6qEUS2Xr4gzHK0Chbola198QE9ovEdnfMoibOQ+mcVSw==",
                "HotelAddress": "F-37, East of Kailash,Delhi,India, , , 110065",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.555555",
                "Longitude": "77.243534",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 163,
                "HotelCode": "1351274",
                "HotelName": "Xenious IC\'s Hotel",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 15788,
                    "Tax": 2845.8,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 18655.8,
                    "PublishedPriceRoundedOff": 18656,
                    "OfferedPrice": 18633.8,
                    "OfferedPriceRoundedOff": 18634,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4+dMZCAAFTYQyE6M0MbynNd4yOdsF7zhBaTC6qC16Kj9GHJTZZhQqVQLooT2RO9pk=",
                "HotelAddress": "A-36, Abul Fazal Enclave, Jamia Nagar,Okhla,Delhi,India, , , 110025",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.557651",
                "Longitude": "77.294429",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 164,
                "HotelCode": "383691",
                "HotelName": "Hotel Meridian Plaza",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Hotel Meridian Plaza, New Delhi, is a fine accommodation facility, that speaks of elegance in every possible way. Beautiful rooms, courteous staff and unmatched hospitality is something guests can expect here. Due to its location in the affluent Greater Kailash, the hotel remains to be a good choice for leisure and business travellers alike.\n\n<b>Location:</b> \nLocated at Greater Kailash, Part-1, Hotel Meridian Plaza falls in close distance to many landmarks in the city. While staying here, guests can choose to visit the nearby attractions like Qutub Minar (Approx. 9km), Lotus temple and (Approx. 3km). Lotus Temple is popular for its magnificent architecture, and is also the only Bahai Temple in Asia. Other popular attractions worth visiting are India Gate, Red Fort and Jama Masjid. \n\nDistance from Indira Gandhi International Airport: 20 km (approx.)\nDistance from New Delhi Railway Station: 14 km (approx.)\n\n<b>Hotel Features:</b> \nElegant and stylish, Hotel Meridian Place is sure to please its patrons with the wide range of services and amenities on offer. While staying here, guests can expect to enjoy basic facilities like internet, 24-hour front desk, 24-hour room service, parking, travel desk and doctor-on-call. Business services, conference suite and convention centre helps corporate travellers during their business events and meetings. The hotel also has a small lounge, where complimentary breakfast is provided each morning. Guests can enjoy variety of beverages and delectable food at the in-house coffee-shop and restaurant during the stay. \n\n<b>Rooms:</b> \nOffering three types of rooms, namely premium, executive and deluxe the hotel manages to create a fine impression with their decor. Simple yet stylish, all their rooms have a luxurious ambience and comforting atmosphere. The basic facilities that one can expect in every room are minibar, flat-screen TVs, temperature control, Wi-Fi access, writing desk and in-room menu. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 16128,
                    "Tax": 2907,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 19057,
                    "PublishedPriceRoundedOff": 19057,
                    "OfferedPrice": 19035,
                    "OfferedPriceRoundedOff": 19035,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB50mDJZh4ZldDP9wj60YDVaRx33/V+Ng5jYM8UdDrxSyVwPd/hlwACR",
                "HotelAddress": "R-41, Greater Kailash, Part-1,Delhi,India, , , 110048",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.548149",
                "Longitude": "77.238425",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 67,
                "HotelCode": "215819",
                "HotelName": "Savoy Suites Noida Delhi N.C.R.",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "The 4-star Savoy Suites Noida Delhi N.C.R. offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. Both business travelers and tourists can enjoy the hotel\'s facilities and services. Service-minded staff will welcome and guide you at the Savoy Suites Noida Delhi N.C.R.. Designed for comfort, selected guestrooms offer television LCD/plasma screen, internet access – wireless, non smoking rooms, air conditioning, wake-up service to ensure a restful night. Access to the hotel\'s fitness center, outdoor pool, massage, billiards, garden will further enhance your satisfying stay. Savoy Suites Noida Delhi N.C.R. is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19068.3,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 19068.3,
                    "PublishedPriceRoundedOff": 19068,
                    "OfferedPrice": 19068.3,
                    "OfferedPriceRoundedOff": 19068,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG599p8emUqxNaNJXFg36W86WY3J2lWXU/+Cp4lxsbVQmgQrefypYD0pX47Vvw2E+jSaODSDAOGpr/aHqXpfKRQp6+qmC4t3uziu/bkNXaKk4+X51BFYUcmf4=",
                "HotelAddress": "A-79 A, Sector 16, New Delhi and NCR, India, , , 201301, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 68,
                "HotelCode": "544446",
                "HotelName": "Fortune Inn Grazia-Ghaziabad",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Located in Ghaziabad, Fortune Inn Grazia-Ghaziabad is a perfect starting point from which to explore New Delhi and NCR. The hotel has everything you need for a comfortable stay. Service-minded staff will welcome and guide you at the Fortune Inn Grazia-Ghaziabad. Some of the well-appointed guestrooms feature television LCD/plasma screen, internet access – wireless, air conditioning, desk, mini bar. To enhance guests\' stay, the hotel offers recreational facilities such as fitness center, sauna, indoor pool, spa, massage. Discover all New Delhi and NCR has to offer by making Fortune Inn Grazia-Ghaziabad your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19475.3,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 19475.3,
                    "PublishedPriceRoundedOff": 19475,
                    "OfferedPrice": 19475.3,
                    "OfferedPriceRoundedOff": 19475,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+Yq6lvt4VpfsxA3cLWdy/Q+735Zt3Gv7ARLoY/cBaI1KFRGBqo+10hoFsk0VnvgULdCICSdQ5l4fNARTipMJ8vXmlQJFSTefJD7lcF3RCjJhicIGUo0q7rRk=",
                "HotelAddress": "1 , Sanjay Nagar Distt. Centre, Delhi National Territory, New Delhi and NCR, India, , , 201002, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 69,
                "HotelCode": "239924",
                "HotelName": "Best Western Skycity Hotel",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Best Western Skycity Hotel is conveniently located in the popular Gurgaon area. The hotel offers a wide range of amenities and perks to ensure you have a great time. All the necessary facilities, including 24-hour room service, free Wi-Fi in all rooms, 24-hour security, convenience store, daily housekeeping, are at hand. Some of the well-appointed guestrooms feature television LCD/plasma screen, air purifier, bathroom phone, carpeting, clothes rack. Enjoy the hotel\'s recreational facilities, including fitness center, golf course (within 3 km), massage, karaoke, before retiring to your room for a well-deserved rest. Best Western Skycity Hotel is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19475.3,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 19475.3,
                    "PublishedPriceRoundedOff": 19475,
                    "OfferedPrice": 19475.3,
                    "OfferedPriceRoundedOff": 19475,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxJG869tMuH4/cJaTPLGoy2FIiEsIj7j5kwqlX7Bb18ie+fPqjhLPtwx/yUpsutb7RkTveIJ/WJI6anYsiud6VMw1oZaP1WZaMuYvZXY3uGbNlzrVpaC4jJA=",
                "HotelAddress": "1,Old Judacial Complex Sector -15 Gurgaon Haryana, New Delhi and NCR, India, , , 122001, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 70,
                "HotelCode": "304982",
                "HotelName": "Galaxy Hotel & Spa",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Stop at Galaxy Hotel & Spa to discover the wonders of New Delhi and NCR. The hotel offers guests a range of services and amenities designed to provide comfort and convenience. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, convenience store, daily housekeeping. Television LCD/plasma screen, separate living room, internet access – wireless, internet access – wireless (complimentary), whirlpool bathtub can be found in selected guestrooms. Enjoy the hotel\'s recreational facilities, including hot tub, fitness center, sauna, golf course (within 3 km), outdoor pool, before retiring to your room for a well-deserved rest. For reliable service and professional staff, Galaxy Hotel & Spa caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19525.02,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 19525.02,
                    "PublishedPriceRoundedOff": 19525,
                    "OfferedPrice": 19525.02,
                    "OfferedPriceRoundedOff": 19525,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUGNNXEqdtU/fpkQ0L9G0mIZbrPLziEXZgHRBv0e1U+LYae1xUO1UnlY/YeWvJGtxQScEEaXLUufYcSp1vagocU4oTcLdqERaDCwjs4d8dEGKHCjsM2YbPJo=",
                "HotelAddress": "NH-8, Sector 15, Part-2, Delhi National Terri, New Delhi and NCR, India, , , 122001, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 165,
                "HotelCode": "42017",
                "HotelName": "juSTa Panchsheel Park",
                "HotelCategory": "Justa Hotels",
                "StarRating": 4,
                "HotelDescription": "When it comes to comfort and convenience, Justa - The Residence Panchsheel Park hotel is up there with the best. Besides its popular restaurant and cafeteria, this hotel also provides business conferencing facilities. The rooms are comfortable and well-tended to by a courteous staff.\n\n<b>Location:</b> \nThe Justa - The Residence Panchsheel Park is situated a small distance apart from the centre of Delhi, making it very accessible for business and leisure travellers. It is well-connected to famous shopping hubs and is vicinity of several popular tourist attractions. Some of the places worth visiting include Lodhi Gardens, Qutab Minar, Siri Fort (Approx. 4km) and the Lotus Temple.\n\nDistance from Indira Gandhi Airport: Approx. 12km\nDistance from New Delhi Railway Station: Approx. 15km\nDistance from Nizzamuddin Railway Station: Approx. 10km\n\n<b>Hotel Features:</b>\nIdeal for the business traveller, the Justa - The Residence Panchsheel Park hotel offers state-of-the-art business facilities including meeting space and a conference hall. The hotel also provides banquet facilities for events and special occasions. Guests can safely park their vehicles in the space provided by the hotel with 24-hour security. A concierge and travel desk ensures that all guest queries are requests are catered to. The hotel also has a lounge and a multi-cuisine restaurant. The hotel has an in-house restaurant which serves Indian, Chinese, continental cuisines.\n\n<b>Rooms:</b>\nJusta - The Residence Panchsheel Park hotel provides accommodation in plush air-conditioned rooms that are fully equipped to please. Each room has a flat-screen TV, mini bar, safe and a tea/coffee maker for the indulgent guest. Business travellers will find the direct dialling facility on the telephone very useful. The hotel also provides internet access with Wi-Fi on charge. ",
                "HotelPromotion": "Book now and save 35%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 16550.4,
                    "Tax": 2983.03,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 8923.6,
                    "PublishedPrice": 19555.43,
                    "PublishedPriceRoundedOff": 19555,
                    "OfferedPrice": 19533.43,
                    "OfferedPriceRoundedOff": 19533,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7fXA6eu64v48TKJ+06sOjqaiOyDc7d2k4XiXThnJ14tJQP//pJerpR7cjjF9Iq7UU=",
                "HotelAddress": "S-362, Panchsheel Park,Delhi,India, , , 110017",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.542399",
                "Longitude": "77.219459",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 71,
                "HotelCode": "237330",
                "HotelName": "Fraser Suites New Delhi",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Ideally located in the prime touristic area of East Delhi, Fraser Suites New Delhi promises a relaxing and wonderful visit. The hotel offers a wide range of amenities and perks to ensure you have a great time. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, car power charging station, daily housekeeping are on the list of things guests can enjoy. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, additional bathroom, additional toilet, air purifier, bathroom phone. Entertain the hotel\'s recreational facilities, including yoga room, hot tub, fitness center, sauna, golf course (within 3 km). No matter what your reasons are for visiting New Delhi and NCR, Fraser Suites New Delhi will make you feel instantly at home.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19646.09,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 19646.09,
                    "PublishedPriceRoundedOff": 19646,
                    "OfferedPrice": 19646.09,
                    "OfferedPriceRoundedOff": 19646,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YnlR71XRQZsYZ5fOTyBARNc5ESf00Cp+PqUSKg+57u94mKI3L226eiQTO76j64qMFJ9sQNSpdDaq2dgwevttJH5I5Zz+ZiYXivNlVLRTsIZ9SgC2CDmRObk=",
                "HotelAddress": "Plot No - 4A, District Center, New Delhi and NCR, India, , , 110091, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 166,
                "HotelCode": "318302",
                "HotelName": "Hotel Classic Diplomat @ Airport Highway",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "",
                "HotelPromotion": "Mobile Deal Book now and get 30% off!",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 16778,
                    "Tax": 3024,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 7200,
                    "PublishedPrice": 19824,
                    "PublishedPriceRoundedOff": 19824,
                    "OfferedPrice": 19802,
                    "OfferedPriceRoundedOff": 19802,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5W9ptiLcrrkIGzpLeZFglpTN6QyNSlkZ/JeLjVH4+pFg==",
                "HotelAddress": "A-4,National Highway-8, Mahipalpur,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.547818",
                "Longitude": "77.126104",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 167,
                "HotelCode": "206754",
                "HotelName": "juSTa Greater Kailash",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Check-in to the posh and lavish hotel Just a - The Residence in Greater Kailash that offers incredible services and uncompromising accommodation. This amazing boutique hotel is an ideal place for all business as well as leisure travellers.\n\n<b>Location:</b>\nLocated at the midst of Greater Kailash in New Delhi, this luxurious hotel is very easily accessible to the tourists from the railway station and also the international airport. The locality where the hotel is situated is bustling with energy and is surrounded by the development of the metropolis. Famous tourist attractions near the hotel are Humayun\'s Tomb and Qutab Minar (Approx. 9km).\n\nDistance from Indira Gandhi Airport: Approx. 17km\nDistance from New Delhi Railway Station: Approx. 13km\nDistance from Nizzamuddin Railway Station: Approx. 6km\n\n<b>Hotel Features:</b>\n Just a - The Residence boutique hotel is an ideal business centre to conduct official seminars and corporate meetings. The decor of this hotel is extremely chic and classy with contemporary interiors and great ambience. Other services offered by the hotel are round-the-clock security, electronic wing card for room access, CCTV surveillance in public areas, doctor on-call services, first aid box and parking services. The 24-hour front desk is primed to help guests with all their doubts. The in-house Multi-Cuisine Restaurant serves American Buffet Breakfast as well as a choice of Continental, South & North Indian & Chinese Cuisines. The Hotel also has a coffee shop serving varieties of coffee and snacks, along with offering a great ambience to spend time with friends and family. Guests can add entertainment to their evenings in the lobby bar and lounge with international and domestic liquor.\n\n<b>Rooms:</b>\nHotel Indulge in Just a has spacious and elegant rooms in this hotel with American king-size beds and warm mattresses for comfort. Each room is equipped with a tea and coffee maker, telephone with direct dial, satellite cable television with a choice of select international channels with DVD player and herb-line bath products for a luxurious stay. There is also a mini bar in each room for the guests to make their own drinks. ",
                "HotelPromotion": "Book now and save 35%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 16950.8,
                    "Tax": 3055.1,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 9139.2,
                    "PublishedPrice": 20027.9,
                    "PublishedPriceRoundedOff": 20028,
                    "OfferedPrice": 20005.9,
                    "OfferedPriceRoundedOff": 20006,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6LIBjdrj9rCHS0Q5HDJzHTb6BD7B4iL7thk7+ts7d9cM/lbDe5x3h4MPUOJtEYXPA=",
                "HotelAddress": "R-53 Greater Kailash,Delhi,India, , , 110048",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.548362",
                "Longitude": "77.241707",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 72,
                "HotelCode": "1109941",
                "HotelName": "Red Fox Hotel, Delhi Airport",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Red Fox Hotel, Delhi Airport in New Delhi, you&apos;ll be connected to the airport and close to DLF Emporio Vasant Kunj and Jaw  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 20283.88,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 20283.88,
                    "PublishedPriceRoundedOff": 20284,
                    "OfferedPrice": 20283.88,
                    "OfferedPriceRoundedOff": 20284,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1ez/nCipA2hefQ3XIEVBGQB29LkpX+O/IxAizTFx3Tl28xqIAxTvjWhidATHnhej89V9xfUiZcEjDuC4mnopkP7isFtjfIAcSA==",
                "HotelAddress": "Asset No.6 Aerocity Hospitality District , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 168,
                "HotelCode": "382492",
                "HotelName": "bloomrooms @ Link Rd-Jangpura",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 17478,
                    "Tax": 3150,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 20650,
                    "PublishedPriceRoundedOff": 20650,
                    "OfferedPrice": 20628,
                    "OfferedPriceRoundedOff": 20628,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6YOJF80pzqSrEXyYpD0cZ5u9jY0cKS9KMLtRDz5rBnvXnlx6WD1bp20qswo6v0ubg=",
                "HotelAddress": "7 Link Rd, Jangpura,Delhi,India, , , 110014",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.581547",
                "Longitude": "77.239612",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 73,
                "HotelCode": "1259953",
                "HotelName": "The Corus",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at The Corus in New Delhi (Connaught Place), you&apos;ll be minutes from New Delhi Railway Station and Palika Bazaar.  This hotel   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 21035.18,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 21035.18,
                    "PublishedPriceRoundedOff": 21035,
                    "OfferedPrice": 21035.18,
                    "OfferedPriceRoundedOff": 21035,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyPszhKk7zXewaUn8+D1CLMJApaDWXdbIgaDwNY+TOWOcmCMNbJ73lujSh1UCqSYUZFadMHBvbJulA==",
                "HotelAddress": "B-49, Inner Circle, Connaught Place New Delhi 110001 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 74,
                "HotelCode": "1295288",
                "HotelName": "Shanti Home",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Shanti Home in New Delhi (West Delhi), you&apos;ll be convenient to Janakpuri District Centre.  This hotel is within the region  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 21520.55,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 21520.55,
                    "PublishedPriceRoundedOff": 21521,
                    "OfferedPrice": 21520.55,
                    "OfferedPriceRoundedOff": 21521,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1eA0nkBbmmqFVfp+vpvJQTQeczh5nLzt1w9BTZn2XXthBbAyxxc4V3m8T5hD9oHVKSrbyjk2do+cd0O0C+vGBF2OX1JbJtyh6HKsAfk+sS4GHtGpF89y/OU=",
                "HotelAddress": "A-1/300 Janakpuri New Delhi 110058 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 75,
                "HotelCode": "ALP|DEL",
                "HotelName": "Alpina Hotels & Suites",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "It is located in the centre of South Delhi, International Airport is 20 kms away, Nizamuddin railway station is 10 kms away, Nehru place metro station is 10 mins away.Pragati Maidan Trade Fair ground is 10 kms away. Rooms have the modern art work of furniture. It has both twin and large king size beds with all the modern amenities. It is serving Indian and Continental delicious food and Outdoor Roof Top lounge has a beautiful view. Hotel exterior is modern designed brick building. It has a small lobby with limited sitting area.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 21879.44,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 21879.44,
                    "PublishedPriceRoundedOff": 21879,
                    "OfferedPrice": 21879.44,
                    "OfferedPriceRoundedOff": 21879,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968oadb9fCcFxTQR11iJMHV3gLvMoc1M31yXr7MOGcSb4crPvJf43vS4JFnx/9At2wME9u+LpAJq+mn0mrBrtDE2/oSMT2awM2UYLtYfvkCTmYmgGyW8oindW",
                "HotelAddress": "E-506  GK II NEW DELHI - 110048 New Delhi 110048 India India, , India, , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 76,
                "HotelCode": "735170",
                "HotelName": "Hyatt Place Gurgaon Udyog Vihar",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Hyatt Place Gurgaon Udyog Vihar is conveniently located in the popular Gurgaon area. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. To be found at the hotel are 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service. Each guestroom is elegantly furnished and equipped with handy amenities. Take a break from a long day and make use of fitness center, golf course (within 3 km), garden. No matter what your reasons are for visiting New Delhi and NCR, Hyatt Place Gurgaon Udyog Vihar will make you feel instantly at home.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22045.91,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22045.91,
                    "PublishedPriceRoundedOff": 22046,
                    "OfferedPrice": 22045.91,
                    "OfferedPriceRoundedOff": 22046,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+Yp6HPTc2SnXnQKereJSJzCk6NABtsQLxrHsY2DzQ1Ezbb9N39JaE6HUSdaPW4Q3ludpJSx58hqaUWIKxZUVoRvHuJG9RJskBJA2CU1vZOtGb1bkuVCUvnH4=",
                "HotelAddress": "15/1 Old Delhi-Gurgaon Road, Sector 18, Gurgaon - 122015, INDIA, Haryana, New Delhi and NCR, India, , , 122015, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 77,
                "HotelCode": "1062257",
                "HotelName": "Park Inn by Radisson New Delhi Lajpat Nagar",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location A stay at Park Inn by Radisson New Delhi Lajpat Nagar places you in the heart of New Delhi, convenient to Ansal Plaza and Humayun&apos;s Tomb.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22331.3,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22331.3,
                    "PublishedPriceRoundedOff": 22331,
                    "OfferedPrice": 22331.3,
                    "OfferedPriceRoundedOff": 22331,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMRJT17sIZ32eyC2WGuBnQwJ3zGLsvwqb4qliucn+Venc6k+xLckmQXSuksjyl0QIP6BrHY+ths2Q==",
                "HotelAddress": "1 & 2 Ring Road Vikram Vihar , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 78,
                "HotelCode": "400464",
                "HotelName": "Four Points by Sheraton New Delhi - Airport Highwa",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Stop at Four Points by Sheraton New Delhi - Airport Highwa to discover the wonders of New Delhi and NCR. The hotel has everything you need for a comfortable stay. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, postal service are there for guest\'s enjoyment. Some of the well-appointed guestrooms feature television LCD/plasma screen, bathroom phone, clothes rack, mirror, scale. Recuperate from a full day of sightseeing in the comfort of your room or take advantage of the hotel\'s recreational facilities, including fitness center, outdoor pool, pool (kids), garden. A welcoming atmosphere and excellent service are what you can expect during your stay at Four Points by Sheraton New Delhi - Airport Highwa.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22352.92,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22352.92,
                    "PublishedPriceRoundedOff": 22353,
                    "OfferedPrice": 22352.92,
                    "OfferedPriceRoundedOff": 22353,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02JVjb7WxbG6tca3jTElKOHj7X0MIhfTTJ1YAm2CXJtg7+7HTx+YLuBZtQJOHSsLXZ8x0C3ZqO+rDiIYfKEBYzx1vXUagECH1iUayZpqxNMRosHJ96r56c18=",
                "HotelAddress": "Plot no.9,National Highway 8-Samalka, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 169,
                "HotelCode": "321453",
                "HotelName": "Emblem Hotel New Friends Colony",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Emblem - A Boutique Hotel, New Delhi, is a perfect blend of luxury and comfort. Featuring well designed interiors with earthy tones, the warm and peaceful ambience of the rooms ensure complete refreshment. This lavish hotel\'s close vicinity to commercial and business districts makes it one of the most preferred destinations for discerning travellers. \n\n<b>Location:</b> \nEmblem - A Boutique Hotel is located at Kalindi Colony, one of the posh areas of South Delhi. Nearby locations that are easily accessible from the hotel include Lotus temple (Approx. 6km) and National Museum (Approx. 8km). National Museum, on the Janpath Lane, houses famous artifacts and traditional masterpieces. There are approximately 2,00,000 fascinating art works of Indian and international origin to look at. These beautiful masterpieces on display are more than 5,000 years old. Other prominent locations worth visiting include Humayun\'s Tomb, Lodi Garden and Akshardham Temple.\n\nDistance from Indira Gandhi International Airport: 17 km (approx.)\nDistance from New Delhi Railway Station: 11 km (approx.)\n\n<b>Hotel Features:</b>\nThe impeccable services combined with heart- warming hospitality will make guests feel right at home. The hotel provides 24-hour front desk, 24-hour room service, parking, travel desk, wheel chair access, 24-hour security, concierge and doctor on call. Corporate guests can avail business services, audio visual equipment, LCD/Projector, meeting facilities and conference facilities. The 24-hour restaurant offers multi-cuisine delicacies to satiate your taste buds. Guests can enjoy their breakfast or have quick meetings at the 24-hour coffee shop. \n\n<b>Rooms:</b> \nRooms are classified as executive rooms and premium rooms. Each room is fully furnished with all the modern amenities. Rooms are equipped with various facilities including air conditioning, television, internet access, minibar, telephone, in room heating, table lamp and tea/coffee maker. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 18978,
                    "Tax": 3420,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22420,
                    "PublishedPriceRoundedOff": 22420,
                    "OfferedPrice": 22398,
                    "OfferedPriceRoundedOff": 22398,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5Bay6ylAo6AaC4srcwcvu8GeNUOWcP3IuPF0QdyA6/0aBtOl4Azp5O5vlrxVInEt4=",
                "HotelAddress": "E-5, Kalindi Colony (New Friends Colony Gurudwara Road),Delhi,India, , , 110065",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.574787",
                "Longitude": "77.264375",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 79,
                "HotelCode": "272562",
                "HotelName": "The Ashtan Sarovar Portico Hotel",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "The Ashtan Sarovar Portico Hotel is conveniently located in the popular South Delhi area. The hotel offers guests a range of services and amenities designed to provide comfort and convenience. 24-hour room service, Wi-Fi in public areas, valet parking, car park, room service are just some of the facilities on offer. Guestrooms are designed to provide an optimal level of comfort with welcoming decor and some offering convenient amenities like television LCD/plasma screen, internet access – wireless, non smoking rooms, air conditioning, wake-up service. The hotel offers various recreational opportunities. The Ashtan Sarovar Portico Hotel is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22471.83,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22471.83,
                    "PublishedPriceRoundedOff": 22472,
                    "OfferedPrice": 22471.83,
                    "OfferedPriceRoundedOff": 22472,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxBnmzVz285s4LZHoC5XO4aYGRAIdIS6yZgcu3Sbs5KgpcRYEzYRQJ/dzEf/tjWjKRdehN/VyD6P4mrJ9WDeEVh1UDQPcF3F9KnXjRBjTkU3AX82DqnTpfpQ=",
                "HotelAddress": "Green Park Extension, New Delhi and NCR, India, , , 110016, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 80,
                "HotelCode": "1047317",
                "HotelName": "The Suryaa New Delhi",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Property Location A stay at The Suryaa New Delhi places you in the heart of New Delhi, convenient to Jamia Millia Islamia and Humayun&apos;s Tomb.  This 5-star   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22966.39,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22966.39,
                    "PublishedPriceRoundedOff": 22966,
                    "OfferedPrice": 22966.39,
                    "OfferedPriceRoundedOff": 22966,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMRJT17sIZ32aIqsD1p2id9Fzr7t4F7T/rcmUx6XuWQ7kupSrEdvgh9P8KB7qeeSkNteHvRF8nXPtsaJ87jlLFSRiv7oJFqvLfGQWASzHLhCEnKN7hIBS4xIQojh8HCD9yHlOZahDkSunddaFBYSOq279/IK2fGZK8=",
                "HotelAddress": "New Friends Colony New Delhi 110025 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 81,
                "HotelCode": "2332752",
                "HotelName": "Udman Hotels and Resorts BY FERNS N PETALS",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22971.25,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22971.25,
                    "PublishedPriceRoundedOff": 22971,
                    "OfferedPrice": 22971.25,
                    "OfferedPriceRoundedOff": 22971,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxHk54qUlNNzv+gqD+VSs2ZqCdDYD2pl2byE4nSBzXDQYNt2pi+neQ++CP/C7CpeYWy/e/hWeZUubOnj5jTa16YsD3hM66R2d04M/KR7QGDmnWuujg1jUSAk=",
                "HotelAddress": "NATIONAL HIGHWAY -8 ADJACENT TO SHIVMURTHI MAHIPALPUR NEAR INTERNATIONAL AIRPORT, Delhi National Territory, New Delhi and NCR, India, , , 110037, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 82,
                "HotelCode": "299127",
                "HotelName": "Hotel Saket 27",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The 3-star Hotel Saket 27 offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. The hotel offers a high standard of service and amenities to suit the individual needs of all travelers. 24-hour room service, free Wi-Fi in all rooms, 24-hour front desk, facilities for disabled guests, express check-in/check-out are there for guest\'s enjoyment. Comfortable guestrooms ensure a good night\'s sleep with some rooms featuring facilities such as television LCD/plasma screen, internet access – wireless, internet access – wireless (complimentary), non smoking rooms, air conditioning. The hotel offers various recreational opportunities. Discover all New Delhi and NCR has to offer by making Hotel Saket 27 your base.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22987.47,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 22987.47,
                    "PublishedPriceRoundedOff": 22987,
                    "OfferedPrice": 22987.47,
                    "OfferedPriceRoundedOff": 22987,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUIR+zIMaJMKYHmpnhHOjatmu2ieGHNhFYMorAYS982/9pAsANRNi90uKwgm9Dr9f5xGIBQNedGOrWMu/5+sWXO97/JBCP5NgvOj1VDec4gd2BfSXeL0zB0U=",
                "HotelAddress": "J-27, Near ITC Sheraton Hotel, Saket, New Delhi and NCR, India, , , 110017, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 83,
                "HotelCode": "71670",
                "HotelName": "Fortune Select Global Hotel Gurgaon",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Stop at Fortune Select Global Hotel Gurgaon to discover the wonders of New Delhi and NCR. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, 24-hour front desk are just some of the facilities on offer. Comfortable guestrooms ensure a good night\'s sleep with some rooms featuring facilities such as television LCD/plasma screen, bathroom phone, clothes rack, linens, locker. The hotel offers various recreational opportunities. No matter what your reasons are for visiting New Delhi and NCR, Fortune Select Global Hotel Gurgaon will make you feel instantly at home.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 23311.22,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 23311.22,
                    "PublishedPriceRoundedOff": 23311,
                    "OfferedPrice": 23311.22,
                    "OfferedPriceRoundedOff": 23311,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p/e5O8Hr1sOyKCi7Mt6QkPvzucIlbOC2obv3NXq7rI+YuAFGriIFODQHSq3J+ITPAnMnr8XvMEdfxdaQpcIxPAedSwytaG/Opu7otwDmkGwboyYr6qcRXRodrh+oBmmAGpjdSUg8n1yDGNExvEIrubaSK59H/pNYZU=",
                "HotelAddress": "Global Arcade, M G Road, New Delhi and NCR, India, , , 122002, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 84,
                "HotelCode": "287259",
                "HotelName": "WelcomHotel Dwarka - ITC Hotels Group",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "WelcomHotel Dwarka - ITC Hotels Group is conveniently located in the popular Dwarka area. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. 24-hour room service, 24-hour security, postal service, private check in/check out, 24-hour front desk are just some of the facilities on offer. Designed for comfort, selected guestrooms offer television LCD/plasma screen, carpeting, clothes rack, complimentary instant coffee, complimentary tea to ensure a restful night. Access to the hotel\'s fitness center, sauna, outdoor pool, spa, massage will further enhance your satisfying stay. No matter what your reasons are for visiting New Delhi and NCR, WelcomHotel Dwarka - ITC Hotels Group will make you feel instantly at home.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 23439.32,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 23439.32,
                    "PublishedPriceRoundedOff": 23439,
                    "OfferedPrice": 23439.32,
                    "OfferedPriceRoundedOff": 23439,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p+iZ1HtfFdW/RVHX4JLtuGwMAKNVSukxAi0RhKNCqV02LvusLpTspAiSachSE28Qh5d5cO/Jmw9/tj26u5hs6SAPDAzcWnhsATmI12txV3/guPUoI/weEqOjMXhR3HY6FrE1EMzHKbSDpokHsi8xAh2uxcmP3yUjVU=",
                "HotelAddress": "Plot No.3, Sector-10, District Center, Delhi National Terri, New Delhi and NCR, India, , , 110075, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 85,
                "HotelCode": "1302538",
                "HotelName": "Hotel Diplomat New Delhi",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at Hotel Diplomat in New Delhi, you&apos;ll be minutes from Dhaula Kuan and close to Rashtrapati Bhavan.  This 4-star hotel is wit  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 23513.37,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 23513.37,
                    "PublishedPriceRoundedOff": 23513,
                    "OfferedPrice": 23513.37,
                    "OfferedPriceRoundedOff": 23513,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMAyopOFYk9cYAjddObbYUz+DWyKZJT+V4qKU6IHdtARCak9w/CCOJQ1zf7hw6ubjSNsiOULazEvm1hjP5R1YDId0PrRzhY5NhRVdL7/0pcT8L5rz2iQOPxQz2A3llZCnDDahBegrJNew==",
                "HotelAddress": "9 Sardar Patel Marg Diplomatic Enclave , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 170,
                "HotelCode": "333079",
                "HotelName": "Radisson Blu Dwarka",
                "HotelCategory": "Carlson Rezidor Hotel Group",
                "StarRating": 5,
                "HotelDescription": "Radisson Blu Dwarka offers a luxurious stay in close proximity to international and domestic airport. The hotel has spacious, well-appointed rooms and convenient amenities designed to provide a comfortable stay to business and leisure guests. A wide array of on-site dining experiences, including Pan-Asian cuisine and traditional Indian fine dining, as well as two lounges and a chic discotheque is also available in the hotel.\n\n<b>Location</b>\nRadisson Blu Dwarka is located at the serene location of Dwarka, southwest of New Delhi. This hotel is just 20 minutes away from main city hub and few kilometres away from the centre of the city. Many famous tourist destinations are Aqua point (7 km approx), India Gate (25km approx.), Lotus Temple (28 km approx) and Qutub Minar (24 km approx.). \n\nDistance from Indira Gandhi Airport: 11 km (Approx.)\nDistance from Palam Railway Station: 5 km (Approx.)\nDistance from New Delhi Railway Station: 27 km (Approx.) \nDistance from Nizzamuddin Railway Station: 30 km (Approx.)\n\n<b>Hotel Features</b>\nThe Radisson Blu hotel is an optimum choice for business travelers with 3 meeting rooms. There is also a terrace swimming pool, fitness centre, high speed internet, currency exchange and doctor on-call service available at the hotel. Indoor games like billiards, table tennis and foosball can be enjoyed at the recreation centre adjacent to the pool. Various brands like Pantaloons, More by Aditya Birla, Croma by Tata, Bata & Carbon diamonds in the in-house Soul City mall offer unique range of products. <b>Anantaa Spa</b> offers a rejuvenating experience with free access to Steam Sauna & Jacuzzi. \n\nGuests can sample a wide array of mouth-watering appetizers and exciting drinks from the comfort of room. F&B Outlets at Radisson Blu Dwarka are:\n\n<b>Spring, The Coffee Shop</b> - An all day dining restaurant offering sumptuous buffet and a la carte meals. This all-day, multi-cuisine restaurant offers international cuisine for breakfast, lunch and dinner. Enjoy a wide range of alcoholic and non-alcoholic beverages to complement your meal.\n\n<b>Atrium, The Tea Lounge</b> - Experience classic English culture with exotic flourishes while you sip Afternoon Tea and nibble on a delicious selection of cakes, pastries and savories. \n\n<b>Rice, The Pan-Asian Restaurant</b> - One of the finest Chinese restaurants with interactive kitchens. Guests may also enjoy a wide range of alcoholic and non-alcoholic beverages along with the meal.\n\n<b>The Zeppelin, The Bar</b> - Zeppelin is a stylish lounge boasting of a variety of exotic cocktails, choice wines, handpicked single malts and spirits. Enjoy turbulence-free dining at Zeppelin in a a classic ambiance with World War II-themed interior, complete with airplanes and collectibles from the era.\n\n<b>Dvar-Gateway to Indian Cuisine</b> - Dvar offers grand traditional Indian recipes while exploring the new age of exotic Indian flavors. \n\n<b>Rooms</b>\nThere are 217 rooms available in this hotel ranging from Superior Rooms (151), Specially Abled Room (01), Business Class (40), Executive Suite (25). All the rooms are equipped with advanced amenities along with sophisticated decor. Each room is well-appointed with climate control, electronic safe, LCD TV with satellite connection and DVD player, and modern bathroom toiletries. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19928,
                    "Tax": 3591,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 23541,
                    "PublishedPriceRoundedOff": 23541,
                    "OfferedPrice": 23519,
                    "OfferedPriceRoundedOff": 23519,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5tYwDbsOvk6XcJEz/Alc9aVUloRvZxAqvFbjTth4+VfxKmPkEycXW93a8CCUEmg1fKy9Mgha+iKA==",
                "HotelAddress": "Plot No. 4, Sector 13, Dwarka,Delhi,India, , , 110057",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.596393",
                "Longitude": "77.036338",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 171,
                "HotelCode": "719438",
                "HotelName": "ibis New Delhi Aerocity-An AccorHotels Brand",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in the heart of Aerocity\'s upcoming business district, ibis New Delhi Aerocity is the perfect destination for travelers on business or leisure.\n\nIn-room:\nThe 445 well-designed rooms feature high-speed free Wi-Fi, LED TV, black out curtains, safe deposit, tea and coffee amenities along with a mini fridge  dedicated to your comfort. Our bathrooms are well-fitted with health faucets and shower cubicles. \n\nLocation:\nThe hotel is located adjacent to both T1 & T3 airport terminals. The Airport Express metro line is just walking distance away from the hotel  connecting you to VFS Visa Centre in 20 mins. With just 30 mins away from New Delhis central commercial hub  Connaught Place & New Delhi Railway Station, the hotel is the most convenience base for all travelers. \n\nCentrally located, the hotel is well-connected to Delhi by National Highway 8 (NH8) linking it to Cyber City  the business hub of Gurgaon with entertainment pit-stops like the Cyber Hub and Ambience Mall. \n\nThe hotel is 0.1 km away from its main point of interest  Worldmark offering a plethora of dining options and strategically located closed to Delhis buzzing shopping malls - DLF Promenade & Emporio & Select Citywalk in Saket. It also enjoys close proximity to Indian Spinal Injuries Centre Hospital and Fortis Hospital as well as tourist attractions like Qutub Minar, India Gate and Humayuns Tomb.\n\nFeatures:\nThe hotel features well-equipped state-of-the-art meeting spaces and can accommodate over 250 delegates. \nBe it light bites or sumptuous spreads, Spice it  our multi-cuisine offers it all! Whats more, experience our difference with our Community Table, meet our City Expert  the know-it-all you\'ll love or just enjoy and unwind at our bar with an attached alfresco area. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 19974,
                    "Tax": 3599.28,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 23595.28,
                    "PublishedPriceRoundedOff": 23595,
                    "OfferedPrice": 23573.28,
                    "OfferedPriceRoundedOff": 23573,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6lH65Qd84KhZbquvjDzSf2xubSMgd4YJQh0Z3CK57/JWIn1v6tkxAS",
                "HotelAddress": "Asset no 9 Hospitality, District Delhi Aerocity IGI Airport, New Delhi,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.551412",
                "Longitude": "77.123133",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 86,
                "HotelCode": "1180550",
                "HotelName": "The Leela Ambience Convention Hotel Delhi",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Property Location Centrally located in New Delhi, The Leela Ambience Convention Hotel, Delhi is convenient to Yamuna Sports Complex and Max Super Speciality Hos  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 23684.71,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 23684.71,
                    "PublishedPriceRoundedOff": 23685,
                    "OfferedPrice": 23684.71,
                    "OfferedPriceRoundedOff": 23685,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyNgVcab9hOO0DjLLGcshRNLTlkLdwFlBYIsf/X/J+d1KdXzKK+uUNp2B0y3JObCXqjYULXW7lSQTg==",
                "HotelAddress": "1 CBD Maharaja Surajmal Road , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 87,
                "HotelCode": "1299820",
                "HotelName": "Allure Hotel - Demo",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Demo -    ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 24718.69,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 24718.69,
                    "PublishedPriceRoundedOff": 24719,
                    "OfferedPrice": 24718.69,
                    "OfferedPriceRoundedOff": 24719,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlXkihFvi968p5+flQ0eGELsj2NAJsuu+lxw3HiTefSnqE2D32rzkLe5Phelj4A3VI103Z+PAohoBhNyz+oF/DMSFm1sG4GRmsyDc4oniL61hP0G7WP8LmzL5YRBDgNnn4Wmx26rLxW6s=",
                "HotelAddress": "Demo - R-57, Greater  Kailash- 1 New Delhi  110048, New Delhi, , , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 88,
                "HotelCode": "111455",
                "HotelName": "Hotel Mosaic - Noida Delhi NCR",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Hotel Mosaic - Noida Delhi NCR is a popular choice amongst travelers in New Delhi and NCR, whether exploring or just passing through. Featuring a complete list of amenities, guests will find their stay at the property a comfortable one. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, wheelchair accessible are there for guest\'s enjoyment. All rooms are designed and decorated to make guests feel right at home, and some rooms come with television LCD/plasma screen, clothes rack, complimentary instant coffee, complimentary tea, free welcome drink. Entertain the hotel\'s recreational facilities, including fitness center, outdoor pool. For reliable service and professional staff, Hotel Mosaic - Noida Delhi NCR caters to your needs.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 24811.65,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 24811.65,
                    "PublishedPriceRoundedOff": 24812,
                    "OfferedPrice": 24811.65,
                    "OfferedPriceRoundedOff": 24812,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p9dNDBzv1/mp2iaok/46bKdIjkS2Auvq9nJSD4sMiFG51m9VTrMbL+BoDQfZGJ6aL8xXjDF3aJ0XKjO2ra79UJNK/Xxt85vSMOvSMqZAJ9es/a6Ss84yDlYB7T2ANDewXqxUgVoJKR3BrE88sGwy/A5FHs/rn8gZis=",
                "HotelAddress": "C - 1 Sector 18, New Delhi and NCR, India, , , 201301, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 172,
                "HotelCode": "327299",
                "HotelName": "Hotel Emarald",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Located in an easily accessible commercial area at Connaught Place, New Delhi. \nHotel Emarald is one of the best luxury hotels in the city, this hotel is idyllic for both business as well as leisure travellers.\n\n<b>Location:</b>\n Situated in the dynamic commercial and shopping hub of the city, i.e. Connaught Place, Hotel Emarald is also close to the railway station and cultural hotspots like  Jantar Mantar(approx 2km),India Gate(approx 4km),Jama Masjid(approx 4km), Lotus Temple, Qutub Minar, and Red Fort.\n\nDistance from Indira Gandhi Airport: Approx. 15km\nDistance from New Delhi Railway Station: Approx. 3km\nDistance from Nizzamuddin Railway Station: Approx. 7km\n\n<b>Hotel features:</b> \nFlaunting a contemporary atmosphere and facilities like an in-house restaurant,business center.Hotel Emarald is a great blend of easy comfort and convenience. With urbane, sophisticated interiors and a well-spoken and knowledgeable staff, this hotel is the perfect choice for your Delhi retreat. The in-house restuarant at hotel serves delicious multi-cuisine dishes.\n\n<b>Rooms:</b>\n This hotel features clean and well-lit rooms that ensure utmost comfort and relaxation. Combining refinement and exuberance, these rooms are well-equipped with every modern amenity imaginable. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 21228,
                    "Tax": 3825,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 25075,
                    "PublishedPriceRoundedOff": 25075,
                    "OfferedPrice": 25053,
                    "OfferedPriceRoundedOff": 25053,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7n8b8NfMwOXM57csgwqp8+sY0vnaCqPcNW3hKBGkWJ0PwadMirwC+6vclZ8YoOMq8=",
                "HotelAddress": "112, Babar Road, Opp. World Trade Center, Connaught Place,Delhi,India, , , 110001",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.632615",
                "Longitude": "77.227167",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 89,
                "HotelCode": "1016321",
                "HotelName": "Hotel ibis New Delhi Aerocity",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "Property Location With a stay at ibis New Delhi Aerocity - An AccorHotels Brand in New Delhi, you&apos;ll be connected to the airport and minutes from Worldmark  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 25097.04,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 25097.04,
                    "PublishedPriceRoundedOff": 25097,
                    "OfferedPrice": 25097.04,
                    "OfferedPriceRoundedOff": 25097,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEdeEhAeY283K/ZOT1dW2OHX0vI7jWX+11FANXU5DRnE0o11wAUwx0z0vnuj+JmskyMRJT17sIZ32UiK2GdUulGi2KN754AA1/bEDm6nPk5S1A8zubzncHyg8vNNcQXJYnlfXmjgi8JiuUFBUMA8JigO6X0LpHv+1K36XbTduuc3mw==",
                "HotelAddress": "9 Asset Delhi Aerocity IGI Airport , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 90,
                "HotelCode": "1089263",
                "HotelName": "The Hans",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Property Location A stay at The Hans Hotel New Delhi places you in the heart of New Delhi, minutes from American Library and close to Red Fort.  This 4-star hot  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 25413.23,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 25413.23,
                    "PublishedPriceRoundedOff": 25413,
                    "OfferedPrice": 25413.23,
                    "OfferedPriceRoundedOff": 25413,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1U9ZVIDifA5O7FSa02moVcud/W1txxKLSCrV6g5FNiv6hs73B/zyxqJ3YXGrTYZlXPc1itNHPwWBnh7OgJjjZmWmI6vb8+jWdA==",
                "HotelAddress": "15 Barakhamba Road Connaught Place , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 91,
                "HotelCode": "297604",
                "HotelName": "Radisson Blu Hotel New Delhi Paschim Vihar",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "The 5-star Radisson Blu Hotel New Delhi Paschim Vihar offers comfort and convenience whether you\'re on business or holiday in New Delhi and NCR. The property features a wide range of facilities to make your stay a pleasant experience. Facilities like 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, gift/souvenir shop are readily available for you to enjoy. Guestrooms are designed to provide an optimal level of comfort with welcoming decor and some offering convenient amenities like television LCD/plasma screen, bathroom phone, carpeting, clothes rack, free welcome drink. Entertain the hotel\'s recreational facilities, including yoga room, hot tub, fitness center, sauna, outdoor pool. Radisson Blu Hotel New Delhi Paschim Vihar is an excellent choice from which to explore New Delhi and NCR or to simply relax and rejuvenate.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 25541.87,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 25541.87,
                    "PublishedPriceRoundedOff": 25542,
                    "OfferedPrice": 25541.87,
                    "OfferedPriceRoundedOff": 25542,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8XDBuGcH8FDjDDjVOgcZMJacs4EE7AoA6EcWTqSFJqxL7hH41P3SDMFXr8aMrtE4DpjdZwQe0D6TF/OWuQK/Bv0PxhReQXsBssobQhQ5EOZi1e8oAKf0J+W2FA7UWOp8g+2RKi/lAC1T7PL+XMg8zFTyNn2RFEj70=",
                "HotelAddress": "Plot No. D, District Centre, Outer Ring Road, Paschim Vihar, New Delhi and NCR, India, , , 110063, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 92,
                "HotelCode": "1135679",
                "HotelName": "Vasant Continental",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Property Location With a stay at Jaypee Vasant Continental, you&apos;ll be centrally located in New Delhi, convenient to Malai Mandir and Qutub Minar.  This 5-s  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 25729.96,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 25729.96,
                    "PublishedPriceRoundedOff": 25730,
                    "OfferedPrice": 25729.96,
                    "OfferedPriceRoundedOff": 25730,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlP9NEeXLqW+4+q5aFQjKapxPtnsUHb6Op/a4L9FRp0DdOYw2sbszL1U9ZVIDifA5O7FSa02moVcsu1H5/faCpB+OgEk7n6BeIkhsR7R+MQvaCKPC8PQq2g7kwi/brwYe5i6+jXWJAihSdkM/BJs8Rrg==",
                "HotelAddress": "Vasant Vihar New Delhi 110057 , , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 93,
                "HotelCode": "1475",
                "HotelName": "Test Prop",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "aa trytr ty tr   ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 25944,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 25944,
                    "PublishedPriceRoundedOff": 25944,
                    "OfferedPrice": 25944,
                    "OfferedPriceRoundedOff": 25944,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlREHQkQveHEfM8uIePElhECnAgAl7HcG+Eiznyjv2UJ6fYM7YFGbIC2r4EAw4rc/YVTzpjOqb85UBZxw9WL2RPudgj27XQncYzUPUehBQOvoNHGT8z84+CbLVGsuwH0wuq3WneILLZehVgcqjvZr3NCeEIThrzaMXA99CyDkrPvD6DrEyAU5zrA==",
                "HotelAddress": "1, Delhi, , 34566456, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 94,
                "HotelCode": "148996",
                "HotelName": "Fortune Inn Grazia - Noida",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Ideally located in the prime touristic area of Noida, Fortune Inn Grazia - Noida promises a relaxing and wonderful visit. Offering a variety of facilities and services, the hotel provides all you need for a good night\'s sleep. 24-hour room service, free Wi-Fi in all rooms, 24-hour security, convenience store, daily housekeeping are on the list of things guests can enjoy. Television LCD/plasma screen, bathroom phone, clothes rack, locker, mirror can be found in selected guestrooms. To enhance guests\' stay, the hotel offers recreational facilities such as fitness center, sauna, golf course (within 3 km), outdoor pool, spa. No matter what your reasons are for visiting New Delhi and NCR, Fortune Inn Grazia - Noida will make you feel instantly at home.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 26181.82,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 26181.82,
                    "PublishedPriceRoundedOff": 26182,
                    "OfferedPrice": 26181.82,
                    "OfferedPriceRoundedOff": 26182,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUNsgneG0cZjBzJR/lNiqW7XUnJRwg94guUZX62vf2N2TXowsemRTJU+mIjqRo5+B8RwQCxFxD335NcvWkGHu0dh9B+h5+GM5MbXKSd6s1wDcnKBJZT7OhVw=",
                "HotelAddress": "Plot 1 A, I Block, Pocket 1, Sector 27, New Delhi and NCR, India, , , 201301, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 95,
                "HotelCode": "478437",
                "HotelName": "Savoy Suites-Greater Noida",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Savoy Suites-Greater Noida is perfectly located for both business and leisure guests in New Delhi and NCR. The hotel offers a wide range of amenities and perks to ensure you have a great time. All the necessary facilities, including 24-hour room service, free Wi-Fi in all rooms, 24-hour security, daily housekeeping, taxi service, are at hand. Each guestroom is elegantly furnished and equipped with handy amenities. The hotel offers various recreational opportunities. Friendly staff, great facilities and close proximity to all that New Delhi and NCR has to offer are three great reasons you should stay at Savoy Suites-Greater Noida.  ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 26374.24,
                    "Tax": 0,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 26374.24,
                    "PublishedPriceRoundedOff": 26374,
                    "OfferedPrice": 26374.24,
                    "OfferedPriceRoundedOff": 26374,
                    "AgentCommission": 0,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=ckZZ2jR/KJFL8uCNV/6rUdikLJoSoWwsQ4+ucla4wpAL9fwTuqkLXc3dJ9jA0ZdlnXViC3bj+p8+QYFXm2aeNXfxacee1wPchtcsaNk/aPW+CrIHZFDiUAfNAjrMYZeT5lRRaV3srJCPHNZKBy/INldwklnmd0459qLdtTN+3R55/Mgs68z9GlyhqRZx0GL4u+a54VjhIQc15ptK8aX6wsAOeozYPxhrz384zFeKu/E=",
                "HotelAddress": "Ansal Plaza, Destination Point.1- C, Institutional Area, New Delhi and NCR, India, , , 201301, , , ",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "",
                "Longitude": "",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 173,
                "HotelCode": "707611",
                "HotelName": "Crowne Plaza Mayur Vihar Noida",
                "HotelCategory": "Intercontinental Hotels Group",
                "StarRating": 5,
                "HotelDescription": "<b>Location: </b>Crowne Plaza Mayur Vihar - Noida is a business-friendly hotel located in New Delhi\'s East Delhi - Noida neighborhood, close to Swaminarayan Akshardham Temple, Humayun\'s Tomb, and Great India Place. Additional points of interest include Jamia Millia Islamia and Fortis Escorts Heart Institute. </br><b>Hotel Features: </b>Dining options at Crowne Plaza Mayur Vihar include a coffee shop/cafe and a snack bar/deli. A bar/lounge is open for drinks. Room service is available 24 hours a day. Recreational amenities include an outdoor pool and a fitness facility. The property\'s full-service health spa has body treatments, massage/treatment rooms, facials, and beauty services. This 5-star property has a business center and offers secretarial services, limo/town car service, and audiovisual equipment. For a surcharge, the property offers a roundtrip airport shuttle (available on request).  Business services and tour assistance are available. Valet parking and self parking are complimentary. Additional property amenities include a concierge desk, multilingual staff, and laundry facilities. This is a smoke-free property. </br><b>Guestrooms: </b> 168 air-conditioned guestrooms at Crowne Plaza Mayur Vihar feature minibars and coffee/tea makers. Furnishings include desks and ergonomic chairs. Bathrooms feature complimentary toiletries and hair dryers. In addition to complimentary newspapers and safes, guestrooms offer phones. Televisions have pay movies. Rooms also include complimentary bottled water and remote lighting/drapery/curtain control. Housekeeping is available daily. </br> ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22388,
                    "Tax": 4014.88,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 26424.88,
                    "PublishedPriceRoundedOff": 26425,
                    "OfferedPrice": 26402.88,
                    "OfferedPriceRoundedOff": 26403,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5E3yfn3Y0+mYk7CO1M2oW4Kb7Xesd62ifjhTIL8rQ+FS9wkqGOGOi7",
                "HotelAddress": "13 B Mayur Vihar, District Centre,Delhi,India, , , 110091",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.5900111",
                "Longitude": "77.2978551",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 174,
                "HotelCode": "44664",
                "HotelName": "Jaypee Vasant Continental",
                "HotelCategory": "Unirez",
                "StarRating": 5,
                "HotelDescription": "Get ready to get a taste of finest luxury, sumptuous comfort and immaculate rooms in Jaypee Vasant Continental, New Delhi, which is a 5 star Boutique hotel. An excellent melange of pleasure and business, this hotel is a perfect choice to relax and pamper. Located in the posh Vasant Vihar area, it can be a great choice for leisure and business travelers. \n\n<b>Location</b>\nLocated in Vasant Vihar area, Jaypee Vasant Continental is in close proximity to the domestic as well as Interantional Airport. Additional points of interest may include DLF Emporio (Approx. 3 km), Qutub Minar (Approx. 10 km), Basant Lok Shopping Complex etc. It is located within 5 kms radius of all embassies and approx. 13 kms from C.P.\n\nDistance from Domestic Airport : Approx. 9 km\nDistance from International Airport : Approx. 8 km\nDistance from New Delhi Railway Station : Approx. 17 km\n\n<b>Features</b>\nThis 5 star property has excellent dining options viz. <b>Eggspectation</b>, a resto cafe, <b>Ano Tai</b>, a Chinese restaurant, <b>Paatra</b>, a speciality restaurant,<b>Tapas</b>, a lounge bar and <b>The Old Baker</b>, the patisserie. The hotel also has a business centre with state-of-the-art equipments, poolside gardens for functions, banquet facilities and a bridal suite. Money Changer, Doctor on Call, Valet Service, Astrologer, 24 hour Concierge, Tour Assistance are a few other services that the hotel provides. Apart from all this, Jaypee also offers a wide variety of excellent wellness treatments, salon services and a gym. A few unique treatments provided at the hotel are Sabai Stone Therapies, Caviar Treatments, Moroccan Rassoul Body Wrap and Aromatic Anti-fatigue Moor Mud etc.\n\n<b>Rooms</b>\nThis exquisite hotel has 119 guest rooms, which includes 11 Suites, 20 Premium Clubs and 22 Club Royal rooms. The chic and contemporary rooms have safe deposit lockers, mini fridge, satellite TV, Tea/coffee maker, direct dial telephone, Wi-fi etc. Separate sitting areas and desks are available in all the rooms. Guests may also request irons/ironing boards and wake-up calls. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 23086,
                    "Tax": 4159.44,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 27267.44,
                    "PublishedPriceRoundedOff": 27267,
                    "OfferedPrice": 27245.44,
                    "OfferedPriceRoundedOff": 27245,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7iZ3TrCrOd0Ye6IiuHeX5jYAtOvtr4KeOY/KxYEIYPwj3btG+lRK6d",
                "HotelAddress": "Vasant Vihar,Delhi,India, , , 110057",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.556474",
                "Longitude": "77.164256",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 175,
                "HotelCode": "379933",
                "HotelName": "Radisson Blu Paschim Vihar",
                "HotelCategory": "Carlson Rezidor Hotel Group",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "Book 14 Days In Advance &amp; Get 15%Off",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 23693,
                    "Tax": 4268.7,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 4650,
                    "PublishedPrice": 27983.7,
                    "PublishedPriceRoundedOff": 27984,
                    "OfferedPrice": 27961.7,
                    "OfferedPriceRoundedOff": 27962,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7uTDZLsvVAu8lR908bdLPc8gY2PEm6CNk4eiaf/oSDrzIuve2wAoJ1",
                "HotelAddress": "Outer Ring Road, Paschim Vihar, New Delhi,Delhi,India, , , 110063",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.667334",
                "Longitude": "77.0911929",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 176,
                "HotelCode": "723516",
                "HotelName": "Holiday Inn New Delhi International Airport",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "<b>Overview</b>\nStay at the Holiday Inn New Delhi International Airport at the Aerocity hospitality district for convenient access to the New Delhi airport, South Delhi , Central Delhi & Gurgaon. The hotel is ideal for business, leisure and transit travellers. Facilities include a choice of 3 dining outlets, 24 hr room service, complimentary Internet access, contemporary rooms and a range of leisure facilities such as Sohum Spa, Fitness Centre, accessible round the clock travel desk, shops and outdoor pool.\n\nYour contemporary guestroom starting at 32 square meters features all the facilities you would expect at a Holiday Inn and includes a 37 inch LED TV, an iPod Docking station, a media hub and triple glazed windows for a peaceful sleep experience. Dining options include Indian and international favourites at the Viva, pub favourites & an extensive beverage selection at The Hangar and pastries at Viva Deli. Travelling to attractions such as Jantar Mantar, Qutub Minar, Red Fort, India Gate, Kingdom of Dreams and many more becomes easier, thanks to convenient access to the Delhi Metro network and National Highway 8.\n\nMeeting Planners and Wedding Planners will also appreciate the flexible meeting spaces and Wedding Function Space that accommodates up to 450 people and are equipped with the latest technologies and supported by the hotels experienced catering team.\n\n<b>Features</b>\nWe are ideally located in close proximity to Delhi Airport with easy access to Gurgaon and Delhi. Offering a variety of dining and leisure options to make sure your stay is relaxed and comfortable. Our hotel has quiet and spacious rooms for a peaceful sleep experience and we are offering customized event solutions and seamless service. We are also apart of the worlds largest loyalty program. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 22478,
                    "Tax": 5980.88,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 28480.88,
                    "PublishedPriceRoundedOff": 28481,
                    "OfferedPrice": 28458.88,
                    "OfferedPriceRoundedOff": 28459,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB4rRUPGVJ2Ns4mYLSJCz8wNDK3qHnCJkSAZFuxWDM8tcVy28pHrmVg/zLEpDquimYK1O0AbBV02gBxOw4v9GnI1",
                "HotelAddress": "Asset Area 12 Hospitality District Aero City - New Delhi,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.550285383993444",
                "Longitude": "77.12291400879622",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 177,
                "HotelCode": "1332060",
                "HotelName": "Pride Plaza Hotel Aerocity",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "The Pride Plaza Hotel at Aerocity Delhi with 385 luxurious rooms, extensive banqueting and convention facilities, exclusive food and beverage outlets, state-of-art wellness center, spacious swimming pool, business center and crew lounge ushers in new era of Indian Luxury Hospitality featuring exclusive modern architecture design and interiors.\n\n1)\tCafe Pride: Located at the lobby level  The all day dining cafe offers world cuisine with a highlight of Trendy interactive kitchens. Guests can enjoy flavours from across the globe besides huge variety of Indian regional cuisine. The Caf offers choice of elaborate buffets & and a-al-carte. \nLocation\t\t: Lobby Level\nTimings\t\t\t: Open round the clock.\nBreakfast Buffet\t: 06:30 Hrs  10.30 Hrs\nLunch Buffet\t\t: 12:30 Hrs  15.00 Hrs \nDinner Buffet\t\t: 19:00 Hrs  23.00 Hrs \n\n2)\tOriental Spice  Our Specialty Pan Asian Restaurant - offers a fine dining experience of oriental delicacies wherein guests can enjoy their meals cooked to perfection by master chefs in a live kitchen. The authentic dcor and warm and personalized service is an experience to cherish.\nLocation \t\t: Lobby level\nTiming \t\t: 19:00 Hrs  11.30 Hrs \n\n3)\tStallion  The Lounge Bar - located at lobby level. The Bar offers ideal venue to relax and unwind, is well stocked with exclusive brands of beverages , wines, spirits & choice of delectable short eats. Whether business meetings or casual outing, Stallion Lounge Bar is the ideal venue.\nLocation\t\t: Lobby Level\nTimings\t\t: 11.00 Hrs  00.45 Hrs  \n\n4)\tAngare  The Pool Side Restaurant - offers sumptuous choice of Kebabs & Curries.\nOpen\t\t\t: October to March\nLocation\t\t: Pool Side \nTimings\t        : 19.00 Hrs  23.30 Hrs\n\n5)\tMr. Confectioner  The Deli Counter - offers delectable & mouth watering choice of bakery & confectionary delicacies besides a elaborate choice of Tea /Coffee, soft beverages. Grab & go facility of wraps, rolls & sandwiches is also available for guests in hurry.\nLocation\t\t: Lobby level\nTimings\t\t: 08.00 Hrs  23.00 Hrs \n\nCONFERENCE & BANQUET FACILITY:\n\nHotels offers a choice of halls & venues for get together, conferences, celebrations & conventions. \n\nImperial Hall\t: Elegantly designed Imperial Hall with carpet area of 5000 Sq Ft with high ceiling and pre function area of 2500 Sq Ft offers the ideal venue for Weddings, Banquets, Celebrations, Conferences, Product Launches, Seminars etc.  The Hall can also be partined into 3three parts to suit your requirement.\n\nChancery Hall\t:Aesthetically done Chancery Hall with carpet area of 2500 Sq Ft offers the ideal venue for any kind of up market get together for 200  250 guest. ",
                "HotelPromotion": "Book now and save 25%",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 25478,
                    "Tax": 4590,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 8500,
                    "PublishedPrice": 30090,
                    "PublishedPriceRoundedOff": 30090,
                    "OfferedPrice": 30068,
                    "OfferedPriceRoundedOff": 30068,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6tCjdswFLTx8JnLkG+XK0y0MHsbx+M+Y87Sbg6+49p+/4rQr9BALFsuAexXX6RsQU=",
                "HotelAddress": "Asset 5-A, Hospitality District, Aerocity, Indra Gandhi lnternational Airport,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.552711",
                "Longitude": "77.122881",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 178,
                "HotelCode": "51285",
                "HotelName": "Hotel Diplomat",
                "HotelCategory": "",
                "StarRating": 4,
                "HotelDescription": "Hotel Diplomat is a sprawling, stately looking boutique hotel in the center of New Delhi. With well-groomed lawns all around, this hotel affords a lavish and relaxing stay in the heart of Delhi.\n\n<b>Location:</b> \nSituated in Chanakyapuri, Hotel Diplomat is ideal for both the business and leisure traveler due to its close proximity to major commercial and shopping areas. Close to famous tourist spots like  Jantar Mantar (approx 7 km), Connaught place (approx 7 km ), Humayun\'s Tomb (approx. 9 km), India Gate, Bhikaji Cama Place, and Humayun\'s Tomb, this hotel is as good as a high-end hotel can get.\n\nDistance from Indira Gandhi Airport: Approx  9km\nDistance from New Delhi Railway Station: Approx 8km\nDistance from Nizzamuddin Railway Station: Approx 10km\n\n<b>Hotel features:</b>\nHotel Diplomat features a regal structure with magnificent interiors, all done in soothing colors. Ensuring a luxurious stay, this hotel is replete with a restaurant, bar and a travel desk. The in-house restaurant Olive Beach has one of the most elegant and stylish set-up. Designed with white and blue theme, the restaurant features a minimalistic decor that has a Mediterranean feel to it. It serves exotic Italian cuisine along with other multi-cuisine delicacies.\n\n<b>Rooms:</b>\n Fully equipped and opulently designed rooms guarantee complete comfort and expediency throughout your stay at the Diplomat. The rooms are complete with LCD TVs, complimentary newspapers, Wi-Fi, minibar, and many other modern amenities. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 27978,
                    "Tax": 5040,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 33040,
                    "PublishedPriceRoundedOff": 33040,
                    "OfferedPrice": 33018,
                    "OfferedPriceRoundedOff": 33018,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6BdEM95ZdDnvQNimg4wvQvFueJBH4IYEaJN89h+GP15Ih5vmIsEB/6",
                "HotelAddress": "9, Sardar Patel Marg, Diplomatic Enclave, Chanakyapuri,Delhi,India, , , 1100021",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.606285",
                "Longitude": "77.188262",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 179,
                "HotelCode": "316100",
                "HotelName": "Fraser Suites New Delhi",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "Fraser Suites is a sophisticated and luxurious hotel located in the swanky residential area of Mayur Vihar and is very close to a metro station. In close proximity to vibrant shopping and commercial hubs, this hotel is idyllic for both the business as well as leisure travellers.\n\n<b>Location: </b>\nFraser Suites is situated in Mayur Vihar, at a 5 minutes walking distance from the metro station. A stone\'s throw away from cultural and tourist places like Red Fort, Jama Masjid, Chandni Chowk, Sanjay Lake (Approx. 4km),Lodi Gardens and Qutab Minar, this hotel is the perfect fusion of comfort and easy convenience.\n\nDistance from Indira Gandhi Airport: Approx.24km\nDistance from New Delhi Railway Station: Approx. 12km\nDistance from Nizzamuddin Railway Station:  Approx. 8km\n\n\n<b>Hotel Features: </b> \nFlaunting a swanky, upscale facade, Fraser Suites is a contemporary hotel offering you the best in comfort and style. Complete with a swimming pool, and a state-of-the-art gym, this hotel is a grand way to enjoy your stay in Delhi.\n\n<b>Rooms:</b>\nFraser Suites features opulently designed rooms spread across the floors, replete with every modern amenity imaginable. The rooms in the hotel ooze a suave, modern aura that is perfect if you are looking for a high-end, chic place to stay at. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 27978,
                    "Tax": 5040,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 33040,
                    "PublishedPriceRoundedOff": 33040,
                    "OfferedPrice": 33018,
                    "OfferedPriceRoundedOff": 33018,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB43uaS9Za5vGqIuDRNz149zElhPerhYPPV+kbQ86D978KToGg+7OOAY+AVx7mPPK8Q=",
                "HotelAddress": "Plot No.4A, District Centre, Mayur Vihar Phase-1,Delhi,India, , , 110091",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.594138",
                "Longitude": "77.298978",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 180,
                "HotelCode": "206749",
                "HotelName": "Hotel The Royal Plaza",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "The Royal Plaza is a deluxe medieval style property with Indo-European architecture. Famous for its world class hospitality and magnificent beauty, the hotel unfolds the most desirable lifestyle experiences to its guests. It has well crafted interiors, multi cuisine restaurants, night club and lounges making it ideal for leisure travelers. Business travelers can also prefer this hotel considering its central location and 5 conference halls.\n\n<b>Location</b>\nThe Royal Plaza is located on the posh Ashoka Road in New Delhi. The Royal Plaza is in close proximity to the business hub of South Delhi. Shopping malls, cinemas and restaurants are also nearby. Many historical and tourist sites like India Gate (approx 2 km), Connaught place (approx 3 km ), Jama Masjid (approx 6 km), Humayun\'s Tomb, Qutub Minar and Lotus Temple are also nearby.\n\nDistance from Indira Gandhi Airport: Approx. 14km\nDistance from New Delhi Railway Station: Approx. 4km\nDistance from Nizzamuddin Railway Station: Approx. 6km\n\n<b>Hotel Features</b>\nThe Royal Plaza New Delhi is a perfect combination of warmth, exceptional service and state of the art technology in an attractive ambience. It also boasts of multi-cuisine restaurants, bar, night club and lounge. The gym, spa & salon and the swimming pool are ideal to unwind after a long and tiring day. <b>Lutyens Coffee Shop</b> is a perfect venue for business meetings or lounging with its serene surroundings and the panoramic view of the Roman Gardens. A menu of exotic teas from Asia and coffees from South America with a variety of accompaniments and relaxing music is available at the The Lord William Tea Lounge. Authentic oriental cuisines with a delectable repertoire of specialties from Mainland China can be sampled at <b>Jasmine</b> restaurant. All types of events and meetings can be arranged in the conference halls with various services.\n\n<b>Rooms</b>\nThe Royal Plaza has luxurious and fully furnished rooms decked with the finest amenities and the medieval style architecture. The air-conditioned rooms come with Italian/French floral design fabrics, marble and onyx work, a Jacuzzi bathtub and solid mahogany furnishings. ",
                "HotelPromotion": "Regular Discounts",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 28778,
                    "Tax": 5184,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 7200,
                    "PublishedPrice": 33984,
                    "PublishedPriceRoundedOff": 33984,
                    "OfferedPrice": 33962,
                    "OfferedPriceRoundedOff": 33962,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6LIBjdrj9rCDAwD0/iBZE8Ah4OIador4K2TCd+iNl5Jr922reCPbQNFkWdwAMndPM=",
                "HotelAddress": "19, Ashoka Road, Janpath Lane, Connaught Place,Delhi,India, , , 110001",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.621576",
                "Longitude": "77.217004",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 181,
                "HotelCode": "358621",
                "HotelName": "Country Inn By Carlson Delhi Saket",
                "HotelCategory": "Carlson Rezidor Hotel Group",
                "StarRating": 4,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 29026,
                    "Tax": 6731.04,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 35779.04,
                    "PublishedPriceRoundedOff": 35779,
                    "OfferedPrice": 35757.04,
                    "OfferedPriceRoundedOff": 35757,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7e9vqBTr/s0rRRiquXdERBCzAiZ2mvn2FLkaKcNsn6s30cT0MseSgrOFGx/hRkisg=",
                "HotelAddress": "Plot No A1, DLF South Court, District Centre, Saket,Delhi,India, , , 110017",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.527441",
                "Longitude": "77.220532",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 182,
                "HotelCode": "707581",
                "HotelName": "Sheraton New Delhi Hotel",
                "HotelCategory": "Sheraton Hotel",
                "StarRating": 5,
                "HotelDescription": "Residing in the historic city of Delhi, Sheraton New Delhi at the Saket District Center, a red sandstone building rising eight stories high, resonates with cosmopolitan elegance and an unparalleled magnetism that characterizes the Capital of the nation. The hotel with exquisite cuisine and warmth of hospitality has earned a distinctive position amongst the 5 star hotels in Delhi.\n\nThis five-star deluxe hotel is conveniently located in the heart of a busy upmarket area and is adjacent to the city center of South Delhi. The hotel is also in close proximity to the Max International Healthcare Hospital. The hotel is an easy 35 minutes drive from the domestic/international airports as well as from some of Delhis largest convention centers such as Pragati Maidan and India Expo Centre.\n\nAmongst the finest 5-star business hotels of Delhi, Sheraton New Delhi offers a warm residential ambience and impeccable personalized service that bestows every discerning traveler with an unforgettable experience. The 220 guest rooms are generously proportioned and bear the thoughtful touches which exude the warmth of Indian hospitality with contemporary convenience. The standard amenities include an executive writing desk, comfortable seating and plasma screen television with cable programming. The Business Centre offers a private meeting room along with state-of-the-art facilities for business needs. \n\nThe Gymnasium located at the Wellness Centre is well equipped and is complete with the finest cardiovascular and strength training equipment. The advice and assistance of the qualified Gymnasium Instructors benefits guests in their wellness endeavors. The Swedish treatments available at the Wellness Centre offer a relaxing experience with a wide selection of oils to choose from. The separate Beauty Salon for ladies and gentlemen provides impeccable service with uncompromising dedication and individual attention. For those who prefer outdoors, the swimming pool located at the lower lobby level rejuvenates them.\n\nA team of dedicated Concierge will cater to guests\' personal needs and provide information on the important cultural and business events in the city. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 35532.44,
                    "Tax": 8881.5,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 44435.94,
                    "PublishedPriceRoundedOff": 44436,
                    "OfferedPrice": 44413.94,
                    "OfferedPriceRoundedOff": 44414,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5EPEgPW/3VnBFvoILCZc3ot1KXW1eHfoucSfab4q7wQHMzhNKYQX0lavqSapOaMw0LilL5IMXYouhAWQwKxuPV",
                "HotelAddress": "District Centre, Saket,Delhi,India, , , 110017",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.52682",
                "Longitude": "77.216017",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 183,
                "HotelCode": "170362",
                "HotelName": "Svelte Hotel and Personal Suites",
                "HotelCategory": "Advent Hospitality",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 35978,
                    "Tax": 10080,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 46080,
                    "PublishedPriceRoundedOff": 46080,
                    "OfferedPrice": 46058,
                    "OfferedPriceRoundedOff": 46058,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB69eV4GLRxZQdm4b1sRZXoIdjy02YnQPbFU0OhAEi8+D67G0HXxMPrHPDUET8Zxqd4=",
                "HotelAddress": "A-3, District Centre, Select Citywalk, Saket,Delhi,India, , , 110017",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.527959",
                "Longitude": "77.217648",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 184,
                "HotelCode": "44645",
                "HotelName": "Jaypee Siddharth",
                "HotelCategory": "Unirez",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 39658,
                    "Tax": 11110.4,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 50790.4,
                    "PublishedPriceRoundedOff": 50790,
                    "OfferedPrice": 50768.4,
                    "OfferedPriceRoundedOff": 50768,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5eWV0Y06sN0iilMDjdhLYXLjmrsiOycCn9ol1zDtccXWWAidkkIkqh",
                "HotelAddress": "3, Rajendra Place,Delhi,India, , , 110008",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.64253",
                "Longitude": "77.17576",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 185,
                "HotelCode": "1361462",
                "HotelName": "Andaz Delhi - A Concept by Hyatt",
                "HotelCategory": "Hyatt Hotels and Resorts",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 39978,
                    "Tax": 11200,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 51200,
                    "PublishedPriceRoundedOff": 51200,
                    "OfferedPrice": 51178,
                    "OfferedPriceRoundedOff": 51178,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB61CKW3T+z148u6ZIwDMUyLxxHAyslveD2QmzOumZOw558lv5BmP5xCglG/FaDcK6LSymZ77sJsMw==",
                "HotelAddress": "Aerocity,Delhi,India, , , 110005",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.554834",
                "Longitude": "77.121789",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 186,
                "HotelCode": "332999",
                "HotelName": "Zip By Spree Hotel Bluestone",
                "HotelCategory": "",
                "StarRating": 3,
                "HotelDescription": "The Hotel Blue Stone is an elegant and fashionable hotel for business and leisure travellers. The contemporary and minimalistic decor of the hotel lets guests lounge or work in comfort. With its conference rooms and banquet facilities, the hotel is an ideal choice for business travellers.\n\n<b>Location: </b>\nLocated in the posh area of Nehru Place in New Delhi, Hotel Blue Stone is ideally placed for the business traveller as it is close to the business hub of South Delhi. It lies in proximity to shopping malls, cinemas, jewellery houses and restaurants that lets the guests de-stress after a long day. Tourists can also visit tourist attractions like Lotus Temple (Approx 2km ),Siri Fort ( Approx 4km), India gate (Approx 10km) , Qutab Minar.\n\nDistance from Indira Gandhi Airport: Approx. 16km\nDistance from New Delhi Railway Station: Approx. 14km\nDistance from Nizzamuddin Railway Station: Approx. 7km\n\n<b>Hotel Features:</b>\nThe Hotel Blue Stone is a business class hotel that comes with all the modern facilities that the guests would require. The hotel lobby is designed around the unique concept of freedom-space. It offers its guests an opportunity to discover the marvels of the capital of India by conducting guided tours. Other facilities include wake-up service, foreign exchange facility, high speed internet access, maid service etc. It houses a multi-cuisine restaurant as well that serves a variety of Indian, Chinese, Continental delicacies.\n\n<b>Rooms:</b> \nThe Hotel Blue Stone has spacious rooms spread over the floors that are equipped with all the amenities a guest would require. The air-conditioned rooms are elegantly styled and come with high speed internet connectivity and modem lines to let the guests work from the comfort of their rooms. The king-sized beds and other furnishings add a touch of royalty and lets the guests revel in the marvel and comfort offered by the hotel. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 39978,
                    "Tax": 11200,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 51200,
                    "PublishedPriceRoundedOff": 51200,
                    "OfferedPrice": 51178,
                    "OfferedPriceRoundedOff": 51178,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5/zLS0OGjDF9ovVeWHiAVQNOD8DZvNaJD0HRVDoEqBt3SteKfuHy8wQ4mb/pggmFA=",
                "HotelAddress": "CC-24,Opp. Paras Cinema, Nehru Enclave, Nehru Place,Delhi,India, , , 110019",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.546494",
                "Longitude": "77.252646",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 187,
                "HotelCode": "732015",
                "HotelName": "Park Inn by Radisson New Delhi IP Extension",
                "HotelCategory": "Carlson Rezidor Hotel Group",
                "StarRating": 4,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 41778,
                    "Tax": 11704,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 53504,
                    "PublishedPriceRoundedOff": 53504,
                    "OfferedPrice": 53482,
                    "OfferedPriceRoundedOff": 53482,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5XEHJQkx3M7vECSW5Q+oFf0SxhX5Yo+tu9EU7Y3I+ziQLSMglEzKMP",
                "HotelAddress": "Plot No. 6A , IP Extension,Delhi,India, , , 110092",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.630066",
                "Longitude": "77.314708",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 188,
                "HotelCode": "159403",
                "HotelName": "Le Meridien New Delhi",
                "HotelCategory": "Starwood Hotels",
                "StarRating": 5,
                "HotelDescription": "Le Meridien is world renowned for its attention to detail and dedication to exemplary customer service. A perfect blend of class and elegance, Le Meridien is a symbol of opulence and comfort. It is an ideal destination for business travellers and family vacationers, who wish to indulge in luxury. This grand hotel will undeniably make every guests Delhi trip a wonderful experience.\n\n<b>Location</b>\nLe Meridien is situated 2 km away from Rashtrapati Bhavan. The hotel is amidst the most alluring shopping and entertainment district, Connaught Place. Guests can make their trip memorable by visiting nearby places of interest like Jantar Mantar (approx 2km), India Gate (approx 1km) and Red Fort. Patrons can also visit Vigyan Bhavan, Pragati Maidan and Old Fort.\n\nDistance from Indira Gandhi International Airport: 20 kms / 40 minutes\nDistance from New Delhi Railway Station: 3 kms / 15 minutes\n\n<b>Features</b>\nThe hotel has a spa- Amatrra that combines the ancient Ayurveda and Astroscience with 21st-century technology and equipment to offer unique treatments to its guests. There are international therapy Rooms, a private meditation Room and lifestyle Room for consultation among others. The hotel has a gift shop as well as a beauty salon. Le Meridien offers a range of amenities including laundry service, direct dialling, travel desk, doctor on call and currency exchange. Guests can relish on Indian, Chinese and Continental cuisines. A cafe and a lounge bar serves to the connoisseurs of dessert and cocktails. An in-house gymnasium and swimming pool add to the leisure quotient. \n\n<b>Rooms</b>\nLe Meridien offers luxurious and well-decorated 358 Rooms, which are spread across 14 floors. These Rooms have a simplistic yet comfortable design. Each Room in warm earthy tones provides the guests with a luxurious ambience and an inviting feel. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 43844.68,
                    "Tax": 10957.88,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 54824.56,
                    "PublishedPriceRoundedOff": 54825,
                    "OfferedPrice": 54802.56,
                    "OfferedPriceRoundedOff": 54803,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB6cimIqdLiucsqCDW44YJ8LAoqXlZj+3G0WeYO/igzZPMrW317sOEtQV/YpGz6/6AAzKG0y9OQtjLp1wV9zn0vo",
                "HotelAddress": "Windsor Place, Janpath, Delhi.,Delhi,India, , , 110001",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.618823",
                "Longitude": "77.217983",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 189,
                "HotelCode": "159396",
                "HotelName": "Hyatt Regency New Delhi",
                "HotelCategory": "Hyatt Hotels and Resorts",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 42978,
                    "Tax": 12040,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 55040,
                    "PublishedPriceRoundedOff": 55040,
                    "OfferedPrice": 55018,
                    "OfferedPriceRoundedOff": 55018,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB69+FUZR4e+Rcln7siDdb8ffwrjVXfBW6rErfh/WEl+3e2N8yMTOb/Linn/MG1vlH0=",
                "HotelAddress": "Bhikaji Cama Place, Ring Road,Delhi,India, , , 110066",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.569047",
                "Longitude": "77.1853299",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 190,
                "HotelCode": "1355238",
                "HotelName": "WelcomHeritage Haveli Dharampura",
                "HotelCategory": "WelcomHeritage",
                "StarRating": 5,
                "HotelDescription": "",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 43978,
                    "Tax": 12320,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 56320,
                    "PublishedPriceRoundedOff": 56320,
                    "OfferedPrice": 56298,
                    "OfferedPriceRoundedOff": 56298,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7zD1BzW+K4IPlkzuhRa69MuU0eI+oOxxCsUGsflyqWgrFpifBMlYjQn4CN3mNjXhQ=",
                "HotelAddress": "2293 Gali, Dharampura Guliyan, Near Jama Masjid, Gate No 3, Dharampura Chandni Chowk,Delhi,India, , , 110006",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.653162",
                "Longitude": "77.233973",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 191,
                "HotelCode": "707583",
                "HotelName": "Eros Hotel Nehru Place",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "<b>Location: </b>Eros Hotel Nehru Place  is a business-friendly hotel located in New Delhi\'s South Delhi neighborhood, close to ISKCON Temple, Humayun\'s Tomb, and Lotus Temple. Additional points of interest include Kalka Mandir and Deshbandhu College. \n\n</br><b>Hotel Features: </b>Dining options at Eros Hotel Nehru Place include 4 restaurants. Room service is available. Recreational amenities include an outdoor pool, a spa tub, a sauna, a fitness facility, and a steam room. The property\'s full-service health spa has massage/treatment rooms and beauty services. This 5-star property has a 24-hour business center and offers small meeting rooms and secretarial services. Wireless Internet access (surcharge) is available in public areas. This New Delhi property has event space consisting of conference/meeting rooms and exhibit space. For a surcharge, the property offers a round trip airport shuttle (available on request).  Guest parking is complimentary. Other property amenities at this Victorian property include a coffee shop/cafe, multilingual staff, and gift shops/newsstands. </br>\n\n<b>Guestrooms: </b> 218 air-conditioned guestrooms at Eros Hotel Nehru Place feature minibars and CD players. Accommodations include refrigerators and coffee/tea makers. Bathrooms feature makeup/shaving mirrors, bathrobes, scales, and hair dryers. Wireless Internet access is available for a surcharge. In addition to desks and complimentary newspapers, guestrooms offer multi-line phones with voice mail. Televisions have premium satellite channels and DVD players. Rooms also include safes. A turn-down service is available nightly, housekeeping is offered daily, and guests may request wake-up calls. </br> ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 48374,
                    "Tax": 13550.88,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 61946.88,
                    "PublishedPriceRoundedOff": 61947,
                    "OfferedPrice": 61924.88,
                    "OfferedPriceRoundedOff": 61925,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB5EPEgPW/3VnElsSVBWy50WhzwzeZ4yFSBVAqIq+RJiKMzTc4S/0iVnoCzdKK+fSESmcTj4Tzz3Dw==",
                "HotelAddress": "American Plaza, Nehru Place,Delhi,India, , , 110019",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.54962",
                "Longitude": "77.24912",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 192,
                "HotelCode": "41187",
                "HotelName": "The Grand Vasant Kunj",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "The Grand New Delhi hotel is a five-star luxury hotel that has been newly refurbished and exudes artful balance of contemporary elegance and modern design. It is designed with a splendid, welcoming white lobby, well appointed rooms, a beautiful spa and a variety of services for convenience of both business and leisure travellers. \n\n<b>Location:</b> \nThe hotel located at New Delhi and is in close proximity to the commercial hubs of the region. Popular tourist attractions such as Vasant Vatika (Approx. 2km), Sanjay Van (Approx. 7km) and Qutub Minar (Approx. 7km) are also located nearby. \n\nDistance from Indira Gandhi Airport: Approx. 11km\nDistance from New Delhi Railway Station: Approx. 19km\nDistance from Nizzamuddin Railway Station: Approx. 18km\n\n<b>Hotel Features:</b>\nThe hotel has a wide range of facilities that offer convenience and comfort. The conference facilities can accommodate up to 2000 guests at a time and the varied business services and meeting rooms make it ideal for business travellers. Other features include banquet facilities, provisions for the physically disabled, spa facilities etc. To indulge in a culinary experience, guests are provided with an array of options. The all day dining restaurant, \'Cascades\' serves national and international cuisines and \'Caraway\' is for Indian food lovers. It houses an Italian restaurant \'It\' and \'Woktok\' is known for its fresh Pan Asian delicacies. The Crystal Lounge and Grand Club Lounge are ideal to sit back and relax. For cocktails and mocktails, Aqua Pool and G Bar are just perfect. If guests wish to enjoy sweet treats then Indulge (The Pastry Shop) has a lot to offer.\n   \n<b>Rooms:</b>\nThe hotel has  rooms equipped with modern facilities so that guests enjoy a hassle free stay.Features such as air-conditioning, flat screen TVs, tea/coffee maker, in-room safe, mini-bar etc. facilitate a relaxing experience. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 57978,
                    "Tax": 16240,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 74240,
                    "PublishedPriceRoundedOff": 74240,
                    "OfferedPrice": 74218,
                    "OfferedPriceRoundedOff": 74218,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7VSxaSjA7Piy6Jv2EyrkAHA6npTjyPWxe1v2TJMy2gOo2xgmPvRYcInuNUz4mxEtU=",
                "HotelAddress": "Vasant kunj - Phase II, Nelson Mandela Road,Delhi,India, , , 110070",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.538923",
                "Longitude": "77.151757",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 193,
                "HotelCode": "786492",
                "HotelName": "Pullman New Delhi Aerocity-An AccorHotels Brand",
                "HotelCategory": "Accor Hotels",
                "StarRating": 5,
                "HotelDescription": "Located at just a 7-minute drive away from the Delhi International Airport, Pullman New Delhi Aerocity Hotel is in close proximity of prominent Business Hubs of Gurgaon and New Delhi. It is also located close to Delhi\'s Shopping Malls including DLF Promenade, Emporio and Worldmark.\n \nPullman New Delhi Aerocity boasts of 270 Rooms including 17 Suites and a Presidential Suite with splendid views of the Airport Runway. These are coupled with amenities like a wide working desk, Bose sound link and complimentary Wi-Fi, making Pullman New Delhi Aerocity the ideal spot for Business and Leisure.\n \nAn extensive range of dining options include Fine Dining Restaurants Pluck and Honk, All Day Eeatery Caf Pluck and Louge Bar Pling, Food Exchange and Quoin. While Pluck is based on Farm-to-Table concept with in-house ingredients from Hotels organic Farm, Honk is Bisutoro-Style Restaurant serving mouth watering Delicacies Ranging from Japanese to Thai.\n \nThe Wellness offering includes outdoor heated Swimming Pool, WOO Wellness Spa & Salon and a fully-equipped Health club, Fit Lounge.  A variety of Ayurvedic, Thai and Swedish Spa treatments can be enjoyed at the WOO Wellness Spa & Salon.\n \nPullman New Delhi Aerocity has a well-equipped meeting/banqueting space which is the largest pillarless ballroom in Delhi/NCR ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 59978,
                    "Tax": 16800,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 76800,
                    "PublishedPriceRoundedOff": 76800,
                    "OfferedPrice": 76778,
                    "OfferedPriceRoundedOff": 76778,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB754X1ogYsuk0npyfsZc4pvqAtwVUWnz9ZtzrXvJAyOaEXsheXfmr0LIVCz0xt0Yc/U5kvgZtK3NQ==",
                "HotelAddress": "Asset No 2, GMR Hospitality District,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.55453",
                "Longitude": "77.12305",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 194,
                "HotelCode": "41188",
                "HotelName": "The Park New Delhi",
                "HotelCategory": "The Park Hotels",
                "StarRating": 5,
                "HotelDescription": "One of the most renowned luxury 5-star hotels in the city, The Park redefines avant-garde hospitality. Handpicked contemporary art displayed throughout the public and private spaces of this hotel offers an elevated standard of design and dcor. Impeccable service along with exciting recreational and leisure facilities makes this hotel one of the most sought after luxury hotels in New Delhi for both business and leisure travellers. \n\n<b>Location</b>\nSituated in the very heart of the city, on Parliament Street near Connaught Place, The Park lies close to notable destinations like Red Fort, India Gate (approx 4km), Old Fort (approx 6km) and Delhi Haat. \n\nDistance from Indira Gandhi International Airport: 20 kms / 40 mins\nDistance from New Delhi Railway Station: 3 kms / 20 mins\n\n<b>Features</b>\nThe Park Hotel offers a stunning fusion of traditional style architecture with a contemporary twist. Guests have access to indulgent restaurants, pools, a health club, spa and salon, along with spacious banquet rooms for celebrations and corporate events. Additional amenities include a concierge desk, multilingual staff, and gift shops. The hotel also is famed for Agni, a celebrated spot for party lovers. Guests can enjoy an array of Italian, Mediterranean and Asian delicacies at the Mist. Awarded as \'The best Indian restaurant\' for 3 consecutive years, Fire offers delectable and authentic Indian food and the poolside restaurant Aqua offers its guest a perfect ambience to relax, lounge and dine. <b>Complimentary internet  is restricted to only one device (Charges applicable for additional devices).</b> \n\n<b>Rooms</b>\nThe 220 aesthetically designed guestrooms at The Park hotel offer authentic luxury. Spread across 11 floors, each of these rooms are categorized into the Luxury Room, Luxury Premium Room, The Residence and Presidential, and the Deluxe Suite. Beautifully furnished, each room offers a host of modern amenities like 2 telephones with global direct dial and voice mail, power outlets at desk level, LCD TV with multi-channel cable, DVD player and much more. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 79978,
                    "Tax": 22400,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 102400,
                    "PublishedPriceRoundedOff": 102400,
                    "OfferedPrice": 102378,
                    "OfferedPriceRoundedOff": 102378,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7VSxaSjA7Pi4xRR0J8V/qtbnz9wAFkq9Axk8HAGTiOYfz8w+mxb7iv7qSFkB/xwCmc0RB+378fr22oOqjaZsyK",
                "HotelAddress": "15 Parliament Street, Near Connaught Place,Delhi,India, , , 110001",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.628933",
                "Longitude": "77.216122",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            },
            {
                "ResultIndex": 195,
                "HotelCode": "723702",
                "HotelName": "The Roseate New Delhi",
                "HotelCategory": "",
                "StarRating": 5,
                "HotelDescription": "<b>Overview</b>\nThe Roseate New Delhi offers a transcendental 5-star leisure experience in the heart of New Delhi, India. Nestled amongst groves of trees and surrounded by beautiful green landscaping, The Roseate New Delhi provides the ultimate urban resort experience, perfectly blending modern convenience and timeless natural beauty.\n\nIdeally located near New Delhi business centre, embassy area, luxury malls, historical sites and airport, the hotel is also within easy reach of the corporate hub of Gurgaon.\n\nSurround yourself with timeless elegance at The Roseate New Delhi, the finest luxury accommodation in New Delhi. Well-appointed guest rooms feature contemporary furnishings, the latest technology and panoramic views of lush landscaped gardens. The Roseate\'s Thai heritage is imbued in every element of its highly personalised guest experience.\n\nAssigned to each guest upon arrival, the The Roseate Lifestyle Executive is an around-the-clock Butler, looking after every personal need.\n\nThe resort\'s 50 elegantly appointed and spacious rooms include 11 Roseate Pool Rooms which come with an additional 40 sq m private pool deck. ",
                "HotelPromotion": "",
                "HotelPolicy": "",
                "Price": {
                    "CurrencyCode": "INR",
                    "RoomPrice": 119978,
                    "Tax": 33600,
                    "ExtraGuestCharge": 0,
                    "ChildCharge": 0,
                    "OtherCharges": 0,
                    "Discount": 0,
                    "PublishedPrice": 153600,
                    "PublishedPriceRoundedOff": 153600,
                    "OfferedPrice": 153578,
                    "OfferedPriceRoundedOff": 153578,
                    "AgentCommission": 22,
                    "AgentMarkUp": 0,
                    "ServiceTax": 0,
                    "TDS": 0
                },
                "HotelPicture": "http://images.cdnpath.com/imageresource.aspx?img=a12Wzatglc0/F4SFOROVAHRIp4BJgrXdtJg0Va/DeB7/LWcUqcRooudyKintI3Xq+iULvwfzVKLoz41p8LxRvaLnQszkagDJ/MgZCg0RfLeIxOrLlQ/WAL/l1yARWUwoHyVIrLT88As=",
                "HotelAddress": "Samalkha, NH-8,Delhi,India, , , 110037",
                "HotelContactNo": "",
                "HotelMap": null,
                "Latitude": "28.531388",
                "Longitude": "77.103226",
                "HotelLocation": null,
                "SupplierPrice": null,
                "RoomDetails": []
            }
        ]
    }
}';

return $array[$key];
	}

    public function getCityHtml($countryCode='IN')
	{
		$this->getCityHtml='<ul id="myULCi" style="height:200px;overflow:hidden;display:none;">';
		$this->getAllCity($countryCode);
			//echo 'wwww<pre>';print_r($this->getAllCity);
			// dd($this->getAllCity);
			// die;
		foreach($this->getAllCity as $city)
		{
			//echo '<pre>wwwwwwwwwwwwwwwwww';print_r($city->name);die;
			$this->getCityHtml.='<li id="'.$city->cityid.'"  class="">'.$city->name.'</li>';
			
		}
		//echo 'ssssssssssssssss';die;
		$this->getCityHtml.='</u>';
		//echo $this->getCityHtml;
		
	}
	protected function getAllCity($countryCode)
	{
		$this->getAllCity=HotelCity::where(array('countrycode'=>$countryCode))->get();
		//echo '$countryCode=='.$countryCode;print_r($this->getAllCity);
		
	}

	public function getCountryHtml()
	{
		$this->getCountryHtml='<ul id="myULCo" style="height:200px;overflow:hidden;display:none;">';
		$this->getNationalityHtml='<ul id="myULNa" style="height:200px;overflow:hidden;display:none;">';
		$this->getCountryLiHtml='';
		$this->getAllCountry();
		//echo 'ww<pre>';print_r($this->getAllCountry);
		foreach($this->getAllCountry as $country)
		{
			//echo 'qqqqqqqqqq';die;
			//echo '<pre>';print_r($country->name);
			$this->getCountryLiHtml.='<li id="'.$country->countrycode.'" class="">'.$country->name.'</li>';
			
		}
		$this->getCountryHtml.=$this->getCountryLiHtml.'</u>';
		$this->getNationalityHtml.=$this->getCountryLiHtml.'</u>';
		//echo $this->getCountryHtml;
		
	}
	protected function getAllCountry()
	{
		$this->getAllCountry=HotelCountry::all();
		//echo '<pre>';print_r($this->getAllCountry);
		
	}

}
