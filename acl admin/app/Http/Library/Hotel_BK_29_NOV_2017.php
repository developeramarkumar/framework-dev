<?php 
namespace App\Http\Library;
use \App\Model\City;
use \App\Model\Order;
use \App\Model\Userdetail;


/**
* All function for hotel
*/
class Hotel
{   use Validation,Cryption;
	
	public $msg='';
	protected $form='';
	protected $paymentForm='';
	protected $hotelInfoForm='';
	
	public function getHotel()
	{
		
		return 'ok';
	}
	
	protected function getAllCity()
	{
		$this->getAllCity=City::all();
		//print_r($this->getAllCity);
		
	}
	    
	public function getCityHtml()
	{
		$this->getCityHtml='<ul id="myUL" style="height:200px;overflow:hidden;display:none;">';
		$this->getAllCity();
		foreach($this->getAllCity as $city)
		{
			//echo '<pre>';print_r($city->name);
			$this->getCityHtml.='<li>'.$city->name.'</li>';
			
		}
		$this->getCityHtml.='</u>';
		//echo $this->getCityHtml;
		
	}
	
	function getHotels($post)
		{
			// echo '<pre>aaaaa';print_r($post);die;
			$loader='';
			$post=$this->clean_input($post);
			$check_empty=$post;
			unset($check_empty['children']);
			$this->is_empty($check_empty);
			if($this->is_empty)
			{
				//echo '<pre>empty';print_r($post);die;
			   return false;	
			}
			
			$validateArr['date_validate'] = $post['start'];
			$validateArr['date1_validate'] = $post['end'];
			$validateArr['number_validate1'] = $post['adults'];
			$validateArr['number_validate'] = $post['children'];
			$this->validate($validateArr);
			
			if($this->validation_error)
			{
				return false;	
			}
			
		    $this->apiSearchHotels($post);
		    
		    if($this->getApiResponse==0)
		    {
				$this->getHotel=$this->msg;
				return;
			}
			
		    $this->generateHtml($this->apiSearchHotels);
		    
		    if($this->generateHtmlCounter>0)
		    {
				$loader='<div class="form-group text-center "><input value="Load More" id="loadMore" class="register_btn" type="button"></div>';
			}
			if($this->generateHtmlCounter==0)
			{
				$this->getHotel='<div  class="container"><div class="row">'.$this->generateHtml.'</div></div>';
			}
			else
			{
				
				$this->getHotel='<div class="text-center">
               <p class="hotel-title-b"><span id="hotelName">Hotels in '.$post['hotelCityName'].'</span></p>
            </div>
            <div class="row">
				   <div  class="col-md-3">
						<div class="">
							<div class="list-group panel">
								<form id="hotelFilter" action="" method="post">
									<a class="list-group-item list-group-item strong text-center" style="background: #d43e3e; color: white;" data-toggle="collapse"> Personalize Your Search</a>
									<a href="#demo1" class="list-group-item list-group-item-success strong" style="background: #f7f7f7;" data-toggle="collapse" data-parent="#MainMenu"> Min-Price <i class="fa fa-caret-down"></i></a>
									<div class="collapse list-group-submenu in" id="demo1">
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio" value="0-1000" name="price"> &#8377; 0 to 1000 </a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio" value="1000-2000" name="price"> &#8377; 1000 to 2000 </a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio" value="2000-3000" name="price"> &#8377; 2000 to 3000 </a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio" value="3000-5000" name="price"> &#8377; 3000 to 5000 </a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio" value="5000-200000" name="price"> &#8377; UPTO 5000 </a>
									</div> 
									<a href="#demo2" class="list-group-item list-group-item strong" style="background: #f7f7f7;" data-toggle="collapse" data-parent="#MainMenu"> Rating <i class="fa fa-caret-down"></i></a>
									<div class="collapse list-group-submenu in" id="demo2">
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio"  value="1" name="rating">&nbsp; 1</a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio"  value="2" name="rating">&nbsp; 2</a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio"  value="3" name="rating">&nbsp; 3</a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio"  value="4" name="rating">&nbsp; 4</a>
									  <a href="javascript:void(0);" class="list-group-item"><input type="radio"  value="5" name="rating">&nbsp; 5</a>
									</div>
								   <a class="list-group-item list-group-item strong text-center" style=" color: white;" data-toggle="collapse"><button type="button" class="btn btn-primary  btn-no-shadow" id="searchFilter">SEARCH</button> </a>
								   <input type="hidden" name="encrypt"  class="form-control city_name" value="'.$this->encrypted_key.'">
								</form>
							</div>
						</div>
					</div>
			       <div  class="col-md-9" id="filterHot">'.$this->generateHtml.$loader.'</div></div></div></div>';
		    }
		}
		
		protected function apiSearchHotels($post)
		{
			
			//~ $this->getApiResponse=1;
			//~ $json='{"clientInfo":{"partnerID":"100200"},"requestSegment":{"currency":"INR","searchType":"search","stayDateRange":{"start":"10\/11\/2017","end":"13\/11\/2017"},"roomStayCandidate":{"guestDetails":{"adults":"1","child":{"age":"12"}}},"hotelSearchCriteria":{"hotelCityName":"Agra","hotelName":{},"area":{},"attraction":{},"rating":{},"sortingPreference":"1","hotelPackage":"N"},"residentOfIndia":"true"},"searchresult":{"hotel":[{"hoteldetail":{"hotelid":"00005175","hotelname":"Hotel Aditya Palace","hoteldesc":"Located near to the Namner Crossing, this smoke-free property maintains 10 rooms across two floors. There is Wireless Internet facility in the lobby area. Guests can avail the breakfast services through room service and can also request for airport transportation facility, which is available at extra cost. The travel desk provides travel assistance to the guests. They can also rejuvenate with a spa session, which is arranged at the nearby center and can also park their vehicles in the free parking zone.","starrating":"1","noofrooms":"1","minRate":"363","rph":"4","webService":"arzooB","contactinfo":{"address":"Namner Crossing,Near S.R Hospital, , Idgah Bus Stand Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Idgah Bus Stand Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/ryl\/kbs\/HO.jpg"}},"geoCode":"27.167824,78.003055"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Non Ac Deluxe Double- Special Rates","roombasis":",10% Discount on Food and Beverages, ","roomTypeCode":"0000182848","ratePlanCode":"0000829922","ratebands":{"validdays":"1111111","wsKey":"qvTjv+rTsc3GHJZzEBm8Bjp5pJP1zETADml0U5ncn3dVtkbt66Nghw5pdFOZ3J93HolV+r+Wp8OaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfiIZXkYbOqQolMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCQTcidff3E4F2xsDogFQrz6I8bvCGgz9BzbDF1KH9z8TrRO69EO0ubiuJ4J8C\/IC5JwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tq2ZhfcOtFhb51SZHEH+bS7gOfPDHL9cTOlQQ1KvLvexWiiIlK8tehvfF294T0zOQXizl9lkmeuTxDrDapJ4gq4kFUW\/4Pu3RQ==","extGuestTotal":"0","roomTotal":"385","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"385"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Non Ac Deluxe Double","roombasis":",10% Discount on Food and Beverages, ","roomTypeCode":"0000182848","ratePlanCode":"0000682577","ratebands":{"validdays":"1111111","wsKey":"0NmNeucnQDLGHJZzEBm8Bjp5pJP1zETADml0U5ncn3dVtkbt66Nghw5pdFOZ3J93HolV+r+Wp8OaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfiIZXkYbOqQolMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCQTcidff3E4F2xsDogFQrz6I8bvCGgz9BzbDF1KH9z8TrRO69EO0ubiuJ4J8C\/IC5JwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9toGbJigLolmnSYrQfbcoJRAiL7ZmqC6d6fYS9hiMTec2weOUbgjswoFrITKP0puHEaqny03be8Lk6VBDUq8u97EpMoNZWc7A1g==","extGuestTotal":"0","roomTotal":"414","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"414"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/c Deluxe Double Room- Special Rates","roombasis":"No Amenities","roomTypeCode":"0000182847","ratePlanCode":"0000829923","ratebands":{"validdays":"1111111","wsKey":"Xys7bLL\/B9PGHJZzEBm8Bjp5pJP1zETADml0U5ncn3dVtkbt66Nghw5pdFOZ3J93W6Quq6RCqw+aT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfiIZXkYbOqQolMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCSEUZVbZ6Mwy1xk5Ny0pJvL+PhkH\/HP\/w88hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QNVVVmzcwxR3xDrDapJ4gq6Db3sQUa3VLfh+69pQRSEbSYrQfbcoJRBrITKP0puHEaqny03be8LkU7VUxYYFM\/iYV5yqFEQ9YQ==","extGuestTotal":"0","roomTotal":"888","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"888"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/c Deluxe Double Room","roombasis":"No Amenities","roomTypeCode":"0000182847","ratePlanCode":"0000682576","ratebands":{"validdays":"1111111","wsKey":"49z\/0GXxw0bGHJZzEBm8Bjp5pJP1zETADml0U5ncn3dVtkbt66Nghw5pdFOZ3J93W6Quq6RCqw+aT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfiIZXkYbOqQolMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCSEUZVbZ6Mwy1xk5Ny0pJvL+PhkH\/HP\/w88hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QFJCmakmfH7exDrDapJ4gq5PbYEBgOQOyd4JoRqYDR8axDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4Q+GveJpvddFFakgJU43uJg==","extGuestTotal":"0","roomTotal":"954","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"954"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00006393","hotelname":"Hotel Viren Plaza","hoteldesc":"This 25 room\u0092s property is located at Maa Vaishno Complex. There are AC and non-AC super deluxe and deluxe rooms to choose from. The property is maintained with a banquet venue for private celebration and a business center for official purpose. There is also free and secure parking space for private vehicles. Guests can also avail the beauty services and enjoy a make over. One can shop for souvenirs at the gift shop of the hotel.","starrating":"2","noofrooms":"1","minRate":"390","rph":"17","webService":"arzooB","contactinfo":{"address":"Maa Vaishno Complex, , Idgah Bus Stand Road Agra, Idgah Bus Stand Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Idgah Bus Stand Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/qyj\/mbq\/HO.jpg"}},"geoCode":"27.165939,77.998219"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Double Bed Non A\/C Room Only","roombasis":"No Amenities","roomTypeCode":"0000024314","ratePlanCode":"0000128222","ratebands":{"validdays":"1111111","wsKey":"0NmNeucnQDLGHJZzEBm8Bjp5pJP1zETADml0U5ncn3coBjWEl6VQvg5pdFOZ3J93D\/IbvYkxWAmaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfUh+\/HA6+rdznb1AeydyYyqFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIWpfC3Ssnfbjhxu9RkNXzuCXqu5r60kOs8pUHpjKWNiNQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTwPEVMcSobVQ=","extGuestTotal":"0","roomTotal":"414","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"414"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Three Bed Room Non A\/C Room Only","roombasis":"No Amenities","roomTypeCode":"0000024315","ratePlanCode":"0000128223","ratebands":{"validdays":"1111111","wsKey":"n\/LfkoeJLOTGHJZzEBm8Bjp5pJP1zETADml0U5ncn3coBjWEl6VQvg5pdFOZ3J936KtZYKruLoiaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfUh+\/HA6+rdznb1AeydyYyqFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCITjzoYWOIN5O2r+I7z1XJhXxhIqdBenRv0kcdk4KpM0uQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTC5Ynjvr\/ImY=","extGuestTotal":"0","roomTotal":"573","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"573"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Four Bed Non A\/C Room Only","roombasis":"No Amenities","roomTypeCode":"0000024316","ratePlanCode":"0000128224","ratebands":{"validdays":"1111111","wsKey":"ZAcs4Uu\/jtPGHJZzEBm8Bjp5pJP1zETADml0U5ncn3coBjWEl6VQvg5pdFOZ3J93cKYnWFOpQomaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfUh+\/HA6+rdznb1AeydyYyqFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIQFrwpV5LpUEwxn4hQdYY\/VY7C\/uIPw0zn\/qv\/7nsI5xQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTgkRqBJM3\/Es=","extGuestTotal":"0","roomTotal":"764","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"764"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Double AC Room","roombasis":"No Amenities","roomTypeCode":"0000094027","ratePlanCode":"0000370878","ratebands":{"validdays":"1111111","wsKey":"49z\/0GXxw0bGHJZzEBm8Bjp5pJP1zETADml0U5ncn3coBjWEl6VQvg5pdFOZ3J93SGZCQm9NCD2aT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfUh+\/HA6+rdznb1AeydyYyqFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIQRveXDPD7BZuYz0w34WyRT06nHAjV6KXIXV9HF3R0ILQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTJUwW5A2QE5s=","extGuestTotal":"0","roomTotal":"954","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"954"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00006492","hotelname":"Hotel Sai Palace","hoteldesc":"Enjoy the eternal view of the Taj Mahal from the roof-top lounge-cum-restaurant of this comfortable stay option, which is located close to the South or East Gate of Taj Mahal. A total of 19 rooms are available for accommodation. Guests can avail all types of travel assistance and tourist information from the hotels travel desk. A scrumptious fare is served at the restaurant-cum-lounge. There is also money exchange bureau available for the convenience of the NRI guests.","starrating":"1","noofrooms":"1","minRate":"399","rph":"18","webService":"arzooB","contactinfo":{"address":"Between South & East Gate Taj Mahal, 3\/117 Chowk Kagzian Taj Ganj, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"10:00:00","checkouttime":"10:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/qyi\/mbp\/HO.jpg"}},"geoCode":"27.169164,78.042374"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Double Room ( Shared Bathroom )","roombasis":",10% Discount on Food and Beverages, Unmarried Couples are not allowed., ","roomTypeCode":"0000024986","ratePlanCode":"0000131119","ratebands":{"validdays":"1111111","wsKey":"80UxCb5leNXGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cnNUXsTYY5cA5pdFOZ3J93FpcZ8VG3zgaaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfBmmQTKkclbzwRgyOSUT3BujJb+jq3Wi8k3rhjshiVFqDuIM0iK1QIV56KIVnVuxrAVWsY001YikTYa2j\/Y\/Goj3\/nVByy12RYcsCBHqZV370\/Y\/NOOJ4kse7kVbSR63+ruvjiorMNA5wnVBx2KS9f8Q6w2qSeIKu5a8gxPgjph7EOsNqkniCruWvIMT4I6YexDrDapJ4gq7pkacnLosJMHCdUHHYpL1\/MNWFSe5rsmlJitB9tyglEJqoUeTngQnXxmmDgOcmohzW50Duv0Njz8Q6w2qSeIKuzjOHvcodKrdrEVyah3c8e+1fGSSCw26\/xDrDapJ4gq7zRTEJvmV41RsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXunBD\/gMLndPD","extGuestTotal":"0","roomTotal":"423","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"423"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007325","hotelname":"Viren Sofitel","hoteldesc":"Planning a visit to Agra - the city of love then stay at Hotel Viren Sofitel, a budget hotel in Agra. This well furnished hotel with all modern amenities is centrally located. It is also situated less than 2 kilometers away from Taj Mahal, one of the Seven Wonders of the World and the most famous monument in India. There are 15 rooms with attached bathroom facility offering round-the-clock room service.","starrating":"1","noofrooms":"1","minRate":"495","rph":"11","webService":"arzooB","contactinfo":{"address":"41,Bansal Nagar,Fatehabad Road, Agra, Bansal Nagar, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Bansal Nagar","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/4\/nxd\/maw\/pyj\/fbs\/HO.jpg"}},"geoCode":"27.161013,78.036224"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Double bed Non Ac Room","roombasis":"No Amenities","roomTypeCode":"0000028794","ratePlanCode":"0000128209","ratebands":{"validdays":"1111111","wsKey":"RJ67eEoqIPTGHJZzEBm8Bjp5pJP1zETADml0U5ncn3fbhVjtVuDw8w5pdFOZ3J93Ukx6YmvmcbSaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtiIakEaVLcFCurOdI10jgJj2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN3CdUHHYpL1\/xDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq63VZzKqMb\/o0IR2F0Clj2AUk81Flf6wf\/ckyb00+3ejW61lqktJkc03xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu4XmHCoAP2UE=","extGuestTotal":"0","roomTotal":"525","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"525"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Double bed Ac Room","roombasis":"No Amenities","roomTypeCode":"0000028795","ratePlanCode":"0000128210","ratebands":{"validdays":"1111111","wsKey":"e29q3OcWCVDGHJZzEBm8Bjp5pJP1zETADml0U5ncn3fbhVjtVuDw8w5pdFOZ3J93ieAsvEA7IoSaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtiIakEaVLcFCurOdI10jgJj2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN3CdUHHYpL1\/xDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq63VZzKqMb\/oy7P4dIcZ8mirWa0rww56HjEOsNqkniCrntvatznFglQGwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6nuJXRaqQhJM=","extGuestTotal":"0","roomTotal":"840","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"840"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00007334","hotelname":"Shanti Lodge","hoteldesc":"Located in the city of Love, hotel Shanti Lodge has all the required amenities and facilities for your comfort. The hotel is located in proximity to all important tourist destinations and attractions. A dedicated travel counter ensures you don\u0092t miss out on anything and have an overall great experience. Room service, luggage storage, backup generator and a 24 hour front desk are also available.","starrating":"1","noofrooms":"1","minRate":"540","rph":"55","webService":"arzooB","contactinfo":{"address":"3\/138 South Gate, Taj Ganj, Agra, , Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/maw\/pyj\/gbr\/HO.jpg"}},"geoCode":"27.167341,78.041572"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000187238","ratePlanCode":"0000695858","ratebands":{"validdays":"1111111","wsKey":"n\/LfkoeJLOTGHJZzEBm8Bjp5pJP1zETADml0U5ncn3d4glRWWwpYjg5pdFOZ3J93drj638n6WXGaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtvqNy4nfP+HO4YqgqcHGFUKHKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvrieCfAvyAuScJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbYggjYHbRxTL2Q5oOVMvpmMNidpeGpiSWqiT21pE0Mu08HjlG4I7MKBayEyj9KbhxGqp8tN23vC5OsPZgaWJ5fNHBWt9RD7yzo=","extGuestTotal":"0","roomTotal":"573","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"573"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Double Non A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000187266","ratePlanCode":"0000695902","ratebands":{"validdays":"1111111","wsKey":"aB+ArFeU0gfGHJZzEBm8Bjp5pJP1zETADml0U5ncn3d4glRWWwpYjg5pdFOZ3J93exw3nmn7UySaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtvqNy4nfP+HO4YqgqcHGFUKHKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvrieCfAvyAuScJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbZjOZZ1n37+SOdUmRxB\/m0uvA\/FtjBKZ613a5Nn3rHJFcHjlG4I7MKBayEyj9KbhxGqp8tN23vC5H293oLICxKFcLw8aAiL9Lo=","extGuestTotal":"0","roomTotal":"636","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"636"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000028102","ratePlanCode":"0000140925","ratebands":{"validdays":"1111111","wsKey":"Yr7dtEDRCITGHJZzEBm8Bjp5pJP1zETADml0U5ncn3d4glRWWwpYjg5pdFOZ3J93pafKOYl3fsGaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtvqNy4nfP+HO4YqgqcHGFUKHKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvrieCfAvyAuScJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbYpT9mqVAVonZj\/9TK3zkwE7VyS4C36KEiCjYaUo9Pg0sHjlG4I7MKBayEyj9KbhxGqp8tN23vC5L\/vraucHkwqfprmzvoJ15E=","extGuestTotal":"0","roomTotal":"827","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"827"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000028103","ratePlanCode":"0000178223","ratebands":{"validdays":"1111111","wsKey":"pv85lB2YJjQ6eaST9cxEwODKXEz\/3SRd+X4fb+MTW93MUcZkQ4x6T3fKb8mQgkHwSdlt6TEKe2WLY4VUcXkeT2MLLQ2VdKnzmk9s1Uz6PbbEOsNqkniCrsHjlG4I7MKBayEyj9KbhxGqp8tN23vC5F\/UGu9+MzKat96pO\/FUSmMo+q4PliysJKFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRHvhay5zV9IrvubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIaibtYLkjbeD0TIXZt4nV2ZDlPTZzbJ8gQ4cvf2HbZ22xDrDapJ4gq4W5hQzQkrY4o5RIcwb63U40TIXZt4nV2bGcT7qJVJAxA==","extGuestTotal":"0","roomTotal":"1082","servicetaxTotal":"368","discount":"0.0","commission":"0","originalRoomTotal":"1082"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Double A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000187248","ratePlanCode":"0000695875","ratebands":{"validdays":"1111111","wsKey":"ku1f+9tL2Y06eaST9cxEwI8UzyKz2K4dF+CymYUZOAXMUcZkQ4x6T3fKb8mQgkHwFWoL3CVpFsz8X1Da3230Z2MLLQ2VdKnzmk9s1Uz6PbbEOsNqkniCrsHjlG4I7MKBayEyj9KbhxGqp8tN23vC5F\/UGu9+MzKat96pO\/FUSmMo+q4PliysJKFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRHvhay5zV9IrvubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIRC5nnfk\/FNfPIbMiKLjV+MNst9\/ob18WZcIQWu1swHBxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U44oUm3PO\/pgQcFa31EPvLOg==","extGuestTotal":"0","roomTotal":"1209","servicetaxTotal":"411","discount":"0.0","commission":"0","originalRoomTotal":"1209"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Suite","roombasis":"No Amenities","roomTypeCode":"0000028104","ratePlanCode":"0000178226","ratebands":{"validdays":"1111111","wsKey":"8eZJUS6DRDs6eaST9cxEwP3j24PDD33iEUboeGMm4MGXKid\/rdIhon\/hJO5vCNVjiG065xYCyu\/Xgbq6qxAVxupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuDduRMYTgQ4i9KU1i+T76CoQj\/og\/zHPzxDrDapJ4gq6+m9VIOMrRSWf9BWsomSQZD3osKZ\/RCcD0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfdydayrJHNXoBcJ1QcdikvX8iswLCfCFSCeKFJtzzv6YE9Z0KTPWJZQbfF294T0zOQXizl9lkmeuTxDrDapJ4gq4Xzctu6RJdSg==","extGuestTotal":"0","roomTotal":"1247","servicetaxTotal":"424","discount":"0.0","commission":"0","originalRoomTotal":"1247"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00007753","hotelname":"Hotel Om Sai Palace","hoteldesc":"Located just five kms from Taj Mahal, one of the seven wonder of the world and about four kms from Agra Cantt. Station, this hotel is one of the conveniently placed hotels in Agra. The hotel has 20 guestrooms to choose from. Zaika, a well-lit restaurant serves variety of cuisines to the guest and Puja is a spacious banquet hall. Facilities like airport transfers are offered on chargeable basis. Travel counter offers travel assistance while free parking space makes it convenient for the guests to park.","starrating":"2","noofrooms":"1","minRate":"575","rph":"62","webService":"arzooB","contactinfo":{"address":"sewla sarai, Gwalior road, , Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282002","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/pyf\/ibq\/HO.jpg"}},"geoCode":"27.12501,78.004802"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room Non A\/C","roombasis":",Breakfast, ","roomTypeCode":"0000029635","ratePlanCode":"0000135988","ratebands":{"validdays":"1111111","wsKey":"LjMY1YR11WTGHJZzEBm8Bjp5pJP1zETADml0U5ncn3c6M2s7OvAhkQ5pdFOZ3J93kBZq9tbSjEiaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfNWKqoB\/HFvElMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCTiwZejQSADkZwLDUpKr8zwkTtnIK786l48hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QJmYE0sUCl76xDrDapJ4gq57E4SxXHCwJzrojf1bXNNaxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U42r+I7z1XJhXoeNTEBhNYfw==","extGuestTotal":"0","roomTotal":"610","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"610"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":",Breakfast, ","roomTypeCode":"0000029636","ratePlanCode":"0000116334","ratebands":{"validdays":"1111111","wsKey":"LW0JRiZ\/ggg6eaST9cxEwP3j24PDD33ixDrDapJ4gq4dtpsPSH10aMQ6w2qSeIKuiUmRICkEjmNwBQ+MCBeHvL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYaTAl2rDeluemkoG39722ycQ6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAzxDrDapJ4gq7nVJkcQf5tLp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysEbvEu8oHovIf+Ek7m8I1WOQ+x0ftDO6mZj\/9TK3zkwEmnFUmX8MNPXu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHqgTCwHTBIpkg==","extGuestTotal":"0","roomTotal":"1246","servicetaxTotal":"423","discount":"0.0","commission":"0","originalRoomTotal":"1246"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00006493","hotelname":"Hotel Viren International","hoteldesc":"This 2Star property at Baluganj is maintained with 20 guestrooms, categorized into Super Deluxe A\/C and Non- A\/C and Deluxe- Non- A\/C rooms, which are provided with 24hours room service. A scrumptious fare is offered at the in-house restaurant. The property is maintained with a banquet venue and meeting rooms for official purpose. The travel counter organizes tour of the city and assists in other travel plans. Guests can also avail the beauty services and enjoy a make over.","starrating":"2","noofrooms":"1","minRate":"590","rph":"22","webService":"arzooB","contactinfo":{"address":"4\/144  near petrol pump, Baluganj,, Agra,UP, Balu Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Balu Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/qyi\/mbq\/HO.jpg"}},"geoCode":"27.166816,78.015697"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Double Bed Non A\/C Room Only","roombasis":"No Amenities","roomTypeCode":"0000024317","ratePlanCode":"0000143016","ratebands":{"validdays":"1111111","wsKey":"2lBCp1J6b0XGHJZzEBm8Bjp5pJP1zETADml0U5ncn3eBQMcZlK3+sA5pdFOZ3J93kkNFyfwKTg+aT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfR6rCAA44Hsl0mvK\/huU5Llwkgbc9sKlRoVEbYNlCdR+vpc4Pgi4QldI0kqzTn0R2SrrkswfU4tHGCb7j9Hzi7e+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIhv1\/BCtn37mucd0Jt4tG4mmwcWktVyGhtSh22OpgyjN5Atkpv5ODgAMHjlG4I7MKBdEUzSDHYepOSRWqAhZjq1Q==","extGuestTotal":"0","roomTotal":"626","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"626"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Three Bed Non A\/C Room Only","roombasis":"No Amenities","roomTypeCode":"0000024318","ratePlanCode":"0000222912","ratebands":{"validdays":"1111111","wsKey":"ZAcs4Uu\/jtPGHJZzEBm8Bjp5pJP1zETADml0U5ncn3eBQMcZlK3+sA5pdFOZ3J93nEUq0DDuPfmaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfR6rCAA44Hsl0mvK\/huU5Llwkgbc9sKlRoVEbYNlCdR+vpc4Pgi4QldI0kqzTn0R2SrrkswfU4tHGCb7j9Hzi7e+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIhipRPFTAkkBrDGfiFB1hj9VjsL+4g\/DTOf+q\/\/uewjnFAtkpv5ODgAMHjlG4I7MKBdEUzSDHYepOCRGoEkzf8Sw==","extGuestTotal":"0","roomTotal":"764","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"764"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Double AC Room","roombasis":"No Amenities","roomTypeCode":"0000094023","ratePlanCode":"0000370860","ratebands":{"validdays":"1111111","wsKey":"49z\/0GXxw0bGHJZzEBm8Bjp5pJP1zETADml0U5ncn3eBQMcZlK3+sA5pdFOZ3J93\/YX2b9ClMNWaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfR6rCAA44Hsl0mvK\/huU5Llwkgbc9sKlRoVEbYNlCdR+vpc4Pgi4QldI0kqzTn0R2SrrkswfU4tHGCb7j9Hzi7e+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIh4G0\/NHXj7FC5jPTDfhbJFPTqccCNXopchdX0cXdHQgtAtkpv5ODgAMHjlG4I7MKBdEUzSDHYepMlTBbkDZATmw==","extGuestTotal":"0","roomTotal":"954","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"954"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00005922","hotelname":"Hotel Sanjay","hoteldesc":"Located close to the famous Agra Fort, this modest property is easily accessible and is close to major attractions including banks, shopping malls, restaurants, airport, and railway station. There are standard double rooms for accommodations across its three floors. Guests can stay connected with family and friends by availing the Internet facility, which is on surcharge. Airport transportation is also provided at an additional cost.","starrating":"1","noofrooms":"1","minRate":"600","rph":"12","webService":"arzooB","contactinfo":{"address":"Near S. R. Hospital, , Namner Agra, Namner, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Namner","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/ryd\/fbp\/HO.jpg"}},"geoCode":"27.16863,78.007167"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Double Room","roombasis":",Taxes, ","roomTypeCode":"0000022133","ratePlanCode":"0001085076","ratebands":{"validdays":"1111111","wsKey":"aB+ArFeU0gfGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cwxlW6GPfrGw5pdFOZ3J93pv2Y8RT8lV+aT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfxxDGBb8p\/QGHKrd+nSsxwwou8ZiEaBh8xrurCudGH\/BP9ihJGIMV7VqliTQwLX0rRUr\/sSg4R8o2QRFrTwoqwUVK\/7EoOEfKNkERa08KKsGl+GsJHqKhuNp0fZ10Sjocb69zQgIqVPOJfph\/HZPsiVGxIadAOkcJXTml0IQUsYPmXeO3\/lRUOHUwd4aAdSSzmQXw1cmmnMP6AXJVD1bGU6Y8DwPwaGwbn4KACQv3zPZ\/2QxKzkKQjO77vKeHzGt7xDrDapJ4gq4OONbvuHIwenn\/KlYmSRXw","extGuestTotal":"0","roomTotal":"636","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"636"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Economy Room","roombasis":"No Amenities","roomTypeCode":"0000025521","ratePlanCode":"0000094939","ratebands":{"validdays":"1111111","wsKey":"DTaA9e8eurvGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cwxlW6GPfrGw5pdFOZ3J93pnWdLJy+3q6aT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfxxDGBb8p\/QGHKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvrieCfAvyAuScJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbYY8JWF2LTiaMPLWUMDieiKiFB5DvxW+NE\/wwmw6QbjIMHjlG4I7MKBayEyj9KbhxGqp8tN23vC5L\/vraucHkwqcLw8aAiL9Lo=","extGuestTotal":"0","roomTotal":"742","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"742"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000035287","ratePlanCode":"0000138566","ratebands":{"validdays":"1111111","wsKey":"tBFAlbT3LYrGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cwxlW6GPfrGw5pdFOZ3J93zYTfoYeeETCaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfxxDGBb8p\/QGHKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvrieCfAvyAuScJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbZneFq2Mhf61dADwU6eU9IayatR90VUbndd3G7JhD5sKh5SaOnHFSe\/3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuAehDS\/MAhOU=","extGuestTotal":"0","roomTotal":"1007","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"1007"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00009172","hotelname":"Hotel Safari","hoteldesc":"Hotel Safari is situated at a birds eye view from one of the seven wonders of the world- Taj Mahal and just 3 kilometers from the Agra Fort. There are 27 guestrooms with an attached bathroom facility available to choose from. Providing pleasant ambience and mouth-watering delicacies is the in-house restaurant. Guest can explore the beautiful city of India- Agra and enjoy a perfect and relaxed holiday with their stay at Hotel Safari.","starrating":"2","noofrooms":"1","minRate":"600","rph":"25","webService":"arzooB","contactinfo":{"address":"Shaheed Nagar, ShamshaBad Road, Opposite to upadhya hospital, Shamshabad, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Shamshabad","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/maw\/nyl\/kbp\/HO.jpg"}},"geoCode":"27.154261,78.034471"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000035119","ratePlanCode":"0001085128","ratebands":{"validdays":"1111111","wsKey":"aB+ArFeU0gfGHJZzEBm8Bjp5pJP1zETADml0U5ncn3f2bc2VKBMIUw5pdFOZ3J93k5bXWETIXmaaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfA\/Z9i8A5u+2HKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvuZrMIpmmYxccJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbZ6vOIXanhQS2Q5oOVMvpmMvA\/FtjBKZ613a5Nn3rHJFcHjlG4I7MKBayEyj9KbhxGqp8tN23vC5H293oLICxKFcLw8aAiL9Lo=","extGuestTotal":"0","roomTotal":"636","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"636"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Non Ac","roombasis":",Complimentary Wi-Fi Internet, ,20% Discount on Food and Beverages, Free room upgrade subject to availability, ","roomTypeCode":"0000035119","ratePlanCode":"0000138170","ratebands":{"validdays":"1111111","wsKey":"04G+fsV4Gc3GHJZzEBm8Bjp5pJP1zETADml0U5ncn3f2bc2VKBMIUw5pdFOZ3J93k5bXWETIXmaaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfA\/Z9i8A5u+2HKrd+nSsxwwou8ZiEaBh8PZhhtrMPHqmkJFFWuf9bCGnJLYw1O2xZ29SpYY5AVKtO23dasxgKi2xsDogFQrz6I8bvCGgz9BzbDF1KH9z8Tr6RRJXCy37NtywAElukq6ATtIzDy+7kpjeRSxwOpj1OWulPUdyTtk5P6HWFnxDnP\/ttG+mjHYRHjkpEfRDFU28ygYBskbu5j5VoTNIK59FiMoGAbJG7uY+VaEzSCufRYvdd0qz+\/eV8Q4fhYw3gh0YSV9dtTZ4iysZpg4DnJqIcmoaqmgc0\/k8wf+IA4CTtS06o7RAJO8Xdw\/sbkZ9PmBvv+8WT52htfHcTCSDyKyr5+esjjrnK35nQA8FOnlPSGkDjn54y8FwSDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu21iSCRvU\/oxc=","extGuestTotal":"0","roomTotal":"859","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"859"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Ac","roombasis":",Complimentary Wi-Fi Internet, ,20% Discount on Food and Beverages, Free room upgrade subject to availability, ","roomTypeCode":"0000035118","ratePlanCode":"0000138169","ratebands":{"validdays":"1111111","wsKey":"NXG+niP2E9w6eaST9cxEwCQqw0Haa\/efxDrDapJ4gq5rdBW3QKP7CsQ6w2qSeIKufxrpvZx2OhytOLJx535O1r0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYvwP4BAwYJAwATE4ay2K0WnW4KP2G\/w4Cpg+qJq1akf\/sA06xRodeXhQZIeGfCqywGzX\/WZ5SKW\/EE6QahFW00sT30oekeL4LyWtskExlNeZVGL4Uqisuo4bPsOEOSK\/GWs5zWR22p3SX\/5131tfAGSozUYjVaNuwjohjH9fRZNfCgaJvJ0BxctaAQwbZfus9Pg+QCZ+vjf30a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfd1ciyI+GVprkcJ1QcdikvX\/5AgXGLWzNuSU9SQv6etyhweOUbgjswoFrITKP0puHEaqny03be8LkcJ1QcdikvX\/zQO22BdNJIw==","extGuestTotal":"0","roomTotal":"1590","servicetaxTotal":"540","discount":"0.0","commission":"0","originalRoomTotal":"1590"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00011868","hotelname":"Hotel Prem Sagar","hoteldesc":"Tactically located and easily accessible just a kilometer from the Agra fort bus stand, airport and Agra Cantt railway station is Hotel Prem Sagar. The child friendly hotel in Agra houses 37 well appointed rooms across 3 floors. Guest amenities include a well informed front desk, breakfast services, a travel counter to help tour the place, free and secure parking and power backup facility.","starrating":"0","noofrooms":"1","minRate":"638","rph":"29","webService":"arzooB","contactinfo":{"address":"264, Station Road, agra Cantt, Agra Cantontment, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Agra Cantontment","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/vye\/jbv\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Non - AC Double Room- Special Rate","roombasis":"No Amenities","roomTypeCode":"0000187288","ratePlanCode":"0000829945","ratebands":{"validdays":"1111111","wsKey":"HpIpfFpKSTHGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cEAt6+f\/ytfg5pdFOZ3J93awxUR\/28fPaaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfBDOMVZKTzd6JV8zyx9Ro5+jJb+jq3Wi8SAS\/psz+s\/vtFOZDhaz6EtGf96t\/LsuWxw1Qqnbhmf1FSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLNMJ4lBGJMZj+S6ZAyYJYyXcLWAdDVSwtSJWO7II1iVdSsbXgdzOSZiQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTDkYV7zMEaCg=","extGuestTotal":"0","roomTotal":"677","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"677"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Non - AC Double Room","roombasis":"No Amenities","roomTypeCode":"0000187288","ratePlanCode":"0000695924","ratebands":{"validdays":"1111111","wsKey":"3ZG58+sVtDLGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cEAt6+f\/ytfg5pdFOZ3J93awxUR\/28fPaaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfBDOMVZKTzd6JV8zyx9Ro5+jJb+jq3Wi8SAS\/psz+s\/vtFOZDhaz6EtGf96t\/LsuWxw1Qqnbhmf1FSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLPeZVvmmynhocmlBO7\/CdRnHlCEyLiD7bGvbqnn9744gnFySqn1IYOSQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTAFssDng05jQ=","extGuestTotal":"0","roomTotal":"712","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"712"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/C Double Room","roombasis":"No Amenities","roomTypeCode":"0000187289","ratePlanCode":"0000695934","ratebands":{"validdays":"1111111","wsKey":"eKor9EQkcFk6eaST9cxEwL0FLc2s8c\/nweOUbgjswoEO4GsMYjRkGGQ5oOVMvpmMUsZa7ZItDD9TnvJkgFZ+CnWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OF0AjKA2cXvojP9bcMEMj51xrKeSEqjVOYcqt36dKzHDCi7xmIRoGHz0ZtQeC\/eubjwtDNrGuC++T7k+js17KUhwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9thLtKJpZLRGrNi2\/fh6H+B4yqe6mEPVL+5\/kcrxMhiqvVf\/cm0eUt+cbAUyUDHRst6rdZ1JTSIubM9n0Dni017oCiR97cNEMsg==","extGuestTotal":"0","roomTotal":"2650","servicetaxTotal":"1350","discount":"0.0","commission":"0","originalRoomTotal":"2650"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00007355","hotelname":"Hotel Sarwan","hoteldesc":"This simple hotel located at Agra Cantonment area is maintained with standard double non\/air-conditioned and air-conditioned rooms. Annpurna Restaurant serves scrumptious local cuisine to the guests between 7a.m to 10.30p.m. High-speed Internet access to stay connected and travel counter for travel assistance is available in this property. There is also free parking facility for private parking.","starrating":"0","noofrooms":"1","minRate":"699","rph":"56","webService":"arzooB","contactinfo":{"address":"88A\/1,Sultanpura Crossing, Agra cant, Agra Cantontment, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Agra Cantontment","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyj\/ibs\/HO.jpg"}},"geoCode":"27.157692,77.997748"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Rom Non-AC","roombasis":"No Amenities","roomTypeCode":"0000028204","ratePlanCode":"0000105220","ratebands":{"validdays":"1111111","wsKey":"Rk\/nnphFvsDGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cRJ8wcowfLZw5pdFOZ3J93QRu3Mb0RqBKaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfWWXMEWqDyyaHKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvk+5Po7NeylIcJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbaokBnQlKhRMMHjlG4I7MKB7P3g5TPYu+p9vd6CyAsSha\/Wpbr5AtPN3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuKullNAdPAL0=","extGuestTotal":"0","roomTotal":"741","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"741"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room Non A\/C","roombasis":",10% Discount on Food and Beverages, ","roomTypeCode":"0000028202","ratePlanCode":"0000142955","ratebands":{"validdays":"1111111","wsKey":"hggn4jZPFUzGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cRJ8wcowfLZw5pdFOZ3J93JWEJ4miYuFqaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfWWXMEWqDyyaHKrd+nSsxwwou8ZiEaBh8CAa\/ib2uzBhj1inhGnKPrzingXJha0XLzB7BY9zrYJ6kYIL4JMQHIZpPbNVM+j22xDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq5ApofH3k8O3783C5wVmVXWJknDEcMqY+xTtVTFhgUz+JVoTNIK59Fi3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuc88TfWqCjPw=","extGuestTotal":"0","roomTotal":"848","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"848"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000028203","ratePlanCode":"0000292005","ratebands":{"validdays":"1111111","wsKey":"RrtYrY2RrSQ6eaST9cxEwEkOHicbZWY9xDrDapJ4gq6MkLfKjx2gbsQ6w2qSeIKuEkXQR7fex62Fzgpo3FA\/r70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYtAhSYDYfM2U7\/WH6wkUyK3W4KP2G\/w4CUyMQmuktpxRT61XX\/g+\/bEOH4WMN4IdGlrUmsmJzbEoygYBskbu5j5VoTNIK59FiMoGAbJG7uY+VaEzSCufRYvdd0qz+\/eV8Q4fhYw3gh0YSV9dtTZ4iysZpg4DnJqIcmoaqmgc0\/k8wf+IA4CTtS06o7RAJO8Xdw\/sbkZ9PmBvTTIWZy8BqKpc0OfMae2AdYqdp4hbgLEKfgoAJC\/fM9ptgsP2WOVBIQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTK9ctH6EKTwQX3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"1166","servicetaxTotal":"396","discount":"0.0","commission":"0","originalRoomTotal":"1166"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00007353","hotelname":"Hotel Saniya Palace","hoteldesc":"This simple hotel at Taj Ganj is located near to the Agra Fort. Guests can choose to stay in any of the 12 guestrooms, categorized into standard and deluxe rooms. The well planted roof-top restaurant serving the scrumptious delights is a feast to the eyes too with the breathtaking view of the Taj Mahal monument. High-speed\/wireless Internet facility can be availed by the guests, who wish to stay connected to family and friends. There is also a travel desk and free and secure parking space.","starrating":"0","noofrooms":"1","minRate":"778","rph":"57","webService":"arzooB","contactinfo":{"address":"Chowk Kagjiyan,South Gate, Taj ganj,Agra, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyj\/ibq\/HO.jpg"}},"geoCode":"27.168847,78.043357"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Non Ac Room","roombasis":",10% Discount on Food and Beverages, ","roomTypeCode":"0000028197","ratePlanCode":"0000143225","ratebands":{"validdays":"1111111","wsKey":"ey2TNl8XSwrGHJZzEBm8Bjp5pJP1zETADml0U5ncn3dLMnVNSiWLkg5pdFOZ3J93xUT7EYMidLCaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvffleuWsyNXDQlMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCQTcidff3E4F2xsDogFQrz6I8bvCGgz9BzbDF1KH9z8TrRO69EO0ubiT7k+js17KUhwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tubjhV6rgOtEmP\/1MrfOTASIYgpY1N515r\/vraucHkwqjM3snvabOUvfF294T0zOQXizl9lkmeuTxDrDapJ4gq4oe0PC64XHbw==","extGuestTotal":"0","roomTotal":"825","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"825"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00000517","hotelname":"Hotel Chanakya","hoteldesc":"Located near All India Radio Station, this budget hotel offers 35 rooms, and is designed with a terrace, which offers the breathtaking view of the Taj Mahal. An in-house multi-cuisine restaurant serves Mughlai, Continental, Chinese, Indian Thali, and traditional, local cuisine to its diners. De-stress with the sauna and steam bath facility at the massage center and and get a makeover at the beauty parlor. A corporate traveler can also host meetings in any of the conference rooms.","starrating":"3","noofrooms":"1","minRate":"780","rph":"1","webService":"arzooB","contactinfo":{"address":"Shahid Nagar Crossing, Shamshabad Road, Shaid Nagar Crossing, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Shaid Nagar Crossing","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/wyh\/ebu\/HO.jpg"}},"geoCode":"27.156587,78.034538"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Non A\/c Standard Double Room","roombasis":"No Amenities","roomTypeCode":"0000191900","ratePlanCode":"0000708362","ratebands":{"validdays":"1111111","wsKey":"Yr7dtEDRCITGHJZzEBm8Bjp5pJP1zETADml0U5ncn3fHLAbY0tZ6Ag5pdFOZ3J93XPsxCz6pVquaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfIwFcJgOOEy3EOsNqkniCruj6ZNSAiHLW1Mn6wbBkF9ZRgWt1GHH6E8Q6w2qSeIKuFluz6IhIkl6fh2hpeEsls8HjlG4I7MKBn4doaXhLJbPB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rB+UtB2sNr9ZMDexY4FMC4Y487th5vvqRXEOsNqkniCrmK+3bRA0QiEGwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6jwZaIr7CH0A=","extGuestTotal":"0","roomTotal":"827","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"827"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/c Standard Double Room","roombasis":"No Amenities","roomTypeCode":"0000191902","ratePlanCode":"0000708368","ratebands":{"validdays":"1111111","wsKey":"JDzsEmgGR0g6eaST9cxEwAfRIMGT9bNc+X4fb+MTW93kYNDXzSJ14eVGy\/RuuWEiHhOMXO35zbRYimnB0y9vt2MLLQ2VdKnzmk9s1Uz6PbbEOsNqkniCrsHjlG4I7MKBayEyj9KbhxGqp8tN23vC5C3n0ClSGOC1nF50UBr98ITvUdF5rORZ\/S4PIoGVYHEX4lfZcQ1awCSEUZVbZ6Mwy1xk5Ny0pJvL2YCXVcDmS5k8hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QGipMI83KrYhxDrDapJ4gq5brKM\/MaSyy7CwSilr9qhXaeidhWMeVCXfF294T0zOQXizl9lkmeuTxDrDapJ4gq78ImBNTySdDw==","extGuestTotal":"0","roomTotal":"1718","servicetaxTotal":"584","discount":"0.0","commission":"0","originalRoomTotal":"1718"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/c Executive Double  Room- Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000191915","ratePlanCode":"0000708389","ratebands":{"validdays":"1111111","wsKey":"C93YZkcA3Sw6eaST9cxEwJdXiTM5SrcSkUuN3t8QWVSIbTrnFgLK7026CiAKBKXJuXdNOfNR2RlfYkOs2FbWx+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKujcVkk6z0ENDZz7ZQWTpq0Icqt36dKzHDCi7xmIRoGHxkf\/7tWw7snOXyVP39zzDGcFsJFu\/4gTpwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tsxgqgsFqi0+w8tZQwOJ6IqVU\/7X+k1MgcQ6w2qSeIKuC93YZkcA3SwO5+o7yVi2fIf0qOkcKwo5\/C137Alaq7b8ITYKn+pxBA==","extGuestTotal":"0","roomTotal":"1780","servicetaxTotal":"605","discount":"0.0","commission":"0","originalRoomTotal":"1780"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/c Deluxe Double Room- Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000191910","ratePlanCode":"0000708385","ratebands":{"validdays":"1111111","wsKey":"C93YZkcA3Sw6eaST9cxEwJdXiTM5SrcSkUuN3t8QWVSIbTrnFgLK7026CiAKBKXJuXdNOfNR2RmlK8PL6mbIf+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKujcVkk6z0ENDZz7ZQWTpq0Icqt36dKzHDCi7xmIRoGHxkf\/7tWw7snOXyVP39zzDGcFsJFu\/4gTpwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tsxgqgsFqi0+mP\/1MrfOTASVU\/7X+k1MgcQ6w2qSeIKuC93YZkcA3SwO5+o7yVi2fIf0qOkcKwo5\/C137Alaq7b8ITYKn+pxBA==","extGuestTotal":"0","roomTotal":"1780","servicetaxTotal":"605","discount":"0.0","commission":"0","originalRoomTotal":"1780"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00012254","hotelname":"Hotel Gayatri Palace","hoteldesc":"Hotel Gayatri Palace is a child friendly hotel located in the beautiful city of Agra at a decent distance from the Taj Mahal. The child friendly hotel offers 11 well appointed rooms for accommodation with a restaurant serving gourmet meals and sip coffee at the cafe. Guest amenities include a well informed front desk service, breakfast services, a travel counter to help tour the place, wireless internet access, free parking and power backup facility.","starrating":"0","noofrooms":"1","minRate":"800","rph":"42","webService":"arzooB","contactinfo":{"address":"Near Police Chowki, Taj Phase -1, Fatehabad Road, Agra, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyk\/ibr\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Non - AC Room","roombasis":"No Amenities","roomTypeCode":"0000187461","ratePlanCode":"0000696188","ratebands":{"validdays":"1111111","wsKey":"hggn4jZPFUzGHJZzEBm8Bjp5pJP1zETADml0U5ncn3cxFExowjtA2Q5pdFOZ3J93TUM5fLuxejmaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfXO5SrFB0RWZJguUFn2qdL4cqt36dKzHDCi7xmIRoGHz0ZtQeC\/eubjwtDNrGuC++T7k+js17KUhwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9thlk9gv9O5InZDmg5Uy+mYw2eeZsUVC12iGfP8gsbIxRweOUbgjswoFrITKP0puHEaqny03be8LkU7VUxYYFM\/hwvDxoCIv0ug==","extGuestTotal":"0","roomTotal":"848","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"848"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00012250","hotelname":"Hotel Taj Prince","hoteldesc":"Hotel Taj Prince is pleasingly structured budget hotel in Agra. This two floored hotel boasts of nicely furnished deluxe and super deluxe rooms. Smokers are offered with smoking rooms as well. Guests are been provided with modern hotel facilities like breakfast services, Wi-Fi facility, free parking space and backup generator for nonstop power supply. A multi-cuisine restaurant is nicely outfitted within this premise to enjoy scrumptious meals.","starrating":"0","noofrooms":"1","minRate":"999","rph":"40","webService":"arzooB","contactinfo":{"address":"Taj Nagari Phase -1, Near Police Chowki, Fatehabad Road,Agra, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/uyk\/ibn\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room Only","roombasis":"No Amenities","roomTypeCode":"0000172961","ratePlanCode":"0000576156","ratebands":{"validdays":"1111111","wsKey":"2Vrp77++CxTGHJZzEBm8Bjp5pJP1zETADml0U5ncn3dvjudn6wDrxw5pdFOZ3J93b\/Nh81u8QiuaT2zVTPo9tp+CgAkL98z2xw1Qqnbhmf3GHJZzEBm8Bg7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrts4HpwZO8CvfnZN\/XLM5EpnwRgyOSUT3BujJb+jq3Wi8SAS\/psz+s\/vtFOZDhaz6EtGf96t\/LsuWxw1Qqnbhmf1FSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLOsk4meyOWXcM71UjA9C6fOaTCzHnABuL0geupme\/8MEdFh5uaZuPnocJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NX3DzilNz9olAX3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"1059","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"1059"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Super Deluxe Room Only","roombasis":"No Amenities","roomTypeCode":"0000172962","ratePlanCode":"0000576157","ratebands":{"validdays":"1111111","wsKey":"RrtYrY2RrSQ6eaST9cxEwEkOHicbZWY9xDrDapJ4gq6dsKIi4Wbj4sQ6w2qSeIKuXloR16GVhXj+NdAteElykL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfY10PDM6ldHI3mX+nIoC5FRC4PIoGVYHEX4lfZcQ1awCSEUZVbZ6Mwy1xk5Ny0pJvLD59NmCHWe6k8hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QJ61c52T+znhxDrDapJ4gq5\/yqW+m8SMc+KFJtzzv6YElWhM0grn0WLfF294T0zOQXizl9lkmeuTxDrDapJ4gq4AhTPCPAUsRQ==","extGuestTotal":"0","roomTotal":"1166","servicetaxTotal":"396","discount":"0.0","commission":"0","originalRoomTotal":"1166"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00001867","hotelname":"Hotel Taj Plaza","hoteldesc":"A 2 star property, Hotel Taj Plaza is located just 600 meters from the Taj Mahal. The hotel is situated in front of the Taj Nature Walk which is famous for wild life, natural beauty, peace and a pollution-free area. There are  twenty-four rooms with the view of the Taj Mahal. The hotel is 8 kms from the Railway Station, 12 kms from the Airport, and 5 kms from the City Centre.","starrating":"2","noofrooms":"1","minRate":"1200","rph":"67","webService":"arzooB","contactinfo":{"address":"Taj Mahal Eastern Gate, VIP Road, Shilp Gram, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Shilp Gram","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/vye\/jbu\/HO.jpg"}},"geoCode":"27.167511,78.051008"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Non Ac Room","roombasis":",Complimentary Wi-Fi Internet, ,All Taxes to be directly paid to the hotel by the customer on Check-in only., ","roomTypeCode":"0000117035","ratePlanCode":"0000433423","ratebands":{"validdays":"1111111","wsKey":"PUhP5KWwKvjGHJZzEBm8Bjp5pJP1zETADml0U5ncn3dJp5ElNlV\/wQ5pdFOZ3J937\/3hKwDG1iBwnVBx2KS9f\/vsB4\/FS5DzOnmkk\/XMRMAPn02YIdZ7qUC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k9s2olbsY76IjTlZzQCRoBPnb1AeydyYyqFRG2DZQnUfj2XIIFojrz9HhTfeTSaiEFIPSojSPgLPaouWdXbk+O\/PNO2OV1F8\/2Kzl6VxmLkzRXXOyFCbeasFsFH1jc\/RXFUUccza0yeFwHs046o9rfOSV+BDFuIlU5pJBYNxhfOZJ0vRFqek204MUkOELKyBIJpOeiC17mcckTtnIK786l48hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QJmUneb+6eW\/xDrDapJ4gq4s8J5lgd\/+uzyGzIii41fjlWhM0grn0WLfF294T0zOQXizl9lkmeuTxDrDapJ4gq7fne+ZPMdHHg==","extGuestTotal":"0","roomTotal":"1200","servicetaxTotal":"0","discount":"182.0","commission":"0","originalRoomTotal":"1273"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Non Ac Room- With Breakfast and Wifi","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000117035","ratePlanCode":"0000433430","ratebands":{"validdays":"1111111","wsKey":"JgkCr64exJM6eaST9cxEwIqKWfCWRplExDrDapJ4gq5Q3TsNDwy5ZMQ6w2qSeIKuCE0L7DG\/7QCcBPkAeeFpf1pGsaJmrAXhmk9s1Uz6PbbEOsNqkniCrsHjlG4I7MKBayEyj9KbhxGqp8tN23vC5C3n0ClSGOC1smIXcJQr3MVdc9MNYQekX4cqt36dKzHDCi7xmIRoGHw9mGG2sw8eqaQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq11bIANw9BN\/CbLz+S50pYQ+D5AJn6+N\/fRrjn3GwXyWmdctwarUJzf0a459xsF8lpnXLcGq1Cc3yEM0Qk19qbmXdjWlec+xEzs9dTjf\/KlqXTml0IQUsYO4fkV3EPlVAQ81ZcQ8uSy0xDrDapJ4gq5tslTdkdt\/zQ5pdFOZ3J937OkWMeEJEVNwnVBx2KS9f6uNtikdJex3v5FzVjT4RUjB45RuCOzCgWshMo\/Sm4cRqqfLTdt7wuSf5HK8TIYqry30Kptf4gHB","extGuestTotal":"0","roomTotal":"2000","servicetaxTotal":"576","discount":"304.0","commission":"0","originalRoomTotal":"2120"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000117036","ratePlanCode":"0000433424","ratebands":{"validdays":"1111111","wsKey":"D5Nq5WDW9+s6eaST9cxEwGetjHbTMqSpV\/Tfi6vKp3gVagvcJWkWzMcmac3579puOxrlJgSXpQTVoUl+PIbUdFxbtZgH1NQDweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6RTkPHi1FCouLPZaE4V2ofsQ6w2qSeIKu6Ppk1ICIctZTSHh5\/nvX3kZBq\/HJJBljQBN\/pfSMYdYDIozQjcGj7DcRP7FXMnaRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIVsw03xQ4ivEd\/ULRo6OVCRQiR3A5A7jDrTWx4TH9ZsnxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U41CsSNxrP0JVwvDxoCIv0ug==","extGuestTotal":"0","roomTotal":"2200","servicetaxTotal":"634","discount":"334.0","commission":"0","originalRoomTotal":"2333"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room With Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000117036","ratePlanCode":"0001000694","ratebands":{"validdays":"1111111","wsKey":"8kXj58VHImU6eaST9cxEwHFFadJi8g9gV\/Tfi6vKp3gVagvcJWkWzMcmac3579puOxrlJgSXpQTVoUl+PIbUdHhVvp23sxrdweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6RTkPHi1FCouLPZaE4V2ofsQ6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAzxDrDapJ4gq7nVJkcQf5tLp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysGGtP87JZppvu2Zj11BT5Khmq9qjisSZ2NADwU6eU9IauPghCpJVUAru+7ynh8xre8Q6w2qSeIKuDjjW77hyMHqE7iCNGTWKow==","extGuestTotal":"0","roomTotal":"2700","servicetaxTotal":"778","discount":"410.0","commission":"0","originalRoomTotal":"2863"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Non Ac Room- With Breakfast,Dinner and Wifi","roombasis":",Breakfast and  Dinner, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000117035","ratePlanCode":"0000433431","ratebands":{"validdays":"1111111","wsKey":"8kXj58VHImU6eaST9cxEwHFFadJi8g9gV\/Tfi6vKp3gVagvcJWkWzMcmac3579puOxrlJgSXpQR1F7KCsxAaHXhVvp23sxrdweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6RTkPHi1FCouLPZaE4V2ofsQ6w2qSeIKu6Ppk1ICIctad9MAPXtHZjhZ5xLYz0Xw0EoATWVQU0gCmKFpf5tlRG1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE\/2KEkYgxXt9unYQjwKmoRFSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLObBYEQK7zdOh\/VhgBv4TgD05UtwgX70BUGhsnTkrfjT+lq633EL7ifcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXjpNXGB+oIqNFakgJU43uJg==","extGuestTotal":"0","roomTotal":"2700","servicetaxTotal":"778","discount":"410.0","commission":"0","originalRoomTotal":"2863"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room With Breakfast & Dinner","roombasis":",Breakfast, Dinner, ","roomTypeCode":"0000117036","ratePlanCode":"0001000696","ratebands":{"validdays":"1111111","wsKey":"lHX\/2WYcjcI6eaST9cxEwO0xGvICOGibYPnG8KjvIoC5d00581HZGW1PPXuH2XyeuXdNOfNR2RmvCXvMkkP72Nj\/TQUfMNIOn4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2zgenBk7wK9\/nPR2O55Qof+JX2XENWsAkdbgo\/Yb\/DgJY613z2Hi8HSp3LsH920T3nh7nQwhR1877bRvpox2ER45KRH0QxVNvMoGAbJG7uY+VaEzSCufRYjKBgGyRu7mPlWhM0grn0WL3XdKs\/v3lfEOH4WMN4IdGElfXbU2eIsrGaYOA5yaiHJqGqpoHNP5PMH\/iAOAk7UtOqO0QCTvF3cP7G5GfT5gb4E0j2Fsx9cQbm99v8ooyCLwJVoXY3J8I+X4fb+MTW93NQGIVnfb8kUC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6kz6nwZ8T564XF9zkShsUkYU=","extGuestTotal":"0","roomTotal":"3400","servicetaxTotal":"1469","discount":"516.0","commission":"0","originalRoomTotal":"3605"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Taj Facing Room","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000117037","ratePlanCode":"0000433425","ratebands":{"validdays":"1111111","wsKey":"3n7gwFxe81E6eaST9cxEwKLjBEnLqxzu51SZHEH+bS4iFyU9Z8PAzkmK0H23KCUQCdnZcfw8MzqFjCdNcpcvkYCBLRLlvzYCxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu1pYixl+4\/8dujWKqT\/aWhfYJRQ1GQxAVunkZan8IOa+MlAnilR3DH1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE\/2KEkYgxXt9unYQjwKmoRFSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLObBYEQK7zdOpTRQBcoAMizK0M8L43s7Ke5FmdKuPhldUD6THhTRJwEcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXeP06gW0zR0NFakgJU43uJg==","extGuestTotal":"0","roomTotal":"3500","servicetaxTotal":"1512","discount":"532.0","commission":"0","originalRoomTotal":"3710"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Taj Facing Room Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000117037","ratePlanCode":"0001000698","ratebands":{"validdays":"1111111","wsKey":"xHkn\/M7h0\/Q6eaST9cxEwBczAai\/cSFyZDmg5Uy+mYwiFyU9Z8PAzkmK0H23KCUQCdnZcfw8MzqFjCdNcpcvkW\/oiKex00oxxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu1pYixl+4\/8dujWKqT\/aWhfYJRQ1GQxAVunkZan8IOa\/Wx5G2M4ADsrVNb7PWMw3fn+RyvEyGKq\/EOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCrkIUNRSCUMtufYsvEY7N4Yftpr12pP5E9sQ6w2qSeIKuuZgnxaPvEkAO5+o7yVi2fIf0qOkcKwo5\/C137Alaq7arQuCQYxSK0Q==","extGuestTotal":"0","roomTotal":"4000","servicetaxTotal":"1728","discount":"608.0","commission":"0","originalRoomTotal":"4240"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Taj Facing Room Breakfast & Dinner","roombasis":",Breakfast, Dinner, ","roomTypeCode":"0000117037","ratePlanCode":"0001000700","ratebands":{"validdays":"1111111","wsKey":"9A1Xqye1VlQ6eaST9cxEwJZWZ6E5qlD1UJDJ+P8NrZm5d00581HZGW1PPXuH2XyeuXdNOfNR2RnxJA7eVIPk\/IO6dKSjK66Xn4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2zgenBk7wK9\/nPR2O55Qof+JX2XENWsAkdbgo\/Yb\/DgJY613z2Hi8HSp3LsH920T3nh7nQwhR1877bRvpox2ER45KRH0QxVNvMoGAbJG7uY+VaEzSCufRYjKBgGyRu7mPlWhM0grn0WL3XdKs\/v3lfEOH4WMN4IdGElfXbU2eIsrGaYOA5yaiHJqGqpoHNP5PMH\/iAOAk7UtOqO0QCTvF3cP7G5GfT5gb4E0j2Fsx9cTKVX\/MSm+XdS\/aRgCMeQZSV\/Tfi6vKp3h1GqEhy2XyykC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k2uTeGtoA77qF9zkShsUkYU=","extGuestTotal":"0","roomTotal":"4700","servicetaxTotal":"2031","discount":"714.0","commission":"0","originalRoomTotal":"4983"},"discountMessage":"Promotion Offer,Save 20% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00012116","hotelname":"Hotel Ratandeep","hoteldesc":"Strategically located at a walk-able distance from the historically enchanting Taj and just a kilometer from the famous Lalquila, Hotel Ratandeep is the perfect stopover. This two-floored hotel is conveniently at a distance of 4.5 kilometers from the railway station. The guests are accommodated in 24 ethnic rooms also providing services like a travel counter and a 24 hour front desk facility.","starrating":"0","noofrooms":"1","minRate":"1150","rph":"31","webService":"arzooB","contactinfo":{"address":"Tourist Complex Area, Fatehabad Road , Agra, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/uyl\/ebt\/HO.jpg"}},"geoCode":"0.0,0.0"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Non A\/C Room","roombasis":",10% Discount on Food and Beverages, ","roomTypeCode":"0000043823","ratePlanCode":"0000171788","ratebands":{"validdays":"1111111","wsKey":"FIX90XH70OE6eaST9cxEwKR0JFwBe4Y+xDrDapJ4gq5e6gjkez9VXsQ6w2qSeIKulTDpazT7h3xM6lkmAsqmr70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYsz+kDWnw38Cyxc0q+KpFaIQj\/og\/zHPzxDrDapJ4gq5JsLR8nE0Lkqsx+ahPwN13HGy4yMVk+tHYc2OtbjNu5c7IOIpKloMuD59NmCHWe6k8hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QDfU7GurSCarxDrDapJ4gq5WrULjq7s6LOKFJtzzv6YEHlJo6ccVJ7\/fF294T0zOQXizl9lkmeuTxDrDapJ4gq51p+ibZMU7ag==","extGuestTotal":"0","roomTotal":"1219","servicetaxTotal":"414","discount":"0.0","commission":"0","originalRoomTotal":"1219"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000043822","ratePlanCode":"0000171787","ratebands":{"validdays":"1111111","wsKey":"NXG+niP2E9w6eaST9cxEwCQqw0Haa\/efxDrDapJ4gq5e6gjkez9VXsQ6w2qSeIKulTDpazT7h3xHp7mGVpq89r0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYsz+kDWnw38Cyxc0q+KpFaIQj\/og\/zHPzxDrDapJ4gq6+m9VIOMrRSWf9BWsomSQZOnmkk\/XMRMD0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfdzNYrMNSXCwtcJ1QcdikvX\/5AgXGLWzNuSU9SQv6etyhweOUbgjswoFrITKP0puHEaqny03be8LkcJ1QcdikvX\/zQO22BdNJIw==","extGuestTotal":"0","roomTotal":"1590","servicetaxTotal":"540","discount":"0.0","commission":"0","originalRoomTotal":"1590"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00012127","hotelname":"Hotel Seva","hoteldesc":"Hotel Seva is a prestigious multi facilitated hotel located a stone throw away from the famous monument depicting love which is the Taj Mahal. This hotel in Agra houses neat and clean rooms for accommodation. Guests can indulge in gourmet meals at the restaurant as well as avail breakfast services. Other amenities include wireless internet access, free parking, a travel counter to help tour the place, airport transport on a chargeable basis and an option of currency exchange.","starrating":"3","noofrooms":"1","minRate":"1150","rph":"38","webService":"arzooB","contactinfo":{"address":"4\/51, Baluganj, Behind Baluganj Petrol Pump, Agra, Balu Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Balu Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyl\/fbu\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Non A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000043850","ratePlanCode":"0000171934","ratebands":{"validdays":"1111111","wsKey":"FIX90XH70OE6eaST9cxEwKR0JFwBe4Y+xDrDapJ4gq5wKLMlCZgw+sQ6w2qSeIKulTDpazT7h3weUmjpxxUnv70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYQpl1ex+hwMD2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN+lQQ1KvLvexxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq6gwvVW3qs9vHfKb8mQgkHw4Zh08jGXEU\/EOsNqkniCrhSF\/dFx+9DhDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2WrpeF2a2mD8=","extGuestTotal":"0","roomTotal":"1219","servicetaxTotal":"414","discount":"0.0","commission":"0","originalRoomTotal":"1219"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000043851","ratePlanCode":"0000171935","ratebands":{"validdays":"1111111","wsKey":"ZX\/8nu+l8rQ6eaST9cxEwPqLxqy3lRv9xDrDapJ4gq5wKLMlCZgw+sQ6w2qSeIKulTDpazT7h3x\/ciGtANGQ8L0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYQpl1ex+hwMD2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN+lQQ1KvLvexxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq6gwvVW3qs9vJwE+QB54Wl\/lKeE6XX19dvEOsNqkniCrmV\/\/J7vpfK0DufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2YJE+eR9eKPk=","extGuestTotal":"0","roomTotal":"1537","servicetaxTotal":"522","discount":"0.0","commission":"0","originalRoomTotal":"1537"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00005886","hotelname":"Panna Paradise","hoteldesc":"This brand new hotel is located 2kms away from one of the \"Seven Wonders of the World\" - the Taj Mahal. There are 19 guestrooms with many different layouts and hi-end amenities. A multi-cuisine fare can be enjoyed at the Speise Restaurant, which is contemporary in its decor and is open between 6a.m to 11p.m. The hotel has a business center and offers banquet facility for private functions. Guests can take a tour of the historical city with the assistance of the travel counter.","starrating":"3","noofrooms":"1","minRate":"1319","rph":"5","webService":"arzooB","contactinfo":{"address":"Before Taj Nagri Crossing, , Taj Nagari Crossing, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Nagari Crossing","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/rye\/lbt\/HO.jpg"}},"geoCode":"27.157842,78.051923"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Premium Room","roombasis":",Complimentary Wi-Fi Internet, ,Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), ","roomTypeCode":"0000179611","ratePlanCode":"0000618490","ratebands":{"validdays":"1111111","wsKey":"n\/zDj3cijdc6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0CiXUzRCvG4dSyeKeLupHtAMzK9LZy0KXzEOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwevlwxhwRagjpeOEcS5kH0kNgmruTwMLvyHW4KP2G\/w4Cpg+qJq1akf\/sA06xRodeXhQZIeGfCqywGzX\/WZ5SKW8VukvsdJ1R2UZBq\/HJJBljfUF8SNEaxWRiOJ0rRz6z3F9iMBxO\/miGazQ\/E6gcWpUl2YNAKnxlMN+b04PNCvuhqrq4ZoC+NNnNUwGDiiM2Xt82Yuh8KWDIvQTYtQm9bRSO6uLcHvZtg\/9n30JFUMuDm8d+mVsUGm8\/J8RcXTrbqp0ODwkQBKreVLs6hdYf4ChonSIMUuSdcu+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIh5Mnj5jnEukC4fkV3EPlVAfn9pUJnDjjKlafNHb\/FxvLDy1lDA4noimshMo\/Sm4cRqqfLTdt7wuRwnVBx2KS9f2P7mIx1K09m","extGuestTotal":"0","roomTotal":"1399","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"1399"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Club Room Only","roombasis":"No Amenities","roomTypeCode":"0000179612","ratePlanCode":"0001044374","ratebands":{"validdays":"1111111","wsKey":"o7dAJ5qNaDQ6eaST9cxEwGNqo0thVAXer26p5\/e+OIKsk4meyOWXcEk9WJIZSBRCuXdNOfNR2RlHoxXZEU\/QkupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu5eXFY4w\/A04vkjAn9egMxocqt36dKzHDCi7xmIRoGHz0ZtQeC\/eubjwtDNrGuC++cFsJFu\/4gTpwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tgpi9jC3uKUhNi2\/fh6H+B7x6+vg7efRRMQ6w2qSeIKuo7dAJ5qNaDQO5+o7yVi2fIf0qOkcKwo5\/C137Alaq7bVhBTR+EcUXw==","extGuestTotal":"0","roomTotal":"1770","servicetaxTotal":"902","discount":"0.0","commission":"0","originalRoomTotal":"1770"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Premium Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ,Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), 20% Discount on Food and Beverages, ","roomTypeCode":"0000179611","ratePlanCode":"0000618502","ratebands":{"validdays":"1111111","wsKey":"OviJQGeJt8I6eaST9cxEwKY8DwPwaGwbl44itbNZBjusk4meyOWXcEk9WJIZSBRCuXdNOfNR2RmjEQGoozWbrOpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu5eXFY4w\/A04vkjAn9egMxocqt36dKzHDCi7xmIRoGHw9mGG2sw8eqaQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq11bIANw9BN\/Pxig\/WIqkZBGQavxySQZY31BfEjRGsVkYjidK0c+s9xfYjAcTv5ohms0PxOoHFqVJdmDQCp8ZTDfm9ODzQr7oaq6uGaAvjTZzVMBg4ojNl7fNmLofClgyL0E2LUJvW0Ujuri3B72bYP\/Z99CRVDLg5vHfplbFBpvPyfEXF0626qdDg8JEASq3vNJbgw8LvyHY9Yp4Rpyj684p4FyYWtFy8wewWPc62CepGCC+CTEByHpUENSry73scQ6w2qSeIKu1qampMlCJcrEOsNqkniCrtampqTJQiXKxDrDapJ4gq7pkacnLosJMHCdUHHYpL1\/MNWFSe5rsmlJitB9tyglEJqoUeTngQnXxmmDgOcmohzW50Duv0Njz8Q6w2qSeIKuDqhd4xYhvYAE9rtVzsSlt6LTmJ4SI9I\/Ni2\/fh6H+B7FK5cInoy8Re77vKeHzGt7xDrDapJ4gq4OONbvuHIwem91KBvaTnRt","extGuestTotal":"0","roomTotal":"1876","servicetaxTotal":"637","discount":"0.0","commission":"0","originalRoomTotal":"1876"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Royal Club Room Only","roombasis":"No Amenities","roomTypeCode":"0000179613","ratePlanCode":"0001044375","ratebands":{"validdays":"1111111","wsKey":"PyukCP9Q9Zk6eaST9cxEwGsPfdIJYKOkFi2BhF2Fq92j8slKZijCITqXmlUPHUo5o\/LJSmYowiFtk60QceLqbCI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe64x1o5BjS44IKi1pR0c+flPYJRQ1GQxAVunkZan8IOa9NGqb3bn4+dnz9g3BaVZA36VBDUq8u97HEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCroAR40VXVgiwciVjvc0dZsb+VCMjKLx3ozYtv34eh\/gebFR1YL3ZTJXu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHoe+PT+UtlOnw==","extGuestTotal":"0","roomTotal":"2141","servicetaxTotal":"1091","discount":"0.0","commission":"0","originalRoomTotal":"2141"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Club Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000179612","ratePlanCode":"0000618498","ratebands":{"validdays":"1111111","wsKey":"ZWLzj5QHYv86eaST9cxEwMCEW5kx6uBHl+hzK4x8SgGj8slKZijCITqXmlUPHUo5o\/LJSmYowiEzxkhR7RJQcSI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe64x1o5BjS44IKi1pR0c+flPYJRQ1GQxAVunkZan8IOa+MlAnilR3DH1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE62hxwXsSBHnAsNSkqvzPDZgJdVwOZLmTyGzIii41fjEpDuaJSXR\/88hsyIouNX4xKQ7miUl0f\/vdcVZCXZFyFhWE\/RZUI5cjd7ydYQyAevqORHj6pLwUZwnVBx2KS9fyFts5o68MhvSYrQfbcoJRD+k2b\/VaHX\/yyeKeLupHtAQPloUGYq+orEOsNqkniCrraLoF3uuVk\/n+RyvEyGKq+aBYAGn7pPKRsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXutS\/N0jvZycI","extGuestTotal":"0","roomTotal":"2247","servicetaxTotal":"1145","discount":"0.0","commission":"0","originalRoomTotal":"2247"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Suite Room","roombasis":"No Amenities","roomTypeCode":"0000288164","ratePlanCode":"0001044372","ratebands":{"validdays":"1111111","wsKey":"QI+1m45KPkw6eaST9cxEwMTwBH4p3L4WCYXxOKXR4maj8slKZijCITqXmlUPHUo5o\/LJSmYowiGIM75lc47p1SI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe64x1o5BjS44IKi1pR0c+flPYJRQ1GQxAVunkZan8IOa9NGqb3bn4+dnz9g3BaVZA36VBDUq8u97HEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCroAR40VXVgiw0Lw7OfIvxP6OhRR10SFr3TYtv34eh\/gerMHjyJfv2hvu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHq5J\/k3cTLafQ==","extGuestTotal":"0","roomTotal":"2512","servicetaxTotal":"1280","discount":"0.0","commission":"0","originalRoomTotal":"2512"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Royal Club with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ,Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), 20% Discount on Food and Beverages, ","roomTypeCode":"0000179613","ratePlanCode":"0000618500","ratebands":{"validdays":"1111111","wsKey":"ttbIIapE9wg6eaST9cxEwBXMq0Lygol\/rMuWsGEKY7yj8slKZijCITqXmlUPHUo5o\/LJSmYowiFtk60QceLqbCI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe64x1o5BjS44IKi1pR0c+flPYJRQ1GQxAVunkZan8IOa\/Wx5G2M4ADsq7DC8b6vsL0DFCQyv5ovHWFGIOsyV2\/QSOpNfi4GN1BdbZa9235cHbsA06xRodeXmJBQw8HEuBwGKDvFhJhioQOpqlQelqxYvH\/tSd5NaZucudSWRGyWv8X\/8vFkCED3xRkBggo1l+EmCMz44WgUOwAp7zYT1IKbCIKCu1Dt4QAyUaIpsMvFKi87qzjLNEiDIEgunCx2Mfdsdkxz9lgDz8eQHdZaZFE8hRbAs8Bb8C09eB0PSJomaLHXQID\/BTHeqLgD2+XgFV4GWXxzFor\/oPEOsNqkniCrhZbs+iISJJen4doaXhLJbPB45RuCOzCgZ+HaGl4SyWzweOUbgjswoFWp5nZ86iUG8Q6w2qSeIKuhz4xSuq4zq7lRsv0brlhIpLS+9\/t4KbjiX6Yfx2T7InULFBeohhejfflIYqwnPKwfUb7S1lTxw9V\/9ybR5S35wYNGzI9YuXm1qNVv9yoFSch0POq7gRoREC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6kzDwV5xeOyF0F9zkShsUkYU=","extGuestTotal":"0","roomTotal":"2618","servicetaxTotal":"1334","discount":"0.0","commission":"0","originalRoomTotal":"2618"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Suite Room With Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000288164","ratePlanCode":"0001044373","ratebands":{"validdays":"1111111","wsKey":"BwTp6VuewP46eaST9cxEwDqj8uJsUhCpzssD+M1rWuSj8slKZijCITqXmlUPHUo5o\/LJSmYowiGIM75lc47p1SI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe64x1o5BjS44IKi1pR0c+flPYJRQ1GQxAVunkZan8IOa\/Wx5G2M4ADsrVNb7PWMw3f6VBDUq8u97HEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCroAR40VXVgiw27DVTPDAHWh0ifENZh3TETYtv34eh\/gex6IV0pZBs0fu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHqDgy2Jhm1M2g==","extGuestTotal":"0","roomTotal":"2989","servicetaxTotal":"1523","discount":"0.0","commission":"0","originalRoomTotal":"2989"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00007354","hotelname":"Maya Hotel","hoteldesc":"Located at birds eye view from the world famous monument-Taj Mahal and close to shopping malls and fast food joints, Maya Hotel truly promises an ideal getaway. This budget hotel in Agra boasts of 12 spacious rooms to stay in. There is also an in-house restaurant to satiate your taste-buds. This multi-cuisine restaurant called Maya is open till 10 pm. If guest wish to explore the city of love- Agra, there is a travel desk to assist.","starrating":"2","noofrooms":"1","minRate":"1200","rph":"58","webService":"arzooB","contactinfo":{"address":"Purani Mandi,Fatehabad Road, AGRA, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/pyj\/ibr\/HO.jpg"}},"geoCode":"27.166393,78.037316"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Wooden Bed","roombasis":"No Amenities","roomTypeCode":"0000028200","ratePlanCode":"0000144533","ratebands":{"validdays":"1111111","wsKey":"qPPKUo2ThQc6eaST9cxEwJA0TXgowK3VxDrDapJ4gq4VHRq+3iF9F8Q6w2qSeIKuEkXQR7fex62VaEzSCufRYr0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV5ETxsRWztCvBCTDkPRb3R\/2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN5\/kcrxMhiqvxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq7MlBoJntS\/a4OT+CHyO\/OiP2x9U1JI4NzEOsNqkniCrqjzylKNk4UHDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2GwwUOdRnqs8=","extGuestTotal":"0","roomTotal":"1272","servicetaxTotal":"432","discount":"0.0","commission":"0","originalRoomTotal":"1272"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000273951","ratePlanCode":"0000990355","ratebands":{"validdays":"1111111","wsKey":"NXG+niP2E9w6eaST9cxEwCQqw0Haa\/efxDrDapJ4gq4VHRq+3iF9F8Q6w2qSeIKufmXykgKx0jJ\/ciGtANGQ8L0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV5ETxsRWztCvBCTDkPRb3R\/2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN5\/kcrxMhiqvxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq7hWs9BHa00cb83C5wVmVXWAiXhFwSq9QzEOsNqkniCrjVxvp4j9hPcDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu23yZj1L+J9oU=","extGuestTotal":"0","roomTotal":"1590","servicetaxTotal":"540","discount":"0.0","commission":"0","originalRoomTotal":"1590"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Family Room","roombasis":"No Amenities","roomTypeCode":"0000273950","ratePlanCode":"0000990354","ratebands":{"validdays":"1111111","wsKey":"W719bc0lKfk6eaST9cxEwDvSL9iGqc7oxDrDapJ4gq4VHRq+3iF9F8Q6w2qSeIKufmXykgKx0jIeUmjpxxUnv70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV5ETxsRWztCvBCTDkPRb3R\/2CUUNRkMQFbp5GWp\/CDmvTRqm925+PnZ8\/YNwWlWQN5\/kcrxMhiqvxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq7hWs9BHa00cW\/VxH9UziQNeXb\/rR0oURLEOsNqkniCrlu9fW3NJSn5DufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2\/2p3SAGPhRE=","extGuestTotal":"0","roomTotal":"2120","servicetaxTotal":"720","discount":"0.0","commission":"0","originalRoomTotal":"2120"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00011803","hotelname":"Hotel Paradise Guest House","hoteldesc":"Hotel Paradise Guest House is situated at a distance of 1.5 kms from the Taj Mahal and is at a close proximity of 5 km from the railway station. With 24 hour room service, free car parking space, local sightseeing and guide arrangements, this hotel has an awesome blend of the best hotel facilities. A sumptuous meal can be enjoyed with a panoramic view of Agra from their roof-top restaurant. A total of 15 guestrooms offer a choice of AC and non-AC accommodation at a reasonable price.","starrating":"1","noofrooms":"1","minRate":"1237","rph":"26","webService":"arzooB","contactinfo":{"address":"Near Hotel Howard Park Plaza, Fatehabad Road Agra, , Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/vye\/dbq\/HO.jpg"}},"geoCode":"0.0,0.0"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Budget Double Room","roombasis":"No Amenities","roomTypeCode":"0000043084","ratePlanCode":"0000177913","ratebands":{"validdays":"1111111","wsKey":"KlwHyH+Tk0Q6eaST9cxEwDQO9yYgkfPmIil\/dQbk+JQM3FmuprjvWdBUcjM5SjNCiG065xYCyu+1WlDSnOj67+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuXZ87RAeyEAPs1B8c4SOgpXZyBeBUwRe0sSYBERRVE8boyW\/o6t1ovEgEv6bM\/rP77RTmQ4Ws+hLRn\/erfy7LllqliTQwLX0rRUr\/sSg4R8o2QRFrTwoqwUVK\/7EoOEfKNkERa08KKsGl+GsJHqKhuNp0fZ10Sjocb69zQgIqVPOJfph\/HZPsiVGxIadAOkcJXTml0IQUsYPmXeO3\/lRUOHUwd4aAdSSzuXdNOfNR2RnBwDGeOusDOJWnzR2\/xcbyXyNPv0reOX5AR92bt6+URcQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1ODyGzIii41fjmFecqhREPWE=","extGuestTotal":"0","roomTotal":"1312","servicetaxTotal":"446","discount":"0.0","commission":"0","originalRoomTotal":"1312"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room","roombasis":"No Amenities","roomTypeCode":"0000043083","ratePlanCode":"0000177912","ratebands":{"validdays":"1111111","wsKey":"j7g9iDc7c6A6eaST9cxEwJMxK3tFtO89BR\/2zROQDWwM3FmuprjvWdBUcjM5SjNCiG065xYCyu+TaeO1z+6aO+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuXZ87RAeyEAPs1B8c4SOgpXZyBeBUwRe0sSYBERRVE8boyW\/o6t1ovEgEv6bM\/rP77RTmQ4Ws+hLRn\/erfy7LllqliTQwLX0rRUr\/sSg4R8o2QRFrTwoqwUVK\/7EoOEfKNkERa08KKsGl+GsJHqKhuNp0fZ10Sjocb69zQgIqVPOJfph\/HZPsiVGxIadAOkcJXTml0IQUsYPmXeO3\/lRUOHUwd4aAdSSzuXdNOfNR2Rkt\/f8irIwa5WKfiogVJIYAcsFRSP8E43taByrWbugp5sQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OCICQVvkT2sdyHkRTHK4FjU=","extGuestTotal":"0","roomTotal":"1576","servicetaxTotal":"535","discount":"0.0","commission":"0","originalRoomTotal":"1576"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00008001","hotelname":"Hotel Apollo","hoteldesc":"Offering state-of-the-art business center, a spa center for rejuvenation and situated less than 2 kilometers from Taj Mahal, one of the seven wonders in the world, Hotel Apollo International is truly an ideal choice for the travelers visiting Agra. There are 38 luxurious guestrooms providing all prerequisite facilities to ensure guests feel at home. Apart from spacious accommodation, you will also enjoy dining Mughlai, Chinese, South Indian and Gujarati cuisines at the air-conditioned restaurant.","starrating":"3","noofrooms":"1","minRate":"1296","rph":"19","webService":"arzooB","contactinfo":{"address":"15\/252\/1,Tourist Complex Area, , Tourist Complex, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Tourist Complex","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/oym\/dbo\/HO.jpg"}},"geoCode":"27.162365,78.039742"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Single Non AC Room","roombasis":"No Amenities","roomTypeCode":"0000054728","ratePlanCode":"0000200231","ratebands":{"validdays":"1111111","wsKey":"CYDn2eAfA\/M6eaST9cxEwNldIVM6N6Ld2ReBY1VlFSxMJ4lBGJMZj8vJGt6KJXMRiG065xYCyu+Lfy91nAhIOupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuA+h3a9xufuC99cpVbL3cm4Qj\/og\/zHPzxDrDapJ4gq6+m9VIOMrRSWf9BWsomSQZl4xRPzkjRoL0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfdyFWJNMZULWNcJ1QcdikvX\/BeVSqu\/6RiDyGzIii41fjytB1YfJlYUDfF294T0zOQXizl9lkmeuTxDrDapJ4gq6QShzgdg5XpQ==","extGuestTotal":"0","roomTotal":"1374","servicetaxTotal":"467","discount":"0.0","commission":"0","originalRoomTotal":"1374"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007324","hotelname":"Jigyasa Palace","hoteldesc":"This 2Star hotel in Fatehabad with glass exteriors is maintained with non-air-conditioned double, air-conditioned double bed and air-conditioned triple bedded rooms. An Indian cuisine can be savoured at the Zaika Restaurant, which is open fro diners between 7a.m-10.30p.m. The hotel is maintained with high-speed Internet access at an extra cost and travel counter, which offers airport transportation on surcharge. There is a spacious party hall for private events.","starrating":"2","noofrooms":"1","minRate":"1350","rph":"53","webService":"arzooB","contactinfo":{"address":"16\/1,Kishan Complex,Road Sadar Bhatti, Agra, Collectorate Road, AGRA, UTTAR PRADESH, India, Pin-208100","citywiselocation":"Collectorate Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyj\/fbr\/HO.jpg"}},"geoCode":"27.180166,78.008741"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Ac Room","roombasis":"No Amenities","roomTypeCode":"0000028054","ratePlanCode":"0000136082","ratebands":{"validdays":"1111111","wsKey":"tdvkQWLcnZg6eaST9cxEwHuWEaet4uvUxDrDapJ4gq5gBsFkbam9ssQ6w2qSeIKufwsU9\/DGfjJv1cR\/VM4kDb0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV9Gt3Gtrg6j7sUEEX188GsyaD1kf9ZL+O6FRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIZ9t\/wBjzFZ9IgJBW+RPax2QToJKXDWyxM8jvdYqrX1VxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4uH5FdxD5VQGs4y2qNXPFKw==","extGuestTotal":"0","roomTotal":"1431","servicetaxTotal":"486","discount":"0.0","commission":"0","originalRoomTotal":"1431"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Triple Bed Room","roombasis":"No Amenities","roomTypeCode":"0000309825","ratePlanCode":"0001088272","ratebands":{"validdays":"1111111","wsKey":"W2uph4uk3lA6eaST9cxEwHipjPEpsC7xxDrDapJ4gq5gBsFkbam9ssQ6w2qSeIKuX56Ei2Q2EVfecu1+JlN9p70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV9Gt3Gtrg6j7sUEEX188GsyaD1kf9ZL+O6FRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6gExd1p6AVmpQBxH3HrhhlMM6ERDpkCYNl4lvVgLSwqwEEa0rUdQCUKxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4d\/ULRo6OVCSs4y2qNXPFKw==","extGuestTotal":"0","roomTotal":"1961","servicetaxTotal":"666","discount":"0.0","commission":"0","originalRoomTotal":"1961"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Family Bed Room","roombasis":"No Amenities","roomTypeCode":"0000309823","ratePlanCode":"0001088269","ratebands":{"validdays":"1111111","wsKey":"ZTzKieO3JIE6eaST9cxEwGF2VpgHLJ4\/xDrDapJ4gq5gBsFkbam9ssQ6w2qSeIKuX56Ei2Q2EVdM6lkmAsqmr70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV9Gt3Gtrg6j7sUEEX188GsyaD1kf9ZL+O6FRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRxgm+4\/R84u3vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6gExd1p6AVmpe6pD3zdE6X8hbqIJJWQtIswFLfPtm5DJ+fRhJXBoCJHxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4cArG93+kYL2s4y2qNXPFKw==","extGuestTotal":"0","roomTotal":"2173","servicetaxTotal":"738","discount":"0.0","commission":"0","originalRoomTotal":"2173"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00012104","hotelname":"Hotel Riddhee Siddhee","hoteldesc":"In the city of eternal love, this three floored Hotel Riddhee Siddhee is the ideal place to dwell in. This hotel with three restful rooms is also equipped with facilities like tasty breakfast services, complimentary newspapers, a parking space and laundry facilities. A backup generator and medical services are available within the hotel premises for an uninterrupted stay.","starrating":"0","noofrooms":"1","minRate":"1500","rph":"32","webService":"arzooB","contactinfo":{"address":"Taj Nagri-1 Near Shilpgram, Taj Gnaj, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyl\/dbr\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room","roombasis":"No Amenities","roomTypeCode":"0000247113","ratePlanCode":"0000946713","ratebands":{"validdays":"1111111","wsKey":"9SmeQgZRF4uaT2zVTPo9tsQ6w2qSeIKuyjQBK3e+Tp\/TJtiw8FOhCpIOhcP9DZ+NrAWIFyzCG9K6Gdxvg0H4F5MkqiULu1TaweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe61ZZQg9ksiztvzcR4x80yKMMxYRlfu\/hThyq3fp0rMcMKLvGYhGgYfPRm1B4L965uPC0M2sa4L75PuT6OzXspSHCdUHHYpL1\/AeaHEazJMrtwnVBx2KS9fwHmhxGsyTK79glFDUZDEBXlCYR\/0AcewdEyF2beJ1dmDzVlxDy5LLTEOsNqkniCrqCpDMcDS+x35UbL9G65YSJHmEe62NJecppPbNVM+j227O3yUnMBjmsWW7PoiEiSXhryb71cDSoKxDrDapJ4gq41cb6eI\/YT3A7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtt8mY9S\/ifaF","extGuestTotal":"0","roomTotal":"1500","servicetaxTotal":"360","discount":"95.0","commission":"0","originalRoomTotal":"1591"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room- Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000247113","ratePlanCode":"0000946719","ratebands":{"validdays":"1111111","wsKey":"9SmeQgZRF4uaT2zVTPo9tsQ6w2qSeIKuyjQBK3e+Tp\/TJtiw8FOhCpIOhcP9DZ+NrAWIFyzCG9K6Gdxvg0H4F5MkqiULu1TaweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe61ZZQg9ksiztvzcR4x80yKMMxYRlfu\/hThyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSrXVsgA3D0E38JsvP5LnSlhDp5pJP1zETA9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3e1WV2YvCqamHCdUHHYpL1\/xx75OKE9420cSghbADHNrJVoTNIK59Fi3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKueBuFbpoHBMQ=","extGuestTotal":"0","roomTotal":"1500","servicetaxTotal":"360","discount":"95.0","commission":"0","originalRoomTotal":"1591"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room","roombasis":"No Amenities","roomTypeCode":"0000247112","ratePlanCode":"0000946712","ratebands":{"validdays":"1111111","wsKey":"9SmeQgZRF4uaT2zVTPo9tsQ6w2qSeIKuyjQBK3e+Tp\/TJtiw8FOhCpIOhcP9DZ+NrAWIFyzCG9JihFRS1Wq\/qJMkqiULu1TaweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe61ZZQg9ksiztvzcR4x80yKMMxYRlfu\/hThyq3fp0rMcMKLvGYhGgYfPRm1B4L965uPC0M2sa4L75PuT6OzXspSHCdUHHYpL1\/AeaHEazJMrtwnVBx2KS9fwHmhxGsyTK79glFDUZDEBXlCYR\/0AcewdEyF2beJ1dmDzVlxDy5LLTEOsNqkniCrqCpDMcDS+x35UbL9G65YSJHmEe62NJecppPbNVM+j227O3yUnMBjmvnVJkcQf5tLhryb71cDSoKxDrDapJ4gq41cb6eI\/YT3A7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtt8mY9S\/ifaF","extGuestTotal":"0","roomTotal":"1500","servicetaxTotal":"360","discount":"95.0","commission":"0","originalRoomTotal":"1591"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room- Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000247112","ratePlanCode":"0000946715","ratebands":{"validdays":"1111111","wsKey":"9SmeQgZRF4uaT2zVTPo9tsQ6w2qSeIKuyjQBK3e+Tp\/TJtiw8FOhCpIOhcP9DZ+NrAWIFyzCG9JihFRS1Wq\/qJMkqiULu1TaweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe61ZZQg9ksiztvzcR4x80yKMMxYRlfu\/hThyq3fp0rMcMKLvGYhGgYfGR\/\/u1bDuycvvSY7fBSl+vsA06xRodeXhQZIeGfCqywGzX\/WZ5SKW+VNFulvnqX4jp5pJP1zETA9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3eOWXA2nga32nCdUHHYpL1\/xx75OKE9420cSghbADHNrJVoTNIK59Fi3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKueBuFbpoHBMQ=","extGuestTotal":"0","roomTotal":"1500","servicetaxTotal":"360","discount":"95.0","commission":"0","originalRoomTotal":"1591"}}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00005381","hotelname":"Taj Heritage","hoteldesc":"Located just a km away from the Taj Mahal and near to the well-known showroom of Saga Emporium, this cream-coloured hotel offers well-maintained accommodations at reasonable rates and indulgent amenities. There are 22 rooms that include executive rooms with the breathtaking view of the Taj Mahal monument. An Indian cuisine is on offer at the Kalpataru Restaurant, which is open for service from 10a.m to 10p.m. The property is maintained with a secure parking space and breakfast serves for the guests.","starrating":"3","noofrooms":"1","minRate":"1398.67","rph":"6","webService":"arzooB","contactinfo":{"address":"25-30A, Fatehabad Road, , Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/ryj\/lbo\/HO.jpg"}},"geoCode":"27.157954,78.048213"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room","roombasis":",Complimentary Wi-Fi Internet, ,Mandatory Gala Dinner Charges on Festive Period Like X-Mas & New Year Eve i.e 24th & 31st December and other Occasions are directly payable to hotels., 20% Discount on Food and Beverages, ","roomTypeCode":"0000183863","ratePlanCode":"0000688307","ratebands":{"validdays":"1111111","wsKey":"G8I81DOe1X+aT2zVTPo9tsQ6w2qSeIKulCBDum\/TxGwsnini7qR7QFaxX1RUofPmLJ4p4u6ke0DQigwyGuTXdcQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6ILXrtjhdYBcHkmFKQWcBcvYJRQ1GQxAVunkZan8IOa+MlAnilR3DH1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE1hbIQc8YYWx6Ff48lz5lKrEvtVjG\/YzqMhx6n4ico5udI\/KlKWMaCJKdDxKRzNG9AKWHPnhm7ihyOMbgqiirQkKW877ECJVEfzq5MolwJRO734o7DYFZj3ZI1Z3pfIPwYWjekqe2tJjSisalqcMFewz3qJkTM3OzNxKPgk2yloWkqD\/UqZ\/pyvN9+wX\/qBbDXCj5DT+biGCjiVraAAzgNj1inhGnKPrzingXJha0XLzB7BY9zrYJ6kYIL4JMQHIelQQ1KvLvexxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq4bd5OxWYw648RrTw69Jqm6X8vnIIP+WH7Dy1lDA4noimLO4BljkB0axDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4uH5FdxD5VQEoi6W7aN1G2A==","extGuestTotal":"0","roomTotal":"1483","servicetaxTotal":"504","discount":"0.0","commission":"0","originalRoomTotal":"1483"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ,Mandatory Gala Dinner Charges on Festive Period Like X-Mas & New Year Eve i.e 24th & 31st December and other Occasions are directly payable to hotels., 20% Discount on Food and Beverages, ","roomTypeCode":"0000183863","ratePlanCode":"0000688311","ratebands":{"validdays":"1111111","wsKey":"ca1f+335A96aT2zVTPo9tsQ6w2qSeIKu6Skb+Pmn6rksnini7qR7QFaxX1RUofPmLJ4p4u6ke0DQigwyGuTXdcQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6ILXrtjhdYBcHkmFKQWcBcvYJRQ1GQxAVunkZan8IOa+MlAnilR3DH1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE62hxwXsSBHUPmqfZRyVAc86uaVf\/w+3XLxehpkLII2rGlp+h8sX0OBZW7LS4aJhPxB4CjsXSirIl4xr9jNMWhhiWv8FsQOk7\/21z99lQCcDr5FL\/TzuUT0WLlI8dCwUElfTSkG870YoQlxprq90OhNo9YAGPIAeqfGPZpAIKhLkLJX4zEtiLFTw0iwKBi+oR7TMJsl5My9pn+4Dys8ojzXMbg0zpPxxIlo0O6PH5Av8NDSYpXJijilHHKq9DqN2lYxN+b0g3blT\/YoSRiDFe21azFh8DeP3kVK\/7EoOEfKNkERa08KKsFFSv+xKDhHyjZBEWtPCirBpfhrCR6iobjadH2ddEo6HG+vc0ICKlTziX6Yfx2T7IlRsSGnQDpHCV05pdCEFLGD5l3jt\/5UVDh1MHeGgHUks95lW+abKeGhYDFAoiQaERyzlYEq6N8YUJAH5\/0xDrjkbB+q8TU6WQLmwaC8rTVJyBsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXum5FIGPo9mK+F9zkShsUkYU=","extGuestTotal":"0","roomTotal":"1907","servicetaxTotal":"648","discount":"0.0","commission":"0","originalRoomTotal":"1907"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Double Room Only","roombasis":",Complimentary Wi-Fi Internet, ,Mandatory Gala Dinner Charges on Festive Period Like X-Mas & New Year Eve i.e 24th & 31st December and other Occasions are directly payable to hotels., 20% Discount on Food and Beverages, ","roomTypeCode":"0000183865","ratePlanCode":"0000688308","ratebands":{"validdays":"1111111","wsKey":"ca1f+335A96aT2zVTPo9tsQ6w2qSeIKu6Skb+Pmn6rksnini7qR7QFaxX1RUofPmLJ4p4u6ke0DA2lQNEC7abMQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6ILXrtjhdYBcHkmFKQWcBcvYJRQ1GQxAVunkZan8IOa+MlAnilR3DH1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE1hbIQc8YYWx6Ff48lz5lKrEvtVjG\/YzqMhx6n4ico5udI\/KlKWMaCJKdDxKRzNG9AKWHPnhm7ihyOMbgqiirQkKW877ECJVEfzq5MolwJRO734o7DYFZj3ZI1Z3pfIPwYWjekqe2tJjSisalqcMFewz3qJkTM3OzNxKPgk2yloWkqD\/UqZ\/pyvN9+wX\/qBbDXCj5DT+biGCjiVraAAzgNj1inhGnKPrzingXJha0XLzB7BY9zrYJ6kYIL4JMQHIelQQ1KvLvexxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq4bd5OxWYw644DHIZKhrwqFebP6J3x33q\/Dy1lDA4noim5FIGPo9mK+xDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4puhY8G5C3ogoi6W7aN1G2A==","extGuestTotal":"0","roomTotal":"1907","servicetaxTotal":"648","discount":"0.0","commission":"0","originalRoomTotal":"1907"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Double Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ,Mandatory Gala Dinner Charges on Festive Period Like X-Mas & New Year Eve i.e 24th & 31st December and other Occasions are directly payable to hotels., 20% Discount on Food and Beverages, ","roomTypeCode":"0000183865","ratePlanCode":"0000688315","ratebands":{"validdays":"1111111","wsKey":"C9sVdO4liKaaT2zVTPo9tsQ6w2qSeIKuZzq9WVf2G5osnini7qR7QFaxX1RUofPmLJ4p4u6ke0DA2lQNEC7abMQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6ILXrtjhdYBcHkmFKQWcBcvYJRQ1GQxAVunkZan8IOa+MlAnilR3DH1l+EzePNN8APSNWfFQcs6nZmRC5A2rwVE62hxwXsSBHUPmqfZRyVAc86uaVf\/w+3XLxehpkLII2rGlp+h8sX0OBZW7LS4aJhPxB4CjsXSirIl4xr9jNMWhhiWv8FsQOk7\/21z99lQCcDr5FL\/TzuUT0WLlI8dCwUElfTSkG870YoQlxprq90OhNo9YAGPIAeqfGPZpAIKhLkLJX4zEtiLFTw0iwKBi+oR7TMJsl5My9pn+4Dys8ojzXMbg0zpPxxIlo0O6PH5Av8NDSYpXJijilHHKq9DqN2lYxN+b0g3blT\/YoSRiDFe21azFh8DeP3kVK\/7EoOEfKNkERa08KKsFFSv+xKDhHyjZBEWtPCirBpfhrCR6iobjadH2ddEo6HG+vc0ICKlTziX6Yfx2T7IlRsSGnQDpHCV05pdCEFLGD5l3jt\/5UVDh1MHeGgHUks95lW+abKeGhr7Lv5nsY9qJlV+TwLMFeUd4V3+RBKp7cCW+bFnNOhibmwaC8rTVJyBsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXuotKNM0L8UlaF9zkShsUkYU=","extGuestTotal":"0","roomTotal":"2331","servicetaxTotal":"792","discount":"0.0","commission":"0","originalRoomTotal":"2331"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00012111","hotelname":"Hotel KUMAR GRAND CASA","hoteldesc":"Truly experience the Indian hospitality at Hotel KUMAR GRAND CASA, located 220 kilometers way from Delhi. This four floored hotel set with elevator facility accommodates its guests in 35 spacious deluxe rooms. Hi-end hotel facilities like small meeting rooms, Wi-Fi facility, ATM\/banking and travel desk to walk around the incredible Agra is offered within this premise. Guests can also enjoy delicious meals in nicely structured multi-cuisine restaurant.","starrating":"0","noofrooms":"1","minRate":"1399","rph":"33","webService":"arzooB","contactinfo":{"address":"8, Bansal Nagar, Behind hotel Mansingh Palace, Fatehabad Road, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyl\/ebo\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room- Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000208235","ratePlanCode":"0000814264","ratebands":{"validdays":"1111111","wsKey":"Cybe0ATUOZk6eaST9cxEwMroJ06\/MiyFgg5Mf5eXJSPTJtiw8FOhCg6LoIL6XeItrAWIFyzCG9KIwBiU2tCnGepiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKui5miJSy+8taqqpPnlud8QuQTL5vkphIthyq3fp0rMcMKLvGYhGgYfGR\/\/u1bDuyc5fJU\/f3PMMZPuT6OzXspSHCdUHHYpL1\/AeaHEazJMrtwnVBx2KS9fwHmhxGsyTK79glFDUZDEBXlCYR\/0AcewdEyF2beJ1dmDzVlxDy5LLTEOsNqkniCrqCpDMcDS+x35UbL9G65YSJHmEe62NJecppPbNVM+j22PyRZ3wZjcXY2Lb9+Hof4HurLKoFjr16sxDrDapJ4gq4LJt7QBNQ5mQ7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtmdvD9sf3xAp","extGuestTotal":"0","roomTotal":"1483","servicetaxTotal":"504","discount":"0.0","commission":"0","originalRoomTotal":"1483"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000043806","ratePlanCode":"0000171741","ratebands":{"validdays":"1111111","wsKey":"7bcG9p98RPM6eaST9cxEwJU29NqZs0s7xDrDapJ4gq7O3oANcv3oXsQ6w2qSeIKulTDpazT7h3wx8272P68Zhr0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYY6xGdgt7wzhc07b5Vz+bKjRS\/ZwlCRLCoVEbYNlCdR+vpc4Pgi4QldI0kqzTn0R2SrrkswfU4tHGHJZzEBm8Bu+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIhn\/x686PtTxciAkFb5E9rHZBQnhI\/QYg14Vs++0q+IELEOsNqkniCrhbmFDNCStjijlEhzBvrdTgiAkFb5E9rHXC8PGgIi\/S6","extGuestTotal":"0","roomTotal":"1484","servicetaxTotal":"504","discount":"0.0","commission":"0","originalRoomTotal":"1484"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00006565","hotelname":"Bhawna Palace","hoteldesc":"This 2Star property is located close to the Agra-Delhi NH2 and St Conrad School. There are standard, deluxe, executive and family rooms available for a comfortable stay. Punjabi cuisine, Mughlai, Chinese, Indian, South-Indian and Continental can be savoured at the in-house cafe restaurant, which is open for diners between open daily from 11a.m to 11p.m, and is also well-stocked with the choicest of beverages. The property is also maintained with a free parking zone.","starrating":"2","noofrooms":"1","minRate":"1419","rph":"20","webService":"arzooB","contactinfo":{"address":"NH2,Bye pass Road, Near St.Conrad School, opp.T.P.Nagar, Bye Pass Road, AGRA, UTTAR PRADESH, India, Pin-282005","citywiselocation":"Bye Pass Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/qyh\/jbs\/HO.jpg"}},"geoCode":"27.21048,77.98509"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Bhawana Deluxe","roombasis":",Breakfast, ","roomTypeCode":"0000024586","ratePlanCode":"0000135909","ratebands":{"validdays":"1111111","wsKey":"XLru\/2yzv3M6eaST9cxEwMJwLQz5W4J4l44itbNZBjveZVvmmynhoSzIfTHvqtq+iG065xYCyu+o\/mMAPzLHDOpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu1OiiI9G\/mHIlMCXqTXdVCS4PIoGVYHEX4lfZcQ1awCTiwZejQSADkZwLDUpKr8zwkTtnIK786l48hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QKtPxvbuoZABxDrDapJ4gq4MpmXghmAqoXCdUHHYpL1\/nnzlgxFuw0IbAUyUDHRst6rdZ1JTSIubM9n0Dni017rTDy0EUmaOIA==","extGuestTotal":"0","roomTotal":"1505","servicetaxTotal":"511","discount":"0.0","commission":"0","originalRoomTotal":"1505"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007551","hotelname":"Hotel Daawat Palace","hoteldesc":"Hotel Daawat Palace is located just few minutes walk from the famous Taj Mahal. One of the most popular hotels in Agra also has 24 tastefully appointed rooms with wireless internet connection and aesthetically decorated huge lobby to welcome the guest. The hotel also offers fine dining experience at the in-house restaurant. Chinese, Continental and Mughlai cuisines are served to the guest.","starrating":"3","noofrooms":"1","minRate":"1473","rph":"64","webService":"arzooB","contactinfo":{"address":"PURANI MANDI ,FATEHABAD ROAD, TAJGANJ, AGRA-1, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-281002","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/pyh\/ibo\/HO.jpg"}},"geoCode":"27.1664,78.037188"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Room Only","roombasis":"No Amenities","roomTypeCode":"0000028965","ratePlanCode":"0000962850","ratebands":{"validdays":"1111111","wsKey":"KAwdmbCEg9Y6eaST9cxEwJ7wgYEhcgT7iVjuyCNYlXWXKid\/rdIhoqTlpgVRijVZiG065xYCyu9b7z\/SCrkygOpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuHEmgyp5\/tpQ8HVbV68OIg5oPWR\/1kv47oVEbYNlCdR+vpc4Pgi4QldI0kqzTn0R2SrrkswfU4tFonSIMUuSdcu+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIhBPtN+2Mo7dQcSghbADHNrFFVZqhX0w3XRhDm8+HEg04WW7PoiEiSXmshMo\/Sm4cRqqfLTdt7wuRwnVBx2KS9fyb2BdugQglk","extGuestTotal":"0","roomTotal":"1562","servicetaxTotal":"531","discount":"0.0","commission":"0","originalRoomTotal":"1562"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Room With Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000028965","ratePlanCode":"0000165090","ratebands":{"validdays":"1111111","wsKey":"W2uph4uk3lA6eaST9cxEwHipjPEpsC7xxDrDapJ4gq7iVOO+hG0UMMQ6w2qSeIKufiIRyo0NWYwt4FM4GA9A070DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYmFvw5R09sp5BO6ZBRhrb08Q6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAzxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysH1VLxvsuzONPmYVI\/XmYI75PPYFP443ycHjlG4I7MKBx\/gbBD7QIozu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHpLd1Gb1H2Uwg==","extGuestTotal":"0","roomTotal":"1961","servicetaxTotal":"666","discount":"0.0","commission":"0","originalRoomTotal":"1961"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Premium Room With Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000028966","ratePlanCode":"0000108991","ratebands":{"validdays":"1111111","wsKey":"gRMydooVm2c6eaST9cxEwIe9+\/+Ldue1xDrDapJ4gq7iVOO+hG0UMMQ6w2qSeIKufiIRyo0NWYy5L2bS1Okoxr0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYmFvw5R09sp5BO6ZBRhrb08Q6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAzxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysJT7F5nEfWQfSrSC8YiEEidha\/Yxf92vjsHjlG4I7MKBGDtPfjqItjbu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHrBhInhO1dG8g==","extGuestTotal":"0","roomTotal":"2385","servicetaxTotal":"810","discount":"0.0","commission":"0","originalRoomTotal":"2385"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Taj Facing With Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000028968","ratePlanCode":"0000108993","ratebands":{"validdays":"1111111","wsKey":"CrZGVYzoYlY6eaST9cxEwNdCcbtCgGXfxDrDapJ4gq7iVOO+hG0UMMQ6w2qSeIKufiIRyo0NWYwkqYfjzPL9wL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYmFvw5R09sp5BO6ZBRhrb08Q6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAzxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysJT7F5nEfWQfhrA54QvVTtiQKb\/1aQPFVsHjlG4I7MKBvdCJSFtjH9Tu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHqcBYWzofD1Vg==","extGuestTotal":"0","roomTotal":"2597","servicetaxTotal":"882","discount":"0.0","commission":"0","originalRoomTotal":"2597"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00011776","hotelname":"Dolphinn Homestay","hoteldesc":"Dolphinn Homestay, a budget hotel in Agra enjoys close proximity to the most illustrious Taj Mahal. This single-story hotel boasts of 10 well-appointed rooms for a bountiful stay. Guests are been provided with customary hotel benefits like 24 hour front desk and backup generator for continuous power supply. Travel desk is also been offered to walk around the beautiful sightseeing of Agra.","starrating":"0","noofrooms":"1","minRate":"1475","rph":"27","webService":"arzooB","contactinfo":{"address":"Vip Road, Next to Kalakriti, Taj Nagri Agra, , Taj Nagari, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Nagari","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/vyf\/kbt\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room","roombasis":"No Amenities","roomTypeCode":"0000302071","ratePlanCode":"0001073456","ratebands":{"validdays":"1111111","wsKey":"N8qb0jQqrFc6eaST9cxEwGAfnD1\/agA5xDrDapJ4gq7BpUvY7VgovsQ6w2qSeIKuybExp9SyDNHWJyiSgQ0jD70DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV3jC5sPzaAFKs\/n\/jRSbHWAUzhyokP0r6Icqt36dKzHDCi7xmIRoGHz0ZtQeC\/eubjwtDNrGuC++T7k+js17KUhwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tlXGEF3VGogO0APBTp5T0hrGWOewnFl1yHCdUHHYpL1\/ep8lILuRfA8bAUyUDHRst6rdZ1JTSIubM9n0Dni017oo1liHf0xNww==","extGuestTotal":"0","roomTotal":"1564","servicetaxTotal":"531","discount":"0.0","commission":"0","originalRoomTotal":"1564"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000208262","ratePlanCode":"0000806942","ratebands":{"validdays":"1111111","wsKey":"N8qb0jQqrFc6eaST9cxEwGAfnD1\/agA5xDrDapJ4gq7BpUvY7VgovsQ6w2qSeIKuR5tTetsje8b+NdAteElykL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV3jC5sPzaAFKs\/n\/jRSbHWAUzhyokP0r6Icqt36dKzHDCi7xmIRoGHz0ZtQeC\/eubjwtDNrGuC++T7k+js17KUhwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9to\/D\/bghQU1051SZHEH+bS7GWOewnFl1yHCdUHHYpL1\/ep8lILuRfA8bAUyUDHRst6rdZ1JTSIubM9n0Dni017oo1liHf0xNww==","extGuestTotal":"0","roomTotal":"1564","servicetaxTotal":"531","discount":"0.0","commission":"0","originalRoomTotal":"1564"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00005924","hotelname":"Hotel Priya","hoteldesc":"This budget property located near to the TDI Mall area offers premium quality service, which includes Internet facility at the lobby. There are 18 well-designed guestrooms across its three floors. Guests can avail the 24hours room service and enjoy its Indian hospitality. Indian, Mughlai, Chinese and Continental fare can be savoured at the roof-top garden restaurant, which is open for service from 6p.m to 12p.m.","starrating":"2","noofrooms":"1","minRate":"1500","rph":"14","webService":"arzooB","contactinfo":{"address":"Opp. Priya Restaurant, Nr. TDI Mall, Fatehabad Road, Agra, Fatehabad Road, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/ryd\/fbr\/HO.jpg"}},"geoCode":"27.160556,78.055833"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room-Free Entry Ticket to Taj Mahal","roombasis":",15% Discount on Food and Beverages, Complementary wifi., ","roomTypeCode":"0000022137","ratePlanCode":"0000081384","ratebands":{"validdays":"1111111","wsKey":"NXG+niP2E9w6eaST9cxEwCQqw0Haa\/efxDrDapJ4gq7xGbJqn22DpcQ6w2qSeIKuBABGBuisda6ZA6NF8QHxIb0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYTlL49OiPrDzEOsNqkniCruj6ZNSAiHLWTBXZ+nfyPpvE99KHpHi+C8lrbJBMZTXmVRi+FKorLqOGz7DhDkivxiFgPXNl2dfLDhI3n7U4rLszQIn8QAcq\/+ZrMIpmmYxccJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbZRdBtrT44TVjYtv34eh\/geu0nhQ+2V5FBwnVBx2KS9f1X\/3JtHlLfnGwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6wIaYHu2f9Jc=","extGuestTotal":"0","roomTotal":"1590","servicetaxTotal":"540","discount":"0.0","commission":"0","originalRoomTotal":"1590"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007760","hotelname":"Sun Hotel","hoteldesc":"Located few minutes away from Taj Mahal, the world famous monument and about a kilometer away from Agra Fort, Sun Hotel is indeed one of the convenient hotels to stay at. The hotel maintains a total of 32 well-furnished rooms which includes deluxe room and executive room. Guests can enjoy great culinary experience at Mehfil , a multi-cuisine restaurant. Free parking facility, Internet access, travel counter to cater to the travel needs and 24 hour room service is also offered to the guests.","starrating":"4","noofrooms":"1","minRate":"1732","rph":"63","webService":"arzooB","contactinfo":{"address":"Plot No. 4\/305, Baluganj(Near Plice Chowki), , Balu Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Balu Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/pyf\/jbn\/HO.jpg"}},"geoCode":"27.167987,78.015784"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room-Single","roombasis":"No Amenities","roomTypeCode":"0000300864","ratePlanCode":"0001068560","ratebands":{"validdays":"1111111","wsKey":"9aJEDLrZ0bQ6eaST9cxEwEyAatEhdKNBxDrDapJ4gq6xr7XU1yFUBMQ6w2qSeIKurpPf33rmYVAfFpq9QID3zBV2WZCJd3Msmk9s1Uz6PbbEOsNqkniCrsHjlG4I7MKBayEyj9KbhxGqp8tN23vC5F\/UGu9+MzKaa4n7P9PxCn\/EOsNqkniCruj6ZNSAiHLW1Mn6wbBkF9ZRgWt1GHH6E8Q6w2qSeIKuNi2\/fh6H+B6fh2hpeEsls8HjlG4I7MKBn4doaXhLJbPB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rC8RRGuyU8ecc1uZI98Fxefa8ZRDzBdQevB45RuCOzCgbxiayxzdSWa7vu8p4fMa3vEOsNqkniCrg441u+4cjB6hKG0A27MYXc=","extGuestTotal":"0","roomTotal":"1732","servicetaxTotal":"468","discount":"354.0","commission":"0","originalRoomTotal":"1836"},"discountMessage":"Promotion Offer,Save 25% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room with Breakfast-Single","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000300864","ratePlanCode":"0001068561","ratebands":{"validdays":"1111111","wsKey":"hWndod3J2G46eaST9cxEwEeQLwKkvjMGoeGBBq4BVL6XKid\/rdIhomXzCHSKmgbsfI3skdTESB9\/5gPBEFm1vV1TxLDDAJTCn4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2D6oiNABYzeaqOMlLnlo3naFRG2DZQnUfj2XIIFojrz9HhTfeTSaiEFIPSojSPgLPaouWdXbk+O+rj8a7D0Do3yp3LsH920T3+20b6aMdhEd3DTibC7qCzTKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG+RObn+kC9sQrFPyZdSgmfB86nv+ZKkxwZoJ+FjZNZ7wqr9v\/i7+zf1wnVBx2KS9f5+CgAkL98z2l3cvVFgXs1f\/cfEFbT0Gguh41MQGE1h\/","extGuestTotal":"0","roomTotal":"1945","servicetaxTotal":"526","discount":"398.0","commission":"0","originalRoomTotal":"2063"},"discountMessage":"Promotion Offer,Save 25% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000270693","ratePlanCode":"0000985915","ratebands":{"validdays":"1111111","wsKey":"7IsUPfkKwjk6eaST9cxEwODxWUks4FQNgg5Mf5eXJSOXKid\/rdIhomXzCHSKmgbsrAWIFyzCG9KQEE85UIj5HI8UzyKz2K4dn4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2D6oiNABYzeaqOMlLnlo3naFRG2DZQnUfr6XOD4IuEJXSNJKs059Edkq65LMH1OLRbczozSesmF\/vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIcIwCt4phVDrHEoIWwAxzazPusL1dzt8WWMeW1MUZIJKw8tZQwOJ6IprITKP0puHEaqny03be8LkcJ1QcdikvX\/+WuT+FkQj3w==","extGuestTotal":"0","roomTotal":"1999","servicetaxTotal":"540","discount":"410.0","commission":"0","originalRoomTotal":"2120"},"discountMessage":"Promotion Offer,Save 25% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Room","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000157897","ratePlanCode":"0000524632","ratebands":{"validdays":"1111111","wsKey":"ExvpTqhBMsE6eaST9cxEwKdQxSXU8gmCBR\/2zROQDWyXKid\/rdIhomXzCHSKmgbsuXdNOfNR2Rl9A1eubgmyt9hPifVJ\/tLLn4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2D6oiNABYzeaqOMlLnlo3naFRG2DZQnUfj2XIIFojrz9HhTfeTSaiEFIPSojSPgLPaouWdXbk+O96oH+sipyaEZWCOwPzjOjMcJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6Pbb24FOyff1WB+dUmRxB\/m0uvhiGVaKlO33EOsNqkniCrko3Q03U5aO+DufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2gF2M1zC5L0I=","extGuestTotal":"0","roomTotal":"2248","servicetaxTotal":"607","discount":"460.0","commission":"0","originalRoomTotal":"2384"},"discountMessage":"Promotion Offer,Save 25% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000270693","ratePlanCode":"0000985920","ratebands":{"validdays":"1111111","wsKey":"+Zt8DNbB+C46eaST9cxEwAGnkg04RXz\/BR\/2zROQDWyXKid\/rdIhomXzCHSKmgbsrAWIFyzCG9KQEE85UIj5HDHtguGJZ+Een4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2D6oiNABYzeaqOMlLnlo3naFRG2DZQnUfj2XIIFojrz+lGeYGMyw1jBrBmxCjaj44pCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSr+20b6aMdhEd3DTibC7qCzTKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG1J\/0xoV49T3611jx62MQcEPnfuiwKdKY2fB4f+PXP9NYG8PztG93sBwnVBx2KS9f5+CgAkL98z2l3cvVFgXs1fMp9IIR4a87eh41MQGE1h\/","extGuestTotal":"0","roomTotal":"2415","servicetaxTotal":"652","discount":"495.0","commission":"0","originalRoomTotal":"2561"},"discountMessage":"Promotion Offer,Save 25% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Room with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000157897","ratePlanCode":"0000985976","ratebands":{"validdays":"1111111","wsKey":"9U7kQEQC13U6eaST9cxEwAurVNbZSQzSgg5Mf5eXJSOXKid\/rdIhomXzCHSKmgbsuXdNOfNR2Rl9A1eubgmytyNk3L93gw\/in4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2D6oiNABYzeaqOMlLnlo3naFRG2DZQnUfj2XIIFojrz+lGeYGMyw1jBrBmxCjaj44pCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSr+20b6aMdhEd3DTibC7qCzTKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG1J\/0xoV49T3QMkai5u6ogVAkKyETnDop5FLjd7fEFlUGIgfx\/X009BwnVBx2KS9f5+CgAkL98z2l3cvVFgXs1eUU4wrtJsYtJJfoTUc0GnO","extGuestTotal":"0","roomTotal":"2499","servicetaxTotal":"675","discount":"512.0","commission":"0","originalRoomTotal":"2650"},"discountMessage":"Promotion Offer,Save 25% on each night"}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00002749","hotelname":"Hotel Metro","hoteldesc":"Centrally located in the commercial area of Sanjay Place, this 2 Star hotel is designed with three stories housing 34 air-conditioned rooms. Guests can dine-in at its roof-top garden restaurant that is maintained with a well-stocked bar. Guests on a business visit are facilitated with a conference hall, which has a seating capacity of upto 50 guests.","starrating":"3","noofrooms":"1","minRate":"1900","rph":"3","webService":"arzooB","contactinfo":{"address":"Block-36, Sanjay Place, , Sanjay Place, AGRA, UTTAR PRADESH, India, Pin-282002","citywiselocation":"Sanjay Place","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/uyf\/hbw\/HO.jpg"}},"geoCode":"27.2018,78.0063"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room-Single","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000024660","ratePlanCode":"0000166676","ratebands":{"validdays":"1111111","wsKey":"NTkqtQig\/bI6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0C7LL+jr2lQuSyeKeLupHtA6D2mvWAjo8DEOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwei\/SZmMcJ\/zjON97gp8T226HKrd+nSsxwwou8ZiEaBh8PZhhtrMPHqmkJFFWuf9bCGnJLYw1O2xZ29SpYY5AVKv7bRvpox2ER0PxDueJtPoEMoGAbJG7uY+VaEzSCufRYjKBgGyRu7mPlWhM0grn0WL3XdKs\/v3lfEOH4WMN4IdGElfXbU2eIsrGaYOA5yaiHJqGqpoHNP5PMH\/iAOAk7UtOqO0QCTvF3cP7G5GfT5gb10oe+nHGQ9OD+xZxJt\/DMGOfg10zVliufoiMSST3Z0KSoHtembAyhkC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k+ITi+M13p7\/F9zkShsUkYU=","extGuestTotal":"0","roomTotal":"1914","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"2014"},"discountMessage":"Promotion Offer,Save 5% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room with Breakfast-Single","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000024660","ratePlanCode":"0001002161","ratebands":{"validdays":"1111111","wsKey":"bkjcxWcGKEo6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0C7LL+jr2lQuSyeKeLupHtA6D2mvWAjo8DEOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwei\/SZmMcJ\/zjON97gp8T226HKrd+nSsxwwou8ZiEaBh8ZH\/+7VsO7Jy+9Jjt8FKX6+wDTrFGh15eFBkh4Z8KrLAbNf9ZnlIpb5U0W6W+epfil4xRPzkjRoL0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfd\/h3teePB5ypn+RyvEyGKq\/jHoNQ58SspL+Rc1Y0+EVIweOUbgjswoFrITKP0puHEaqny03be8Lkn+RyvEyGKq8t9CqbX+IBwQ==","extGuestTotal":"0","roomTotal":"2014","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"2120"},"discountMessage":"Promotion Offer,Save 5% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000008959","ratePlanCode":"0000136052","ratebands":{"validdays":"1111111","wsKey":"s4Pd1OoD8Uw6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0C7LL+jr2lQuSyeKeLupHtAtRj4zAouR37EOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwei\/SZmMcJ\/zjON97gp8T226HKrd+nSsxwwou8ZiEaBh8PZhhtrMPHqmkJFFWuf9bCGnJLYw1O2xZ29SpYY5AVKv7bRvpox2ER0PxDueJtPoEMoGAbJG7uY+VaEzSCufRYjKBgGyRu7mPlWhM0grn0WL3XdKs\/v3lfEOH4WMN4IdGElfXbU2eIsrGaYOA5yaiHJqGqpoHNP5PMH\/iAOAk7UtOqO0QCTvF3cP7G5GfT5gb7\/vFk+dobXyaCME8f0bD54xV1oTnOVxY7\/WuvJTeSal2\/tbdzt2CvEC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k8CV+DQkEnWQF9zkShsUkYU=","extGuestTotal":"0","roomTotal":"2518","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"2650"},"discountMessage":"Promotion Offer,Save 5% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000008959","ratePlanCode":"0001002159","ratebands":{"validdays":"1111111","wsKey":"FIaQDxIQFQc6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0C7LL+jr2lQuSyeKeLupHtAtRj4zAouR37EOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwei\/SZmMcJ\/zjON97gp8T226HKrd+nSsxwwou8ZiEaBh8PZhhtrMPHqmkJFFWuf9bCGnJLYw1O2xZ29SpYY5AVKtdWyADcPQTfwmy8\/kudKWEl4xRPzkjRoL0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfd0PGTKlYO2eWn+RyvEyGKq\/Oba3J+QVmEY6TVxgfqCKjweOUbgjswoFrITKP0puHEaqny03be8Lkn+RyvEyGKq8TAXul0rNOaQ==","extGuestTotal":"0","roomTotal":"2719","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"2862"},"discountMessage":"Promotion Offer,Save 5% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Super Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000278879","ratePlanCode":"0001002167","ratebands":{"validdays":"1111111","wsKey":"Xac586Iou5Y6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0C7LL+jr2lQuSyeKeLupHtAkxO8l+06y43EOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwei\/SZmMcJ\/zjON97gp8T226HKrd+nSsxwwou8ZiEaBh89GbUHgv3rm48LQzaxrgvvnBbCRbv+IE6cJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbZhfQD6kW4WmkmK0H23KCUQhII\/WvNBHMXpUENSry73sW7HM\/oTEtD7GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6KnF8DQMsLUQ=","extGuestTotal":"0","roomTotal":"3021","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"3180"},"discountMessage":"Promotion Offer,Save 5% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Super Deluxe Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000278879","ratePlanCode":"0001002168","ratebands":{"validdays":"1111111","wsKey":"ztSlSJinOLs6eaST9cxEwA+fTZgh1nupLJ4p4u6ke0C7LL+jr2lQuSyeKeLupHtAkxO8l+06y43EOsNqkniCrj61Dp4lgPVpxhyWcxAZvAY6eaST9cxEwO77vKeHzGt7xDrDapJ4gq4OONbvuHIwei\/SZmMcJ\/zjON97gp8T226HKrd+nSsxwwou8ZiEaBh8PZhhtrMPHqmkJFFWuf9bCGnJLYw1O2xZ29SpYY5AVKtdWyADcPQTfwmy8\/kudKWEl4xRPzkjRoL0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfd\/78pPcl8AMo6VBDUq8u97FqSTIqH56XDcepIaXYxo3iweOUbgjswoFrITKP0puHEaqny03be8Lk6VBDUq8u97Hn1jouFu\/BZA==","extGuestTotal":"0","roomTotal":"3223","servicetaxTotal":"0","discount":"0.0","commission":"0","originalRoomTotal":"3392"},"discountMessage":"Promotion Offer,Save 5% on each night"}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00007331","hotelname":"Hotel Sai President","hoteldesc":"This 2Star hotel in Agra is located near to the commercial area of MG Road. There are deluxe and super deluxe rooms for accommodation. Rose wood Restaurant offers scrumptious dishes to its patrons between 10a.m to 12p.m. The travel counter offers travel assistance and arranges boat rides for the pleasure of the guests. There is also secure parking facility at extra cost.","starrating":"2","noofrooms":"1","minRate":"1667","rph":"15","webService":"arzooB","contactinfo":{"address":"Dholpur House,M.G Road, Agra-1, M G Road, AGRA, UTTAR PRADESH, India, Pin-208001","citywiselocation":"M G Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyj\/gbo\/HO.jpg"}},"geoCode":"27.175,78.007222"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000028083","ratePlanCode":"0000140922","ratebands":{"validdays":"1111111","wsKey":"pEyC+u5Paes6eaST9cxEwL\/nRf6QPFr7m+TalthMfiSXKid\/rdIhopuVJm0nseB\/iG065xYCyu9Xp+PTH8\/ufupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuW8TrixYptZduFp8qrviGK9ONVWGRO4IxoVEbYNlCdR+vpc4Pgi4QldI0kqzTn0R2SrrkswfU4tHGCb7j9Hzi7e+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIh7s56rvWQz9mm6FjwbkLeiEzVbN8OZiKZfrpNSZklutJJitB9tyglEGshMo\/Sm4cRqqfLTdt7wuRwnVBx2KS9f0t4zD8v6QYW","extGuestTotal":"0","roomTotal":"1768","servicetaxTotal":"601","discount":"0.0","commission":"0","originalRoomTotal":"1768"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00012260","hotelname":"Hotel Goverdhan","hoteldesc":"Conveniently located at a decent distance from the Taj Mahal is Hotel Goverdhan. The hotel in Agra is a budget hotel located 4kms from the nearest rail-head and 3kms from the bus stand with 20 well appointed rooms for accommodation. Guest amenities include a well informed round the clock front desk service and power backup facility to ensure uninterrupted services.","starrating":"0","noofrooms":"1","minRate":"1674","rph":"45","webService":"arzooB","contactinfo":{"address":"Delhi Gate Agra, , Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282002","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyk\/jbn\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000044138","ratePlanCode":"0000172360","ratebands":{"validdays":"1111111","wsKey":"nJ6BTM0ZS+I6eaST9cxEwDXow1pkGwxEgg5Mf5eXJSPTJtiw8FOhCrLunVTG5faoiG065xYCyu9J4XQub9Mi5+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu+EhkyJoIcryv0Hd7CYG\/9PYJRQ1GQxAVunkZan8IOa9NGqb3bn4+dnz9g3BaVZA3mk9s1Uz6PbbEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCrpUvo5mi\/CPbB+siW2wnwo4B\/Wh0lrRDRjYtv34eh\/geIwiXglmzxl\/u+7ynh8xre8Q6w2qSeIKuDjjW77hyMHppxRwzjtAq5w==","extGuestTotal":"0","roomTotal":"1775","servicetaxTotal":"603","discount":"0.0","commission":"0","originalRoomTotal":"1775"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007549","hotelname":"Hotel Orbit Inn","hoteldesc":"Centrally located in the heart of the city and just 6 kms from Taj Mahal, the world famous monument, Hotel Orbit Inn is an apt stay option for business and leisure travelers alike. In order to satisfy the guests to the core, Hotel Orbit Inn maintains 12 well-appointed guestrooms to enjoy a perfect stay. There is also an in-house restaurant, namely \"Moti-Mahal Delux,\" which is famous for its tandoori cuisine.  It also serves mouth-watering Chinese and Continental cuisine to its diners.","starrating":"3","noofrooms":"1","minRate":"1699","rph":"61","webService":"arzooB","contactinfo":{"address":"70-71,Gandhi Nagar,NH2, AGRA, Gandhi Nagar, AGRA, UTTAR PRADESH, India, Pin-282005","citywiselocation":"Gandhi Nagar","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/pyh\/hbw\/HO.jpg"}},"geoCode":"27.205979,78.013205"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single-A\/C Room only","roombasis":",10% Discount on Food and Beverages, ","roomTypeCode":"0000028959","ratePlanCode":"0000162574","ratebands":{"validdays":"1111111","wsKey":"PWan6TEN2AE6eaST9cxEwPuQelXSnQmNgg5Mf5eXJSOXKid\/rdIhoj7U0RNKk2j2iG065xYCyu+sjtfEL7ZtWupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuBlwP4GP11P0XBTh8yXZ7aPYJRQ1GQxAVunkZan8IOa9mSEXdYy\/zyvXgdD0iaJmix10CA\/wUx3qi4A9vl4BVeBll8cxaK\/6DxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysOtdgBXR6TKJLuqIQURAlFd+3SRN4nbsV95xcrfKlmD+QqGTP+CzSChAtkpv5ODgAMHjlG4I7MKBdEUzSDHYepPaDzRXNEIRvhfc5EobFJGF","extGuestTotal":"0","roomTotal":"1801","servicetaxTotal":"612","discount":"0.0","commission":"0","originalRoomTotal":"1801"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double-A\/C Room only","roombasis":"No Amenities","roomTypeCode":"0000028960","ratePlanCode":"0000136007","ratebands":{"validdays":"1111111","wsKey":"5FAk1JymCU86eaST9cxEwNoqxkG\/d1G3gg5Mf5eXJSOXKid\/rdIhoj7U0RNKk2j2iG065xYCyu8s5w8YTUqKwupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuBlwP4GP11P0XBTh8yXZ7aPYJRQ1GQxAVunkZan8IOa9NGqb3bn4+dnz9g3BaVZA36VBDUq8u97HEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCrq+QbXqiNMByxGtPDr0mqboiT+\/L\/4Zs4TYtv34eh\/ge3lunkT6t5wru+7ynh8xre8Q6w2qSeIKuDjjW77hyMHowp7rFFySlnQ==","extGuestTotal":"0","roomTotal":"1907","servicetaxTotal":"648","discount":"0.0","commission":"0","originalRoomTotal":"1907"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Single-A\/C Room only","roombasis":"No Amenities","roomTypeCode":"0000028961","ratePlanCode":"0000162575","ratebands":{"validdays":"1111111","wsKey":"Ro2iskMHX9M6eaST9cxEwOI7X+chi1I5gg5Mf5eXJSOXKid\/rdIhoj7U0RNKk2j2iG065xYCyu8DAUyjbDbVKOpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuBlwP4GP11P0XBTh8yXZ7aPYJRQ1GQxAVunkZan8IOa9NGqb3bn4+dnz9g3BaVZA36VBDUq8u97HEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCroLl47AyEjjnciVjvc0dZsYyzwtJMJsf\/DYtv34eh\/geoZLdl+EZuRvu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHrBVKTC+LF6og==","extGuestTotal":"0","roomTotal":"2331","servicetaxTotal":"792","discount":"0.0","commission":"0","originalRoomTotal":"2331"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Double-A\/C Room only","roombasis":"No Amenities","roomTypeCode":"0000028962","ratePlanCode":"0000136008","ratebands":{"validdays":"1111111","wsKey":"VuQXEbH6oZw6eaST9cxEwM8Dn8CWvIN\/gg5Mf5eXJSOXKid\/rdIhoj7U0RNKk2j2iG065xYCyu\/fqHSwVIuD+OpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuBlwP4GP11P0XBTh8yXZ7aPYJRQ1GQxAVunkZan8IOa9NGqb3bn4+dnz9g3BaVZA36VBDUq8u97HEOsNqkniCrtampqTJQiXKxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu6ZGnJy6LCTBwnVBx2KS9fzDVhUnua7JpSYrQfbcoJRCaqFHk54EJ18Zpg4DnJqIc1udA7r9DY8\/EOsNqkniCrq+QbXqiNMBygMchkqGvCoV85N8aLSMl4jYtv34eh\/ge3l\/I9UUqasLu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHoSb1MDd\/Vxsg==","extGuestTotal":"0","roomTotal":"2437","servicetaxTotal":"828","discount":"0.0","commission":"0","originalRoomTotal":"2437"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00006061","hotelname":"Hotel Shree Residency","hoteldesc":"This 2Star abode is located on the main Delhi-Kanpur Road in close proximity to all posh residential colonies and commercial hub of Agra. There are exquisitely furnished and well-equipped standard, deluxe and executive rooms. Guests are offered room service and the mini-business centre offers facilities for official purpose. Tour\/sightseeing assistance is provided to the guests, who wish to experience the true essence of the city.","starrating":"2","noofrooms":"1","minRate":"1733.33","rph":"16","webService":"arzooB","contactinfo":{"address":"Adjcent to Shree Talkies, NH-2, Bye paas road, Bye Pass Road, AGRA, UTTAR PRADESH, India, Pin-282002","citywiselocation":"Bye Pass Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/qym\/jbo\/HO.jpg"}},"geoCode":"27.209437,78.007716"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000022613","ratePlanCode":"0000084980","ratebands":{"validdays":"1111111","wsKey":"zYWSe9Uk7V6aT2zVTPo9tsQ6w2qSeIKum4gi\/T6vULHeZVvmmynhoRvuq68MJ9KiiG065xYCyu\/V8n6xUTlZmOpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuRm6Tj4niIqXZjTlBV0eWeUOpGCIBs82xLg8igZVgcRfiV9lxDVrAJIRRlVtnozDLXGTk3LSkm8uRO2cgrvzqXjyGzIii41fjEpDuaJSXR\/88hsyIouNX4xKQ7miUl0f\/vdcVZCXZFyFhWE\/RZUI5cjd7ydYQyAevqORHj6pLwUZwnVBx2KS9fyFts5o68MhvSYrQfbcoJRD+k2b\/VaHX\/yyeKeLupHtAw5dTOBa\/ALzEOsNqkniCriH\/TfkGMGbycJ1QcdikvX9XGIRuBaP2vUC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k\/IzREs6vAopGsvPA5trUgY=","extGuestTotal":"0","roomTotal":"1838","servicetaxTotal":"624","discount":"0.0","commission":"0","originalRoomTotal":"1838"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000022611","ratePlanCode":"0000084978","ratebands":{"validdays":"1111111","wsKey":"0\/HmcYW7kzGaT2zVTPo9tsQ6w2qSeIKuQQsDBy9RmMDeZVvmmynhoRvuq68MJ9KiiG065xYCyu\/7JPxupclxMupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuRm6Tj4niIqXZjTlBV0eWeUOpGCIBs82xLg8igZVgcRfiV9lxDVrAJIRRlVtnozDLXGTk3LSkm8uRO2cgrvzqXjyGzIii41fjEpDuaJSXR\/88hsyIouNX4xKQ7miUl0f\/vdcVZCXZFyFhWE\/RZUI5cjd7ydYQyAevqORHj6pLwUZwnVBx2KS9fyFts5o68MhvSYrQfbcoJRD+k2b\/VaHX\/yyeKeLupHtAfQHj1+mos3vEOsNqkniCrjO2agcW89BIn+RyvEyGKq9sjN1cn5mOHEC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k7X7qFnVx\/cBGsvPA5trUgY=","extGuestTotal":"0","roomTotal":"2156","servicetaxTotal":"732","discount":"0.0","commission":"0","originalRoomTotal":"2156"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive","roombasis":"No Amenities","roomTypeCode":"0000022612","ratePlanCode":"0000084979","ratebands":{"validdays":"1111111","wsKey":"sWIfm8+eKnGaT2zVTPo9tsQ6w2qSeIKu6THbTaKbIYneZVvmmynhoRvuq68MJ9KiiG065xYCyu8dukQ5V7PgLupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuRm6Tj4niIqXZjTlBV0eWeUOpGCIBs82xLg8igZVgcRfiV9lxDVrAJIRRlVtnozDLXGTk3LSkm8uRO2cgrvzqXjyGzIii41fjEpDuaJSXR\/88hsyIouNX4xKQ7miUl0f\/vdcVZCXZFyFhWE\/RZUI5cjd7ydYQyAevqORHj6pLwUZwnVBx2KS9fyFts5o68MhvSYrQfbcoJRD+k2b\/VaHX\/yyeKeLupHtAWIqsqqYUEz\/EOsNqkniCrkvPOX9e82b6n+RyvEyGKq+MkgioChef4kC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6kwsiiySyXxh1GsvPA5trUgY=","extGuestTotal":"0","roomTotal":"2474","servicetaxTotal":"840","discount":"0.0","commission":"0","originalRoomTotal":"2474"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00007352","hotelname":"Hotel Riya Palace","hoteldesc":"This 2Star hotel at Agra is located amidst a busy bazaar area. There are large, spacious rooms and suites elegantly embellished with modernistic furniture and complemented with hi-end amenities. Guests are offered in-room dinning service between 6:30-11.30p.m. The hotel is maintained with travel counter for travel assistance and secure parking space for private vehicles. There is also high-speed\/wireless Internet access on surcharge to stay connected with friends and family.","starrating":"2","noofrooms":"1","minRate":"1757","rph":"59","webService":"arzooB","contactinfo":{"address":"52,Idgah Bus Stand Road, Agra, Idgah Bus Stand Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Idgah Bus Stand Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyj\/ibp\/HO.jpg"}},"geoCode":"27.166405,77.999946"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room- Special Rates","roombasis":",Breakfast, ","roomTypeCode":"0000028191","ratePlanCode":"0000835342","ratebands":{"validdays":"1111111","wsKey":"92In4d8+BYc6eaST9cxEwOapnw2Z+cdoO0NCrb5XjoOXKid\/rdIholJYDKkLz7ooiG065xYCyu8nRpzubYFaz+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKup\/GTXGPoPfQKdPM7LDxR\/WCau5PAwu\/Idbgo\/Yb\/DgJY613z2Hi8HSp3LsH920T3+20b6aMdhEeOSkR9EMVTbzKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG+4it9LxZQWhnmw0dDFAiVgftrvq5HddkT1hteU3V1+DB+yEWVi9DNlwnVBx2KS9f5+CgAkL98z2l3cvVFgXs1eLr09WlbUI6yc3vtkQKtxI","extGuestTotal":"0","roomTotal":"1863","servicetaxTotal":"633","discount":"0.0","commission":"0","originalRoomTotal":"1863"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ,Two Bottles Mineral Water, Tea\/Coffee maker in the Room., Complimentary local daily News Paper, 15% discount on laundry services, Two bottle of packaged drinking water., Free Pick Up From Railway Station and Bus Stand - Condition Apply., ","roomTypeCode":"0000028191","ratePlanCode":"0000105202","ratebands":{"validdays":"1111111","wsKey":"W2uph4uk3lA6eaST9cxEwHipjPEpsC7xxDrDapJ4gq4CcxYbGXHw78Q6w2qSeIKuoO5nQkCd2NWV5mzeNBBx9r0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYx3YvixWshtpJguUFn2qdL4cqt36dKzHDCi7xmIRoGHxkf\/7tWw7snL70mO3wUpfr7ANOsUaHXl4UGSHhnwqssBs1\/1meUilvOUFr\/ALmLweGUoeL3rY5RQRIBYkwvBItMdTQeP4bjBPq7SiQNzQV+xDGh6jF5M9nK+VuWAOVTJePcZ6PCCEA5QxQkMr+aLx1v6GTFThhD1TuX1pE+KQ5CU6b\/EJ17sc2Qce3vUI4WAioAoEkJ0XPxEmCF1MabZycKZGXZayNKFve1RJoCraduXS8\/HDzBkcbmNU8WZLvxF51FNQq6ebjw61XFMOcZtW5QU5Q\/al3uZuK7obHAaYEcDgLMQGXCL1qnYuNvDuSd1WjkisKgzH8csL5ginnArKHAFbPPlMuB5kf8hcYwNZUKzRlxPYbdRGTHBWMY6DjT2nGCb7j9Hzi7e+5tRqvU0cv0zwAqHUz8vrvubUar1NHL9M8AKh1M\/L68yqFmlT7DvPXW+hNqGFh40S6XqAWHmYBMH\/iAOAk7Uu9wZV4PEFj5KjkR4+qS8FGfp3ja4QPK0j2bwqEnq6nqKPyyUpmKMIhvTmUC6px5cIzoREOmQJg2XiW9WAtLCrAQRrStR1AJQrEOsNqkniCrhbmFDNCStjijlEhzBvrdTh39QtGjo5UJKzjLao1c8Ur","extGuestTotal":"0","roomTotal":"1961","servicetaxTotal":"666","discount":"0.0","commission":"0","originalRoomTotal":"1961"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Super Deluxe Double","roombasis":",Complimentary Wi-Fi Internet, Breakfast, Taxes, ,Two Bottles Mineral Water, Complimentary local daily News Paper, Tea\/Coffee maker in the Room., 15% discount on laundry services, Free Pick Up From Railway Station and Bus Stand - Condition Apply., ","roomTypeCode":"0000028193","ratePlanCode":"0000105204","ratebands":{"validdays":"1111111","wsKey":"5U\/aqDBL+3I6eaST9cxEwNVeIgkChOAuxDrDapJ4gq4CcxYbGXHw78Q6w2qSeIKuoO5nQkCd2NUKmI06pV6dUr0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYx3YvixWshtpJguUFn2qdL4cqt36dKzHDCi7xmIRoGHw9mGG2sw8eqaQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq11bIANw9BN\/VVShk1ndj3dtucQPU\/b3LYpMaZfD9qtagdmY\/nGLhIWDMOFptPFpHAxQkMr+aLx1v6GTFThhD1TuX1pE+KQ5CU6b\/EJ17sc2o74HKxK+yYtSdj6USofldk5ewyVi92EO2yTPGg8bw4ltML3DN1V8WkaNZQig6lH1YCcM4UKtClwXDheGjTMXvNLkmZF7nmhorHAgM9SYoJfxSOaDNELk7nagXcvhsFSiCNAFLqRy8jWVDNhHwSAeWi0aaT0rOtGHu593RpYSvWAXTC5\/tGSvgU\/2KEkYgxXt9unYQjwKmoRFSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLO5d00581HZGUiBbPMOlPM5RtdRvffcUGUDeznK6Pljx4uUC5j9QuqhcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXsddyEPXP1uRFakgJU43uJg==","extGuestTotal":"0","roomTotal":"2067","servicetaxTotal":"702","discount":"0.0","commission":"0","originalRoomTotal":"2067"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Maharaja Double Room","roombasis":",Breakfast, Complimentary Wi-Fi Internet, Taxes, ,Two Bottles Mineral Water, Tea\/Coffee maker in the Room., Complimentary local daily News Paper, 15% discount on laundry services, Free Pick Up From Railway Station and Bus Stand - Condition Apply., ","roomTypeCode":"0000028195","ratePlanCode":"0000105206","ratebands":{"validdays":"1111111","wsKey":"vDcDyAG7aDw6eaST9cxEwOmWop42dBElxDrDapJ4gq4CcxYbGXHw78Q6w2qSeIKuoO5nQkCd2NVutZapLSZHNL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYx3YvixWshtpJguUFn2qdL4cqt36dKzHDCi7xmIRoGHxkf\/7tWw7snL70mO3wUpfr7ANOsUaHXl4UGSHhnwqssBs1\/1meUilv0CHNs6neM9ZtucQPU\/b3LYpMaZfD9qtagdmY\/nGLhIUi6u7+no\/NcUBSJ7beJEY7QMlgYjmkk1EDz7oseN9oDK3bfmd1+4Jm7ANOsUaHXl41yAF389e6acbIYQFOUjcxzlcvlq9+yrLeROX+p9eF\/kaNZQig6lH1YCcM4UKtClwXDheGjTMXvNLkmZF7nmhorHAgM9SYoJfxSOaDNELk7nagXcvhsFSiCNAFLqRy8jWVDNhHwSAeWi0aaT0rOtGHu593RpYSvWAXTC5\/tGSvgU\/2KEkYgxXt9unYQjwKmoRFSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLO5d00581HZGa2htR0DfJNolFOMK7SbGLQW5hQzQkrY4ndFKUio9ZT\/cJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXW2Ny\/HnrjrBFakgJU43uJg==","extGuestTotal":"0","roomTotal":"2491","servicetaxTotal":"846","discount":"0.0","commission":"0","originalRoomTotal":"2491"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Riya Family Room","roombasis":",Complimentary Wi-Fi Internet, Breakfast, Taxes, ,Two Bottles Mineral Water, Complimentary local daily News Paper, Tea\/Coffee maker in the Room., 15% discount on laundry services, Free Pick Up From Railway Station and Bus Stand - Condition Apply., ","roomTypeCode":"0000028196","ratePlanCode":"0000105207","ratebands":{"validdays":"1111111","wsKey":"CrZGVYzoYlY6eaST9cxEwNdCcbtCgGXfxDrDapJ4gq4CcxYbGXHw78Q6w2qSeIKuoO5nQkCd2NXK0HVh8mVhQL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYx3YvixWshtpJguUFn2qdL4cqt36dKzHDCi7xmIRoGHw9mGG2sw8eqaQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq11bIANw9BN\/VVShk1ndj3dtucQPU\/b3LYpMaZfD9qtagdmY\/nGLhIWDMOFptPFpHAxQkMr+aLx1v6GTFThhD1TuX1pE+KQ5CU6b\/EJ17sc2o74HKxK+yYtSdj6USofldk5ewyVi92EO2yTPGg8bw4ltML3DN1V8WkaNZQig6lH1YCcM4UKtClwXDheGjTMXvNLkmZF7nmhorHAgM9SYoJfxSOaDNELk7nagXcvhsFSiCNAFLqRy8jWVDNhHwSAeWi0aaT0rOtGHu593RpYSvWAXTC5\/tGSvgU\/2KEkYgxXt9unYQjwKmoRFSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLO5d00581HZGY4TmgnxoqDcnDQVlBG3tjoDeznK6Pljx9nCh7Bb\/7q2cJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NX2OeK7GfidK9FakgJU43uJg==","extGuestTotal":"0","roomTotal":"2597","servicetaxTotal":"882","discount":"0.0","commission":"0","originalRoomTotal":"2597"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00009339","hotelname":"Hotel 9 Star","hoteldesc":"Hotel 9 Star, one of the most economical boutique hotels in Agra is located at a birds eye view from one of the seven wonders of the world- Taj Mahal. This centrally air-conditioned hotel in Agra offers 20 well-lit and well-ventilated rooms spread across four floors. Plenty of open spaces, lush-green lawns and a multi-cuisine restaurant to pamper your palate, Hotel 9 Star is sure to provide you a perfect retreat.","starrating":"3","noofrooms":"1","minRate":"1799","rph":"65","webService":"arzooB","contactinfo":{"address":"18\/159-A\/4B,Opp Kailash Cinema Purani Mandi X-ing Tajganj , , Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/nyj\/gbw\/HO.jpg"}},"geoCode":"0.0,0.0"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room - Room Only","roombasis":"No Amenities","roomTypeCode":"0000066138","ratePlanCode":"0000266082","ratebands":{"validdays":"1111111","wsKey":"5FAk1JymCU86eaST9cxEwNoqxkG\/d1G3gg5Mf5eXJSOctY9r1yW1hM3j65mJ\/auBiG065xYCyu8PfTwusnWwmepiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuaiJEX1Q7MD57SukdB39T8IQj\/og\/zHPzxDrDapJ4gq6+m9VIOMrRSWf9BWsomSQZl4xRPzkjRoL0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfdyZClIpjrorJcJ1QcdikvX8IMDctMOO9XaboWPBuQt6Ir9aluvkC083fF294T0zOQXizl9lkmeuTxDrDapJ4gq4gAFgmtQtwig==","extGuestTotal":"0","roomTotal":"1907","servicetaxTotal":"648","discount":"0.0","commission":"0","originalRoomTotal":"1907"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000066138","ratePlanCode":"0000396099","ratebands":{"validdays":"1111111","wsKey":"9Hbhl2dP8xk6eaST9cxEwN3EiDnrznJHgg5Mf5eXJSOctY9r1yW1hM3j65mJ\/auBiG065xYCyu8PfTwusnWwmepiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuaiJEX1Q7MD57SukdB39T8IQj\/og\/zHPzxDrDapJ4gq6txVt5UIC95tJAHo6LORdOWX4TN4803wA9I1Z8VByzqdmZELkDavBUT\/YoSRiDFe21azFh8DeP3kVK\/7EoOEfKNkERa08KKsFFSv+xKDhHyjZBEWtPCirBpfhrCR6iobjadH2ddEo6HG+vc0ICKlTziX6Yfx2T7IlRsSGnQDpHCV05pdCEFLGD5l3jt\/5UVDh1MHeGgHUks3yN7JHUxEgfqFnhLfjSGQzazG4LGye8DCB66mZ7\/wwRLfE2W7GpAuDEOsNqkniCrhbmFDNCStjijlEhzBvrdTgzoREOmQJg2dzXOTpIb1yD","extGuestTotal":"0","roomTotal":"2119","servicetaxTotal":"720","discount":"0.0","commission":"0","originalRoomTotal":"2119"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room - Room Only","roombasis":"No Amenities","roomTypeCode":"0000066139","ratePlanCode":"0000266084","ratebands":{"validdays":"1111111","wsKey":"9Hbhl2dP8xk6eaST9cxEwN3EiDnrznJHgg5Mf5eXJSOctY9r1yW1hM3j65mJ\/auBiG065xYCyu\/mOhDG1ct42OpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuaiJEX1Q7MD57SukdB39T8IQj\/og\/zHPzxDrDapJ4gq6+m9VIOMrRSWf9BWsomSQZl4xRPzkjRoL0a459xsF8lpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfd45A4QyqCs+on+RyvEyGKq\/s\/Q4TnL6D4jOhEQ6ZAmDZr9aluvkC083fF294T0zOQXizl9lkmeuTxDrDapJ4gq4Xvr54eKEO8Q==","extGuestTotal":"0","roomTotal":"2119","servicetaxTotal":"720","discount":"0.0","commission":"0","originalRoomTotal":"2119"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Super Deluxe Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000174640","ratePlanCode":"0000580830","ratebands":{"validdays":"1111111","wsKey":"k9pY5v\/e9jk6eaST9cxEwCarg4GUSz+Mgg5Mf5eXJSOctY9r1yW1hM3j65mJ\/auBuXdNOfNR2RmWgsnItXTa9epiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuaiJEX1Q7MD57SukdB39T8IQj\/og\/zHPzxDrDapJ4gq68n4NKjwp3bb6bT1yuD+ZeilfZECPfnPH1rTPW5NEMkelQQ1KvLvexxDrDapJ4gq7WpqakyUIlysQ6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq4hpdUSe1SnU2PZevbBWNzJMy\/wv9PfjbI2Lb9+Hof4Ht+UwXHNzqeF7vu8p4fMa3vEOsNqkniCrg441u+4cjB6nKAVHEP1DLU=","extGuestTotal":"0","roomTotal":"2649","servicetaxTotal":"900","discount":"0.0","commission":"0","originalRoomTotal":"2649"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000066139","ratePlanCode":"0000396102","ratebands":{"validdays":"1111111","wsKey":"MNzj39yXArU6eaST9cxEwEo8RCJLkHce0APBTp5T0hrNsBdkv84IksPLWUMDieiKw8acyB9lCVGmq4jreNqzC3WUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OF0AjKA2cXvoRU5gns9IrJCJV8zyx9Ro5+jJb+jq3Wi8RCwsaIZd6kIMUJDK\/mi8dYUYg6zJXb9BI6k1+LgY3UFqasZDVSUo0FCetm5bwWAzxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysEcShiBiF5SxjRSaBDG+3rHDB6MiU2ShvsHjlG4I7MKB+h5yY\/\/D\/rXu+7ynh8xre8Q6w2qSeIKuDjjW77hyMHrON3b64sOY+w==","extGuestTotal":"0","roomTotal":"5194","servicetaxTotal":"2646","discount":"0.0","commission":"0","originalRoomTotal":"5194"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00005899","hotelname":"Rajrani Residency","hoteldesc":"Strategically located near to the famous monument of the Taj Mahal, this 3Star property maintains 28 air-conditioned rooms. The Kesar Restaurant offers multi-cuisine fare to its diners from 9a.m to 11p.m. The property is maintained with a business centre for official purpose. Guests can also access the Internet facility, which is complimentary.","starrating":"3","noofrooms":"1","minRate":"1848","rph":"10","webService":"arzooB","contactinfo":{"address":"Mehra Plaza, Tajganj,, Fatehabad Road, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/rye\/mbw\/HO.jpg"}},"geoCode":"27.163436,78.03863"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room Only","roombasis":"No Amenities","roomTypeCode":"0000064362","ratePlanCode":"0000254912","ratebands":{"validdays":"1111111","wsKey":"ds7juGzogz46eaST9cxEwIYhgTRMZn1tiVjuyCNYlXWsk4meyOWXcPRdN0ImxhOaiG065xYCyu8bRbi2hreh2upiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuf7NwKp\/FodjyfH6KUu\/z7+Oo3JvsF0aWdbgo\/Yb\/DgJTIxCa6S2nFFPrVdf+D79sQ4fhYw3gh0ZD8Q7nibT6BDKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YGzxvaItBOC13AbIk7eCTcrYJ4ZcUj\/cJuI8G\/r2tnF7kR2hy6txDrE9wnVBx2KS9f5+CgAkL98z2l3cvVFgXs1cNd1Mjshdw\/SA4C7D+cyeJ","extGuestTotal":"0","roomTotal":"1959","servicetaxTotal":"666","discount":"0.0","commission":"0","originalRoomTotal":"1959"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00001868","hotelname":"Usha Kiran Palace","hoteldesc":"This hotel is situated just 2.5 kms from the railway station and 2.5 kms from the airport. Bearing a total of 24 rooms, this place offers an in-house multi-cuisine restaurant. The hotels proximity to the main shopping complexes, major attractions and main business centres, makes it an ideal place for a stopover.","starrating":"3","noofrooms":"1","minRate":"1899","rph":"68","webService":"arzooB","contactinfo":{"address":"Partappura Crossing, M.G.Road, Pratappura, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Pratappura","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/vye\/jbv\/HO.jpg"}},"geoCode":"27.167034,78.008779"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":",Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), 20% Discount on Food and Beverages, ","roomTypeCode":"0000257000","ratePlanCode":"0000967170","ratebands":{"validdays":"1111111","wsKey":"dTdt3SKKSCM6eaST9cxEwNcm32eIrmwEgg5Mf5eXJSO5d00581HZGWAezBBAASkarAWIFyzCG9IedUJeTrBu0epiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuoMUm48JEuWGqgtlhxOk0zWCau5PAwu\/Idbgo\/Yb\/DgKmD6omrVqR\/+wDTrFGh15eYkFDDwcS4HAYoO8WEmGKhA6mqVB6WrFi8f+1J3k1pm5y51JZEbJa\/xf\/y8WQIQPfFGQGCCjWX4SYIzPjhaBQ7ACnvNhPUgpsIgoK7UO3hADJRoimwy8UqLzurOMs0SIMgSC6cLHYx92x2THP2WAPPx5Ad1lpkUTyFFsCzwFvwLT14HQ9ImiZosddAgP8FMd6ouAPb5eAVXgZZfHMWiv+g8Q6w2qSeIKuFluz6IhIkl6fh2hpeEsls8HjlG4I7MKBn4doaXhLJbPB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rD+s7QLai6cI2WWO2NcB7kJGqMISuzI9f3ecXK3ypZg\/jw+KxAnBHbIQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTSjNbi82GyN0X3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"2013","servicetaxTotal":"684","discount":"0.0","commission":"0","originalRoomTotal":"2013"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room -Single","roombasis":",Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), 20% Discount on Food and Beverages, ","roomTypeCode":"0000257001","ratePlanCode":"0000967173","ratebands":{"validdays":"1111111","wsKey":"dTdt3SKKSCM6eaST9cxEwNcm32eIrmwEgg5Mf5eXJSO5d00581HZGWAezBBAASkarAWIFyzCG9KbEsToLis3\/+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuoMUm48JEuWGqgtlhxOk0zWCau5PAwu\/Idbgo\/Yb\/DgKmD6omrVqR\/+wDTrFGh15eYkFDDwcS4HAYoO8WEmGKhA6mqVB6WrFi8f+1J3k1pm5y51JZEbJa\/xf\/y8WQIQPfFGQGCCjWX4SYIzPjhaBQ7ACnvNhPUgpsIgoK7UO3hADJRoimwy8UqLzurOMs0SIMgSC6cLHYx92x2THP2WAPPx5Ad1lpkUTyFFsCzwFvwLT14HQ9ImiZosddAgP8FMd6ouAPb5eAVXgZZfHMWiv+g8Q6w2qSeIKuFluz6IhIkl6fh2hpeEsls8HjlG4I7MKBn4doaXhLJbPB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rD+s7QLai6cIzmGRHuQ1L+fGqMISuzI9f3ecXK3ypZg\/jw+KxAnBHbIQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTSjNbi82GyN0X3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"2013","servicetaxTotal":"684","discount":"0.0","commission":"0","originalRoomTotal":"2013"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room with Breakfast and Lunch Or Dinner","roombasis":",Lunch or Dinner, Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000257000","ratePlanCode":"0001014602","ratebands":{"validdays":"1111111","wsKey":"uZgnxaPvEkA6eaST9cxEwB7qPBSb08VFweOUbgjswoEiFyU9Z8PAzmQ5oOVMvpmMmv9sYrccn81uxzP6ExLQ+3WUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1ODUfBWkaC+jvmUU2Uo7xH3Q8EonjzfxqIPYJRQ1GQxAVunkZan8IOa+kjaaYP7D0UWu4I5lyaMOUQDhehyWZcgNGQavxySQZY0ATf6X0jGHWAyKM0I3Bo+zWrNj\/XKscJeXyVP39zzDGcFsJFu\/4gTpwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tjdkjdqaIQWG51SZHEH+bS4MU+eaOiGCOdyTJvTT7d6Nbscz+hMS0PsbAUyUDHRst6rdZ1JTSIubM9n0Dni017qN5oMcejY0Rw==","extGuestTotal":"0","roomTotal":"4240","servicetaxTotal":"2160","discount":"0.0","commission":"0","originalRoomTotal":"4240"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room with Breakfast and Lunch or Dinner","roombasis":",Lunch or Dinner, Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000257001","ratePlanCode":"0001014603","ratebands":{"validdays":"1111111","wsKey":"pVTbQpokAd06eaST9cxEwI6TVxgfqCKjweOUbgjswoEiFyU9Z8PAzmQ5oOVMvpmMmv9sYrccn83LyRreiiVzEXWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1ODUfBWkaC+jvmUU2Uo7xH3Q8EonjzfxqIPYJRQ1GQxAVunkZan8IOa+kjaaYP7D0UWu4I5lyaMOUnKr2HkUqcIlQnrZuW8FgM0eFN95NJqIQUg9KiNI+As9qi5Z1duT473qgf6yKnJoRcFsJFu\/4gTpwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tjdkjdqaIQWGFluz6IhIkl7xMmXQ3mxoOusPZgaWJ5fNbscz+hMS0PsbAUyUDHRst6rdZ1JTSIubM9n0Dni017qSXym1lykwcg==","extGuestTotal":"0","roomTotal":"5300","servicetaxTotal":"2700","discount":"0.0","commission":"0","originalRoomTotal":"5300"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ,Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), 20% Discount on Food and Beverages, ","roomTypeCode":"0000257001","ratePlanCode":"0000967184","ratebands":{"validdays":"1111111","wsKey":"qq+Aq9EHZxWaT2zVTPo9tsQ6w2qSeIKujq8ldyimhvGaT2zVTPo9trqu8ZPLqWFimk9s1Uz6PbZT5A9u1VRjKXen6K5i+j4CL3Msa0e\/TKk6eaST9cxEwA+fTZgh1nupQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTLggWybrullbQHU2i+mQP+iUwJepNd1UJLg8igZVgcRfiV9lxDVrAJEzgiLj10z6feRfRkx9Pa7hRW5m3X1IgI8SZSjj2l5wLpRnmBjMsNYxUxvSCe3WTill+EzePNN8AFy+2xLoJEER0iLNNU8aObH0HFJq8sicJ1gG1Fqmlr5O0G+80IX6KQdBFSquv\/leJoYSh5s9G7G6mE7zsrH4Q\/z\/HyD+IhPgLjBEdy2kbog1lsPq\/eXFkoH8ybzhp2YZd4LOh5mEJpP9fLBK4+nxaZ6Ov7tVHt7+JtEfmz714Db1sbA6IBUK8+iPG7whoM\/Qc2wxdSh\/c\/E60TuvRDtLm4nBbCRbv+IE6cJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbY7w66rBl0K0jYtv34eh\/geVRKOTXWJ5SvEOsNqkniCrqqvgKvRB2cVcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXVOdOUY1+OtrsuwTuuT97Mw==","extGuestTotal":"0","roomTotal":"5865","servicetaxTotal":"3944","discount":"0.0","commission":"0","originalRoomTotal":"5865"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ,Complimentary entry ticket for Taj Mahal (only for Indian National and applicable for booking done one day before for 2 people only), 20% Discount on Food and Beverages, ","roomTypeCode":"0000257000","ratePlanCode":"0000967181","ratebands":{"validdays":"1111111","wsKey":"sVwDXvDk2AuaT2zVTPo9tsQ6w2qSeIKutpZlXqb7cF2aT2zVTPo9trqu8ZPLqWFimk9s1Uz6PbZT5A9u1VRjKcHjlG4I7MKBL3Msa0e\/TKk6eaST9cxEwA+fTZgh1nupQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTLggWybrullbQHU2i+mQP+iUwJepNd1UJLg8igZVgcRfiV9lxDVrAJOLBl6NBIAOReVm+XlhBmuxGQavxySQZY0ATf6X0jGHWAyKM0I3Bo+xUxvSCe3WTill+EzePNN8AFy+2xLoJEER0iLNNU8aObH0HFJq8sicJ1gG1Fqmlr5O0G+80IX6KQdBFSquv\/leJoYSh5s9G7G6mE7zsrH4Q\/z\/HyD+IhPgLjBEdy2kbog1lsPq\/eXFkoH8ybzhp2YZd4LOh5mEJpP9fLBK4+nxaZ6Ov7tVHt7+JtEfmz714Db1sbA6IBUK8+iPG7whoM\/Qc2wxdSh\/c\/E60TuvRDtLm4nBbCRbv+IE6cJ1QcdikvX8B5ocRrMkyu3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbY7w66rBl0K0nen6K5i+j4C9iQtgf+eRyTEOsNqkniCrrFcA17w5NgLcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NX1tlEiINK7tPsuwTuuT97Mw==","extGuestTotal":"0","roomTotal":"6077","servicetaxTotal":"4048","discount":"0.0","commission":"0","originalRoomTotal":"6077"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00000520","hotelname":"Hotel Ashish Palace","hoteldesc":"Located next to Chetak Circle, this 3 star hotel is merely 3 kms away from the railway station. Bearing a total of 32 rooms, this hotel comprises an air-conditioned conference hall, which is fully equipped with all the modern business facilities. Diners can savour delicacies at its in-house restaurant that serves Continental and Indian cuisine in a pleasant ambiance.","starrating":"3","noofrooms":"1","minRate":"2100","rph":"2","webService":"arzooB","contactinfo":{"address":"Tourist Complex Area, Fatehabad Road, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/wyh\/fbn\/HO.jpg"}},"geoCode":"27.162166,78.036407"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double  Room","roombasis":"No Amenities","roomTypeCode":"0000179303","ratePlanCode":"0000616934","ratebands":{"validdays":"1111111","wsKey":"ciUk5rsUuQo6eaST9cxEwI7Lsyvl+njXoPUPBcOayfuIbTrnFgLK7+l1Oxc4ywiOuXdNOfNR2RmEwGIh7Ov0XjDO9fyT\/4z5weOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6KRKirogOttucQ1eYvoOiwDamL\/NzEmHbhCP+iD\/Mc\/PEOsNqkniCrr6b1Ug4ytFJZ\/0FayiZJBmXjFE\/OSNGgvRrjn3GwXyWmdctwarUJzf0a459xsF8lpnXLcGq1Cc3yEM0Qk19qbmXdjWlec+xEzs9dTjf\/KlqXTml0IQUsYO4fkV3EPlVAQ81ZcQ8uSy0xDrDapJ4gq5tslTdkdt\/zQ5pdFOZ3J93SucgknhbCnWf5HK8TIYqrz6kl2RU4sfqhbqIJJWQtIuVaEzSCufRYt8Xb3hPTM5BeLOX2WSZ65PEOsNqkniCru74PLu6Yu4G","extGuestTotal":"0","roomTotal":"2100","servicetaxTotal":"681","discount":"97.0","commission":"0","originalRoomTotal":"2227"},"discountMessage":"Promotion Offer,Save 10% on each night"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Family Room","roombasis":"No Amenities","roomTypeCode":"0000179304","ratePlanCode":"0000616936","ratebands":{"validdays":"1111111","wsKey":"nVJaMiNJZmM6eaST9cxEwHkLccQVmNotweOUbgjswoFcNsrH7w1NfcHjlG4I7MKB01XidTku\/7IxF3UfiqD35nWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OF0AjKA2cXvoDgVe4dyJaawChb6UV2VMOGCau5PAwu\/Idbgo\/Yb\/DgJTIxCa6S2nFFPrVdf+D79sQ4fhYw3gh0ZD8Q7nibT6BDKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YGzZZzNIxqLu2XM3HyAGXqc7GV3qoZZw\/YJ+CgAkL98z23hREVuKoaqRAtkpv5ODgAMHjlG4I7MKBdEUzSDHYepO9BYU+tPx6Vhfc5EobFJGF","extGuestTotal":"0","roomTotal":"3710","servicetaxTotal":"1890","discount":"0.0","commission":"0","originalRoomTotal":"3710"}}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00012256","hotelname":"Hotel R K Palace","hoteldesc":"Strategically situated just 1.5kms from the beautiful Taj Mahal is Hotel R K Palace. This hotel in Agra houses 19 well appointed rooms for accommodation. This guest house has a power backup to ensure uninterrupted services and is easily accessible by the nearest rail-head which is just 6kms away. Other amenities include a well informed front desk service and a travel counter to help tour the place.","starrating":"0","noofrooms":"1","minRate":"2232","rph":"43","webService":"arzooB","contactinfo":{"address":"Tajnagri , Shilpgron Road, , Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyk\/ibt\/HO.jpg"}},"geoCode":"0.0,0.0"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Non A\/C Room","roombasis":"No Amenities","roomTypeCode":"0000044129","ratePlanCode":"0000172350","ratebands":{"validdays":"1111111","wsKey":"e+sDxcnoNB86eaST9cxEwMM+GVnPYXxsO0NCrb5XjoPTJtiw8FOhCr8v4+UMzwCEiG065xYCyu+Z3Oneuxm2wupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKunXdblH1JV0iyZxUPr1EircQ6w2qSeIKu6Ppk1ICIctbUyfrBsGQX1lGBa3UYcfoTxDrDapJ4gq7B45RuCOzCgZ+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysMUMzarILHur+JB6tvCIaPLLIT1HHoOpz91QCLt5A3Ggaed1sJztacBAtkpv5ODgAMHjlG4I7MKBdEUzSDHYepPnLpf8HOmuiBfc5EobFJGF","extGuestTotal":"0","roomTotal":"2366","servicetaxTotal":"804","discount":"0.0","commission":"0","originalRoomTotal":"2366"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00005331","hotelname":"Hotel Tara Grand","hoteldesc":"This is a well placed 2Star hotel situated near to the Eastern Gate of the Taj Mahal in front of the Jalma Hospital. There are 18 deluxe rooms maintained with all modern facilities. Guests are offered breakfast service and can also savour on a multi-cuisine fare at the Osite Restaurant, which is a fully air-conditioned 24hours restaurant. The travel counter assists guests with their travel arrangements and organizes tour of the beautiful city. Guests are also provided with secure car parking facility.","starrating":"2","noofrooms":"1","minRate":"2350","rph":"7","webService":"arzooB","contactinfo":{"address":"Taj Link Road, Eastern Gate of Taj Mahal, Near Ship Gram Parking, Taj East Gate Road, AGRA, UTTAR PRADESH, India, Pin-248001","citywiselocation":"Taj East Gate Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/ryj\/gbo\/HO.jpg"}},"geoCode":"27.168567,78.050121"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Double Room","roombasis":"No Amenities","roomTypeCode":"0000174694","ratePlanCode":"0000581040","ratebands":{"validdays":"1111111","wsKey":"vDcDyAG7aDw6eaST9cxEwOmWop42dBElxDrDapJ4gq7Gg+GrZgynhsQ6w2qSeIKuA97PXKj6mJnecXK3ypZg\/r0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYh0LH4tbUXemQl98GFYiWPC4PIoGVYHEX4lfZcQ1awCSEUZVbZ6Mwy1xk5Ny0pJvLkTtnIK786l48hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QNzcLIs5fPHsxDrDapJ4gq7AOG1KG7eSa49E\/KfzSAq2HlJo6ccVJ7\/fF294T0zOQXizl9lkmeuTxDrDapJ4gq4W\/lMj13jq0w==","extGuestTotal":"0","roomTotal":"2491","servicetaxTotal":"846","discount":"0.0","commission":"0","originalRoomTotal":"2491"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00004185","hotelname":"Hotel Moti Palace","hoteldesc":"This five storey hotel with an elevator is located close to the airport in MG Road and maintains 21 centrally air-conditioned luxurious rooms with 24hours room service. Guests can relish on a pure vegetarian fare in the air-conditioned restaurant by the name of Pankhuri. Corporate travelers are facilitated with secretarial services.","starrating":"3","noofrooms":"1","minRate":"2400","rph":"73","webService":"arzooB","contactinfo":{"address":"Dhakran Crossing, M.G. Road, Agra, Agra, Rakab Ganj, AGRA, UTTAR PRADESH, India, Pin-282010","citywiselocation":"Rakab Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/syl\/lbs\/HO.jpg"}},"geoCode":"27.179415,78.005667"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Room","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000018212","ratePlanCode":"0000058639","ratebands":{"validdays":"1111111","wsKey":"MdNRqNlPgzU6eaST9cxEwLMPH5zXbqHmxDrDapJ4gq7BLIntlmy+0MQ6w2qSeIKutqEHLkr+aW632mdpmPG+AL0DGWrmSBV5D59NmCHWe6lPuT6OzXspSHCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/cjW5ScldfYO0dADzDcTXBJguUFn2qdL4cqt36dKzHDCi7xmIRoGHw9mGG2sw8eqaQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq\/ttG+mjHYRHQ\/EO54m0+gQygYBskbu5j5VoTNIK59FiMoGAbJG7uY+VaEzSCufRYvdd0qz+\/eV8Q4fhYw3gh0YSV9dtTZ4iysZpg4DnJqIcmoaqmgc0\/k8wf+IA4CTtS06o7RAJO8Xdw\/sbkZ9PmBvkYNDXzSJ14Q\/+gsmHsGF85IdZGUe8o3yfgoAJC\/fM9uXIWRFz+c3SQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTllTnMITKFH0X3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"2544","servicetaxTotal":"864","discount":"0.0","commission":"0","originalRoomTotal":"2544"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00010983","hotelname":"Hotel Mani Ram Palace","hoteldesc":"Hotel Maniram palace is centrally located in Agra and maintains a total of 36 well furnished rooms that offer comfort and peace to the travelers. To pamper the taste buds of its guests with delicious food, the hotel maintains an in-house restaurant. Guest amenities like taxi hire, medical services, laundry and dry cleaning, currency exchange, ample car parking space and internet access facilities are offered by the hotel.","starrating":"2","noofrooms":"1","minRate":"3499","rph":"69","webService":"arzooB","contactinfo":{"address":"1 Basal Nagar, Fatehabad Road, Agra Cantontment, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Agra Cantontment","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/wyd\/lbq\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000306469","ratePlanCode":"0001079306","ratebands":{"validdays":"1111111","wsKey":"\/I3GjaocBVeaT2zVTPo9tsQ6w2qSeIKu7umD7qTp0kQsnini7qR7QHLnMkuii+fDLJ4p4u6ke0BxRrHOqaj08cQ6w2qSeIKuMU1qHpuXriVPuT6OzXspSJpPbNVM+j22xDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4XQCMoDZxe+idxZ1Cod4UzDbqmYfoboSomg9ZH\/WS\/juhURtg2UJ1H49lyCBaI68\/pRnmBjMsNYwawZsQo2o+OKQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq\/ttG+mjHYRHjkpEfRDFU28ygYBskbu5j5VoTNIK59FiMoGAbJG7uY+VaEzSCufRYvdd0qz+\/eV8Q4fhYw3gh0YSV9dtTZ4iysZpg4DnJqIcmoaqmgc0\/k8wf+IA4CTtS06o7RAJO8Xdw\/sbkZ9PmBsrQTqsip7GCvQkKedYVCco3E3QdPaUjyLPZHc55\/cks+xjZ2tCEZxucJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXc8smLh\/DhcSSX6E1HNBpzg==","extGuestTotal":"0","roomTotal":"3499","servicetaxTotal":"727","discount":"1360.0","commission":"0","originalRoomTotal":"3710"},"discountMessage":"Promotion Offer,Save  43%"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000306472","ratePlanCode":"0001079307","ratebands":{"validdays":"1111111","wsKey":"C3kSi4LLIUmaT2zVTPo9tsQ6w2qSeIKuJaByblOE7YGaT2zVTPo9ttq20ktpKDfUmk9s1Uz6Pba\/Ul1cgyS\/k+dUmRxB\/m0uG4v4ubDrD\/GaT2zVTPo9tsQ6w2qSeIKuweOUbgjswoFrITKP0puHEaqny03be8LkLefQKVIY4LXRfp3O+jGruCHOFjHP9UpCNqYv83MSYduEI\/6IP8xz88Q6w2qSeIKuvJ+DSo8Kd22+m09crg\/mXopX2RAj35zxvgHxT\/tbejv1J+LLGgfYkE\/2KEkYgxXt9unYQjwKmoRFSv+xKDhHyjZBEWtPCirBRUr\/sSg4R8o2QRFrTwoqwaX4awkeoqG42nR9nXRKOhxvr3NCAipU84l+mH8dk+yJUbEhp0A6RwldOaXQhBSxg+Zd47f+VFQ4dTB3hoB1JLOZBfDVyaacw4fJBiE+HegsXnrvf8+TayFfPA2V8+yfoC26NYmpQka9xDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4aew1Tc2IoRTc1zk6SG9cgw==","extGuestTotal":"0","roomTotal":"4099","servicetaxTotal":"1151","discount":"1593.0","commission":"0","originalRoomTotal":"4346"},"discountMessage":"Promotion Offer,Save  43%"}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00010925","hotelname":"Hotel Taj Galaxy","hoteldesc":"Designed in traditional decor, Hotel Taj Galaxy is conveniently located just a kilometer away from the world famous monument- The Taj Mahal. This elegantly designed hotel i exclusively built keeping in mind the needs of the budget travelers. A total of 51 rooms that are spread across five floors are equipped with a French balcony. From freshly baked breads, crunchy cereals during breakfast to fantastic international cuisines, an in-house restaurant offers it all.","starrating":"3","noofrooms":"1","minRate":"2000","rph":"72","webService":"arzooB","contactinfo":{"address":"18 B-18C Taj Nagari,Phase-1,, Fatehabad Road , Agra (India), Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/wyd\/fbs\/HO.jpg"}},"geoCode":"27.159643,78.055817"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room","roombasis":",20% Discount on Food and Beverages, ","roomTypeCode":"0000252629","ratePlanCode":"0000958028","ratebands":{"validdays":"1111111","wsKey":"01LqxuMhPoyaT2zVTPo9tsQ6w2qSeIKuII65N6nXywcExd1p6AVmpQoOM2ccaC02o\/LJSmYowiF8KOXz\/a5okyI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6RTkPHi1FCouA\/nad6juXTOOo3JvsF0aWdbgo\/Yb\/DgKwzT9rdo93CIlo0O6PH5Av8NDSYpXJijilHHKq9DqN2lYxN+b0g3blT\/YoSRiDFe21azFh8DeP3kVK\/7EoOEfKNkERa08KKsFFSv+xKDhHyjZBEWtPCirBpfhrCR6iobjadH2ddEo6HG+vc0ICKlTziX6Yfx2T7IlRsSGnQDpHCV05pdCEFLGD5l3jt\/5UVDh1MHeGgHUks5y1j2vXJbWEPp3u0CJVvKZ8ZHUYDeUCEUe2JKngZu00\/Rp0NnyFpC6vV6nW0osBkBsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXulXZ22sOSsv7F9zkShsUkYU=","extGuestTotal":"0","roomTotal":"3534","servicetaxTotal":"1560","discount":"0.0","commission":"0","originalRoomTotal":"3534"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00012166","hotelname":"The Bharat Guest House","hoteldesc":"The Bharat Guest House is pleasingly structured budget hotel in Agra. This three floored hotel boasts of 20 tastefully furnished rooms for a splendid stay. Facilities offered are healthy breakfast services, free and secured parking space for vehicles and laundry\/dry cleaning facility, etc. An authentic meal can be relished at the hotels multi-cuisine restaurant. There is also a travel desk that arranges for sightseeing tours.","starrating":"0","noofrooms":"1","minRate":"3720","rph":"39","webService":"arzooB","contactinfo":{"address":"Near ITC Mughal Hotel,25\/34A\/18\/2A, Fatehabad Road, Tajgang, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/uyl\/jbt\/HO.jpg"}},"geoCode":"0.0,0.0"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room Single","roombasis":"No Amenities","roomTypeCode":"0000043944","ratePlanCode":"0000172086","ratebands":{"validdays":"1111111","wsKey":"ooPd8tdGMJI6eaST9cxEwL+Rc1Y0+EVIYPnG8KjvIoDTJtiw8FOhCgFLab4RAxPriG065xYCyu9XDJ\/HTUNVf+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuHdCyXEgUI73+67hLuEsTOsWJFePYMoVphyq3fp0rMcMKLvGYhGgYfPRm1B4L965uPC0M2sa4L75PuT6OzXspSHCdUHHYpL1\/AeaHEazJMrtwnVBx2KS9fwHmhxGsyTK79glFDUZDEBXlCYR\/0AcewdEyF2beJ1dmDzVlxDy5LLTEOsNqkniCrqCpDMcDS+x35UbL9G65YSJHmEe62NJecppPbNVM+j22chWdKMtI8lfQA8FOnlPSGsKOJZxauB9j6VBDUq8u97FkByzhS7+O0xsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXuuOrBuS1abgv","extGuestTotal":"0","roomTotal":"3944","servicetaxTotal":"2009","discount":"0.0","commission":"0","originalRoomTotal":"3944"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000043945","ratePlanCode":"0000172087","ratebands":{"validdays":"1111111","wsKey":"FnuROURZvDE6eaST9cxEwHqLokTOa3o+d6formL6PgK9CYXKnUVwLNADwU6eU9IagcdsgOXJRX3cj6fe4VA2GXWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OBcwG5i87Si4hVTzfMRKMeCJYkYLfVL0fhdkLe5D3v\/uhCP+iD\/Mc\/PEOsNqkniCrr6b1Ug4ytFJZ\/0FayiZJBk6eaST9cxEwPRrjn3GwXyWmdctwarUJzf0a459xsF8lpnXLcGq1Cc3yEM0Qk19qbmXdjWlec+xEzs9dTjf\/KlqXTml0IQUsYO4fkV3EPlVAQ81ZcQ8uSy0xDrDapJ4gq5tslTdkdt\/zQ5pdFOZ3J93EXeqiKtptSbckyb00+3ejZk5RLxk3Doquczg+Q77VG\/B45RuCOzCgWshMo\/Sm4cRqqfLTdt7wuTckyb00+3ejQ+xVNGJaOSO","extGuestTotal":"0","roomTotal":"4929","servicetaxTotal":"2511","discount":"0.0","commission":"0","originalRoomTotal":"4929"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00000186","hotelname":"Grand Hotel","hoteldesc":"In the midst of beautifully landscaped lawns lies a colonial structure of the Grand Hotel Agra is a 3 star hotel that graciously combines old world charm with modern day luxury. Located close to the Taj Mahal the hotel is a delightful blend of an ideal location with a pleasing ambience. 71 tastefully furnished rooms, offer a relaxing sanctuary after an action packed day. The all day Multi-Cuisine restaurant serves delicious Indian, Continental and Oriental dishes.","starrating":"4","noofrooms":"1","minRate":"4000","rph":"0","webService":"arzooB","contactinfo":{"address":"137, Station Road, , Agra Cantontment, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Agra Cantontment","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/wyl\/lbt\/HO.jpg"}},"geoCode":"27.158056,78.000278"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Single Room Only","roombasis":"No Amenities","roomTypeCode":"0000000368","ratePlanCode":"0000000367","ratebands":{"validdays":"1111111","wsKey":"uZgnxaPvEkA6eaST9cxEwB7qPBSb08VFweOUbgjswoF3FqI3PczOvtADwU6eU9IaYniYZ2grHEyPMg+TlIX5QnWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OAE7WgppvyGtOwTPho0JJOHAq+jF1TtQBXW4KP2G\/w4CUyMQmuktpxRT61XX\/g+\/bEOH4WMN4IdGdw04mwu6gs0ygYBskbu5j5VoTNIK59FiMoGAbJG7uY+VaEzSCufRYvdd0qz+\/eV8Q4fhYw3gh0YSV9dtTZ4iysZpg4DnJqIcmoaqmgc0\/k8wf+IA4CTtS06o7RAJO8Xdw\/sbkZ9PmBsnB3MwoxLAQaN1bZvcZTUtYvJSzmSzegOfgoAJC\/fM9qZk5n8R922SQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqT\/ZV9r8OlUZcX3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"4240","servicetaxTotal":"2160","discount":"0.0","commission":"0","originalRoomTotal":"4240"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double  Room Only","roombasis":"No Amenities","roomTypeCode":"0000000369","ratePlanCode":"0000013137","ratebands":{"validdays":"1111111","wsKey":"uZgnxaPvEkA6eaST9cxEwB7qPBSb08VFweOUbgjswoF3FqI3PczOvtADwU6eU9IaYniYZ2grHEwrUXeLgEOJsnWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OAE7WgppvyGtOwTPho0JJOHAq+jF1TtQBXW4KP2G\/w4CUyMQmuktpxRT61XX\/g+\/bEOH4WMN4IdGdw04mwu6gs0ygYBskbu5j5VoTNIK59FiMoGAbJG7uY+VaEzSCufRYvdd0qz+\/eV8Q4fhYw3gh0YSV9dtTZ4iysZpg4DnJqIcmoaqmgc0\/k8wf+IA4CTtS06o7RAJO8Xdw\/sbkZ9PmBsgFfEI47vYXJVajk9Afp8bYvJSzmSzegOfgoAJC\/fM9qZk5n8R922SQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqT\/ZV9r8OlUZcX3ORKGxSRhQ==","extGuestTotal":"0","roomTotal":"4240","servicetaxTotal":"2160","discount":"0.0","commission":"0","originalRoomTotal":"4240"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00012042","hotelname":"Hotel Kirandeep","hoteldesc":"Hotel Kirandeep is a hotel located at Mall Road in Agra. There are 20 elegantly decorated guestrooms with attached bathrooms available to choose from. There is a 24 hour front desk to answer guest queries and a travel counter to assist the guests in their sightseeing trips, so that they do not miss on any of the major city attractions. A backup generator is also available to ensure uninterrupted service during power cuts. Guests are also offered breakfast services to start their day on a healthy note.","starrating":"0","noofrooms":"1","minRate":"4092","rph":"34","webService":"arzooB","contactinfo":{"address":"Agra Cantt Station Road , Mall Road, Agra, Mall Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Mall Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/uym\/hbp\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room - A\/C","roombasis":"No Amenities","roomTypeCode":"0000043633","ratePlanCode":"0000171446","ratebands":{"validdays":"1111111","wsKey":"fgGXSsvZOlI6eaST9cxEwDiR22kcPIIo\/0FozSsHXwwExd1p6AVmpbpGeDgTdGP0o\/LJSmYowiG8ctfBwT5wISI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6XydV2CyVg1\/9hNdR98Lom8Q6w2qSeIKu6Ppk1ICIctbUyfrBsGQX1lGBa3UYcfoTxDrDapJ4gq7B45RuCOzCgZ+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysJWkRFmDmt8LuHoWayHQ1XVXbuD9VDcCWzOY23sDz1IYWZuAJ5ny9DpAtkpv5ODgAMHjlG4I7MKBdEUzSDHYepN+r\/A+rtt\/EBfc5EobFJGF","extGuestTotal":"0","roomTotal":"4338","servicetaxTotal":"2210","discount":"0.0","commission":"0","originalRoomTotal":"4338"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00012252","hotelname":"Hotel G L Palace","hoteldesc":"Hotel G L Palace is located in the land of the Taj at Agra. The hotel is located at a decent distance from the famous monument and offers 20 well appointed rooms across a 4 storey structure. The guests can avail round the clock assistance from a well informed front desk service and it also has a backup generator to ensure uninterrupted services.","starrating":"0","noofrooms":"1","minRate":"4650","rph":"41","webService":"arzooB","contactinfo":{"address":"531, M.G Road, Bhagwan Talkies Crossing, M G Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"M G Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uyk\/ibp\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room","roombasis":"No Amenities","roomTypeCode":"0000044123","ratePlanCode":"0000172344","ratebands":{"validdays":"1111111","wsKey":"FnuROURZvDE6eaST9cxEwHqLokTOa3o+d6formL6PgI7G+p9+FyZIOdUmRxB\/m0uGjN4bx7W\/mGHxVrvP1PMJHWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OF0AjKA2cXvokictLO1uMNBJguUFn2qdL4cqt36dKzHDCi7xmIRoGHz0ZtQeC\/eubjwtDNrGuC++T7k+js17KUhwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tr4wMX9rT8Q\/Ni2\/fh6H+B6GQKN3XfutpNyTJvTT7d6Nb6XZ2p\/cn2MbAUyUDHRst6rdZ1JTSIubM9n0Dni017qSLiexI\/Uq7A==","extGuestTotal":"0","roomTotal":"4929","servicetaxTotal":"2511","discount":"0.0","commission":"0","originalRoomTotal":"4929"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000044124","ratePlanCode":"0000172345","ratebands":{"validdays":"1111111","wsKey":"PPeBmSLZ+FA6eaST9cxEwDZb3YPjJboNVHIaLxe2qJkExd1p6AVmpWNG4k0u7PM7o\/LJSmYowiFOTezBwunWeiI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6nQ\/qOMhbdKO9vdT4i8Phg2Cau5PAwu\/Idbgo\/Yb\/DgJTIxCa6S2nFFPrVdf+D79sQ4fhYw3gh0aWtSayYnNsSjKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG6qXkZe0gYdRGiqvpUc4aZX5DBN5Iqb20LSz8Ul9UhfF\/rTNqkrzTSVwnVBx2KS9f5+CgAkL98z2l3cvVFgXs1dPQjHqeoYHK38wqdwBgJ2B","extGuestTotal":"0","roomTotal":"6162","servicetaxTotal":"3140","discount":"0.0","commission":"0","originalRoomTotal":"6162"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00012048","hotelname":"The Taj Residency","hoteldesc":"Architecturally luxurious, The Taj Residency offers the experience of a premium lifestyle in the city of Agra. With world class facilities like airport transportation, Wi-Fi, a parking space, currency exchange, laundry facilities and a travel counter providing guidance to the tourists, the hotel provides a perfect stay option. The hotel also has availability of a backup generator. Guests can relish on a scrumptious fare in the fine dining restaurant as well as enjoy healthy breakfast services.","starrating":"3","noofrooms":"1","minRate":"5208","rph":"35","webService":"arzooB","contactinfo":{"address":"Near Police Chowki, Baluganj, , Balu Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Balu Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/mav\/uym\/hbv\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room","roombasis":"No Amenities","roomTypeCode":"0000043648","ratePlanCode":"0000171462","ratebands":{"validdays":"1111111","wsKey":"wxTcScb8dqA6eaST9cxEwKspJX5Y836fxtOu2hK6arUExd1p6AVmpThNgeUWlc+Bo\/LJSmYowiGsSbfrRipBGyI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe64N3dGtsDm\/sN6CI4ZrOz5HzDhfdh7GrJ6Mlv6OrdaLxIBL+mzP6z++0U5kOFrPoS0Z\/3q38uy5a1azFh8DeP3kVK\/7EoOEfKNkERa08KKsFFSv+xKDhHyjZBEWtPCirBpfhrCR6iobjadH2ddEo6HG+vc0ICKlTziX6Yfx2T7IlRsSGnQDpHCV05pdCEFLGD5l3jt\/5UVDh1MHeGgHUks7l3TTnzUdkZ0JWSbn1FIemqNmrk+JBDMhjWU\/JGLT5k8gfZBQSR6ALEOsNqkniCrhbmFDNCStjijlEhzBvrdTha4IdKUspuxuMvOZDcnAVS","extGuestTotal":"0","roomTotal":"5521","servicetaxTotal":"2813","discount":"0.0","commission":"0","originalRoomTotal":"5521"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007258","hotelname":"Wyndham Grand Agra","hoteldesc":"Built on 18 acres of land, this magnificent property located close to the Taj Mahal is inspired by the intricacy of Mughal architecture with splendid display of terraced lawns, fountains and ancient interiors. A total of 112 spacious suites and rooms are available. Dining options include: The Orient Cafe, Jharoka, Pakhtoon, Zaiqa serve authentic Mughalai, Indian and international cuisines. While Mharo Gaon, the Rajasthani theme restaurant serves the local cuisine with live folk dance performances.","starrating":"5","noofrooms":"1","minRate":"4900","rph":"52","webService":"arzooB","contactinfo":{"address":"7th Milestone,Fatehabad Road, AGRA ,UTTAR PRADEH , Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282006","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"14:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyk\/ibv\/HO.jpg"}},"geoCode":"27.156781,78.063868"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Room Breakfast","roombasis":",Breakfast, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066276","ratePlanCode":"0000266847","ratebands":{"validdays":"1111111","wsKey":"SFhHTfNRWnGaT2zVTPo9tsQ6w2qSeIKuGHGWqsK6BuGj8slKZijCIZ5XYWeGtUnoo\/LJSmYowiFHAtVN6Z9CbCI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6rDJweGsmhMKtKZbUsD9Qvrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvP6UZ5gYzLDWMMvQofmRhbYpHhTfeTSaiEDEXK+9Zi9eLr1sSCYv+t6n7ceECpDHIs2oW44dI380UCyGIb1GXRc35LOFYbi27dLOl9cO4bX480tLutXlRi3Sdi428O5J3VWn47l0YwdMtPrYosVOm+2\/6UUquUms+hRuvejdlgWLHcJ1QcdikvX+IPD199I8HW3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbY5byJL3RkLy0mK0H23KCUQ0WRJwxW5nw7EOsNqkniCrkhYR03zUVpxcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXLIMEvBWXKFGCmuaL\/IlfAA==","extGuestTotal":"0","roomTotal":"5901","servicetaxTotal":"3006","discount":"0.0","commission":"0","originalRoomTotal":"5901"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Premier Room With Breakfast","roombasis":",Breakfast, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066277","ratePlanCode":"0000266849","ratebands":{"validdays":"1111111","wsKey":"WYK6R88fNsiaT2zVTPo9tsQ6w2qSeIKuOx5SBkG73P6j8slKZijCIZ5XYWeGtUnoo\/LJSmYowiFZg9\/2VwQPZyI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6rDJweGsmhMKtKZbUsD9Qvrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvP6UZ5gYzLDWMMvQofmRhbYpHhTfeTSaiEDEXK+9Zi9eLr1sSCYv+t6n7ceECpDHIs2oW44dI380UCyGIb1GXRc35LOFYbi27dLOl9cO4bX480tLutXlRi3Sdi428O5J3VWn47l0YwdMtPrYosVOm+2\/6UUquUms+hRuvejdlgWLHcJ1QcdikvX+IPD199I8HW3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbY5byJL3RkLy8PLWUMDieiKB3Jabxrkef\/EOsNqkniCrlmCukfPHzbIcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXlOx5H1IaA\/GCmuaL\/IlfAA==","extGuestTotal":"0","roomTotal":"6643","servicetaxTotal":"3384","discount":"0.0","commission":"0","originalRoomTotal":"6643"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior room with 2  meals, Breakfast & Lunch or Dinner","roombasis":",Breakfast, Dinner, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066276","ratePlanCode":"0000751110","ratebands":{"validdays":"1111111","wsKey":"4UK7HkrNO6Y6eaST9cxEwEns2eA6nY2\/0APBTp5T0hpZ7ju4aacQy2Q5oOVMvpmMw8acyB9lCVEwaJHhMi2sSHWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OJY0zN33ox0W5GVb\/1Zd5pLDJG63Ev+tO8Q6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAz8ah\/JmA+E38rsDrWA27MY0ZBq\/HJJBljMdrRvjAAdFkkNDuD9p3uSebCoS+Yss2PpsupIBlXwTF1spgijmUXg85AOGgafJgfVqock89A4F\/cbStmorooQsqTP2q3S5LmxanaBhN0UF8ZS3EymOiXURwVjGOg409pCfL4i57el1kCyerrewwsj9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIfla3XJvXmjkPOlQF9FF\/yuQUJ4SP0GINb0OrfOsRQJZxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4TZENuizFqNtwvDxoCIv0ug==","extGuestTotal":"0","roomTotal":"6784","servicetaxTotal":"3456","discount":"0.0","commission":"0","originalRoomTotal":"6784"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Room With Breakfast","roombasis":",Breakfast, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066278","ratePlanCode":"0000266850","ratebands":{"validdays":"1111111","wsKey":"EGSp3WtJwXo6eaST9cxEwMsHG+UgUuTs0APBTp5T0hpZ7ju4aacQy2Q5oOVMvpmMw8acyB9lCVEtga2eLThbynWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OJY0zN33ox0W5GVb\/1Zd5pLDJG63Ev+tO8Q6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAzK7A61gNuzGNGQavxySQZYzHa0b4wAHRZJDQ7g\/ad7knmwqEvmLLNj6bLqSAZV8ExdbKYIo5lF4POQDhoGnyYH1aqHJPPQOBf3G0rZqK6KELKkz9qt0uS5sWp2gYTdFBfGUtxMpjol1EcFYxjoONPaQny+Iue3pdZAsnq63sMLI\/TPACodTPy+u+5tRqvU0cv0zwAqHUz8vrzKoWaVPsO89db6E2oYWHjRLpeoBYeZgEwf+IA4CTtS73BlXg8QWPkqORHj6pLwUZ+neNrhA8rSPZvCoSerqeoo\/LJSmYowiEqC1IN90djY5GX1yaKVHppwe7k7uBDUa5PmeWS+ymo5cQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OARLjhQTm0W+cLw8aAiL9Lo=","extGuestTotal":"0","roomTotal":"7314","servicetaxTotal":"3726","discount":"0.0","commission":"0","originalRoomTotal":"7314"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Premier room with 2 meals , Breakfast & Lunch or Dinner","roombasis":",Breakfast, Dinner, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066277","ratePlanCode":"0000751115","ratebands":{"validdays":"1111111","wsKey":"d1\/4+\/AuaQKaT2zVTPo9tsQ6w2qSeIKucI1TylBfPtij8slKZijCIZ5XYWeGtUnoo\/LJSmYowiFZg9\/2VwQPZyI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6rDJweGsmhMKtKZbUsD9Qvrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvP6UZ5gYzLDWMQb30u4iyia9xLTH2KVGPK0eFN95NJqIQMRcr71mL14uvWxIJi\/63qftx4QKkMcizahbjh0jfzRQLIYhvUZdFzfks4VhuLbt0s6X1w7htfjzS0u61eVGLdJ2Ljbw7kndVafjuXRjB0y0+tiixU6b7b\/pRSq5Saz6FG696N2WBYsdwnVBx2KS9f4g8PX30jwdbcJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tlYOYniQ+NmUmP\/1MrfOTAQKwFTDDhtZxcQ6w2qSeIKud1\/4+\/AuaQJwnVBx2KS9f5+CgAkL98z2l3cvVFgXs1dRuBb6655VCIKa5ov8iV8A","extGuestTotal":"0","roomTotal":"8233","servicetaxTotal":"5814","discount":"0.0","commission":"0","originalRoomTotal":"8233"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Room with Breakfast and Lunch Or Dinner","roombasis":",Lunch or Dinner, Breakfast, ,Pick up from Railway station or Airport., ","roomTypeCode":"0000066278","ratePlanCode":"0000971051","ratebands":{"validdays":"1111111","wsKey":"5sJxuMYibrKaT2zVTPo9tsQ6w2qSeIKu7aLOpXWFEO2j8slKZijCIZ5XYWeGtUnoo\/LJSmYowiGFQm65JQkBJSI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6rDJweGsmhMKtKZbUsD9Qvrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvPwwJRkwyqzgFnh7nQwhR185dWyADcPQTf\/9qmKB0gdCChBafUrOEGSSF0vpiqevPF\/VZp6uWBEDZ6xvPypN9tNODb9gGS\/PWZesPZgaWJ5fNxDrDapJ4gq5Uv\/XG+Ac5P8Q6w2qSeIKu1qampMlCJcrEOsNqkniCrumRpycuiwkwcJ1QcdikvX8w1YVJ7muyaUmK0H23KCUQmqhR5OeBCdfGaYOA5yaiHNbnQO6\/Q2PPxDrDapJ4gq7aH3NA60ud7X9yIa0A0ZDwrFN4frcXvidJitB9tyglEC6ry1QHLiXWxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4IZ8\/yCxsjFEALoAffNoK\/w==","extGuestTotal":"0","roomTotal":"8551","servicetaxTotal":"6036","discount":"0.0","commission":"0","originalRoomTotal":"8551"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room With Breakfast","roombasis":",Breakfast, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066279","ratePlanCode":"0000266851","ratebands":{"validdays":"1111111","wsKey":"uhCTe6JYQVGaT2zVTPo9tsQ6w2qSeIKuu3xEbfsaQSuj8slKZijCIZ5XYWeGtUnoo\/LJSmYowiFsgT1OpT2AfiI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6rDJweGsmhMKtKZbUsD9Qvrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvP6UZ5gYzLDWMMvQofmRhbYpHhTfeTSaiEDEXK+9Zi9eLr1sSCYv+t6n7ceECpDHIs2oW44dI380UCyGIb1GXRc35LOFYbi27dLOl9cO4bX480tLutXlRi3Sdi428O5J3VWn47l0YwdMtPrYosVOm+2\/6UUquUms+hRuvejdlgWLHcJ1QcdikvX+IPD199I8HW3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbYKS51F2eIuX3en6K5i+j4CAgkoquoxo17EOsNqkniCrroQk3uiWEFRcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXTqO\/pXb7y6rxor9Oe\/5dUw==","extGuestTotal":"0","roomTotal":"9788","servicetaxTotal":"7756","discount":"0.0","commission":"0","originalRoomTotal":"9788"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room With Breakfast and Lunch or Dinner","roombasis":",Lunch or Dinner, Breakfast, Complimentary Wi-Fi Internet, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066279","ratePlanCode":"0000971054","ratebands":{"validdays":"1111111","wsKey":"+hJroHEpVIzEOsNqkniCrsHjlG4I7MKBpfmn3U08gXwOaXRTmdyfd+oeYDZSATo6Dml0U5ncn3dibf5sUdp2MZpPbNVM+j22n4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2UnA9NSlU1WKWFGzQAINJ6ej6ZNSAiHLWhCP+iD\/Mc\/PEOsNqkniCroQ1FakCBovhQuk5Mopq0QlOtoccF7EgR3lZvl5YQZrsRkGr8ckkGWNAE3+l9Ixh1gMijNCNwaPsMvQofmRhbYpHhTfeTSaiEDEXK+9Zi9eLr1sSCYv+t6n7ceECpDHIs2oW44dI380UCyGIb1GXRc35LOFYbi27dLOl9cO4bX480tLutXlRi3Sdi428O5J3VWn47l0YwdMtPrYosVOm+2\/6UUquUms+hRuvejdlgWLHcJ1QcdikvX+IPD199I8HW3CdUHHYpL1\/AeaHEazJMrv2CUUNRkMQFeUJhH\/QBx7B0TIXZt4nV2YPNWXEPLkstMQ6w2qSeIKuoKkMxwNL7HflRsv0brlhIkeYR7rY0l5ymk9s1Uz6PbZVxfs8ScWNWzYtv34eh\/gejZsTsW3g16UWW7PoiEiSXqLhYqo8vq1iFluz6IhIkl5rITKP0puHEaqny03be8LkcJ1QcdikvX8a\/Z93qhhMfQ==","extGuestTotal":"0","roomTotal":"11060","servicetaxTotal":"8764","discount":"0.0","commission":"0","originalRoomTotal":"11060"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Suite Room With Breakfast","roombasis":",Breakfast, ,Two Complimentary drink IMFL (House Brand )  Per room  per day, Pick up from Railway station or Airport., ","roomTypeCode":"0000066280","ratePlanCode":"0000266852","ratebands":{"validdays":"1111111","wsKey":"VYNVOxcBFQsPn02YIdZ7qdEyF2beJ1dmhmDwGPuEsMuXKid\/rdIhovLYHT3srwqtiG065xYCyu8CHr8gXvlqy+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuhjo0fxOeabZmBMT51ms7Wgou8ZiEaBh86Mlv6OrdaLxI7ej\/Mh7ujfUn4ssaB9iQSgXFmTpVzfsMUJDK\/mi8da6SN4fgm1CQzRU6CFci1nxsayGzjP2pgZ14wrQ7eAG3GLwzb7CuP7LJctOFMcWFREA5GZyc0BBPeqFomP6oOvh2oF3L4bBUovBSlK3J+wydsEe037jsw6oc3DWIdCaT5DV+jRgjNWDkIgJBW+RPax0SkO5olJdH\/zyGzIii41fjEpDuaJSXR\/+91xVkJdkXIWFYT9FlQjlyN3vJ1hDIB6+o5EePqkvBRnCdUHHYpL1\/IW2zmjrwyG9JitB9tyglEP6TZv9Vodf\/LJ4p4u6ke0BsDD+6mhwvM8Q6w2qSeIKuubeZsXMpJgBwnVBx2KS9fy6oIzEpMOUGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2b0P3KKSR\/DA=","extGuestTotal":"0","roomTotal":"13780","servicetaxTotal":"10920","discount":"0.0","commission":"0","originalRoomTotal":"13780"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Suite Room With Breakfast and Lunch Or Dinner","roombasis":",Lunch or Dinner, Complimentary Wi-Fi Internet, Breakfast, ,Pick up from Railway station or Airport., ","roomTypeCode":"0000066280","ratePlanCode":"0000971055","ratebands":{"validdays":"1111111","wsKey":"F4VK1a8rhaUPn02YIdZ7qTyGzIii41fjWuZiPzcsiYGXKid\/rdIhovLYHT3srwqtiG065xYCyu8CHr8gXvlqy+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuhjo0fxOeabZmBMT51ms7Wgou8ZiEaBh86Mlv6OrdaLxHb9E6vktAlkuDadL5Lsbn8ah\/JmA+E39HhTfeTSaiEFIPSojSPgLPaouWdXbk+O+rj8a7D0Do3yp3LsH920T3rUpHaOoywfKOB6OY3QLy3q77z\/XIqRMEc63tMlLNzTUxtLaBUTsDSao+GIEP4SGYQhzJLFeG31Km9V16Bq\/kvpnXLcGq1Cc39GuOfcbBfJaZ1y3BqtQnN8hDNEJNfam5l3Y1pXnPsRM7PXU43\/ypal05pdCEFLGDuH5FdxD5VQEPNWXEPLkstMQ6w2qSeIKubbJU3ZHbf80OaXRTmdyfd\/qcZ5ztxDbFcJ1QcdikvX8SFEI+OyXXMCICQVvkT2sdVf\/cm0eUt+cbAUyUDHRst6rdZ1JTSIubM9n0Dni017qYCqKzJnpKAA==","extGuestTotal":"0","roomTotal":"15370","servicetaxTotal":"12180","discount":"0.0","commission":"0","originalRoomTotal":"15370"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00002741","hotelname":"Utkarsh Vilas","hoteldesc":"Situated on Fatehbad road. This hotel bears a total of 62 rooms. The gourmets can satiate their taste buds at the multi-cuisine restaurant, or simply unwind at the 24 hrs coffee shop, which overlook the swimming pool. A bar, serving exotic cocktails and mocktails is also available. There is also a gymnasium and a full service spa, including sauna, steam and jacuzzi for rejuvenation. Other hotel facilities include laundry service, doctor on call, travel services and 24 hrs room service.","starrating":"4","noofrooms":"1","minRate":"6600","rph":"54","webService":"arzooB","contactinfo":{"address":"Fatehabad Road, Opp. Pacific Mall, Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/uyf\/hbo\/HO.jpg"}},"geoCode":"27.157883,78.057604"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Club Room with Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000119422","ratePlanCode":"0000441868","ratebands":{"validdays":"1111111","wsKey":"0GZodORoL846eaST9cxEwC\/mDLAI0f41Ni2\/fh6H+B5kMTipIz4Lfnen6K5i+j4CicJAUzCWxz+mixmMUu6D\/nWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OHcueTWM1rAuqMhKu7zEmiBt39f81kKuDqFRG2DZQnUfj2XIIFojrz+lGeYGMyw1jDcRP7FXMnaRbczozSesmF\/vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCIehbZ5jY3KwwBEuOFBObRb4SVPAqg4gTWGoLoLkecsZrxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4eNH4vPyrJ+1wvDxoCIv0ug==","extGuestTotal":"0","roomTotal":"6996","servicetaxTotal":"3564","discount":"0.0","commission":"0","originalRoomTotal":"6996"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Junior Suite with Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000119423","ratePlanCode":"0000441874","ratebands":{"validdays":"1111111","wsKey":"Ixcpy4mKbDU6eaST9cxEwPNXUoJ1GA+q51SZHEH+bS5kMTipIz4Lfnen6K5i+j4CicJAUzCWxz\/vPEQKOqk+NXWUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OHcueTWM1rAuqMhKu7zEmiBt39f81kKuDqFRG2DZQnUfj2XIIFojrz+lGeYGMyw1jDcRP7FXMnaRbczozSesmF\/vubUar1NHL9M8AKh1M\/L677m1Gq9TRy\/TPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCITVA6CFuYhY7jkHk+1mUZJXpc4ccefK3Ruf55ZLsVSuFxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4go2GlKPT4NJwvDxoCIv0ug==","extGuestTotal":"0","roomTotal":"8268","servicetaxTotal":"6552","discount":"0.0","commission":"0","originalRoomTotal":"8268"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00008007","hotelname":"Mango Hotels, Agra","hoteldesc":"This refreshingly budget hotel in Agra is conveniently located on National Highway 2 and just two kms from Sikandara. In the cultural city of India- Agra which houses Taj Mahal, one of the seven wonders of the world, Mango Suites | Ascent  stands out as unique and fully- functional business hotel. The hotel houses a total of 60 guestrooms with elegant decor and modern comforts. Quorum, a conference room is well-equipped with modern amenities. Guests can savor a sumptuous food at Spagetti.","starrating":"3","noofrooms":"1","minRate":"8000","rph":"24","webService":"arzooB","contactinfo":{"address":"Block #66 & 73, NH-2 Delhi Agra Road Next to Dawar, Sikandra , Industries,Near Asaramji Bapu , Sikandra, AGRA, UTTAR PRADESH, India, Pin-282007","citywiselocation":"Sikandra","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/maw\/oym\/dbu\/HO.jpg"}},"geoCode":"27.220959,77.929155"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Double Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259252","ratePlanCode":"0000971897","ratebands":{"validdays":"1111111","wsKey":"AsjT31DbmnGaT2zVTPo9tsQ6w2qSeIKuoFqZT0RaP0RMJ4lBGJMZj5WJ1Md3JPDArAWIFyzCG9Kivq9swZzvq0+rr6bkzm4Lswb5zUd8l4HGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6jwcKiuE+zvxWubvVUqp2T0H66HFs5fqkLg8igZVgcRfiV9lxDVrAJEzgiLj10z6feRfRkx9Pa7hRW5m3X1IgI8SZSjj2l5wLxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysEG7Nh0PfL3I0L1qhBwe6G7L2nOyZOCbfscmac3579pu1VcPwLhKIB5JitB9tyglEGshMo\/Sm4cRqqfLTdt7wuRTtVTFhgUz+HYyx1Z2rdpo","extGuestTotal":"0","roomTotal":"8267","servicetaxTotal":"744","discount":"6076.0","commission":"0","originalRoomTotal":"8763"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Twin Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259253","ratePlanCode":"0000971899","ratebands":{"validdays":"1111111","wsKey":"AsjT31DbmnGaT2zVTPo9tsQ6w2qSeIKuoFqZT0RaP0RMJ4lBGJMZj5WJ1Md3JPDArAWIFyzCG9JxwMyllXCrCU+rr6bkzm4Lswb5zUd8l4HGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6jwcKiuE+zvxWubvVUqp2T0H66HFs5fqkLg8igZVgcRfiV9lxDVrAJEzgiLj10z6feRfRkx9Pa7hRW5m3X1IgI8SZSjj2l5wLxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysEG7Nh0PfL3I9F03QibGE5rL2nOyZOCbfscmac3579pu1VcPwLhKIB5JitB9tyglEGshMo\/Sm4cRqqfLTdt7wuRTtVTFhgUz+HYyx1Z2rdpo","extGuestTotal":"0","roomTotal":"8267","servicetaxTotal":"744","discount":"6076.0","commission":"0","originalRoomTotal":"8763"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Twin Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259256","ratePlanCode":"0000971902","ratebands":{"validdays":"1111111","wsKey":"xZJoRj49duyaT2zVTPo9tsQ6w2qSeIKu0aZO05X5Ab5MJ4lBGJMZj5WJ1Md3JPDArAWIFyzCG9JQte6jx7PFw3ipjPEpsC7x9OpxwI1eilzGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6jwcKiuE+zvxWubvVUqp2T0H66HFs5fqkLg8igZVgcRfiV9lxDVrAJEzgiLj10z6feRfRkx9Pa7hRW5m3X1IgI8SZSjj2l5wLxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysEG7Nh0PfL3IS0797P9s3uKf14Df54lJZscmac3579puDjG5PkOfsGdJitB9tyglEGshMo\/Sm4cRqqfLTdt7wuRd3G7JhD5sKmyVvK821ABl","extGuestTotal":"0","roomTotal":"9067","servicetaxTotal":"816","discount":"6664.0","commission":"0","originalRoomTotal":"9611"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259254","ratePlanCode":"0000971901","ratebands":{"validdays":"1111111","wsKey":"xZJoRj49duyaT2zVTPo9tsQ6w2qSeIKu0aZO05X5Ab5MJ4lBGJMZj5WJ1Md3JPDArAWIFyzCG9IDfrKXl7\/\/UnipjPEpsC7x9OpxwI1eilzGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6jwcKiuE+zvxWubvVUqp2T0H66HFs5fqkLg8igZVgcRfiV9lxDVrAJEzgiLj10z6feRfRkx9Pa7hRW5m3X1IgI8SZSjj2l5wLxDrDapJ4gq4WW7PoiEiSXp+HaGl4SyWzweOUbgjswoGfh2hpeEsls8HjlG4I7MKBVqeZ2fOolBvEOsNqkniCroc+MUrquM6u5UbL9G65YSKS0vvf7eCm44l+mH8dk+yJ1CxQXqIYXo335SGKsJzysEG7Nh0PfL3ITtK5+dzRRr2f14Df54lJZscmac3579puDjG5PkOfsGdJitB9tyglEGshMo\/Sm4cRqqfLTdt7wuRd3G7JhD5sKmyVvK821ABl","extGuestTotal":"0","roomTotal":"9067","servicetaxTotal":"816","discount":"6664.0","commission":"0","originalRoomTotal":"9611"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Double Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000259252","ratePlanCode":"0000971908","ratebands":{"validdays":"1111111","wsKey":"C8vQC+mReNWaT2zVTPo9tsQ6w2qSeIKuVLqvvkwTQ1yj8slKZijCIcmrgmh4Q\/7Po\/LJSmYowiEZddxx4XwnXARLjhQTm0W+ELYZeSiJQbQ6eaST9cxEwA+fTZgh1nupQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTAvC91vWuRjtj36H7wGvJu1qRAAUhvAnBhyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSrXVsgA3D0E38JsvP5LnSlhJeMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3fyl2KTvqLdap\/kcrxMhiqvXLjFK0hiR8Olko5DljAeSAmD6AOblreO7vu8p4fMa3vEOsNqkniCrg441u+4cjB6wzpJNLvIT2BiwpL2g37TRw==","extGuestTotal":"0","roomTotal":"9467","servicetaxTotal":"1002","discount":"6958.0","commission":"0","originalRoomTotal":"10035"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Twin Room with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259253","ratePlanCode":"0000971910","ratebands":{"validdays":"1111111","wsKey":"C8vQC+mReNWaT2zVTPo9tsQ6w2qSeIKuVLqvvkwTQ1yj8slKZijCIcmrgmh4Q\/7Po\/LJSmYowiFc9FSuTbbzQwRLjhQTm0W+ELYZeSiJQbQ6eaST9cxEwA+fTZgh1nupQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTAvC91vWuRjtj36H7wGvJu1qRAAUhvAnBhyq3fp0rMcMKLvGYhGgYfGR\/\/u1bDuycvvSY7fBSl+vsA06xRodeXhQZIeGfCqywGzX\/WZ5SKW+VNFulvnqX4peMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3d1Rne3637QbZ\/kcrxMhiqvXLjFK0hiR8Olko5DljAeSAmD6AOblreO7vu8p4fMa3vEOsNqkniCrg441u+4cjB6wzpJNLvIT2BiwpL2g37TRw==","extGuestTotal":"0","roomTotal":"9467","servicetaxTotal":"1002","discount":"6958.0","commission":"0","originalRoomTotal":"10035"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Duplex Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259257","ratePlanCode":"0000971904","ratebands":{"validdays":"1111111","wsKey":"eKor9EQkcFk6eaST9cxEwL0FLc2s8c\/nweOUbgjswoHRgey\/DhWLgkmK0H23KCUQ7LZebPcnOIJCrhF96DpWGdarJ40Sb7M1weOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6hYRzO5tS1BMnkv\/\/4M0emrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvP0eFN95NJqIQUg9KiNI+As9qi5Z1duT473qgf6yKnJoRcFsJFu\/4gTpwnVBx2KS9fwHmhxGsyTK7cJ1QcdikvX8B5ocRrMkyu\/YJRQ1GQxAV5QmEf9AHHsHRMhdm3idXZg81ZcQ8uSy0xDrDapJ4gq6gqQzHA0vsd+VGy\/RuuWEiR5hHutjSXnKaT2zVTPo9tuf5fTn7k0DNNi2\/fh6H+B4yqe6mEPVL+3CdUHHYpL1\/QtpbKaN8rawO5+o7yVi2fIf0qOkcKwo5\/C137Alaq7ZwJkOohxtWDA==","extGuestTotal":"0","roomTotal":"10000","servicetaxTotal":"1350","discount":"7350.0","commission":"0","originalRoomTotal":"10600"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Twin Room with Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000259256","ratePlanCode":"0000971913","ratebands":{"validdays":"1111111","wsKey":"eubzM\/VgUAKaT2zVTPo9tsQ6w2qSeIKuSq+xcYm7zeSj8slKZijCIcmrgmh4Q\/7Po\/LJSmYowiEaErkqfTuLzzxmjGk+VMTjR7nrcqV9fiQ6eaST9cxEwA+fTZgh1nupQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTAvC91vWuRjtj36H7wGvJu1qRAAUhvAnBhyq3fp0rMcMKLvGYhGgYfGR\/\/u1bDuycvvSY7fBSl+vsA06xRodeXhQZIeGfCqywGzX\/WZ5SKW+VNFulvnqX4peMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3dRFTtBI8+2ap\/kcrxMhiqv15wPti4XixfRMhdm3idXZjL9KUXNEf8QQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTEnhNzj60gjSCmuaL\/IlfAA==","extGuestTotal":"0","roomTotal":"10267","servicetaxTotal":"1386","discount":"7546.0","commission":"0","originalRoomTotal":"10883"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Double Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000259254","ratePlanCode":"0000971912","ratebands":{"validdays":"1111111","wsKey":"eubzM\/VgUAKaT2zVTPo9tsQ6w2qSeIKuSq+xcYm7zeSj8slKZijCIcmrgmh4Q\/7Po\/LJSmYowiGqoalWTFzMazxmjGk+VMTjR7nrcqV9fiQ6eaST9cxEwA+fTZgh1nupQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTAvC91vWuRjtj36H7wGvJu1qRAAUhvAnBhyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSrXVsgA3D0E38JsvP5LnSlhJeMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3c1t3mIFinbP5\/kcrxMhiqv15wPti4XixfRMhdm3idXZjL9KUXNEf8QQLZKb+Tg4ADB45RuCOzCgXRFM0gx2HqTEnhNzj60gjSCmuaL\/IlfAA==","extGuestTotal":"0","roomTotal":"10267","servicetaxTotal":"1386","discount":"7546.0","commission":"0","originalRoomTotal":"10883"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Duplex Room with Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000259257","ratePlanCode":"0000971915","ratebands":{"validdays":"1111111","wsKey":"3n7gwFxe81E6eaST9cxEwKLjBEnLqxzu51SZHEH+bS7Rgey\/DhWLgkmK0H23KCUQ7LZebPcnOIJCrhF96DpWGbkU\/JGXA\/cVweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6hYRzO5tS1BMnkv\/\/4M0emrp5GWp\/CDmvoVEbYNlCdR+PZcggWiOvP0eFN95NJqIQUg9KiNI+As9qi5Z1duT476uPxrsPQOjfKncuwf3bRPf7bRvpox2ER0PxDueJtPoEMoGAbJG7uY+VaEzSCufRYjKBgGyRu7mPlWhM0grn0WL3XdKs\/v3lfEOH4WMN4IdGElfXbU2eIsrGaYOA5yaiHJqGqpoHNP5PMH\/iAOAk7UtOqO0QCTvF3cP7G5GfT5gb3SGu6PYlM2KmAxVmTK7\/3BeQbF2yu0C\/n4KACQv3zPapAyBuvZgSb3CdUHHYpL1\/n4KACQv3zPaXdy9UWBezV\/irp7p3fJ4LcLw8aAiL9Lo=","extGuestTotal":"0","roomTotal":"11200","servicetaxTotal":"1512","discount":"8232.0","commission":"0","originalRoomTotal":"11872"},"discountMessage":"Promotion Offer,Save 75% on Mon, Tue, Wed, Thu, Fri, Sat and Sun"}]},"promotion":"true"},{"hoteldetail":{"hotelid":"00012028","hotelname":"Hotel Narayan Palace","hoteldesc":"This budget property is located at Krishna Nagar in Agra. Sporting a contemporary look, Hotel Narayan Palace offers 10 well furnished rooms that have cream colored walls and wood furniture. A travel desk is available to assist guests in tour planning and transport arrangement. Hotel Narayan Palace is an ideal hotel for families and ensures delightful guest experience.","starrating":"0","noofrooms":"1","minRate":"7440","rph":"36","webService":"arzooB","contactinfo":{"address":"392, Krishna Nagar , Station Road Agra Cantt, Agra Cantontment, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Agra Cantontment","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/uym\/fbv\/HO.jpg"}},"geoCode":","},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Air Cool Room","roombasis":"No Amenities","roomTypeCode":"0000043594","ratePlanCode":"0000171409","ratebands":{"validdays":"1111111","wsKey":"wwrv4IK99v46eaST9cxEwND4BmFAUrNFclTt\/hiFQ67TJtiw8FOhCpNNCUvwBYIQiG065xYCyu9c55K9U2ht4OpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuxNT97CkYciO5\/Gt2\/sPSgzamL\/NzEmHbhCP+iD\/Mc\/PEOsNqkniCrr6b1Ug4ytFJZ\/0FayiZJBk6eaST9cxEwPRrjn3GwXyWmdctwarUJzf0a459xsF8lpnXLcGq1Cc3yEM0Qk19qbmXdjWlec+xEzs9dTjf\/KlqXTml0IQUsYO4fkV3EPlVAQ81ZcQ8uSy0xDrDapJ4gq5tslTdkdt\/zQ5pdFOZ3J93QdLZKc8OqmG\/762rnB5MKveozP3qtUJEntp+uaACUGTB45RuCOzCgWshMo\/Sm4cRqqfLTdt7wuS\/762rnB5MKijglaa8\/LRD","extGuestTotal":"0","roomTotal":"7887","servicetaxTotal":"4018","discount":"0.0","commission":"0","originalRoomTotal":"7887"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00011823","hotelname":"Harshit Paying Guest House","hoteldesc":"Centrally located in Agra, Harshit Paying Guest House is just behind the TDI Mall in Taj Nagri. This two storey hotel has a total of 5 comfortable rooms to offer. The most beautiful Taj Mahal is just a few minutes walk away from the guest house. The airport and Idgah Bus Stand is located around 2 kms away making traveling easier for the guests. Guests can plan a trip to the historic Agra Fort which is 6 kms away. There is a back-up generator installed in case there are any power cuts.","starrating":"0","noofrooms":"1","minRate":"9300","rph":"28","webService":"arzooB","contactinfo":{"address":"50-A, Taj Nagri Phase-1, Behind Tdi Mall, Tajganj, Agra, , Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/3\/nxd\/mav\/vye\/fbq\/HO.jpg"}},"geoCode":"0.0,0.0"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Non Ac Room","roombasis":"No Amenities","roomTypeCode":"0000043125","ratePlanCode":"0000178012","ratebands":{"validdays":"1111111","wsKey":"CRJINjsb6QM6eaST9cxEwEALNbry6B3g51SZHEH+bS5SpmdK1EfQghZbs+iISJJegcdsgOXJRX0gQaXHFslRB3WUsdCvd60fT7k+js17KUiaT2zVTPo9tsQ6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OFGR3J5b1rjLOJej+2UkWjPXtAiICGgG7\/EkrqhIjPqExDrDapJ4gq7o+mTUgIhy1tTJ+sGwZBfWUYFrdRhx+hPEOsNqkniCrsHjlG4I7MKBn4doaXhLJbPB45RuCOzCgZ+HaGl4SyWzweOUbgjswoFWp5nZ86iUG8Q6w2qSeIKuhz4xSuq4zq7lRsv0brlhIpLS+9\/t4KbjiX6Yfx2T7InULFBeohhejfflIYqwnPKwPYCl\/CjLEMKVcowD+5m\/oudzJw6SShfeweOUbgjswoHRYsjITgQEWO77vKeHzGt7xDrDapJ4gq4OONbvuHIwehx0yj1aTdFc","extGuestTotal":"0","roomTotal":"9858","servicetaxTotal":"7812","discount":"0.0","commission":"0","originalRoomTotal":"9858"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00006966","hotelname":"Hotel Raj Palace","hoteldesc":"Located near to the Shilpgram, this 2Star property is maintained with 11 guestrooms, offering the Taj Mahal and the city view from its windows. There is a banquet space for a gathering of 50 -60 people. Guests can satiate their taste buds with a scrumptious fare served at the in-house restaurant. The property is also maintained with a travel counter and free parking space.","starrating":"2","noofrooms":"1","minRate":"10230","rph":"51","webService":"arzooB","contactinfo":{"address":"25\/376 Taj Mahal VIP road, East Gate,, Near Shilpgram Agra, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"10:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/qyd\/jbt\/HO.jpg"}},"geoCode":"27.16478,78.055415"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Room- Breakfast","roombasis":"No Amenities","roomTypeCode":"0000026378","ratePlanCode":"0000099022","ratebands":{"validdays":"1111111","wsKey":"NJokwNjciwMPn02YIdZ7qWMAL03\/AA3w0ychVep8x1ej8slKZijCIYr90pmJCxULo\/LJSmYowiF1KzLPv9c04CI8VQ11F3ouweOUbgjswoGWtSayYnNsSscNUKp24Zn9GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe61ZZQg9ksizuTGq\/cjuSEnmCau5PAwu\/Idbgo\/Yb\/DgJTIxCa6S2nFFPrVdf+D79sQ4fhYw3gh0aOSkR9EMVTbzKBgGyRu7mPlWhM0grn0WLEOoiDJX7yE5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG0YIpfRASNXCmgu9OnoXEWTSXi59RU8SRn\/oXYv34styMs\/SgkRl4cjEOsNqkniCrhbmFDNCStjijlEhzBvrdTjRMhdm3idXZkausIoeB988","extGuestTotal":"0","roomTotal":"10844","servicetaxTotal":"8594","discount":"0.0","commission":"0","originalRoomTotal":"10844"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00007427","hotelname":"The Retreat","hoteldesc":"This contemporary hotel in Agra is very close to the Taj Mahal and the Kheria Airport. There are newly appointed standard and superior guestrooms maintained with hi-end amenities. A signature restaurant serves Chinese, Continental and Indian cuisines to its diners, who can also enjoy a drink in the bar. There is also two banquet venues maintained with impeccable service and is ideal place to host different kinds of small business meets and promotions with a capacity to accommodate up to 100 people.","starrating":"4","noofrooms":"1","minRate":"3200","rph":"60","webService":"arzooB","contactinfo":{"address":"2565A2 Taj Nagari Phase -1,, Near Prateek Enclave, Taj Nagari, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Nagari","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/pyi\/fbu\/HO.jpg"}},"geoCode":"27.162059,78.054192"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room Only","roombasis":",Complimentary Wi-Fi Internet, ,20% Discount on Food and Beverages, ","roomTypeCode":"0000028607","ratePlanCode":"0000872919","ratebands":{"validdays":"1111111","wsKey":"wPYQapXQah\/EOsNqkniCrsHjlG4I7MKBM\/tN0sbWV6wOaXRTmdyfd11Vs0doJSglDml0U5ncn3cYlzkBiJDh+5pPbNVM+j22n4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2AETougxzUyP2exSa3FgK7y4PIoGVYHEX4lfZcQ1awCRM4Ii49dM+n3kX0ZMfT2u4UVuZt19SICPEmUo49pecCzwTP0eLrzIxqzH5qE\/A3XccbLjIxWT60dhzY61uM27lzsg4ikqWgy7fYGr3GxH8\/TyGzIii41fjEpDuaJSXR\/88hsyIouNX4xKQ7miUl0f\/vdcVZCXZFyFhWE\/RZUI5cjd7ydYQyAevqORHj6pLwUZwnVBx2KS9fyFts5o68MhvSYrQfbcoJRD+k2b\/VaHX\/yyeKeLupHtA\/Dy\/Bed3FqXEOsNqkniCrpxThDOY9kB4xDrDapJ4gq7A9hBqldBqH8Q6w2qSeIKuFuYUM0JK2OKOUSHMG+t1OOKFJtzzv6YEbJW8rzbUAGU=","extGuestTotal":"0","roomTotal":"11731","servicetaxTotal":"8976","discount":"0.0","commission":"0","originalRoomTotal":"11731"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room-For Single","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000028606","ratePlanCode":"0000106704","ratebands":{"validdays":"1111111","wsKey":"wPYQapXQah\/EOsNqkniCrsHjlG4I7MKBM\/tN0sbWV6wOaXRTmdyfd11Vs0doJSglDml0U5ncn3eWuk9GO7W47JpPbNVM+j22n4KACQv3zPbHDVCqduGZ\/cYclnMQGbwGDufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2AETougxzUyP2exSa3FgK7y4PIoGVYHEX4lfZcQ1awCRM4Ii49dM+n3kX0ZMfT2u4UVuZt19SICPEmUo49pecC8Q6w2qSeIKuNi2\/fh6H+B6fh2hpeEsls8HjlG4I7MKBn4doaXhLJbPB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rDbhwBfjqF4hyRG0uym\/qvAR3rDhDY5hUzmwaC8rTVJyGhuUmcy1vaE5sGgvK01ScgbAUyUDHRst6rdZ1JTSIubM9n0Dni017qd6IxId8gyTCc3vtkQKtxI","extGuestTotal":"0","roomTotal":"11731","servicetaxTotal":"8976","discount":"0.0","commission":"0","originalRoomTotal":"11731"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room with Breakfast","roombasis":",Breakfast, ,Mandatory Gala dinner on 31st Dec (New year Eve) with unlimited drinks@ 3000\/- per pax and for children from 05 years upto 11 years @ 1500\/- per child., Mandatory New Year Gala Dinner 31 Dec Rs 3500 per person & Rs 1700 per child (6-12 Years)  to be paid directly at the time of check-in by the guest, ","roomTypeCode":"0000028607","ratePlanCode":"0000135991","ratebands":{"validdays":"1111111","wsKey":"c18NM1dHKizEOsNqkniCrsHjlG4I7MKBlhKU6\/BCqzksnini7qR7QJ7Vt0vtZe4+LJ4p4u6ke0AlagpdZDTo18Q6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6o2D3VFzO6XGzeoP636xGH4cqt36dKzHDCi7xmIRoGHxkf\/7tWw7snP2nnivZse15OWjKdUy+ZqcM9DyualGx2iPyZ5+Sd8L392SNWd6XyD9M0WE20N2YIAEBMnKRRCcnkGCbHYfJxdwz3650SQ4v4xSAmo6sW0kgX+WvHrgm4nn2sA+juBg3QUXBiJcYLs+QYaODq0GOd0fzcDc+7Q3xUiqVGk4ozILWI6z\/wpmvKLMi4tJvYBQ55acd6VZVtzxYD1+AZN1moOFqODXcoNpgF6dnuZmauchxWTfJPizZOoqdqHPRVZLhk7BuwR26DPwc64THRNSsCjjl1RZ2Z9X2Wu7r8T4EoUaSUehf4TrBQ409+Uu\/OweC2PpzcGhvPuLuMH++PL2u7JXFk5ZlsqNfUBrdzlD7pSfolx8T3Tq2ds8a4lESUrPtLYpnjIRbZGUZ6C7lRrhcyIE2sLHumXtMjd9gavcbEfz9PIbMiKLjV+MSkO5olJdH\/zyGzIii41fjEpDuaJSXR\/+91xVkJdkXIWFYT9FlQjlyN3vJ1hDIB6+o5EePqkvBRnCdUHHYpL1\/IW2zmjrwyG9JitB9tyglEP6TZv9Vodf\/LJ4p4u6ke0ASvyYPHY6ZmsQ6w2qSeIKufPMM+qmxbOPEOsNqkniCrnNfDTNXRyosxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4PIbMiKLjV+Nb+qq17Fj1mg==","extGuestTotal":"0","roomTotal":"13215","servicetaxTotal":"10132","discount":"0.0","commission":"0","originalRoomTotal":"13215"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Standard Room with Breakfast-For Single","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ,Mandatory Christmas Gala Dinner for 24th Evening at INR 3000 AI per Adult., ","roomTypeCode":"0000028606","ratePlanCode":"0000986073","ratebands":{"validdays":"1111111","wsKey":"c18NM1dHKizEOsNqkniCrsHjlG4I7MKBlhKU6\/BCqzksnini7qR7QJ7Vt0vtZe4+LJ4p4u6ke0BsXGPI545iGcQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6o2D3VFzO6XGzeoP636xGH4cqt36dKzHDCi7xmIRoGHw9mGG2sw8eqaQkUVa5\/1sIacktjDU7bFnb1KlhjkBUq11bIANw9BN\/Z8ccmVYgYQNcFWfx\/qWUgM69H6Kn7u0DgJ2X8Cme3XOaGbZ1iUHFbzaRqThR4c+hCEYyDHoOCAcmIDQfXrrrW4JS\/4bYBfoWZxHftqdsec77bRvpox2ER3cNOJsLuoLNMoGAbJG7uY+VaEzSCufRYjKBgGyRu7mPlWhM0grn0WL3XdKs\/v3lfEOH4WMN4IdGElfXbU2eIsrGaYOA5yaiHJqGqpoHNP5PMH\/iAOAk7UtOqO0QCTvF3cP7G5GfT5gbUn\/TGhXj1Pfl1nP+yp9typwBNW0EbO4hYZhICW\/mpM1I2nZFNT9jlGVo81G88QK6DufqO8lYtnyH9KjpHCsKOfwtd+wJWqu2LPhPy6kbSVJiwpL2g37TRw==","extGuestTotal":"0","roomTotal":"13215","servicetaxTotal":"10132","discount":"0.0","commission":"0","originalRoomTotal":"13215"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00006391","hotelname":"Taj Inn Hotel","hoteldesc":"This well-designed hotel is located in the center of Agra surrounded by malls and boutiques near to the Taj View, X-ing Fatehabad Road. There are 37 Ac deluxe guestrooms maintained with hi-end amenities. The Star Chilly Restaurant serves multi-cuisine fare to its diners, who can also savour on multi-cuisine delicacies at the Flower Nest Restaurant, which is open from 6a.m to 11p.m. The property is also maintained with a free parking zone.","starrating":"3","noofrooms":"1","minRate":"12166.67","rph":"21","webService":"arzooB","contactinfo":{"address":"18\/163 , B\/4, Taj View  X-ing , Fatehabad Road , Fatehabad Road, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Fatehabad Road","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"00:00:00","checkouttime":"00:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/qyj\/mbo\/HO.jpg"}},"geoCode":"27.162031,78.039069"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room Only","roombasis":",20% Discount on Food and Beverages, 10% Discount on Food and Beverages, ","roomTypeCode":"0000023951","ratePlanCode":"0000135240","ratebands":{"validdays":"1111111","wsKey":"prlRqCufspvEOsNqkniCrsHjlG4I7MKBvAd1JpMINCMsnini7qR7QH\/YFMuN7ipALJ4p4u6ke0Dd33TLkdqlYMQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6+MT1vQ9gwwkNV1dy\/1drUcQ6w2qSeIKu6Ppk1ICIctYw0tEitsNmHcT30oekeL4LyWtskExlNeZVGL4Uqisuo4bPsOEOSK\/GDJCy6xVkKQ6rMfmoT8DddxxsuMjFZPrR2HNjrW4zbuXOyDiKSpaDLtmAl1XA5kuZLJ4p4u6ke0ASkO5olJdH\/yyeKeLupHtAEpDuaJSXR\/+91xVkJdkXIWFYT9FlQjlyN3vJ1hDIB6+o5EePqkvBRnCdUHHYpL1\/IW2zmjrwyG9JitB9tyglEP6TZv9Vodf\/LJ4p4u6ke0AGWrkFbfUvwcQ6w2qSeIKutu8jaahtdIzEOsNqkniCrqa5Uagrn7KbxDrDapJ4gq4W5hQzQkrY4o5RIcwb63U4PIbMiKLjV+MGPuxdEPhWug==","extGuestTotal":"0","roomTotal":"12897","servicetaxTotal":"10220","discount":"0.0","commission":"0","originalRoomTotal":"12897"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room- Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ,20% Discount on Food and Beverages, ","roomTypeCode":"0000023951","ratePlanCode":"0000595463","ratebands":{"validdays":"1111111","wsKey":"fr2tM2UReYjEOsNqkniCrsHjlG4I7MKB+QYz0h9e8Rssnini7qR7QH\/YFMuN7ipALJ4p4u6ke0Dd33TLkdqlYMQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6+MT1vQ9gwwkNV1dy\/1drUcQ6w2qSeIKu6Ppk1ICIctZTSHh5\/nvX3kZBq\/HJJBljQBN\/pfSMYdYDIozQjcGj7Nas2P9cqxwlr0sYb67lXwODuIM0iK1QIV56KIVnVuxrAVWsY001YikTYa2j\/Y\/GorWdqcFYAsR7aJ0iDFLknXJDK+imLEcGedM8AKh1M\/L6QyvopixHBnnTPACodTPy+vMqhZpU+w7z11voTahhYeNEul6gFh5mATB\/4gDgJO1LvcGVeDxBY+So5EePqkvBRn6d42uEDytI9m8KhJ6up6ij8slKZijCITUSNR5ua2i7uH5FdxD5VQHPKYDC4MnZuTyGzIii41fjVuH5Vw2UiE1Atkpv5ODgAMHjlG4I7MKBdEUzSDHYepNGSlr0mamf2IKa5ov8iV8A","extGuestTotal":"0","roomTotal":"13162","servicetaxTotal":"10430","discount":"0.0","commission":"0","originalRoomTotal":"13162"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Room Only","roombasis":",20% Discount on Food and Beverages, ","roomTypeCode":"0000023950","ratePlanCode":"0000135239","ratebands":{"validdays":"1111111","wsKey":"\/E8pM7SjMFrEOsNqkniCrsHjlG4I7MKBAl5ZTuwn97Usnini7qR7QH\/YFMuN7ipALJ4p4u6ke0D2JzQSnR1vmsQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6+MT1vQ9gwwkNV1dy\/1drUcQ6w2qSeIKu6Ppk1ICIctYw0tEitsNmHcT30oekeL4LyWtskExlNeZVGL4Uqisuo4bPsOEOSK\/G+20b6aMdhEdD8Q7nibT6BN\/oBXQect9AlWhM0grn0WLf6AV0HnLfQJVoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG+\/7xZPnaG188p2T0DCHRLuEsepNlRxxg2Vo81G88QK6xjj32Ug+YB1laPNRvPECug7n6jvJWLZ8h\/So6RwrCjn8LXfsCVqrtrZIqyQP9G6lYsKS9oN+00c=","extGuestTotal":"0","roomTotal":"13427","servicetaxTotal":"10640","discount":"0.0","commission":"0","originalRoomTotal":"13427"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Room- Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ,20% Discount on Food and Beverages, ","roomTypeCode":"0000023950","ratePlanCode":"0000880748","ratebands":{"validdays":"1111111","wsKey":"wn2s66bsTusPn02YIdZ7qdEyF2beJ1dmiKju5ArJDa\/eZVvmmynhoXhFhjhD63AOiG065xYCyu+vRvM2vkE5o+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu6U2l3ka5Dezb2RFjTR0b1y4PIoGVYHEX4lfZcQ1awCTiwZejQSADkXlZvl5YQZrsRkGr8ckkGWNAE3+l9Ixh1gMijNCNwaPsY4TV5XtWLhz14HQ9ImiZosddAgP8FMd6ouAPb5eAVXgZZfHMWiv+g8Q6w2qSeIKuFluz6IhIkl4xFaaYdMjyuMHjlG4I7MKBMRWmmHTI8rjB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rC65AFgt1iNH7cU+go\/tY7QvF558AwIQfyfgoAJC\/fM9jrn5VRXYAdycJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXxPAEfincvhas4y2qNXPFKw==","extGuestTotal":"0","roomTotal":"13515","servicetaxTotal":"10710","discount":"0.0","commission":"0","originalRoomTotal":"13515"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room- Breakfast & Dinner","roombasis":",Breakfast, Dinner, ,20% Discount on Food and Beverages, ","roomTypeCode":"0000023951","ratePlanCode":"0000690565","ratebands":{"validdays":"1111111","wsKey":"tKdAXruKKo7EOsNqkniCrsHjlG4I7MKBTJVc1jwlodUsnini7qR7QH\/YFMuN7ipALJ4p4u6ke0Dd33TLkdqlYMQ6w2qSeIKuPrUOniWA9WnGHJZzEBm8Bjp5pJP1zETA7vu8p4fMa3vEOsNqkniCrg441u+4cjB6+MT1vQ9gwwkNV1dy\/1drUcQ6w2qSeIKu6Ppk1ICIctad9MAPXtHZjlCetm5bwWAz8ah\/JmA+E388Ez9Hi68yMasx+ahPwN13HGy4yMVk+tHYc2OtbjNu5c7IOIpKloMu2YCXVcDmS5ksnini7qR7QBKQ7miUl0f\/LJ4p4u6ke0ASkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QMl2naRLQ2SdxDrDapJ4gq57bWS7zcIupcQ6w2qSeIKutKdAXruKKo7EOsNqkniCrhbmFDNCStjijlEhzBvrdTi4fkV3EPlVAcq09RDfMlDd","extGuestTotal":"0","roomTotal":"14028","servicetaxTotal":"11116","discount":"0.0","commission":"0","originalRoomTotal":"14028"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Deluxe Room- With All Meals","roombasis":",All Meals, ,20% Discount on Food and Beverages, ","roomTypeCode":"0000023951","ratePlanCode":"0000690552","ratebands":{"validdays":"1111111","wsKey":"npDLVffq1gQPn02YIdZ7qeKFJtzzv6YE+eB9aRz8mZ\/eZVvmmynhoXhFhjhD63AOiG065xYCyu\/3OqZTn0TvUupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu6U2l3ka5Dezb2RFjTR0b1y4PIoGVYHEX4lfZcQ1awCRG5tK70U\/CqCYxikXFqLlmiWjQ7o8fkC\/w0NJilcmKOKUccqr0Oo3aVjE35vSDduVP9ihJGIMV7bVrMWHwN4\/e12FskO1VIws2QRFrTwoqwddhbJDtVSMLNkERa08KKsGl+GsJHqKhuNp0fZ10Sjocb69zQgIqVPOJfph\/HZPsiVGxIadAOkcJXTml0IQUsYPmXeO3\/lRUOHUwd4aAdSSz3mVb5psp4aEaHEZS+kH33UYQ5vPhxINO3LOts7Y5PMZTObZq9v0B5cHjlG4I7MKBayEyj9KbhxGqp8tN23vC5HCdUHHYpL1\/64qq7nlt3f4=","extGuestTotal":"0","roomTotal":"14734","servicetaxTotal":"11676","discount":"0.0","commission":"0","originalRoomTotal":"14734"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00003029","hotelname":"ITC Mughal - A Luxury Collection Hotel","hoteldesc":"City-centric, this 5 Star hotel at Fatehabad Road exudes royalty and is amidst 35 acres of beautifully landscaped garden space, and close to the Taj Mahal and maintains 233 rooms with Mughal interior designs, winning it the Agha Khan Award of excellence for its architecture. The hotel won the coveted Sword of Honour from the British Safety Council for its commitment of safety. The swimming pool is up for renovation until 30 April, 2012. Therefore hotel has made alternate arrangements at the spa pool.","starrating":"5","noofrooms":"1","minRate":"17100","rph":"8","webService":"arzooB","contactinfo":{"address":"Taj Ganj, Fatehabad Road, , Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"14:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/tym\/fbw\/HO.jpg"}},"geoCode":"27.161004,78.043992"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Royal Mughal Suites - Internet Only Rate with Breakfast","roombasis":",Breakfast, ,Mandatory\u00a0Gala\u00a0Dinner\u00a0including\u00a0liquor\u00a0on\u00a024th\u00a0Dec\u00a0at\u00a0INR\u00a04500\u00a0plus\u00a0Tax\u00a0per\u00a0person\u00a0and\u00a0on\u00a031st\u00a0Dec\u00a0at\u00a0INR\u00a07500\u00a0plus\u00a0Tax\u00a0per\u00a0person\u00a0to\u00a0be\u00a0paid\u00a0directly\u00a0at\u00a0the\u00a0hotel\u00a0by\u00a0the\u00a0guest, Complimentary tea \/ coffee maker in the room, Personalized check-in and check-out, Two bottles of packaged drinking water (500 ml) per day, Access to Swimming Pool & Gymnasium, Complimentary access to internet at Club Business Centre (30 minutes), Cocktail Hour at an exclusive lounge from 19:00 - 20:00 hours., Free use of Fitness Center, Compulsory Gala Dinner charges applicable for Christmas and New Year to be paid at the hotel directly, GST as applicable w.e.f. 01-July-2017 and the difference in tax amount to be paid at the time of check-in directly by the guest, Fresh cut fruits platter (on request), ","roomTypeCode":"0000010831","ratePlanCode":"0000115396","ratebands":{"validdays":"1111111","wsKey":"zJqEEppKKUcPn02YIdZ7qSICQVvkT2sdSx3VhwUas+p8jeyR1MRIH\/Om5kkDPbnqiG065xYCyu9t+xSm\/hg7zupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu197VcuF1Zir8LB3temm+Xja4CvzWTJQGy\/HS8nIKZHdtPMqQlbn1DIcqt36dKzHDCi7xmIRoGHxkf\/7tWw7snP2nnivZse15OWjKdUy+ZqcYYIoimmZrgasxQJsL3aJAgPxAYrcWeWUwnh+y1u4uMQLUiqqhdSPrTUFW9W7RqIyvvn5hUeIlU2tpEup0Q2VxriJlAEbNagmvkwyeBtdlmiNHsEcVQQB2riZU13gzuZo+LQYkhaKqRRqevpo\/1hNzGHiLo62vOEXj9qAqJMUxyJ\/fQA5ARMs4gfnGKCy4bGKkCp4E0gAjlKDWXXYqwNsCdRLPj9TkIN+kgHnNtpZTe3C29KsFwn9Q9w6RZCE+F4M\/VjCxmvRwKdJAHo6LORdOWX4TN4803wDiFT3cZZUXkv53qVDwWkgJLnygygrzVhz9ZxeBT\/\/\/KBYbaEpNOymBs9DM7eKM9UdUY3vDlh6hUzPa2kU1F23Sf+DzcKHNOml0vPxw8wZHG2IJpu+ReWBmbD2qeKosLjiCzYIq\/0kNsVPDXJ5hJbcqDbzqn5946CcT3OeciXfQurg2SgVaPPCFDsxMwfQlijPh5Glppcvg\/kQu3PIHuqJG0ht7l9yb0o5GQavxySQZY\/ob6dz16PYwmb\/cbQKvbn+Q7bwWLjeuYk9Sl16vGElYpt4pYQANTNw0T5JL7wh4L8Tfg5KtSiuFj5OwHk5ywZqlbA1UsCzDtFS5s7EeLcib1yyPYLBCh6e4+8e2rSAlQIlZHh8f9fz5uSVNhRgVv7d+vOjLRToBtEhDlaM0W7YDbCqCLaTj383uHOEloQ54C+cltdm+qvCDsBRb4Ne91F8ywRUIrBzZJpQOV5+TDDD9Mg998Z72X\/5TmEhrWoHgAzlJTQrNyqXOv4Mb4BJdc\/btQlRyBYl8NCom+nV7n4SbcMKhjAJKgCLoNeS4xljHZzr97QzeJkTT7d3qZMfTqflG7x1jPzCsoq2NbbgZljbJ6FZuqavClhbwB1oJ\/PQHf4DKasmyh6Qt+5mX9YQ0BG3Uc4NJZFPzjBnP6ejomgNDHLsHF3TQYLJOAgBaPmol\/5AVRIlNWYqppeGWT9icWQpYoCdJx+mWClPDSLAoGL6hPoZh8ML5JLvY64SLSVY2UOnFf+jlLTXAmDM7K1BffdU8EaiyyKd\/RU0DUyXoacEmNgWifZrJwYPrD2YGlieXzcQ6w2qSeIKuVL\/1xvgHOT\/EOsNqkniCrtampqTJQiXKxDrDapJ4gq7pkacnLosJMHCdUHHYpL1\/MNWFSe5rsmlJitB9tyglEJqoUeTngQnXxmmDgOcmohzW50Duv0Njz8Q6w2qSeIKurh\/+8vOhMuPK0HVh8mVhQIkdR8Tyx7xTweOUbgjswoFCNaKsMxLmzkC2Sm\/k4OAAweOUbgjswoF0RTNIMdh6k4Y6sFKDsVzrRWpICVON7iY=","extGuestTotal":"0","roomTotal":"18126","servicetaxTotal":"14364","discount":"0.0","commission":"0","originalRoomTotal":"18126"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Royal Mughal Suites with Breakfast","roombasis":",Breakfast, ,Mandatory\u00a0Gala\u00a0Dinner\u00a0including\u00a0liquor\u00a0on\u00a024th\u00a0Dec\u00a0at\u00a0INR\u00a04500\u00a0plus\u00a0Tax\u00a0per\u00a0person\u00a0and\u00a0on\u00a031st\u00a0Dec\u00a0at\u00a0INR\u00a07500\u00a0plus\u00a0Tax\u00a0per\u00a0person\u00a0to\u00a0be\u00a0paid\u00a0directly\u00a0at\u00a0the\u00a0hotel\u00a0by\u00a0the\u00a0guest, Complimentary tea \/ coffee maker in the room, Two bottles of packaged drinking water (500 ml) per day, Access to Swimming Pool & Gymnasium, GST as applicable w.e.f. 01-July-2017 and the difference in tax amount to be paid at the time of check-in directly by the guest, Fresh cut fruits platter (on request), ","roomTypeCode":"0000010831","ratePlanCode":"0000065467","ratebands":{"validdays":"1111111","wsKey":"fTuEIt0NkSwPn02YIdZ7qRxKCFsAMc2spXjBGpBkCux8jeyR1MRIH\/Om5kkDPbnqiG065xYCyu9t+xSm\/hg7zupiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu197VcuF1Zir8LB3temm+Xja4CvzWTJQGy\/HS8nIKZHdtPMqQlbn1DIcqt36dKzHDCi7xmIRoGHxkf\/7tWw7snP2nnivZse15OWjKdUy+ZqcYYIoimmZrgasxQJsL3aJAgPxAYrcWeWUwnh+y1u4uMQLUiqqhdSPrTUFW9W7RqIyvvn5hUeIlU2tpEup0Q2VxriJlAEbNagmvkwyeBtdlmiNHsEcVQQB2riZU13gzuZo+LQYkhaKqRRqevpo\/1hNzGHiLo62vOEXj9qAqJMUxyJ\/fQA5ARMs4gfnGKCy4bGKkCp4E0gAjlKDWXXYqwNsCdRLPj9TkIN+kgHnNtpZTe3C29KsFwn9Q9w6RZCE+F4M\/VjCxmvRwKdJAHo6LORdOWX4TN4803wDiFT3cZZUXkv53qVDwWkgJLnygygrzVhz9ZxeBT\/\/\/KGuBx9eYJuA9L+3hzJ5l3TRmT9+\/3I7Vf+bb4ghnHBROH8zHDByN3UTKVASIqa7dOAV3qVTZ3+nFmPoGbZbIRLm8zNpdxFKGzggvF8ZvyC+\/zE2CkQX8YskgFEwmFMNEiiv2RhvDb0a8U5hIa1qB4AM3HpyjOXb6REaQOeUqZmHM0JACJfPzC8pusUsQ5\/c9wk7awucwM5k4ogk+NyOjKGUxy3f3DNJ97\/0ksLDKG35878LUX5mdqU2R\/kLjCl6WoiazyKpB\/GBqGo2rw8KSA3CZsPTnUTm2KC1LjUKI7kJVCLMzR+N4KU3arDsILV\/nPw0MRwHHzMD5iX4fhibBycVWYUOWfBtMssQ6w2qSeIKumP\/1MrfOTASM60Vp8\/F0H8HjlG4I7MKBn4doaXhLJbPB45RuCOzCgVanmdnzqJQbxDrDapJ4gq6HPjFK6rjOruVGy\/RuuWEiktL73+3gpuOJfph\/HZPsidQsUF6iGF6N9+UhirCc8rB+ApRtOGWrN68gNxXLKxyNFnZHKnqU\/c+fgoAJC\/fM9jN3p9reDwVpcJ1QcdikvX+fgoAJC\/fM9pd3L1RYF7NXyFfavXmhD9VwvDxoCIv0ug==","extGuestTotal":"0","roomTotal":"19080","servicetaxTotal":"15120","discount":"0.0","commission":"0","originalRoomTotal":"19080"}}]},"promotion":"false"},{"hoteldetail":{"hotelid":"00005891","hotelname":"Hotel Deviram Palace","hoteldesc":"This fully air-conditioned hotel located close to Taj Mahal is artistically designed, which combines modern aspects with traditional touches. There are 30air-conditioned rooms with first class amenities. The Clay Oven Restaurant serves multi-cuisine fare to its diners. There is also a banquet venue, ideal for wedding receptions and private parties. A business center provides facilities for corporate related work.","starrating":"3","noofrooms":"1","minRate":"60000","rph":"9","webService":"arzooB","contactinfo":{"address":"3\/2 Pratap Pura,, Agra, U P, Pratappura, AGRA, UTTAR PRADESH, India, Pin-232001","citywiselocation":"Pratappura","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/rye\/mbo\/HO.jpg"}},"geoCode":"27.166667,78.008611"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Super Deluxe Room with Breakfast","roombasis":",Breakfast, ","roomTypeCode":"0000022035","ratePlanCode":"0000136051","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWsk4meyOWXcLfj43dPHQuAiG065xYCyu++eGG0AtXjBepiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu17VX52kKQQgDyvw8rTwJwTamL\/NzEmHbhCP+iD\/Mc\/PEOsNqkniCrq3FW3lQgL3mCbLz+S50pYSXjFE\/OSNGgvRrjn3GwXyWmdctwarUJzf0a459xsF8lpnXLcGq1Cc3yEM0Qk19qbmXdjWlec+xEzs9dTjf\/KlqXTml0IQUsYO4fkV3EPlVAQ81ZcQ8uSy0xDrDapJ4gq5tslTdkdt\/zQ5pdFOZ3J93RNFQ5V+Tz3R9vd6CyAsShd1yQRGsEeQ\/d2uTZ96xyRVuxzP6ExLQ+xsBTJQMdGy3qt1nUlNIi5sz2fQOeLTXuoEVQal3yTEM","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00009308","hotelname":"Hotel Raakesh","hoteldesc":"City-centric and at a stones throw distance from the main market and Idgah railway station, Hotel Raakesh provides the much -needed comfort and peace to the travelers. Guests can choose to stay in any of the 27 rooms which offer a choice of triple bedded, double bedded and single bedded accommodation. Vegetarians are treated with delicious meals at the in-house restaurant. There is also a meeting room for official purpose.","starrating":"0","noofrooms":"1","minRate":"60000","rph":"66","webService":"arzooB","contactinfo":{"address":"Dhakran Crossing, M.G. Road,, , Dhakran Crossing, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Dhakran Crossing","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/2\/nxd\/maw\/nyj\/dbv\/HO.jpg"}},"geoCode":"27.179169,78.006039"},"ratedetail":{"rate":{"ratetype":"Y","hotelPackage":"N","roomtype":"Single AC Room","roombasis":"No Amenities","roomTypeCode":"0000065649","ratePlanCode":"0000262179","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWctY9r1yW1hIE9FLorBxRLiG065xYCyu8RyZ\/aHMbvSepiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKuPRxp82Ud+KWenDfmWN4W9i4PIoGVYHEX4lfZcQ1awCSEUZVbZ6Mwy1xk5Ny0pJvLD59NmCHWe6k8hsyIouNX4xKQ7miUl0f\/PIbMiKLjV+MSkO5olJdH\/73XFWQl2RchYVhP0WVCOXI3e8nWEMgHr6jkR4+qS8FGcJ1QcdikvX8hbbOaOvDIb0mK0H23KCUQ\/pNm\/1Wh1\/8snini7qR7QGunTy1XkJGZxDrDapJ4gq6IF8HZKJONZH293oLICxKFQtpbKaN8rawO5+o7yVi2fIf0qOkcKwo5\/C137Alaq7awbVYCba0I8A==","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}}},"promotion":"false"},{"hoteldetail":{"hotelid":"00004061","hotelname":"Hotel Mandakini Villas","hoteldesc":"This 3 Star property with white-shaded walls is in close proximity to the heritage monument of the Taj Mahal. Guests can check-in at 8 a.m. to any of the 33 AC rooms, which include 22 superior rooms, nine suite rooms and two Mandakini suites with 24hours room service. A scrumptious fare is also served in the in-house restaurant for 200 people. Corporate travelers are also facilitated with conference facility. The travel desk also organizes sightseeing tour of the city, on request.","starrating":"3","noofrooms":"1","minRate":"60000","rph":"74","webService":"arzooB","contactinfo":{"address":"Fatehabad Road, Purani Mandi, Taj Ganj-Near Kailash Cinema, Taj Ganj, AGRA, UTTAR PRADESH, India, Pin-282001","citywiselocation":"Taj Ganj","locationinfo":"NA","phone":"NA","fax":"NA","email":"NA","website":"NA"},"bookinginfo":{"checkintime":"12:00:00","checkouttime":"12:00:00"},"services":{"creditcards":"All","hotelservices":"NA","roomservices":"NA"},"facilities":"NA","images":{"image":{"imagedesc":"Main","imagepath":"cdn.travelpartnerweb.com\/DesiyaImages\/Image\/1\/nxd\/maw\/sym\/jbo\/HO.jpg"}},"geoCode":"27.165307,78.037938"},"ratedetail":{"rate":[{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive  Double Room With Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000018130","ratePlanCode":"0000122979","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWbBYEQK7zdOhvuq68MJ9KiiG065xYCyu8u6i1BClM69+piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu0QfcTT7x+769Zdt9iAOJnxauuN3WzMAqhyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSrXVsgA3D0E38JsvP5LnSlhJeMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3cE3jD0JK8WCX293oLICxKF3XJBEawR5D93a5Nn3rHJFW7HM\/oTEtD7GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6gRVBqXfJMQw=","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Double Room With Breakfast","roombasis":",Complimentary Wi-Fi Internet, Breakfast, ","roomTypeCode":"0000014350","ratePlanCode":"0000122980","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWbBYEQK7zdOhvuq68MJ9KiiG065xYCyu9KUl8ay6HA++piGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu0QfcTT7x+769Zdt9iAOJnxauuN3WzMAqhyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSrXVsgA3D0E38JsvP5LnSlhJeMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3dVEVV34XGMgH293oLICxKF3XJBEawR5D93a5Nn3rHJFW7HM\/oTEtD7GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6gRVBqXfJMQw=","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Executive Single Room With Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000024567","ratePlanCode":"0000122976","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWbBYEQK7zdOhvuq68MJ9KiiG065xYCyu\/xQvzClvOv2epiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu0QfcTT7x+769Zdt9iAOJnxauuN3WzMAqhyq3fp0rMcMKLvGYhGgYfGR\/\/u1bDuycvvSY7fBSl+vsA06xRodeXhQZIeGfCqywGzX\/WZ5SKW+VNFulvnqX4peMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3fzotDGyu70yX293oLICxKF3XJBEawR5D93a5Nn3rHJFW7HM\/oTEtD7GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6gRVBqXfJMQw=","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Luxury Single Room Only","roombasis":",Complimentary Wi-Fi Internet, ,Late Check out (subject to availability), 10% Discount on Food and Beverages, ","roomTypeCode":"0000024566","ratePlanCode":"0000122978","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWbBYEQK7zdOhvuq68MJ9KiiG065xYCyu\/9JZe\/+DLerOpiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu0QfcTT7x+769Zdt9iAOJnxauuN3WzMAqhyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSr6KDwUQbRE08hdJaxwXakdlRejOky\/+l21v4OkxlVo9YxCQ6lwGE5bWJ6Hi9yHbQL9eB0PSJomaLHXQID\/BTHeqLgD2+XgFV4GWXxzFor\/oPEOsNqkniCrhZbs+iISJJen4doaXhLJbPB45RuCOzCgZ+HaGl4SyWzweOUbgjswoFWp5nZ86iUG8Q6w2qSeIKuhz4xSuq4zq7lRsv0brlhIpLS+9\/t4KbjiX6Yfx2T7InULFBeohhejfflIYqwnPKwayHYgpZdomMdVumGUbgbbrV0FuLjNojYn4KACQv3zPbEb7z5QsnWCXCdUHHYpL1\/n4KACQv3zPaXdy9UWBezV7\/nRf6QPFr7cLw8aAiL9Lo=","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Luxury Double Room Only","roombasis":",Complimentary Wi-Fi Internet, ","roomTypeCode":"0000014351","ratePlanCode":"0000122981","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWbBYEQK7zdOhvuq68MJ9KiiG065xYCyu9Umknbv2hDUepiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu0QfcTT7x+769Zdt9iAOJnxauuN3WzMAqhyq3fp0rMcMKLvGYhGgYfD2YYbazDx6ppCRRVrn\/WwhpyS2MNTtsWdvUqWGOQFSr+20b6aMdhEdD8Q7nibT6BDKBgGyRu7mPlWhM0grn0WIygYBskbu5j5VoTNIK59Fi913SrP795XxDh+FjDeCHRhJX121NniLKxmmDgOcmohyahqqaBzT+TzB\/4gDgJO1LTqjtEAk7xd3D+xuRn0+YG2ozETcJ+KJmJFrGddjoWWZrrU6PqE0+\/j61Dp4lgPVpo6XSAsjf6qDEOsNqkniCrhbmFDNCStjijlEhzBvrdTh3a5Nn3rHJFS30Kptf4gHB","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}},{"ratetype":"Y","hotelPackage":"N","roomtype":"Superior Single Room With Breakfast","roombasis":",Breakfast, Complimentary Wi-Fi Internet, ","roomTypeCode":"0000024563","ratePlanCode":"0000122977","ratebands":{"validdays":"1111111","wsKey":"ySzCn3m1MDUPn02YIdZ7qbXEn6I+8+g8rFRSIoNrVQWbBYEQK7zdOhvuq68MJ9KiiG065xYCyu8zJSXYWZGB9upiGBX8m+ZLxDrDapJ4gq7B45RuCOzCgZa1JrJic2xK3xdveE9MzkF4s5fZZJnrk8Q6w2qSeIKu0QfcTT7x+769Zdt9iAOJnxauuN3WzMAqhyq3fp0rMcMKLvGYhGgYfGR\/\/u1bDuycvvSY7fBSl+vsA06xRodeXhQZIeGfCqywGzX\/WZ5SKW+VNFulvnqX4peMUT85I0aC9GuOfcbBfJaZ1y3BqtQnN\/Rrjn3GwXyWmdctwarUJzfIQzRCTX2puZd2NaV5z7ETOz11ON\/8qWpdOaXQhBSxg7h+RXcQ+VUBDzVlxDy5LLTEOsNqkniCrm2yVN2R23\/NDml0U5ncn3eCLTS4QjVqhX293oLICxKF3XJBEawR5D93a5Nn3rHJFW7HM\/oTEtD7GwFMlAx0bLeq3WdSU0iLmzPZ9A54tNe6gRVBqXfJMQw=","extGuestTotal":"0","roomTotal":"63600","servicetaxTotal":"50400","discount":"0.0","commission":"0","originalRoomTotal":"63600"}}]},"promotion":"false"}]}}';
            //~ $hotelInfo = json_decode($json,TRUE);
            
            //~ $this->apiSearchHotels=$hotelInfo;
            //~ return ;
            
            
            $childStr='';
			if($post['children'])
			{
				$childStr='<child>';
				for($i=$post['children']; $i>0; --$i)
				{
				   $childStr .='<age>12</age>';
				}
				$childStr.='</child>';
					
			}
			
			$parameters = array(
				'fromdate' => $post['start'],
				'todate' =>$post['end'],
				'hotelCityName' =>$post['hotelCityName'],
				'roomStayCandidate' =>'<guestDetails> <adults>'.$post['adults'].'</adults>'.$childStr.'</guestDetails>'
			);       
        
	    //echo '<pre>';print_r($parameters);die;
	    
			$key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYyYzg2ZGRiMzg5MTg5NTIxNTQ3Y2U1NmZlNGUwYWNiOGRkOGRmZGVjMWQ4NDgxZTM3ODg3YmNmYWNmNjc5MGVjZTZmMjNkZDJhYTg2NDg0In0.eyJhdWQiOiI1IiwianRpIjoiNjJjODZkZGIzODkxODk1MjE1NDdjZTU2ZmU0ZTBhY2I4ZGQ4ZGZkZWMxZDg0ODFlMzc4ODdiY2ZhY2Y2NzkwZWNlNmYyM2RkMmFhODY0ODQiLCJpYXQiOjE1MDIxODY1NDIsIm5iZiI6MTUwMjE4NjU0MiwiZXhwIjoxNTMzNzIyNTQyLCJzdWIiOiI3MDgzIiwic2NvcGVzIjpbXX0.lRSF_dySLElmkc4w0q2DoLnZpgXg89WzhglPLIMGaRrb8NdPrxbkaUsmxTwxz9nF5De70CZye7MKxXYRkHoj-x7k_te58aQVYDkQTz14qs1g56AObQoY3pZuZFFI8L27d6PRz0E2XK4YlOrGQXHFhXTNlpLfey-ArKyl53t2FhYTEK3R59Ls9UFQjDRp_26wk0Tip6iKr-6R5d6oDBH4qlXbd49cru9h2BcVPGFI8lryQKkhBza4PLMtfIsbZJtlm_xXffGItKr1q2HQ5PQK8JU1wzPiO0MewfcqNsuoPgdIrDkHAOJtkABmc9xfZlBrQUQaGHZty14dpSwmWt9Jesa5X8Ubermui7ZGcKHFVigdM3Fy0LsTDZHn0d8ewNfBcOoGmWFRMJ4J-i0EqSggYsFBUTPJR4OQRD_mn5BTErr_Y1H-i6YVJ4f_o2BDh5ztRDqcLW74_rNWTzTyzmUC2vYilrWe8ZS5RDWBH9d5aoj1PnJ0ay7jkZllkoPCnpzzhWZc8LJa761r_UBGI5psBsFJdoqkKxrO13UTxzc9dH1Fb76MNv_oarR9CSL4nvNdMp1NGchaVJwuorwLiTdc_tLrdqGk-pNHbp2X6EtwfBwBxA8FXC5ydSMeZmRY1zGgc41blrN1R8PHPcK7TPCmpeTUsLGgouA4-y4xv4xvgEk";

			$header = ["Accept:application/json", "Authorization:Bearer ".$key];

			$method = 'POST';

			$url = "https://www.pay2all.in/api/hotel/v1/HotelAvailSearch";


			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			$response = curl_exec($ch);
			//echo '<pre>';print_r($parameters);
            //echo '<pre>';print_r($response);die;
			$xml=simplexml_load_string($response) or die("Error: Cannot create object");
			//echo '<pre>';print_r($hotelInfo['error']);die;
			
			$json = json_encode($xml);
			
			//echo '<pre>';print_r($json);die;
			$hotelInfo = json_decode($json,TRUE);
            
            
            $this->apiSearchHotels='';
			if(array_key_exists('error',$hotelInfo))
			{
				//echo '<pre>qqq';print_r($hotelInfo);die;
				$this->msg=$hotelInfo['error']['message'];
				$this->getApiResponse=0;

			}
			else
			{
				$this->getApiResponse=1;
				$this->apiSearchHotels=$hotelInfo;	
			}
			
			
		}
		
		protected function generateHtml($hotelInfo)
	    {
			$html=$htmlInner=$amenities='';
			$counter=$loop=0;
			$totalHotels=count($hotelInfo['searchresult']['hotel']);
			foreach($hotelInfo['searchresult']['hotel'] as $key=>$hotelsDet)
			{	
				$hoteldesc = strip_tags($hotelsDet['hoteldetail']['hoteldesc']);

				if (strlen($hoteldesc) > 200) {

					// truncate string
					$stringCut = substr($hoteldesc, 0, 200);

					// make sure it ends in a word so assassinate doesn't become ass...
					$hoteldesc = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a href="/this/story">Read More</a>'; 
				}
				
				//~ if(array_key_exists(0,$hotelsDet['ratedetail']['rate']))
				//~ {
				   //~ $is_Arr=true;
				   //~ $rateArr=$hotelsDet['ratedetail']['rate'][0];
				   //~ $roomtypeArr=array_column($hotelsDet['ratedetail']['rate'],'roomtype');
				   //~ $roomtype=implode(', ',$roomtypeArr);
				   //~ $roombasis=$hotelsDet['ratedetail']['rate'][0]['roombasis'];
				//~ }
				//~ else
				//~ {
					//~ $roomtype=$hotelsDet['ratedetail']['rate']['roomtype'];
					//~ $roombasis=$hotelsDet['ratedetail']['rate']['roombasis'];
					//~ $rateArr=$hotelsDet['ratedetail']['rate'];
					//~ $is_Arr=false;
				//~ }
				
				
				//~ $roombasis=trim($roombasis);
					//~ $roombasisArr=explode(',',$roombasis);
					//~ $amenities=$amenitiesInner='';
					//~ $counter1=0;
					
					//~ $roombasisArrFilter=array_filter($roombasisArr, function($value) { return ($value !== '' && $value !== ' '); });
					//~ //echo '<pre>';print_r($roombasisArrFilter);
					//~ $totalItems=count($roombasisArrFilter);
				    //~ foreach($roombasisArrFilter as $amenty)
				    //~ {
					   //~ //echo '<pre>';print_r($amenty);
					   //~ ++$counter1;
					   //~ $amenitiesInner.='<td><i class="fa fa-check-circle"></i> '.$amenty.'</td>';
					  
					   //~ if($counter1%2==0 || $totalItems==$counter1)
					   //~ {
						   //~ $amenities.='<tr>'.$amenitiesInner.'</tr>';
						   //~ $amenitiesInner='';
					   //~ }
					 
						 
				    //~ }
				
				
				
				
				++$counter;
				$addsArr=explode('Pin',$hotelsDet['hoteldetail']['contactinfo']['address']);
		        
				
				$htmlInner.='<div class="col-md-4">
							  <div class="thumbnail rooms-box clearfix">
								 <img src="http://'.$hotelsDet['hoteldetail']['images']['image']['imagepath'].'" alt="bedroom-1">
								 <div class="caption detail">
									<header class="clearfix">
									   <div class="pull-left">
										  <h5 class="title">
											 <a href="rooms-details.html">'.$hotelsDet['hoteldetail']['hotelname'].'</a>
										  </h5>
										  <ul class="custom-list">
											 <li>
												<a href="javascript:void(0);">'.$addsArr[0].' </a> 
											 </li>
										  </ul>
										 <div class="star-rating">
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=5?'fa-star':'fa-star-o').'" data-rating="5"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=4?'fa-star':'fa-star-o').'" data-rating="4"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=3?'fa-star':'fa-star-o').'" data-rating="3"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=2?'fa-star':'fa-star-o').'" data-rating="2"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=1?'fa-star':'fa-star-o').'" data-rating="1"></span>
									   </div>
									   </div>
									   <div class="price">
										  <span>from Rs.'.$hotelsDet['hoteldetail']['minRate'].'/day</span>
									   </div>
									</header>
									<p>'.$hoteldesc.'</p>';
									
									$htmlInnerr='<table class="table table-striped mt30">
                                       <tbody>'.$amenities.'</tbody>
                                     </table>';
                                     
							$htmlInner.='<div class="btn-div">
									   <a class="hotlink" id="'.$hotelsDet['hoteldetail']['hotelid'].'" href="javascript:void(0);">
									   <span class="read-more">More Details</span>
									   <span class="icon-arrow-right2 bg-danger">
									   <i class="fa  fa-angle-right"></i>
									   </span>
									   </a>
									</div>
								 </div>
							  </div>
						   </div>';
				if($counter%3==0 || $totalHotels==$counter)
				{
					++$loop;
					$html.='<div class="row hotelList"  style="display:none;">'.$htmlInner.'</div>';
					$htmlInner='';
					//if($loop==2)
					//break;
				}

			}
			
			if($counter==0)
			{
			   	$html='<div class="col-md-10 col-md-offset-1"><div class="sooy-not"><h5>'.$this->noRecMsg.'</h5></div></div>';
			}
			
			$this->generateHtml=$html;
			$this->generateHtmlCounter=$counter;
	    }
	    
	    
	   function getHotelFilter($post)
		{
			
			
			$post=$this->clean_input($post);
			
			$check_empty=$post;
			unset($check_empty['children']);
			unset($check_empty['minprice']);
			$this->is_empty($check_empty);
			if($this->is_empty)
			{
				//echo 'hellooo';print_r($post);die;
			   return false;	
			}
			
			$validateArr['date_validate'] = $post['start'];
			$validateArr['date1_validate'] = $post['end'];
			$validateArr['number_validate1'] = $post['adults'];
			$validateArr['number_validate'] = $post['children'];
			$this->validate($validateArr);
			
			if($this->validation_error)
			{
				return false;	
			}
		    $this->apiSearchHotels($post);
		    if($this->getApiResponse==0)
		    {
				$this->getHotelFilter=$this->msg;
				return;
			}
			//echo '<pre>';print_r($this->apiSearchHotels);die;
		    $this->generateHtmlFilter($this->apiSearchHotels,$post);
		    $loadMore='';
		    //echo $this->generateHtmlFilterCounter;die;
		    if($this->generateHtmlFilterCounter>6)
		    {
				$loadMore='<div class="form-group text-center "><input value="Load More" id="loadMore" class="register_btn" type="button"></div>';
			
		    }
		    $this->getHotelFilter=$this->generateHtmlFilter.$loadMore;
		}
		protected function generateHtmlFilter($hotelInfo,$post)
	    {
			$html=$htmlInner=$amenities='';
			$counter=$loop=0;
			$totalHotels=count($hotelInfo['searchresult']['hotel']);
			$priceArr=explode('-',$post['price']);
			foreach($hotelInfo['searchresult']['hotel'] as $key=>$hotelsDet)
			{	
				$hoteldesc = strip_tags($hotelsDet['hoteldetail']['hoteldesc']);

				if (strlen($hoteldesc) > 200) {

					$stringCut = substr($hoteldesc, 0, 200);
					$hoteldesc = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a href="/this/story">Read More</a>'; 
				}
				//~ if(array_key_exists(0,$hotelsDet['ratedetail']['rate']))
				//~ {
				   //~ $is_Arr=true;
				   //~ $rateArr=$hotelsDet['ratedetail']['rate'][0];
				   //~ $roomtypeArr=array_column($hotelsDet['ratedetail']['rate'],'roomtype');
				   //~ $roomtype=implode(', ',$roomtypeArr);
				   //~ $roombasis=$hotelsDet['ratedetail']['rate'][0]['roombasis'];
				//~ }
				//~ else
				//~ {
					//~ $roomtype=$hotelsDet['ratedetail']['rate']['roomtype'];
					//~ $roombasis=$hotelsDet['ratedetail']['rate']['roombasis'];
					//~ $rateArr=$hotelsDet['ratedetail']['rate'];
					//~ $is_Arr=false;
				//~ }
				
				    //~ $roombasis=trim($roombasis);
					//~ $roombasisArr=explode(',',$roombasis);
					//~ $amenities=$amenitiesInner='';
					//~ $counter1=0;
					
					//~ $roombasisArrFilter=array_filter($roombasisArr, function($value) { return ($value !== '' && $value !== ' '); });
					//~ //echo '<pre>';print_r($roombasisArrFilter);
					//~ $totalItems=count($roombasisArrFilter);
				    //~ foreach($roombasisArrFilter as $amenty)
				    //~ {
					   //~ //echo '<pre>';print_r($amenty);
					   //~ ++$counter1;
					   //~ $amenitiesInner.='<td><i class="fa fa-check-circle"></i> '.$amenty.'</td>';
					  
					   //~ if($counter1%2==0 || $totalItems==$counter1)
					   //~ {
						   //~ $amenities.='<tr>'.$amenitiesInner.'</tr>';
						   //~ $amenitiesInner='';
					   //~ }
					 
						 
				    //~ }
				
				//echo '//'.$post['rating'];
				//die;
				if($hotelsDet['hoteldetail']['starrating']==$post['rating'] && $priceArr[0]<=$hotelsDet['hoteldetail']['minRate'] && $hotelsDet['hoteldetail']['minRate']<=$priceArr[1])
				{
					//echo 'enter';die;
					++$counter;
					$addsArr=explode('Pin',$hotelsDet['hoteldetail']['contactinfo']['address']);
					$htmlInner.='<div class="col-md-4">
							  <div class="thumbnail rooms-box clearfix">
								 <img src="http://'.$hotelsDet['hoteldetail']['images']['image']['imagepath'].'" alt="bedroom-1">
								 <div class="caption detail">
									<header class="clearfix">
									   <div class="pull-left">
										  <h5 class="title">
											 <a href="rooms-details.html">'.$hotelsDet['hoteldetail']['hotelname'].'</a>
										  </h5>
										  <ul class="custom-list">
											 <li>
												<a href="javascript:void(0);">'.$addsArr[0].' </a> 
											 </li>
										  </ul>
										 <div class="star-rating">
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=5?'fa-star':'fa-star-o').'" data-rating="5"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=4?'fa-star':'fa-star-o').'" data-rating="4"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=3?'fa-star':'fa-star-o').'" data-rating="3"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=2?'fa-star':'fa-star-o').'" data-rating="2"></span>
											<span class="fa '.($hotelsDet['hoteldetail']['starrating']>=1?'fa-star':'fa-star-o').'" data-rating="1"></span>
									   </div>
									   </div>
									   <div class="price">
										  <span>from Rs.'.$hotelsDet['hoteldetail']['minRate'].'/day</span>
									   </div>
									</header>
									<p>'.$hoteldesc.'</p>';
									
							$htmlInnerr='<table class="table table-striped mt30">
                                       <tbody>'.$amenities.'</tbody>
                                     </table>';
                                     
							$htmlInner.='<div class="btn-div">
									   <a class="hotlink" id="'.$hotelsDet['hoteldetail']['hotelid'].'" href="javascript:void(0);">
									   <span class="read-more">More Details</span>
									   <span class="icon-arrow-right2 bg-danger">
									   <i class="fa  fa-angle-right"></i>
									   </span>
									   </a>
									</div>
								 </div>
							  </div>
						   </div>';
						   $htmlTop='<div class="row hotelList"  style="display:none;">'.$htmlInner.'</div>';
							if($counter%3==0 )
							{
								++$loop;
								$html.=$htmlTop;
								$htmlInner='';
								//if($loop==2)
								//break;
							}
				
				
			}
			
		}
		    if($counter==0)
			{
				//echo '$this->msg===='.$this->msg;
				$html='<div class="col-md-10 col-md-offset-1"><div class="sooy-not"><h5>'.$this->noRecMsg.'</h5></div></div>';
			}
			$this->generateHtmlFilter=$html;
			$this->generateHtmlFilterCounter=$counter;
	    
	}
	
	function getHotelDetails($post)
	{
		
		$post=$this->clean_input($post);
		$check_empty=$post;
		unset($check_empty['children']);
		unset($check_empty['is_login']);
		unset($check_empty['user']);
		$this->is_empty($check_empty);
		if($this->is_empty)
		{   
			//echo 'errrqqor';die;
		   return false;	
		}
		//echo 'errroawwqqqqqr';die;
		$validateArr['date_validate'] = $post['start'];
		$validateArr['date1_validate'] = $post['end'];
		$validateArr['number_validate1'] = $post['id'];
		$validateArr['number_validate'] = $post['children'];
		$this->validate($validateArr);
		
		if($this->validation_error)
		{
			//echo 'errror';die;
			return false;	
		}
		$this->apiSearchHotels($post);
	    if($this->getApiResponse==0)
		{
			$this->generateHtmlDetails=$this->msg;
			return;
		}
		//echo '<pre>';print_r($this->apiSearchHotels);die;
		$this->generateHtmlDetails($this->apiSearchHotels,$post);

	}
		
	protected function curlHitApi($parameters,$key,$url)
	{
		
		$header = ["Accept:application/json", "Authorization:Bearer ".$key];

		$method = 'POST';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
		//echo '<pre>';print_r($parameters);
        //echo '<pre>wweer';print_r($response);die;
		$xml=simplexml_load_string($response) or die("Error: Cannot create object");
		//echo '<pre>';print_r($xml);die;
		$json = json_encode($xml);
		$hotelInfo = json_decode($json,TRUE);
		if(array_key_exists('error',$hotelInfo))
		{
			$this->curlHitApi=$hotelInfo['error']['message'];

		}
		else
		{
			$this->curlHitApi=$hotelInfo;
			
		}
		
		
	}
		
	public function generateHtmlDetails($hotelInfo,$post)
	{

		$html='';
		$counter=0;

		foreach($hotelInfo['searchresult']['hotel'] as $key=>$hotelsDet)
		{

			if($hotelsDet['hoteldetail']['hotelid']==$post['id'])
			 {	
				 if(array_key_exists(0,$hotelsDet['ratedetail']['rate']))
					{
					   $is_Arr=true;
					   unset($hotelsDet['ratedetail']['rate'][0]['ratebands']['originalRoomTotal']);
					   $rateArr=$hotelsDet['ratedetail']['rate'][0];
					   $roomtypeArr=array_column($hotelsDet['ratedetail']['rate'],'roomtype');
					   $roomtype=implode(', ',$roomtypeArr);
					   $roombasis=$hotelsDet['ratedetail']['rate'][0]['roombasis'];
					  
					}
					else
					{
						unset($hotelsDet['ratedetail']['rate']['ratebands']['originalRoomTotal']);
						$roomtype=$hotelsDet['ratedetail']['rate']['roomtype'];
						$roombasis=$hotelsDet['ratedetail']['rate']['roombasis'];
						$rateArr=$hotelsDet['ratedetail']['rate'];
						$is_Arr=false;
						
					}
					$roombasis=trim($roombasis);
					$roombasisArr=explode(',',$roombasis);
					$amenities=$amenitiesInner='';
					$counter1=0;
					
					$roombasisArrFilter=array_filter($roombasisArr, function($value) { return ($value !== '' && $value !== ' '); });

					$totalItems=count($roombasisArrFilter);
				    foreach($roombasisArrFilter as $amenty)
				    {

					   ++$counter1;
					   $amenitiesInner.='<td><i class="fa fa-check-circle"></i> '.$amenty.'</td>';
					  
					   if($counter1%3==0 || $totalItems==$counter1)
					   {
						   $amenities.='<tr>'.$amenitiesInner.'</tr>';
						   $amenitiesInner='';
					   }
					 
						 
				    }
				
				
				 ++$counter;
				 // echo '<pre>';print_r($post):die;
				 $session=$post['is_login']==1?json_decode($post['user']):json_decode('{"id":0,"name":""}');
				// echo '<pre>';print_r($post):die;
			
					
					$html='<div class="container">
         <div class="row">
            <div class="col-md-8 details-slider1">
               <div id="myCarousel" class="carousel slide" data-ride="carousel">
                  <!-- Indicators -->
                  <!-- Wrapper for slides -->
                  <div class="carousel-inner">
                     <div class="item active">
                        <img style="height: 429px;" src="http://'.$hotelsDet['hoteldetail']['images']['image']['imagepath'].'" alt="Los Angeles">
                     </div>
                     <div class="item">
                        <img style="height: 429px;" src="http://'.$hotelsDet['hoteldetail']['images']['image']['imagepath'].'" alt="Chicago">
                     </div>
                     <div class="item">
                        <img style="height: 429px;" src="http://'.$hotelsDet['hoteldetail']['images']['image']['imagepath'].'" alt="New York">
                     </div>
                  </div>
                  <!-- Left and right controls -->
                  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                  <span class=""><img src="img/leftslider.png"></span>
                  <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#myCarousel" data-slide="next">
                  <span class=""><img src="img/rightslider.png"></span>
                  <span class="sr-only">Next</span>
                  </a>
               </div>
            </div>
            <div class="col-md-4">
               <div id="servicebg" class="">
                  <div class="inner-wraper">
                     <div class="tab-offer">
                        <section class="home-content-top hotel-side">
                           <div id="registerform" class=" text-center">
                              <h5 class="tiitle-s">'.$hotelsDet['hoteldetail']['hotelname'].'</h5>
                              <form>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                       <label for="name" class="cols-sm-2 control-label">Where are you going?</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                                             <input  disabled="disabled" class="form-control" name="from_location" id="from_location" placeholder="City, Airport, etc" type="text" value="'.($post['hotelCityName']?$post['hotelCityName']:'').'">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="clearfix">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label for="email" class="cols-sm-2 control-label">Check-in</label>
                                          <div class="cols-sm-10">
                                             <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                                                <input disabled="disabled" class="form-control" name="start" id="start" placeholder="YYYY-MM-DD" type="date" value="'.($post['start']?$post['start']:'').'">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label for="email" class="cols-sm-2 control-label">Check-out</label>
                                          <div class="cols-sm-10">
                                             <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
                                                <input  disabled="disabled" autocomplete="on" class="form-control" name="depart_date" id="depart_date" placeholder="YYYY-MM-DD" type="date" value="'.($post['end']?$post['end']:'').'">
                                             </div>
                                          </div>
                                          <p class="mobile error"></p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="clearfix">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label for="email" class="cols-sm-2 control-label">Adults </label>
                                          <div class="cols-sm-10">
                                             <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                                 <input  disabled="disabled" class="form-control" name="adults" id="adults" value="'.$post['adults'].'" type="number">
                                                
                                             </div>
                                          </div>
                                          <p class="mobile error"></p>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label for="username" class="cols-sm-2 control-label">Children </label>
                                          <div class="cols-sm-10">
                                             <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                                <input disabled="disabled" class="form-control" name="children" id="children" value="'.$post['children'].'" type="number">
                                                
                                             </div>
                                          </div>
                                          <p class="referid error text-danger"></p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="form-group text-center ">';
                                 if($post['is_login']==0)
                                 {
									$html.='<a href="'.base_url().'login" type="button">
                                    <input value="Book Now" name="submitForm" class="register_btn " type="button"></a>';
                                 
								 }
								 else
								 {
									 $html.='<input value="Book Now" name="submitForm" id="provBook" class="register_btn " type="button">';
                                 
							     }
                                  $html.='</div>
                              </form>
                           </div>
                        </section>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6 tab-offer tabh">
               <div class="col-md-6">
                  <h5 class="tiitle-s">'.$hotelsDet['hoteldetail']['hotelname'].'</h5>
               </div>
               <div class="col-md-6">
                  <h5 class="tiitle-s">₹ '.$hotelsDet['hoteldetail']['minRate'].'</h5>
               </div>
               <table class="table table-striped mt30">
                  <tbody>'.$amenities.'</tbody>
               </table>
               <p>'.$hotelsDet['hoteldetail']['hoteldesc'].'</p>
            </div>
            <div class="col-md-6">
               <div class="tab-offer">
                  <section class="home-content-top">
                     <!--our-quality-shadow-->
                     <div class="tabbable-panel margin-tops4 ">
                        <div class="tabbable-line">
                           <ul class="nav nav-tabs tabtop">
                              <li class="active"> <a href="#tab_default_1" data-toggle="tab"> Address  </a> </li>
                              <li> <a href="#tab_default_2" data-toggle="tab"> Entry Time</a> </li>
                           </ul>
                           <div class="tab-content margin-tops">
                              <div class="tab-pane active fade in" id="tab_default_1">
                                 <div class="col-md-12">
                                   '.$hotelsDet['hoteldetail']['contactinfo']['address'].'
                                 </div>
                              </div>
                              <div class="tab-pane fade" id="tab_default_2">
                                 <div class="col-md-12">
                                    Check-in : '.$hotelsDet['hoteldetail']['bookinginfo']['checkintime'].', Check-out : '.$hotelsDet['hoteldetail']['bookinginfo']['checkouttime'].'
                                 </div>
                              </div>
                             
                           </div>
                        </div>
                     </div>
                  </section>
               </div>
            </div>
         </div>
         
      </div>';
					
					
					
					
					
					$this->encodeStr($hotelsDet['hoteldetail']['minRate']);
					
					
				$this->form='
				<form id="proviForm" action="" method="post">
				
				<input id="webService"  name="webService"   value="'.$hotelsDet['hoteldetail']['webService'].'" type="hidden">';    
			
				 $this->form.="<input id='ratebands'   name='ratebands'    value='".json_encode($rateArr['ratebands'])."' type='hidden'>";
		
							
					$this->form.='<input id="RoomType"    name="RoomType"     value="'.$rateArr['roomtype'].'" type="hidden">
							
							<input id="RoomTypeCode"  name="RoomTypeCode"   value="'.$rateArr['roomTypeCode'].'" type="hidden">     
							<input id="RatePlanCode"  name="RatePlanCode"   value="'.$rateArr['ratePlanCode'].'" type="hidden">
							<input id="roombasis"     name="roombasis"      value="'.$rateArr['roombasis'].'" type="hidden">
							';
					$this->form.="<input id='roomStayCandidate'  name='roomStayCandidate'   value='".json_encode($hotelInfo['requestSegment']['roomStayCandidate'])."' type='hidden'>";    
							$this->form.='<input id="Hotelid"             name="Hotelid"              value="'.$hotelsDet['hoteldetail']['hotelid'].'" type="hidden">
							<input id="firstName"     id="firstName"      name="firstName"            value="'.$session->name.'" type="hidden">
							
							<input id="lastName"      id="lastName"       name="lastName"              value="'.$session->name.'" type="hidden">  
							
							<input id="todate"     id="todate"        name="todate"                value="'.$hotelInfo['requestSegment']['stayDateRange']['end'].'" type="hidden">
							<input id="fromdate"      id="fromdate"   name="fromdate"              value="'.$hotelInfo['requestSegment']['stayDateRange']['start'].'" type="hidden"> 
							   
							<input id="residentOfIndia"     name="residentOfIndia"       value="'.$hotelInfo['requestSegment']['residentOfIndia'].'" type="hidden">
							 ';
					
					$this->form.='</form>';
					$this->paymentForm='<form id="payForm" action="'.base_url().'secure/paymentForHotel" method="post">
						<input id="hotel_order_id"  name="hotel_order_id"   value="" type="hidden">
						<input id="amount"          name="amount"           value="'.$this->encodeStr.'" type="hidden">
						<input id="hotelid"         name="hotelid"          value="'.$hotelInfo['searchresult']['hotel'][0]['hoteldetail']['hotelid'].'" type="hidden">
					</form>'; 
					
				$this->hotelInfoForm='<form id="hotelInfoForm" action="" method="post">
						<input id="hotelName"     type="hidden" name="hotelName"     value="'.$hotelsDet['hoteldetail']['hotelname'].'" >
						<input id="hotelAddress"  type="hidden" name="hotelAddress"  value="'.$hotelsDet['hoteldetail']['contactinfo']['address'].'" >
						<input id="entryTime"  type="hidden" name="entryTime"  value=" Check-in : '.$hotelsDet['hoteldetail']['bookinginfo']['checkintime'].', Check-out : '.$hotelsDet['hoteldetail']['bookinginfo']['checkouttime'].'" >
					</form>';
			}
			
			
		}
		if($counter==0)
		{
			$html=$this->msg;
		}
	   
		$this->generateHtmlDetails=$html.$this->form.$this->paymentForm.$this->hotelInfoForm;
	}
	
	public function insertPaymentDetail($post)
	{
		$user= \Core\Auth::guard('user')->user();
		$session=json_decode($user);
		$contact_details=json_encode($session);
		
		$this->decodeStr($post['amount']);
		unset($post['amount']);
		
		$hotelInfo['hotelName']=$post['hotelName'];
		$hotelInfo['hotelAddress']=$post['hotelAddress'];
		$hotelInfo['entryTime']=$post['entryTime'];
		
		unset($post['hotelName']);
		unset($post['hotelAddress']);
		unset($post['entryTime']);
		
		$hotelInfoJson=json_encode($hotelInfo);
        $hotel_details=json_encode($post);
        
        $hotel_details=stripslashes($hotel_details);
        //echo '<pre>';print_r(stripslashes($hotel_details));die;
		$user_id=$user->id;
		$date=date('Y-m-d H:i:s');
		$amount=$this->decodeStr;
		$user_insert = \App\Model\Order::create(['hotel_details'=>$hotel_details,'contact_details'=>$contact_details,'user_id'=>$user_id,'order_date'=>$date,'amount'=>$amount,'hotel_info'=>$hotelInfoJson]);
		//echo '$amount'.$amount;die;
		
		if($user_insert)
		{
			
			echo  base64_encode($user_insert->id);
		}
		else
		{
			echo 0;
		}
		
	}
	
   public function updatePaymentStatus($get)
   {
       //echo '<pre>';print_r($get);
	    $order=\App\Model\Order::find($get['hotel_order_id']);
	    $order->payment_status=$get['order_status'];
	    $order->payment_status_response=$get['response'];
	    $order->save();
        //echo '<pre>';print_r($order);die;
		if($order)
		{
			if($order->payment_status=='Completed')
			{
				return $this->provisionalBooking($get);
			}
			else
			{
				return false;
			}

		}
		else
		{
			return false;
		}
	}

	public function provisionalBooking($get)
	{
		
		$user= \Core\Auth::guard('user')->user();

		$order=\App\Model\Order::find($get['hotel_order_id']);
		$find=array('"{','}"');
		$replace=array('{','}');
		$hotel_detailsInfo=str_replace($find,$replace,$order->hotel_details);
		$parameters=json_decode($hotel_detailsInfo,true);
		$this->xmlConversion($parameters['ratebands']);
		$parameters['ratebands']=$this->xmlConversion;

        if(array_key_exists('child',$parameters['roomStayCandidate']['guestDetails']))
        {
			$this->xmlConversion($parameters['roomStayCandidate']['guestDetails']['child']);
			$parameters['roomStayCandidate']['guestDetails']['child']=$this->xmlConversion;
		}
		$this->xmlConversion($parameters['roomStayCandidate']['guestDetails']);
		$parameters['roomStayCandidate']['guestDetails']=$this->xmlConversion;

		$this->xmlConversion($parameters['roomStayCandidate']);
		$parameters['roomStayCandidate']=$this->xmlConversion;
		
		$userdetail= Userdetail::where(array('user_id'=>$user->id))->first();
		$address=$userdetail?($userdetail->city.'-'.$userdetail->postal_code.',state='.$userdetail->state):"";
		$parameters['address']=strlen($address)>4?$address:'sector15';
		$parameters['city']=$userdetail?$userdetail->city:'noida';
		$parameters['state']=$userdetail?$userdetail->state:'uttrapradesh';
		$parameters['zipcode']=$userdetail?$userdetail->postal_code:'201301';
		$parameters['title']=$userdetail?($userdetail->gender=='male'?'Mr':'Mrs'):'Mr';
		
		$parameters['emailAddress']=strlen($user->email)>4?$user->email:'reply@test.com';
		$parameters['phonenumber']=strlen($user->mobile)>4?$user->mobile:9999999999;

		
		$key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYyYzg2ZGRiMzg5MTg5NTIxNTQ3Y2U1NmZlNGUwYWNiOGRkOGRmZGVjMWQ4NDgxZTM3ODg3YmNmYWNmNjc5MGVjZTZmMjNkZDJhYTg2NDg0In0.eyJhdWQiOiI1IiwianRpIjoiNjJjODZkZGIzODkxODk1MjE1NDdjZTU2ZmU0ZTBhY2I4ZGQ4ZGZkZWMxZDg0ODFlMzc4ODdiY2ZhY2Y2NzkwZWNlNmYyM2RkMmFhODY0ODQiLCJpYXQiOjE1MDIxODY1NDIsIm5iZiI6MTUwMjE4NjU0MiwiZXhwIjoxNTMzNzIyNTQyLCJzdWIiOiI3MDgzIiwic2NvcGVzIjpbXX0.lRSF_dySLElmkc4w0q2DoLnZpgXg89WzhglPLIMGaRrb8NdPrxbkaUsmxTwxz9nF5De70CZye7MKxXYRkHoj-x7k_te58aQVYDkQTz14qs1g56AObQoY3pZuZFFI8L27d6PRz0E2XK4YlOrGQXHFhXTNlpLfey-ArKyl53t2FhYTEK3R59Ls9UFQjDRp_26wk0Tip6iKr-6R5d6oDBH4qlXbd49cru9h2BcVPGFI8lryQKkhBza4PLMtfIsbZJtlm_xXffGItKr1q2HQ5PQK8JU1wzPiO0MewfcqNsuoPgdIrDkHAOJtkABmc9xfZlBrQUQaGHZty14dpSwmWt9Jesa5X8Ubermui7ZGcKHFVigdM3Fy0LsTDZHn0d8ewNfBcOoGmWFRMJ4J-i0EqSggYsFBUTPJR4OQRD_mn5BTErr_Y1H-i6YVJ4f_o2BDh5ztRDqcLW74_rNWTzTyzmUC2vYilrWe8ZS5RDWBH9d5aoj1PnJ0ay7jkZllkoPCnpzzhWZc8LJa761r_UBGI5psBsFJdoqkKxrO13UTxzc9dH1Fb76MNv_oarR9CSL4nvNdMp1NGchaVJwuorwLiTdc_tLrdqGk-pNHbp2X6EtwfBwBxA8FXC5ydSMeZmRY1zGgc41blrN1R8PHPcK7TPCmpeTUsLGgouA4-y4xv4xvgEk";
		$url='https://www.pay2all.in/api/hotel/v1/HotelProvisional';
		$this->curlHitApi($parameters,$key,$url);
		$get['booking_response']=json_encode($this->curlHitApi);
		if($this->curlHitApi['allocresult']['allocavail']=='Y' || $this->curlHitApi['allocresult']['allocavail']=='y')
		{
			
			if($this->updateBookingStatus($get))
			{
				
				$parameters['fromallocation']=$this->curlHitApi['allocresult']['allocavail'];
				$parameters['allocid']=$this->curlHitApi['allocresult']['allocid'];
				$parameters['wsKey']=$this->curlHitApi['allocresult']['wsKey'];
				$parameters['hotelCityName']='Delhi';
				
				
				
				$url='https://www.pay2all.in/api/hotel/v1/HotelBooking';
				$this->curlHitApi($parameters,$key,$url);
				if($this->curlHitApi['bookingresponse']['bookingstatus']=='C' || $this->curlHitApi['bookingresponse']['bookingstatus']=='c')
				{
					$get['booking_response']=json_encode($this->curlHitApi);
					return $this->updateBookingStatusConfirm($get);
				}
				else
				{
				    return false;
				}
				
			}
			else
			{
				   return false;
			}
		}
		
	}
	    
  public function xmlConversion($arr)
  {
	  $str="";
	  foreach($arr as $key=>$val) 
	  {
		  $str.="<".$key.">";
		  $str.=$val;
		  $str.="</".$key.">";
	  }
	  $this->xmlConversion=$str;
  }
      
    public function updateBookingStatus($get)
	{
		//echo '<pre>wwwwwwwwwww';print_r($get);die;
		$order=\App\Model\Order::find($get['hotel_order_id']);
		$order->booking_status_response=$get['booking_response'];
		$order->booking_status=1;
		$order->save();
		if($order)
		{
			return true;

		}
		else
		{
			return false;
		}
	}
	    
	public function updateBookingStatusConfirm($get)
	{
		
		$order=\App\Model\Order::find($get['hotel_order_id']);
		$order->booking_status=2;
		$order->booking_status_response=$get['booking_response'];
		$order->save();
		if($order)
		{
			return true;

		}
		else
		{
			return false;
		}
	}
	    

		
	protected function getEmailerTableContent($post)
	{
		$order=\App\Model\Order::find($post['hotel_order_id'])->toArray();
		//echo '<pre>';print_r($order['payment_status']);
		$paymentStatus=($order['payment_status']=="Sent")?'Pending':ucfirst($order['payment_status']);
		$bookingStatus=$order['booking_status']==0?'Initial':($order['booking_status']==1?'Pending':'Booked');
		//echo '$paymentStatus='.$paymentStatus;die;
		$order_date=date('d-M-Y',strtotime($post['order_date']));
		
		$fromdate=explode('/',$post['hotel_details']['fromdate']);
		$todate=explode('/',$post['hotel_details']['todate']);
		
		$fromdate=date('d-M-Y',strtotime($fromdate[2].'-'.$fromdate[1].'-'.$fromdate[0]));
		$todate=date('d-M-Y',strtotime($todate[2].'-'.$todate[1].'-'.$todate[0]));
		
		
		$hotel_order_id=$post['hotel_order_id'];
		
		
		//$this->encodeStr($this->encrypted_key);
		$encrypted_key=$this->encrypted_key;
		//echo 'tttttttttttttttt=='.$this->encodeStr;die;
		
		$this->getEmailerTableContent='
				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth top_head">
										   <tbody>
											
											  <tr>
												 <td width="100%" height="5"></td>
											  </tr>
											 
											  <tr>
												 <td>
													<table width="305" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
													   <tbody>
														  <tr>
															 <td align="left" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #ffffff; padding-left:7px;">
																<a href="'.base_url().'print?hotel_order_id='.$hotel_order_id.'&encrypted_key='.$encrypted_key.'"><img title="Print" style="cursor:pointer;" src="'.base_url().'assets/images/print.png" ></img></a>
																<a download href="'.base_url().'download?hotel_order_id='.$hotel_order_id.'&encrypted_key='.$encrypted_key.'"><img style="cursor:pointer;"  title="Download"  border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.base_url().'assets/images/download.png" class="logo"/></a>
												
															 </td>
															 
														  </tr>
													   </tbody>
													</table>
													<table width="280" align="right" border="0" cellpadding="0" cellspacing="0" class="emhide">
													   <tbody>
														  <tr>
															 <td align="right" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #ffffff">
																<a href="javascript:void(0);" onClick="'.base_url().'/print?hotel_order_id='.$hotel_order_id.'&encrypted_key='.$encrypted_key.'" style="text-decoration: none; color: #ffffff"></a> 
															 </td>
														  </tr>
													   </tbody>
													</table>
												 </td>
											  </tr>
											
											  <tr>
												 <td width="100%" height="6"></td>
											  </tr>
										   
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>

				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#e8eaed" width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth header_email">
										   <tbody>
											  <tr>
												
												 <td class="logo1">
													<a target="_blank" href="#"><img width="220" border="0" height="45" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.base_url().'assets/data/option5/logo.png" class="logo"></a>
												 </td>
												 
												 <td class="text_aline">
												 <div class="invoice-title">
												
								<h2 style="margin: 2px 0;font-size: 17px;">Invoice# : '.$post['hotel_order_id'].' </h2>
								 <p style=" width:100%;font-size: 17px;padding-top: 2px;padding-bottom:3px;"> Order Date : '.$order_date.'</p>
							
							</div>
												 </td>
												 
												 
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>

				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#ffffff" width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
										   <tbody>
											  <tr>
												 <td>
													<table width="290" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														  <tr>
															 <td>
																<!-- start of text content table -->
																<table width="270" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
																   <tbody>
																	  <tr>
																		 <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #333333; text-align:left;line-height: 24px;"> 	
									<strong>Hotel Details:</strong>
									<p style="width:100%;">'.$post['hotel_info']['hotelName'].'</p>
										
									<p style="width:100%;">	'.$post['hotel_info']['hotelAddress'].'</p>
									<p style="width:100%;">'.$post['hotel_info']['entryTime'].'</p>
									
																		 </td>
																	  </tr>
																	 
																	
																   </tbody>
																</table>
															 </td>
														  </tr>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
													   </tbody>
													</table>
													<table width="290" align="right" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														  <tr>
															 <td>
																<!-- start of text content table -->
																<table width="270" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
																   <tbody>
																	  <tr>
																		 <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #333333; text-align:right;line-height: 24px;">
																				
									<strong>Customer Details:</strong>
										<p style="width:100%;">'.$post['contact_details']['name'].'</p>
											<p style="width:100%;">'.$post['contact_details']['email'].'</p>
											<p style="width:100%;">'.$post['contact_details']['mobile'].'</p>
									
																		 </td>
																	  </tr>
																   
																	  
																   </tbody>
																</table>
															 </td>
														  </tr>
														  
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														 
													   </tbody>
													</table>
													
												 </td>
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>



				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
							   <tbody>
							   
								 
								  
								  <tr>
									 <td width="100%"  style="padding: 10px 9px;background-color: #fff; border: solid 1px #ccc;">
										<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
										   <tbody>
											   <tr class="tr_border_btom">
													<td class="text-center td_pading_botom"><strong>Payment Status</strong></td>
													<td class="text-center td_pading_botom"><strong>Hotel Booking Status</strong></td>
												</tr>
												
												<tr class="tr_border_btom">
													<td class="text-center td_pading_botom td_pading_top">'.$paymentStatus.'</td>
													<td class="text-center td_pading_botom td_pading_top">'.$bookingStatus.'</td>
							
												</tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
								  
								  <tr>
									 <td width="100%"  style="padding: 10px 9px;background-color: #fff; border: solid 1px #ccc;">
										<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
										   <tbody>
											  <tr class="tr_border_btom">
													<td class="td_pading_botom"><strong>Adult</strong></td>
													<td class="text-center td_pading_botom"><strong>Children</strong></td>
													<td class="text-center td_pading_botom"><strong>Days</strong></td>
													<td class="text-center td_pading_botom"><strong>Amount</strong></td>
												</tr>
												
												<tr class="tr_border_btom">
													<td class="td_pading_top">'.$post['hotel_details']['roomStayCandidate']['guestDetails']['adults'].'</td>
													<td class="text-center td_pading_top">'.(array_key_exists('child',$post['hotel_details']['roomStayCandidate']['guestDetails'])?count($post['hotel_details']['roomStayCandidate']['guestDetails']['child']):0).'</td>
													<td class="text-center td_pading_top">'.$fromdate.' To '. $todate.'</td>
													<td class="td_pading_top">&#8377; '.$post['amount'].'</td>
												</tr>

												
										   </tbody>
										</table>
									 </td>
								  </tr>
								  
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>




				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#ffffff" width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
										   <tbody>
											  <tr>
												 <td>
													<table width="600" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
													  
														 
														  <tr>
															 <td class="border_td_solid"></td>
																		 
																		 
																		 
																	  </tr>
																   </tbody>
																</table>
															 </td>
														  </tr>
														 
													   </tbody>
													</table>
												   
												 </td>
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>

				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth top_head">
										   <tbody>
											  
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											 
											  <tr>
												 <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #ffffff">
												   © 2016-2026 SBSCash. All Rights Reserved .
												 </td>
											  </tr>
											
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											  
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>';
				
	$this->getEmailerTableContentShow='<div style="margin:0 auto;">
				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="50%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto;">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#e8eaed" width="100%" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth header_email">
										   <tbody>
											  <tr>
												
												 <td class="logo1">
													<a target="_blank" href="'.base_url().'print?hotel_order_id='.$hotel_order_id.'&encrypted_key='.$encrypted_key.'"><img style="cursor:pointer;"  title="Print"  border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.base_url().'assets/images/print.png" class="logo"/></a>
													<a download href="'.base_url().'download?hotel_order_id='.$hotel_order_id.'&encrypted_key='.$encrypted_key.'"><img style="cursor:pointer;"  title="Download"  border="0" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.base_url().'assets/images/download.png" class="logo"/></a>
												 </td>
												 <td class="text_aline">
												 <div class="invoice-title">
												
								<h2 style="margin: 2px 0;font-size: 17px;color:#000;text-align:right;">Invoice# : '.$post['hotel_order_id'].' </h2>
								 <p style=" width:100%;font-size: 17px;padding-top: 2px;padding-bottom:3px;"> Order Date : '.$order_date.'</p>
							
							</div>
												 </td>
												 
												 
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>

				<table width="50%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="50%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto;">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
										   <tbody>
											  <tr>
												 <td>
													<table width="290" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														  <tr>
															 <td>
																<!-- start of text content table -->
																<table width="270" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
																   <tbody>
																	  <tr>
																		 <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #333333; text-align:left;line-height: 24px;"> 	
									<strong>Hotel Details:</strong>
									<p style="width:100%;">'.$post['hotel_info']['hotelName'].'</p>
										
									<p style="width:100%;">	'.$post['hotel_info']['hotelAddress'].'</p>
									<p style="width:100%;">'.$post['hotel_info']['entryTime'].'</p>
									
																		 </td>
																	  </tr>
																	 
																	
																   </tbody>
																</table>
															 </td>
														  </tr>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
													   </tbody>
													</table>
													<table width="290" align="right" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														  <tr>
															 <td>
																<!-- start of text content table -->
																<table width="270" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
																   <tbody>
																	  <tr>
																		 <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #333333; text-align:right;line-height: 24px;">
																				
									<strong>Customer Details:</strong>
										<p style="width:100%;">'.$post['contact_details']['name'].'</p>
											<p style="width:100%;">'.$post['contact_details']['email'].'</p>
											<p style="width:100%;">'.$post['contact_details']['mobile'].'</p>
									
																		 </td>
																	  </tr>
																   
																	  
																   </tbody>
																</table>
															 </td>
														  </tr>
														  
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														 
													   </tbody>
													</table>
													
												 </td>
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>



				<table width="50%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
				         
					     <tr>
						 <td>
							<table width="50%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto;">
							   <tbody>
								 
								  <tr>
									 <td width="100%"  style="padding: 10px 9px;background-color: #fff; border: solid 1px #ccc;">
										<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
										   <tbody>
											  <tr class="tr_border_btom">
													<td class="text-center td_pading_botom"><strong>Payment Status</strong></td>
													<td class="text-center td_pading_botom"><strong>Hotel Booking Status</strong></td>
												</tr>
												
												<tr class="tr_border_btom">
													<td class="text-center td_pading_botom td_pading_top">'.$paymentStatus.'</td>
													<td class="text-center td_pading_botom td_pading_top">'.$bookingStatus.'</td>
												</tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
								   <tr>
									 <td width="100%"  style="padding: 10px 9px;background-color: #fff; border: solid 1px #ccc;">
										<table style="margin:0 auto;" bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
										   <tbody>
											  <tr class="tr_border_btom">
													<td class="td_pading_botom"><strong>Adult</strong></td>
													<td class="text-center td_pading_botom"><strong>Children</strong></td>
													<td class="text-center td_pading_botom"><strong>Days</strong></td>
													<td class="text-center td_pading_botom"><strong>Amount</strong></td>
												</tr>
												
												<tr class="tr_border_btom">
													<td class="td_pading_top">'.$post['hotel_details']['roomStayCandidate']['guestDetails']['adults'].'</td>
													<td class="text-center td_pading_top">'.(array_key_exists('child',$post['hotel_details']['roomStayCandidate']['guestDetails'])?count($post['hotel_details']['roomStayCandidate']['guestDetails']['child']):0).'</td>
													<td class="text-center td_pading_top">'.$fromdate.' To '. $todate.'</td>
													<td class="td_pading_top">&#8377; '.$post['amount'].'</td>
												</tr>

												
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table></center>';
				
				
	$this->getEmailerTableContentPrint='
				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth top_head">
										   <tbody>
											
											  <tr>
												 <td width="100%" height="5"></td>
											  </tr>
											 
											  <tr>
												 <td>
													<table width="305" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
													   <tbody>
														  <tr>
														  </tr>
													   </tbody>
													</table>
													<table width="280" align="right" border="0" cellpadding="0" cellspacing="0" class="emhide">
													   <tbody>
														  <tr>
															 <td align="right" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #ffffff">

															 </td>
														  </tr>
													   </tbody>
													</table>
												 </td>
											  </tr>
											
											  <tr>
												 <td width="100%" height="6"></td>
											  </tr>
										   
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>

				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#e8eaed" width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth header_email">
										   <tbody>
											  <tr>
												
												 <td class="logo1">
													<a target="_blank" href="#"><img width="220" border="0" height="45" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.base_url().'assets/data/option5/logo.png" class="logo"></a>
												 </td>
												 
												 <td class="text_aline">
												 <div class="invoice-title">
												
								<h2 style="margin: 2px 0;font-size: 17px;color:#000;text-align:right;">Invoice# : '.$post['hotel_order_id'].' </h2>
								 <p style=" width:100%;font-size: 17px;padding-top: 2px;padding-bottom:3px;"> Order Date : '.$order_date.'</p>
							
							</div>
												 </td>
												 
												 
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>

				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#ffffff" width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
										   <tbody>
											  <tr>
												 <td>
													<table width="290" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														  <tr>
															 <td>
																<!-- start of text content table -->
																<table width="270" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
																   <tbody>
																	  <tr>
																		 <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #333333; text-align:left;line-height: 24px;"> 	
									<strong>Hotel Details:</strong>
									<p style="width:100%;">'.$post['hotel_info']['hotelName'].'</p>
										
									<p style="width:100%;">	'.$post['hotel_info']['hotelAddress'].'</p>
									<p style="width:100%;">'.$post['hotel_info']['entryTime'].'</p>
									
																		 </td>
																	  </tr>
																	 
																	
																   </tbody>
																</table>
															 </td>
														  </tr>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
													   </tbody>
													</table>
													<table width="290" align="right" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														  <tr>
															 <td>
																<!-- start of text content table -->
																<table width="270" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
																   <tbody>
																	  <tr>
																		 <td style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #333333; text-align:right;line-height: 24px;">
																				
									<strong>Customer Details:</strong>
										<p style="width:100%;">'.$post['contact_details']['name'].'</p>
											<p style="width:100%;">'.$post['contact_details']['email'].'</p>
											<p style="width:100%;">'.$post['contact_details']['mobile'].'</p>
									
																		 </td>
																	  </tr>
																   
																	  
																   </tbody>
																</table>
															 </td>
														  </tr>
														  
														  <tr>
															 <td width="100%" height="10"></td>
														  </tr>
														 
													   </tbody>
													</table>
													
												 </td>
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>



				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
							   <tbody>
							   
							     <tr>
									 <td width="100%"  style="padding: 10px 9px;background-color: #fff; border: solid 1px #ccc;">
										<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
										   <tbody>
											  <tr class="tr_border_btom">
													<td class="text-center td_pading_botom"><strong>Payment Status</strong></td>
													<td class="text-center td_pading_botom"><strong>Hotel Booking Status</strong></td>
												</tr>
												
												<tr class="tr_border_btom">
													<td class="text-center td_pading_botom td_pading_top">'.$paymentStatus.'</td>
													<td class="text-center td_pading_botom td_pading_top">'.$bookingStatus.'</td>
												</tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
								  
								  <tr>
									 <td width="100%"  style="padding: 10px 9px;background-color: #fff; border: solid 1px #ccc;">
										<table bgcolor="#ffffff" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth ">
										   <tbody>
											  <tr class="tr_border_btom">
													<td class="td_pading_botom"><strong>Adult</strong></td>
													<td class="text-center td_pading_botom"><strong>Children</strong></td>
													<td class="text-center td_pading_botom"><strong>Days</strong></td>
													<td class="text-center td_pading_botom"><strong>Amount</strong></td>
												</tr>
												
												<tr class="tr_border_btom">
													<td class="td_pading_top">'.$post['hotel_details']['roomStayCandidate']['guestDetails']['adults'].'</td>
													<td class="text-center td_pading_top">'.(array_key_exists('child',$post['hotel_details']['roomStayCandidate']['guestDetails'])?count($post['hotel_details']['roomStayCandidate']['guestDetails']['child']):0).'</td>
													<td class="text-center td_pading_top">'.$fromdate.' To '. $todate.'</td>
													<td class="td_pading_top">&#8377; '.$post['amount'].'</td>
												</tr>

												
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>




				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table bgcolor="#ffffff" width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
										   <tbody>
											  <tr>
												 <td>
													<table width="600" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
													   <tbody>
													  
														 
														  <tr>
															 <td class="border_td_solid"></td>
																		 
																		 
																		 
																	  </tr>
																   </tbody>
																</table>
															 </td>
														  </tr>
														 
													   </tbody>
													</table>
												   
												 </td>
											  </tr>
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>

				<table width="100%" bgcolor="#e8eaed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
				   <tbody>
					  <tr>
						 <td>
							<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
							   <tbody>
								  <tr>
									 <td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth top_head">
										   <tbody>
											  
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											 
											  <tr>
												 <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #ffffff">
												   © 2016-2026 SBSCash. All Rights Reserved .
												 </td>
											  </tr>
											
											  <tr>
												 <td width="100%" height="20"></td>
											  </tr>
											  
										   </tbody>
										</table>
									 </td>
								  </tr>
							   </tbody>
							</table>
						 </td>
					  </tr>
				   </tbody>
				</table>';

	}
		
	protected function getEmailer($post)
	{
		//echo '<pre>';print_r($post);die;
		$this->getEmailerTableContent($post);
		
		$this->getEmailer='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		  <style type="text/css">
		 /* Client-specific Styles */
		 #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
		 body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
		 /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
		 .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
		 .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  */
		 #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
		 img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
		 a img {border:none;}
		 .image_fix {display:block;}
		 p {margin: 0px 0px !important;}
		 table td {border-collapse: collapse;}
		 table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
		 a {color: #9ec459;text-decoration: none;text-decoration:none!important;}
		 
		 .top_head{    background-color: #0b224c;}
		 
		 .text_aline{ text-align: right;padding-right: 10px;}
		 
		 .header_email{background-color: #fff;
		 border-bottom: solid 1px #ccc;}
		.logo1{padding-left: 6px;}
		
		.td_pading_botom{padding-bottom: 7px;}	
		
		.td_pading_top{padding-top: 7px; padding-bottom: 7px;}	
		
		.tr_border_btom{border-bottom: solid 1px #ccc;}	
		
		.border_td_solid{border: solid 1px #ccc;}

		 
		 /*STYLES*/
		 table[class=full] { width: 100%; clear: both; }
		 /*IPAD STYLES*/
		 @media only screen and (max-width: 640px) {
		 a[href^="tel"], a[href^="sms"] {
		 text-decoration: none;
		 color: #9ec459; /* or whatever your want */
		 pointer-events: none;
		 cursor: default;
		 }
		 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		 text-decoration: default;
		 color: #9ec459 !important;
		 pointer-events: auto;
		 cursor: default;
		 }
		 table[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 td[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 img[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 img[class=banner] {width: 440px!important;height:147px!important;}
		 table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
		 table[class=icontext] {width: 345px!important;text-align:center!important;}
		 img[class="colimg2"] {width:420px!important;height:243px!important;}
		 table[class="emhide"]{display: none!important;}
		 img[class="logo"]{width:440px!important;height:110px!important;}
		 
		 }
		 /*IPHONE STYLES*/
		 @media only screen and (max-width: 480px) {
		 a[href^="tel"], a[href^="sms"] {
		 text-decoration: none;
		 color: #9ec459; /* or whatever your want */
		 pointer-events: none;
		 cursor: default;
		 }
		 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		 text-decoration: default;
		 color: #9ec459 !important; 
		 pointer-events: auto;
		 cursor: default;
		 }
		 table[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 td[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 img[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 img[class=banner] {width: 280px!important;height:93px!important;}
		 table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
		 table[class=icontext] {width: 186px!important;text-align:center!important;}
		 img[class="colimg2"] {width:260px!important;height:150px!important;}
		 table[class="emhide"]{display: none!important;}
		 img[class="logo"]{width:280px!important;height:70px!important;}
		
		 }
		</style>
		</head>
		<body>'.
		$this->getEmailerTableContent.
		'</body></html>';
		
		$this->getEmailerShow='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		  
		
		  <style type="text/css">
		 /* Client-specific Styles */
		 #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
		 body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
		 /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
		 .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
		 .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  */
		 #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
		 img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
		 a img {border:none;}
		 .image_fix {display:block;}
		 p {margin: 0px 0px !important;}
		 table td {border-collapse: collapse;}
		 table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
		 a {color: #9ec459;text-decoration: none;text-decoration:none!important;}
		 
		 .top_head{    background-color: #0b224c;}
		 
		 .text_aline{ text-align: right;padding-right: 10px;}
		 
		 .header_email{background-color: #fff;
		 border-bottom: solid 1px #ccc;}
		.logo1{padding-left: 6px;}
		
		.td_pading_botom{padding-bottom: 7px;}	
		
		.td_pading_top{padding-top: 7px; padding-bottom: 7px;}	
		
		.tr_border_btom{border-bottom: solid 1px #ccc;}	
		
		.border_td_solid{border: solid 1px #ccc;}

		 
		 /*STYLES*/
		 table[class=full] { width: 100%; clear: both; }
		 /*IPAD STYLES*/
		 @media only screen and (max-width: 640px) {
		 a[href^="tel"], a[href^="sms"] {
		 text-decoration: none;
		 color: #9ec459; /* or whatever your want */
		 pointer-events: none;
		 cursor: default;
		 }
		 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		 text-decoration: default;
		 color: #9ec459 !important;
		 pointer-events: auto;
		 cursor: default;
		 }
		 table[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 td[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 img[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 img[class=banner] {width: 440px!important;height:147px!important;}
		 table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
		 table[class=icontext] {width: 345px!important;text-align:center!important;}
		 img[class="colimg2"] {width:420px!important;height:243px!important;}
		 table[class="emhide"]{display: none!important;}
		 img[class="logo"]{width:440px!important;height:110px!important;}
		 
		 }
		 /*IPHONE STYLES*/
		 @media only screen and (max-width: 480px) {
		 a[href^="tel"], a[href^="sms"] {
		 text-decoration: none;
		 color: #9ec459; /* or whatever your want */
		 pointer-events: none;
		 cursor: default;
		 }
		 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		 text-decoration: default;
		 color: #9ec459 !important; 
		 pointer-events: auto;
		 cursor: default;
		 }
		 table[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 td[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 img[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 img[class=banner] {width: 280px!important;height:93px!important;}
		 table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
		 table[class=icontext] {width: 186px!important;text-align:center!important;}
		 img[class="colimg2"] {width:260px!important;height:150px!important;}
		 table[class="emhide"]{display: none!important;}
		 img[class="logo"]{width:280px!important;height:70px!important;}
		
		 }
		</style>
		</head>
		<body>'.
		$this->getEmailerTableContentShow.
		'</body></html>';
		
		$this->getEmailerPrint='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		  
		
		  <style type="text/css">
		 /* Client-specific Styles */
		 #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
		 body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
		 /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
		 .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
		 .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  */
		 #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
		 img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
		 a img {border:none;}
		 .image_fix {display:block;}
		 p {margin: 0px 0px !important;}
		 table td {border-collapse: collapse;}
		 table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
		 a {color: #9ec459;text-decoration: none;text-decoration:none!important;}
		 
		 .top_head{    background-color: #0b224c;}
		 
		 .text_aline{ text-align: right;padding-right: 10px;}
		 
		 .header_email{background-color: #fff;
		 border-bottom: solid 1px #ccc;}
		.logo1{padding-left: 6px;}
		
		.td_pading_botom{padding-bottom: 7px;}	
		
		.td_pading_top{padding-top: 7px; padding-bottom: 7px;}	
		
		.tr_border_btom{border-bottom: solid 1px #ccc;}	
		
		.border_td_solid{border: solid 1px #ccc;}

		 
		 /*STYLES*/
		 table[class=full] { width: 100%; clear: both; }
		 /*IPAD STYLES*/
		 @media only screen and (max-width: 640px) {
		 a[href^="tel"], a[href^="sms"] {
		 text-decoration: none;
		 color: #9ec459; /* or whatever your want */
		 pointer-events: none;
		 cursor: default;
		 }
		 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		 text-decoration: default;
		 color: #9ec459 !important;
		 pointer-events: auto;
		 cursor: default;
		 }
		 table[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 td[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 img[class=devicewidth] {width: 440px!important;text-align:center!important;}
		 img[class=banner] {width: 440px!important;height:147px!important;}
		 table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
		 table[class=icontext] {width: 345px!important;text-align:center!important;}
		 img[class="colimg2"] {width:420px!important;height:243px!important;}
		 table[class="emhide"]{display: none!important;}
		 img[class="logo"]{width:440px!important;height:110px!important;}
		 
		 }
		 /*IPHONE STYLES*/
		 @media only screen and (max-width: 480px) {
		 a[href^="tel"], a[href^="sms"] {
		 text-decoration: none;
		 color: #9ec459; /* or whatever your want */
		 pointer-events: none;
		 cursor: default;
		 }
		 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
		 text-decoration: default;
		 color: #9ec459 !important; 
		 pointer-events: auto;
		 cursor: default;
		 }
		 table[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 td[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 img[class=devicewidth] {width: 280px!important;text-align:center!important;}
		 img[class=banner] {width: 280px!important;height:93px!important;}
		 table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
		 table[class=icontext] {width: 186px!important;text-align:center!important;}
		 img[class="colimg2"] {width:260px!important;height:150px!important;}
		 table[class="emhide"]{display: none!important;}
		 img[class="logo"]{width:280px!important;height:70px!important;}
		
		 }
		</style>
		</head>
		<body>'.
		$this->getEmailerTableContentPrint.
		'</body></html>';
		
	}
	    
	public function getInvoice($post)
	{
		$row=\App\Model\Order::find($post['hotel_order_id'])->toArray();
       
		if(count($row))
		{
		   $post=array_merge($row,$post);
		   //echo '<pre>';print_r($post);die;
		   $post['contact_details']=json_decode($row['contact_details'],true);
		   $post['hotel_info']=json_decode($row['hotel_info'],true);
		   
		   $find=array('"{','}"');
		   $replace=array('{','}');
		   $hotel_details=str_replace($find,$replace,$row['hotel_details']);
		   
		   $post['hotel_details']=json_decode( $hotel_details,true);
		   //echo '<pre>';print_r($post);die;
		}

		$this->getEmailer($post);     
		$to = $post['contact_details']['email'];
		$subject = "Hotel Booking Invoice";
		$txt = "Hi ".$post['contact_details']['name'].", ".$this->getEmailer;
		$headers = "From: no-reply@happypesa.com" . "\r\n" ."CC: dharamvir@happypesa.com";
	    mail($to,$subject,$txt,$headers);
        echo $this->getEmailerShow;
		
	}

   public function getInvoicePrint($post)
   {
		$row=\App\Model\Order::find($post['hotel_order_id'])->toArray();
		
		if(count($row))
		{
		   $post=array_merge($post,$row);
		   $post['contact_details']=json_decode($row['contact_details'],true);
		   $post['hotel_info']=json_decode($row['hotel_info'],true);
		   
		   $find=array('"{','}"');
		   $replace=array('{','}');
		   $hotel_details=str_replace($find,$replace,$row['hotel_details']);
		   
		   $post['hotel_details']=json_decode($hotel_details,true);
			//echo '<pre>';print_r($post);die;
		}
		$this->getEmailer($post);  
		echo $this->getEmailerPrint;
		
  }
		
		
  
	
}
