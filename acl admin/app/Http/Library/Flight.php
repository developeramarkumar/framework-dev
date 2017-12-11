<?php
namespace App\Http\Library;
use \App\Model\Userdetail;
use \App\Model\FlightDataFrmApi;
session_start();

include_once(__DIR__.'/../../../flight/logicalFunction.php');

//echo getIpAddress();

class Flight { 
public function searchAuthentication() { }


public function searchFlightAPI($post) {
$departDate = explode('/', $post['depart_date']);
$_departDate = $departDate[2] . '-'. $departDate[0] . '-'. $departDate[1]. 'T00:00:00';

$getcitynameFrom = \App\Model\TboAirport::where(
  'AiportCode','LIKE', strtoupper($post['from_location']))->first();


$getcitynameTo   = \App\Model\TboAirport::where(
  'AiportCode','LIKE', strtoupper($post['to_location']))->first();

if($post['wayType']!=1){
$returnDate = explode('/', $post['return_date']);
$_returnDate = $returnDate[2] . '-'. $returnDate[0] . '-'. $returnDate[1]. 'T00:00:00';
}
$NoOfAdult = $post['NoOfAdutls'];
$NoOfChilds = $post['NoOfChilds'];
$NoOfInfants = $post['NoOfInfants'];

$data = array(
"ClientId" => "ApiIntegrationNew",
"UserName" => "HAPPINESS EASY",
"Password" => "HAPPY@123",
"EndUserIp" => "203.122.11.211"
);

$header = array( "cache-control: no-cache", "content-type: application/json" );
$url ="http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate";
$str_data = json_encode($data);
$method = "POST";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str_data);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);
$response = curl_exec($ch);
$json = json_decode($response);
if($json->Status == 1){
     $json->TokenId;
}
if(@$post['wayType']==1){
$data1 = array(
  "EndUserIp" => "203.122.11.211",
  "TokenId" => $json->TokenId,
  "AdultCount" => $post['NoOfAdutls'],
  "ChildCount" => $post['NoOfChilds'],
  "InfantCount" => $post['NoOfInfants'],
  "DirectFlight" => "false",
  "OneStopFlight" => "false",
  //"JourneyType" => $post['wayType'],
  "JourneyType" => "1",
  "PreferredAirlines" => array("AI"),
    "Segments"=> array(array(
      "Origin"=> strtoupper($post['from_location']),
      "Destination" => strtoupper($post['to_location']),
      "FlightCabinClass" => "1",
      "PreferredDepartureTime" => $_departDate,
      "PreferredArrivalTime" => $_departDate
    )),
    "Sources"=> array(
    "SG"
    )
);
} else {
$data1 = array(
  "EndUserIp" => "203.122.11.211",
  "TokenId" => $json->TokenId,
  "AdultCount" => $post['NoOfAdutls'],
  "ChildCount" => $post['NoOfChilds'],
  "InfantCount" => $post['NoOfInfants'],
  "DirectFlight" => "false",
  "OneStopFlight" => "false",
  "JourneyType" => $post['wayType'],
  "PreferredAirlines" => null,
    "Segments"=> array(array(
      "Origin"=> strtoupper($post['from_location']),
      "Destination" => strtoupper($post['to_location']),
      "FlightCabinClass" => "1",
      "PreferredDepartureTime" => $_departDate,
      "PreferredArrivalTime" => $_departDate
    ),
    array(
    "Origin" => strtoupper($post['to_location']),
    "Destination" => strtoupper($post['from_location']),
    "FlightCabinClass" => 1,
    "PreferredDepartureTime" => $_returnDate,
    "PreferredArrivalTime" => $_returnDate
  )),
    "Sources"=> array(
    "SG"
    )
);
}

//echo '<pre>';print_r($data1); die;

$FlightSearchResult = \App\Model\FlightDataFrmApi::where(array(
		'from_location'=>trim(strtoupper($post['from_location'])),
		'to_location'=>trim(strtoupper($post['to_location'])),
		))->first();

//dd($FlightSearchResult);

$alreadyExists = count($FlightSearchResult);

if($alreadyExists == 0) {

$header1 = array( "cache-control: no-cache", "content-type: application/json" );
$url1 ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Search/";
$str_data1 = json_encode($data1);
//echo $str_data1; die;
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str_data1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url1);
$responseData = curl_exec($ch);
$jsonRecords = json_decode($responseData,TRUE);
$twowayjsonRecords = json_decode($responseData,TRUE);


$apiDataRecord = new \App\Model\FlightDataFrmApi;
$apiDataRecord->apiData 	  = $responseData;
$apiDataRecord->flightWay 	  = $post['wayType'];
$apiDataRecord->from_location = strtoupper($post['from_location']);
$apiDataRecord->to_location   = strtoupper($post['to_location']);
$apiDataRecord->depart_date   = $post['depart_date'];
$apiDataRecord->return_date   = $post['return_date'];
$apiDataRecord->save();

} else {
$jsonRecords = json_decode($FlightSearchResult->apiData,TRUE);

//$jsonRecords = json_decode($jsonRecords,TRUE);
$twowayjsonRecords = json_decode($FlightSearchResult->apiData,TRUE);
}


$getDepartDate = date("jS F, Y", strtotime($post['depart_date']));
$traceID =  $jsonRecords['Response']['TraceId'];


if(@$post['wayType']==1) : //echo '1'; die('test');?>
<div class="col-md-12 flight_top_right">
<div class="col-md-3 flight_right2"><?php echo strtoupper($post['from_location']); ?>(<?php echo $getcitynameFrom->CityName.','.$getcitynameFrom->Countryname; ?>)</div>
<div class="col-md-1 flight_right2">
<i class="fa fa-arrow-right" aria-hidden="true"></i>
</div>
<div class="col-md-3 flight_right2"><?php echo strtoupper($post['to_location']); ?>(<?php echo $getcitynameTo->CityName.','.$getcitynameTo->Countryname; ?>)</div>
<div class="col-md-5 flight_right2"> <?php echo $getDepartDate; ?>, 
  <?php echo $NoOfAdult; ?> Adult(s), <?php if($NoOfChilds!="0"){ echo $NoOfChilds .'&nbsp;'.'Childs,'; } ?> <?php if($NoOfInfants!="0"){ echo $NoOfInfants. '&nbsp;'.'Childs'; } ?>  </div>
</div>
<div class="clearfix"></div>
<div class="bus_right_side_main1">
    <div class="row">
      <div class="col-md-2"><h5 class="flight_operator">Operator</h5></div>
      <div class="col-md-2 ">
        <h5 class="flight_operator">Departure <i class="fa fa-caret-down" aria-hidden="true"></i>
        </h5>
      </div>
      <div class="col-md-2 bus_pading_left">
        <h5 class="flight_operator">Arrival <i class="fa fa-caret-up" aria-hidden="true"></i>
        </h5>
      </div>
      <div class="col-md-2 "><h5 class="flight_operator">Duration</h5></div>
      <div class="col-md-2 bus_pading_left">
        <h5 class="flight_operator"> Price <i class="fa fa-caret-up" aria-hidden="true"></i></h5>
      </div>
    </div>
</div>
<?php ?>

<?php
if($jsonRecords['Response']['ResponseStatus'] == 1) {
$i=1;
foreach ($jsonRecords['Response']['Results'][0] as $rowRecord) :
$nOofSeat       = $rowRecord['Segments']['0']['0']['NoOfSeatAvailable'];
$Baggage        = $rowRecord['Segments']['0']['0']['Baggage'];
$AirlineCode    = $rowRecord['Segments']['0']['0']['Airline']['AirlineCode'];
$AirlineName    = $rowRecord['Segments']['0']['0']['Airline']['AirlineName'];
$FlightNumber   = $rowRecord['Segments']['0']['0']['Airline']['FlightNumber'];

$Depart_DepTime       = $rowRecord['Segments']['0']['0']['Origin']['DepTime'];
$Depart_AirportCode   = $rowRecord['Segments']['0']['0']['Origin']['Airport']['AirportCode'];
$Depart_AirportName   = $rowRecord['Segments']['0']['0']['Origin']['Airport']['AirportName'];
$Depart_Terminal      = $rowRecord['Segments']['0']['0']['Origin']['Airport']['Terminal'];
$Depart_CityCode      = $rowRecord['Segments']['0']['0']['Origin']['Airport']['CityCode'];
$Depart_CityName      = $rowRecord['Segments']['0']['0']['Origin']['Airport']['CityName'];
$Depart_CountryCode   = $rowRecord['Segments']['0']['0']['Origin']['Airport']['CountryCode'];
$Depart_CountryName   = $rowRecord['Segments']['0']['0']['Origin']['Airport']['CountryName'];

$Arival_ArrTime       = $rowRecord['Segments']['0']['0']['Destination']['ArrTime'];
$Arival_AirportCode   = $rowRecord['Segments']['0']['0']['Destination']['Airport']['AirportCode'];
$Arival_AirportName   = $rowRecord['Segments']['0']['0']['Destination']['Airport']['AirportName'];
$Arival_Terminal      = $rowRecord['Segments']['0']['0']['Destination']['Airport']['Terminal'];
$Arival_CityCode      = $rowRecord['Segments']['0']['0']['Destination']['Airport']['CityCode'];
$Arival_CityName      = $rowRecord['Segments']['0']['0']['Destination']['Airport']['CityName'];
$Arival_CountryName   = $rowRecord['Segments']['0']['0']['Destination']['Airport']['CountryName'];

$Duration             = $rowRecord['Segments']['0']['0']['Duration'];

$hours = floor($Duration / 60);
$minutes = $Duration % 60;

$nOofSeat = $rowRecord['Segments']['0']['0']['NoOfSeatAvailable'];

$StopOver         = $rowRecord['Segments']['0']['0']['StopOver'];
$StopPoint        = $rowRecord['Segments']['0']['0']['StopPoint'];

$StopPointArrivalTime = $rowRecord['Segments']['0']['0']['StopPointArrivalTime'];
$StopPointDepartureTime = $rowRecord['Segments']['0']['0']['StopPointDepartureTime'];
$StopPointArrivalTime  = date("g:i A", strtotime($StopPointArrivalTime));
$StopPointDepartureTime  = date("g:i A", strtotime($StopPointDepartureTime));

$departTime  = date("g:i A", strtotime($Depart_DepTime));
$arivalTime  = date("g:i A", strtotime($Arival_ArrTime));
$getDepartDate = date("D M, j", strtotime($Depart_DepTime));
$ResultIndex = $rowRecord['ResultIndex'];
?>
<div class="flight_right_side_main">
  <div class="bus_listin_box_main">
      <div class="flight_listin_box">
          <div class="row bus_margin0">
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans"><img src="<?= base_url();?>assets/images/airline/airline.jpg" alt="cruise">
                      <p><?php echo $AirlineName; ?>
                          <br> <?php echo $AirlineCode; ?>-<?php echo $FlightNumber; ?></p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans2">
                      <h5> <?php echo $Depart_AirportCode; ?>(<?php echo $departTime; ?>) <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                      <p><?php echo $Depart_CityName; ?></p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans2">
                      <h5> <?php echo $Arival_AirportCode; ?>(<?php echo $arivalTime; ?>)</h5>
                      <p><?php echo $Arival_CityName; ?></p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans3">
                      <h5><?php echo $hours.'h'.'&nbsp;'. $minutes.'m'; ?></h5>
                      <?php if($StopOver!=""){ ?><em style="font-size: 10px;color: #d58512;"><small ><?php echo $StopOver; ?> Stop(s)</small></em><?php } ?>
                      <span><?php echo $nOofSeat; ?> seat(s) left</span>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans4">
                      <span>
                          <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $rowRecord['Fare']['BaseFare']; ?>
                      </span>
                  </div>
              </div>
<div class="col-md-2 ">
  <div class="bus_hans2">
  <form name="FlightPassenger" id="FlightPassenger" method="POST" action="FlightPassengerDetails.php">
    <input type="hidden" name="ResultIndexkey" value="<?php echo base64_encode($ResultIndex); ?>" >
    <input type="hidden" name="TokenId" value="<?php echo $json->TokenId; ?>"> 
    <input type="submit" name="submitSearch" class="btn btn-warning flight_hans_btn" value="Book Now">
    <input type="hidden" name="indexBB" value="<?php echo $rowRecord['IsLCC']; ?>">
    <input type="hidden" name="traceID" value="<?php echo base64_encode($traceID); ?>" >
  </form>
  </div>
</div>
          
          </div>
          <div class="flight_Check">Check-In Baggage:<?php echo strtolower(ucfirst($Baggage)); ?></div>
      </div>
  </div>
</div>
<?php
$i++;
endforeach;

	} else {
	echo '<div id="container">
  	<div id="fof">
	    <div class="hgroup">
	      <h1 style="color:#979797;">Something Just Went Wrong!</h1>
	    </div>
	    <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
	    <p>Go <a style="color:#ff3366" href="javascript:history.go(-1)">Back</a> or <a style="color:#ff3366" href="'.base_url().'">Home</a></p>
  	</div>
</div>';
	}
else :

$depart_date = date("D, j M, Y", strtotime($post['depart_date']));
$arival_date = date("D, j M, Y", strtotime($post['return_date']));

echo '<div class="col-md-12 flight-listing">
  <div class="col-md-12 flight_top_right">
      <dfn>
          <em id="OD">'.$getcitynameFrom->CityName.'('.strtoupper($post['from_location']).') ⇋ '.$getcitynameTo->CityName.'('.strtoupper($post['to_location']).')</em>
          <tt><b class="bold">Dept : </b>
            <tt id="DepartureDatett">'.$depart_date.'</tt>
            </tt>  <tt><b class="bold">Return :</b>
            <tt id="ReturnDatett">'.$arival_date.'</tt> '.@$post['NoOfAdutls'].' Adult(s) </tt>
      </dfn>    
  </div>';  

echo '<div class="flight_right_side_main"><div class="bus_listin_box_main">';

if($getcitynameFrom->AiportCode && $getcitynameTo->AiportCode && $getcitynameFrom->CountryCode == $getcitynameTo->CountryCode){
echo '<div class="col-md-6  pad-left0 test1">';  

$TraceId = $twowayjsonRecords['Response']['TraceId'];
if($twowayjsonRecords['Response']['ResponseStatus'] == 1) {
$i=1;
foreach (@$twowayjsonRecords['Response']['Results']['0'] as $rowRecord) : //echo "<pre>";print_r($rowRecord);
$departTime = $rowRecord['Segments']['0']['0']['Origin']['DepTime'];
$arivalTime = $rowRecord['Segments']['0']['0']['Destination']['ArrTime'];

$_departTime  = date("g:i A", strtotime($departTime));
$_arivalTime  = date("g:i A", strtotime($arivalTime));

$Duration = $rowRecord['Segments']['0']['0']['Duration'];
$hours = floor($Duration / 60);
$minutes = $Duration % 60;
if($rowRecord['IsRefundable']==1){
$class="red";
} else {
$class="";
}
$resultIndex = $rowRecord['ResultIndex'];

$perAdultFare = $rowRecord['FareBreakdown']['0']['BaseFare'] / $rowRecord['FareBreakdown']['0']['PassengerCount'];
$AirlineName = $rowRecord['Segments']['0']['0']['Airline']['AirlineName'];
$AirlineCode = $rowRecord['Segments']['0']['0']['Airline']['AirlineCode'];
$FlightNumber = $rowRecord['Segments']['0']['0']['Airline']['FlightNumber'];
$OriginAirportCode = $rowRecord['Segments']['0']['0']['Origin']['Airport']['AirportCode'];
$OriginCityName = $rowRecord['Segments']['0']['0']['Origin']['Airport']['CityName'];
$DestinationAirportCode = $rowRecord['Segments']['0']['0']['Destination']['Airport']['AirportCode'];
$DestinationCityName = $rowRecord['Segments']['0']['0']['Destination']['Airport']['CityName'];
$timeDuration = $hours.'h'.'&nbsp;'. $minutes.'m';

//echo "<pre>";print_r($rowRecord); die;
echo '<div class="col-md-12 pad0">
  <div class="flight_listin_box onewaysty" id="oneway-'.$i.'">
      <div class="row bus_margin0">                            
          <div class="col-md-2 bus_pading_left">
              <div class="flight_hans">
              <img src="'.base_url().'assets/images/jet.png" class="text-center" />
                  <p>'.$rowRecord['Segments']['0']['0']['Airline']['AirlineName'].'</p>
                      <p> '.$rowRecord['Segments']['0']['0']['Airline']['AirlineCode'].'-'.$rowRecord['Segments']['0']['0']['Airline']['FlightNumber'].'</p>
              </div>
          </div>
          <div class="col-md-3 bus_pading_left">
              <div class="flight_hans2">
                  <h5> '.$rowRecord['Segments']['0']['0']['Origin']['Airport']['AirportCode'].'('.$_departTime.') <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                  <p>'.$rowRecord['Segments']['0']['0']['Origin']['Airport']['CityName'].'</p>
              </div>
          </div>
          <div class="col-md-3 bus_pading_left">
              <div class="flight_hans2">
                  <h5>'.$rowRecord['Segments']['0']['0']['Destination']['Airport']['AirportCode'].'('.$_arivalTime.')</h5>
                  <p>'.$rowRecord['Segments']['0']['0']['Destination']['Airport']['CityName'].'</p>
              </div>
          </div>
          <div class="col-md-2 bus_pading_left">
              <div class="flight_hans3">
                  <h5>'.$hours.'h'.'&nbsp;'. $minutes.'m'.'</h5>
                  <span>'.$rowRecord['Segments']['0']['0']['NoOfSeatAvailable'].' Seat (s) left</span>
              </div>
          </div>
          <div class="col-md-2 bus_pading_left">
              <div class="flight_hans4 rs">
                 <span>
                      <i class="fa fa-inr" aria-hidden="true"></i> '.$perAdultFare.'
                      <div class="main-item">
                          <div class="item-list"><a  data-toggle="modal" data-target="#exampleModal" href=""> <i class="fa fa-sticky-note" aria-hidden="true"></i></a> </div>
                          <div class="item-list"> <i class="fa fa-registered '.$class.'" aria-hidden="true"></i></div>
                      </div>
                 </span>
              </div>
          </div>
			<div class="col-md-12">
				<a class="btn btn-success" onclick="javascript:selectedRecord(\''.$i.'\',\''.$resultIndex.'\',\''.$AirlineName.'\',\''.$AirlineCode.'\',\''.$FlightNumber.'\',\''.$OriginAirportCode.'\',\''.$OriginCityName.'\',\''.$DestinationAirportCode.'\',\''.$DestinationCityName.'\',\''.$timeDuration.'\',\''.$_departTime.'\',\''.$_arivalTime.'\',\''.$perAdultFare.'\')">Select</a>
			</div>     
	 </div>	  
      <div class="flight_Check">Check-In Baggage:'.$rowRecord['Segments']['0']['0']['Baggage'].'</div>
  </div>
 </div>'; 
$i++;
endforeach;  

echo '</div>';

echo '<div class="col-md-6 pad-right0">';  
$j=0;
//echo "<pre>";print_r($twowayjsonRecords); die;
foreach ($twowayjsonRecords['Response']['Results']['1'] as $rowRecords) :
$departTime = $rowRecords['Segments']['0']['0']['Origin']['DepTime'];
$arivalTime = $rowRecords['Segments']['0']['0']['Destination']['ArrTime'];
$_departTime  = date("g:i A", strtotime($departTime));
$_arivalTime  = date("g:i A", strtotime($arivalTime));
$Duration = $rowRecords['Segments']['0']['0']['Duration'];
$hours = floor($Duration / 60);
$minutes = $Duration % 60;
if($rowRecords['IsRefundable']==1){ $class="red"; } else { $class=""; }
//echo "<pre>";print_r($rowRecords); die;
$perAdultFare = $rowRecords['FareBreakdown']['0']['BaseFare'] / $rowRecords['FareBreakdown']['0']['PassengerCount'];

$ResultIndex = $rowRecords['ResultIndex'];
$AirlineName = $rowRecords['Segments']['0']['0']['Airline']['AirlineName'];
$AirlineCode = $rowRecords['Segments']['0']['0']['Airline']['AirlineCode'];
$FlightNumber = $rowRecords['Segments']['0']['0']['Airline']['FlightNumber'];
$OriginAirportCode = $rowRecords['Segments']['0']['0']['Origin']['Airport']['AirportCode'];
$OriginCityName = $rowRecords['Segments']['0']['0']['Origin']['Airport']['CityName'];
$DestinationAirportCode = $rowRecords['Segments']['0']['0']['Destination']['Airport']['AirportCode'];
$DestinationCityName = $rowRecords['Segments']['0']['0']['Destination']['Airport']['CityName'];
$timeDuration = $hours.'h'.'&nbsp;'. $minutes.'m';

echo '<div class="col-md-12 pad0">
<div class="flight_listin_box twowaysty" id="twoway-'.$j.'">
    <div class="row bus_margin0">                            
        <div class="col-md-2 bus_pading_left">
            <div class="flight_hans">
            <img src="'.base_url().'assets/images/jet.png" class="text-center" />
                <p>'.$rowRecords['Segments']['0']['0']['Airline']['AirlineName'].'
                    <br>'.$rowRecords['Segments']['0']['0']['Airline']['AirlineCode'].'-'.$rowRecords['Segments']['0']['0']['Airline']['FlightNumber'].'</p>
            </div>
        </div>
        
        <div class="col-md-3 bus_pading_left">
            <div class="flight_hans2">
                <h5> '.$rowRecords['Segments']['0']['0']['Origin']['Airport']['AirportCode'].'('.$_departTime.') <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                <p>'.$rowRecords['Segments']['0']['0']['Origin']['Airport']['CityName'].'</p>
            </div>
        </div>
        <div class="col-md-3 bus_pading_left">
            <div class="flight_hans2">
                <h5>'.$rowRecords['Segments']['0']['0']['Destination']['Airport']['AirportCode'].'('.$_arivalTime.')</h5>
                <p>'.$rowRecords['Segments']['0']['0']['Destination']['Airport']['CityName'].'</p>
            </div>
        </div>
        <div class="col-md-2 bus_pading_left">
            <div class="flight_hans3">
                <h5>'.$hours.'h'.'&nbsp;'. $minutes.'m'.'</h5>
                <span>'.$rowRecords['Segments']['0']['0']['NoOfSeatAvailable'].' Seat (s) left</span>
            </div>
        </div>
        <div class="col-md-2 bus_pading_left">
            <div class="flight_hans4 rs">
                <span>
                    <i class="fa fa-inr" aria-hidden="true"></i> '.$perAdultFare.'
                    <div class="main-item">
                         <div class="item-list"><a  data-toggle="modal" data-target="#exampleModal2" href=""> <i class="fa fa-sticky-note" aria-hidden="true"></i></a> </div>
                        <div class="item-list">
                         <a><i class="fa fa-registered '.$class.'" aria-hidden="true"></i> </a>
                        </div>
                    </div>
                </span>
            </div>
        </div>
       <div class="col-md-12">
    <a class="btn btn-success" onclick="javascript:twowayBookedDetail(\''.$j.'\',\''.$ResultIndex.'\',\''.$AirlineName.'\',\''.$AirlineCode.'\',\''.$FlightNumber.'\',\''.$OriginAirportCode.'\',\''.$OriginCityName.'\',\''.$DestinationAirportCode.'\',\''.$DestinationCityName.'\',\''.$timeDuration.'\',\''.$_departTime.'\',\''.$_arivalTime.'\',\''.$perAdultFare.'\')">Select</a>
    </div>
       </div>
        <div class="flight_Check">Check-In Baggage:'.$rowRecords['Segments']['0']['0']['Baggage'].'</div>
    </div>
    
</div>';
$j++;
endforeach; 
} 
echo '</div>';
echo '</div></div></div>';
} elseif($getcitynameFrom->CountryCode != $getcitynameTo->CountryCode){
echo '<div class="col-md-12  pad-left0">'; 
//echo "<pre>";print_r($twowayjsonRecords); die;
	if($twowayjsonRecords['Response']['ResponseStatus'] == 1){

		foreach ($twowayjsonRecords['Response']['Results'][0] as $IntralValue) {


$adults = @$IntralValue['0']['FareBreakdown']['PassengerCount'];
$childs = @$IntralValue['1']['FareBreakdown']['PassengerCount'];
$infants = @$IntralValue['2']['FareBreakdown']['PassengerCount'];

$_departTime  = date("g:i A", strtotime($IntralValue['Segments']['0']['0']['Origin']['DepTime']));
$_arivalTime  = date("g:i A", strtotime($IntralValue['Segments']['0']['0']['Destination']['ArrTime']));


$_departTime1  = date("g:i A", strtotime($IntralValue['Segments']['1']['0']['Origin']['DepTime']));
$_arivalTime1  = date("g:i A", strtotime($IntralValue['Segments']['1']['0']['Destination']['ArrTime']));

$hours = floor($IntralValue['Segments']['0']['0']['Duration'] / 60);
$minutes = $IntralValue['Segments']['0']['0']['Duration'] % 60;


$hours1 = floor($IntralValue['Segments']['1']['0']['Duration'] / 60);
$minutes1 = $IntralValue['Segments']['1']['0']['Duration'] % 60;


$IntralValue['Segments']['1']['0'];
			//echo "<pre>";print_r($IntralValue); 
			echo '<div class="flight_right_side_main">
  <div class="bus_listin_box_main">
      <div class="flight_listin_box">
          <div class="row bus_margin0" style="padding-top: 5px;">
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans"><img src="http://10.107.4.8/cashcoin/assets/images/airline/airline.jpg" alt="cruise">
                      <p>'.$IntralValue['Segments']['0']['0']['Airline']['AirlineName'].' <br> '.$IntralValue['Segments']['0']['0']['Airline']['AirlineCode'].'-'.$IntralValue['Segments']['0']['0']['Airline']['FlightNumber'].'</p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans2">
                      <h5> '.$IntralValue['Segments']['0']['0']['Origin']['Airport']['AirportCode'].'('.$_departTime.') <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                      <p>'.$IntralValue['Segments']['0']['0']['Origin']['Airport']['CityName'].'</p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans2">
                      <h5> '.$IntralValue['Segments']['0']['0']['Destination']['Airport']['AirportCode'].'('.$_arivalTime.')</h5>
                      <p>'.$IntralValue['Segments']['0']['0']['Destination']['Airport']['CityName'].'</p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans3">
                      <h5>'.$hours.'h'.'&nbsp;'. $minutes.'m'.'</h5>
                                            <span>'.$IntralValue['Segments']['0']['0']['NoOfSeatAvailable'].' seat(s) left</span>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans4">
                      <span><i class="fa fa-inr" aria-hidden="true"></i>'.$IntralValue['FareBreakdown'][0]['BaseFare'].'</span>
                  </div>
              </div>
              </div>


              <div class="row bus_margin0" style="padding-top: 5px;">

               <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans"><img src="http://10.107.4.8/cashcoin/assets/images/airline/airline.jpg" alt="cruise">
                      <p>'.$IntralValue['Segments']['1']['0']['Airline']['AirlineName'].' <br> '.$IntralValue['Segments']['1']['0']['Airline']['AirlineCode'].'-'.$IntralValue['Segments']['1']['0']['Airline']['FlightNumber'].'</p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans2">
                      <h5> '.$IntralValue['Segments']['1']['0']['Origin']['Airport']['AirportCode'].'('.$_departTime1.') <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                      <p>'.$IntralValue['Segments']['1']['0']['Origin']['Airport']['CityName'].'</p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans2">
                      <h5> '.$IntralValue['Segments']['1']['0']['Destination']['Airport']['AirportCode'].'('.$_arivalTime1.')</h5>
                      <p>'.$IntralValue['Segments']['1']['0']['Destination']['Airport']['CityName'].'</p>
                  </div>
              </div>
              <div class="col-md-2 bus_pading_left">
                  <div class="flight_hans3">
                      <h5>'.$hours1.'h'.'&nbsp;'. $minutes1.'m'.'</h5>
                        <span>'.$IntralValue['Segments']['1']['0']['NoOfSeatAvailable'].' seat(s) left</span>
                  </div>
              </div>
             
<div class="col-md-2 ">
  <div class="bus_hans2">
  <form name="FlightPassenger" id="FlightPassenger" method="POST" action="FlightPassengerDetails.php">
    <input type="hidden" name="ResultIndexkey" value="'.base64_encode($IntralValue['ResultIndex']).'">
    <input type="hidden" name="TokenId" value="'.$json->TokenId.'"> 
    <input type="hidden" name="traceID" value="'.base64_encode($twowayjsonRecords['Response']['TraceId']).'">
    <input type="submit" name="submitSearch" class="btn btn-warning flight_hans_btn" value="Book Now">
    <input type="hidden" name="international" value="'.base64_encode(1).'">
  </form>
  </div>
</div>
          
          </div>
          <div class="flight_Check">Check-In Baggage:'.$IntralValue['Segments']['1']['0']['Baggage'].', '.$IntralValue['Segments']['0']['0']['Baggage'].'</div>
      </div>
  </div>
</div>';
	
	}


	} else {

	echo '<div id="container">
  <div id="fof">
    <div class="hgroup">
      <h1 style="color:#979797;">Something Just Went Wrong!</h1>
    </div>
    <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
    <p>Go <a style="color:#ff3366" href="javascript:history.go(-1)">Back</a> or <a style="color:#ff3366" href="'.base_url().'">Home</a></p>
  </div>
</div>';

	}
	
//echo "<pre>";print_r($twowayjsonRecords); die;
echo '</div>';

}else {

echo '<div id="container">
  <div id="fof">
    <div class="hgroup">
      <h1 style="color:#979797;">Something Just Went Wrong!</h1>
    </div>
    <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
    <p>Go <a style="color:#ff3366" href="javascript:history.go(-1)">Back</a> or <a style="color:#ff3366" href="'.base_url().'">Home</a></p>
  </div>
</div>';
//echo "<pre>";print_r($twowayjsonRecords);
} 


endif;

if($post['wayType']==2){
echo '<section class="btfix-footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="flight_listin_box">
                            <div class="row bus_margin0">                            
                                <div class="col-md-3 bus_pading_left">
                                    <div class="flight_hans">
                                    <img src="'.base_url().'assets/images/jet.png" class="text-center">
                                        <p class="AirlineName">Airindia</p>
                                            <p><span class="airlineCode"> SG</span>-<span class="flightNumber">159B</span></p>
                                    </div>
                                </div>
                                <div class="col-md-3 bus_pading_left">
                                    <div class="flight_hans2">
                                        <h5> <span class="originAirportCode">DEL</span>(<span class="departTime">(11:45)</span>) <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                                        <p class="originCityName">Delhi</p>
                                    </div>
                                </div>
                                <div class="col-md-3 bus_pading_left">
                                    <div class="flight_hans2">
                                        <h5> <span class="destinationAirportCode">BOM</span>(<span class="arivalTime">23:45</span>)</h5>
                                        <p class="destinationCityName">Mumbai</p>
                                    </div>
                                </div>
                                <div class="col-md-3 bus_pading_left">
                                    <div class="flight_hans3">
                                        <h5 class="timeDuration">02h 10m</h5> 
                                        <span>Non-stop</span>                                       
                                    </div>
                                </div>                               
                                
                            </div>                           
                        </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="flight_listin_box">
                            <div class="row bus_margin0">                            
                                <div class="col-md-3 bus_pading_left">
                                   <div class="flight_hans">
                                    <img src="'.base_url().'assets/images/jet.png" class="text-center">
                                        <p class="AirlineNametwo">Airindia</p>
                                            <p><span class="airlineCodetwo"> SG</span>-<span class="flightNumbertwo">159B</span></p>
                                    </div>
                                </div>
                                <div class="col-md-3 bus_pading_left">
                                   <div class="flight_hans2">
                                        <h5> <span class="destinationAirportCodetwo">DEL</span>(<span class="departTimetwo">(11:45)</span>) <i class="fa fa-arrow-right fight_flot" aria-hidden="true"></i></h5>
                                        <p class="destinationCityNametwo">Delhi</p>
                                    </div>
                                </div>
                                <div class="col-md-3 bus_pading_left">
                                   <div class="flight_hans2">
                                        <h5> <span class="originAirportCodetwo">BOM</span>(<span class="arivalTimetwo">23:45</span>)</h5>
                                        <p class="originCityNametwo">Mumbai</p>
                                    </div>
                                </div>
                                <div class="col-md-3 bus_pading_left">
                                    <div class="flight_hans3">
                                        <h5 class="timeDurationtwo">02h 10m</h5> 
                                        <span>Non-stop</span>                                       
                                    </div>
                                </div>                               
                                
                            </div>                           
                        </div>
                </div>
            </div>
            <div class="col-sm-4">
              <div class="price-item"><i class="fa fa-inr" aria-hidden="true"></i>
              <input type="hidden" name="amounttotal" class="one" value="0">
              <input type="hidden" name="amounttotal" class="two" value="0">
              <h3 id="grandtotal11">9,189</h3>
              </div>
               <div class="price-item">
              <form name="FlightPassenger" id="FlightPassenger" method="POST" action="FlightPassengerDetails.php">
                    <input type="hidden" name="OutBound" value="" class="onwIndexkey">
                    <input type="hidden" name="InnerBound" value="" class="twoIndexkey">
                    <input type="hidden" name="TokenId" value='.base64_encode($json->TokenId).'> 
                    <input type="hidden" name="FlightWay" value="2">
                    <input type="hidden" name="TraceId" value='.base64_encode(@$TraceId).'>
                    <input type="submit" name="submitSearch" class="price-item" value="Book Flight">
              </form>
              </div>
            </div>
        </div>
    </div>
</section>'; } ?>

<?php } /* closed searchFlightAPI Class */

public function FlightPassengerDetails($selectValue) {

if(@$selectValue['FlightWay']==2) {
$TraceId      = base64_decode($selectValue['TraceId']);
$TokenId      = base64_decode($selectValue['TokenId']);
$OutBound     = base64_decode($selectValue['OutBound']);
$InnerBound   = base64_decode($selectValue['InnerBound']);

if($selectValue['OutBound']){
$fareRule = array(
"EndUserIp" => "203.122.11.211",
"TokenId" => $TokenId,
"TraceId" => $TraceId,
"ResultIndex" => $OutBound
);

$header_fareRule = array( "cache-control: no-cache", "content-type: application/json" );
$url_fateRule ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareRule/";
$str_data_fareRule = json_encode($fareRule);
//print_r($str_data_fareRule);

$method_fareRule = "POST";
$ch_fareRule = curl_init();
curl_setopt($ch_fareRule, CURLOPT_HEADER, false);
curl_setopt($ch_fareRule, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_fareRule, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch_fareRule, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch_fareRule, CURLOPT_HTTPHEADER, $header_fareRule);
curl_setopt($ch_fareRule, CURLOPT_CUSTOMREQUEST, $method_fareRule);
curl_setopt($ch_fareRule, CURLOPT_POSTFIELDS, $str_data_fareRule);
curl_setopt($ch_fareRule, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch_fareRule, CURLOPT_URL, $url_fateRule);
$response_fareRule = curl_exec($ch_fareRule);
$fare_rule = json_decode($response_fareRule,TRUE);
//echo "<pre>";print_r($fare_rule);

  if($fare_rule['Response']['ResponseStatus']==1){
    $getTraceId = $fare_rule['Response']['TraceId'];
  }

$FareQuote = array(
"EndUserIp" => "203.122.11.211",
"TokenId" => $TokenId,
"TraceId" => @$getTraceId,
"ResultIndex" => $OutBound
);

$header = array( "cache-control: no-cache", "content-type: application/json" );
$url ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote/";
$str_data_fare = json_encode($FareQuote);
$method = "POST";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str_data_fare);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);
$responseOutBound = curl_exec($ch);
$getOutBoundRecord = json_decode($responseOutBound,TRUE);

$getOutBoundIndex = @$getOutBoundRecord['Response']['Results']['ResultIndex'];
$getOutBoundLcc   = @$getOutBoundRecord['Response']['Results']['IsLCC'];
$getTraceId       = @$getOutBoundRecord['Response']['TraceId'];

} /* closed OB here */

if($selectValue['InnerBound']){
$fareRule = array(
"EndUserIp" => "203.122.11.211",
"TokenId" => $TokenId,
"TraceId" => $TraceId,
"ResultIndex" => $InnerBound
);

$header_fareRule = array( "cache-control: no-cache", "content-type: application/json" );
$url_fateRule ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareRule/";
$str_data_fareRule = json_encode($fareRule);

$method_fareRule = "POST";
$ch_fareRule = curl_init();
curl_setopt($ch_fareRule, CURLOPT_HEADER, false);
curl_setopt($ch_fareRule, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_fareRule, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch_fareRule, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch_fareRule, CURLOPT_HTTPHEADER, $header_fareRule);
curl_setopt($ch_fareRule, CURLOPT_CUSTOMREQUEST, $method_fareRule);
curl_setopt($ch_fareRule, CURLOPT_POSTFIELDS, $str_data_fareRule);
curl_setopt($ch_fareRule, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch_fareRule, CURLOPT_URL, $url_fateRule);
$response_fareRule = curl_exec($ch_fareRule);
$fare_rule = json_decode($response_fareRule,TRUE);
//echo "<pre>";print_r($fare_rule);

  if($fare_rule['Response']['ResponseStatus']==1){
    $getTraceId = $fare_rule['Response']['TraceId'];
  }

$FareQuote = array(
"EndUserIp" => "203.122.11.211",
"TokenId" => $TokenId,
"TraceId" => @$getTraceId,
"ResultIndex" => $InnerBound
);

$header = array( "cache-control: no-cache", "content-type: application/json" );
$url ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote/";
$str_data_fare = json_encode($FareQuote);
$method = "POST";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str_data_fare);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);
$responsedareInnerBound = curl_exec($ch);
$getInnerBoundRecord = json_decode($responsedareInnerBound,TRUE);

$getInnerBoundIndex    = @$getInnerBoundRecord['Response']['Results']['ResultIndex'];
$getInnerBoundLcc      = @$getInnerBoundRecord['Response']['Results']['IsLCC'];


$Adult  = @$getInnerBoundRecord['Response']['Results']['FareBreakdown'][0]['PassengerCount'];
$Child  = @$getInnerBoundRecord['Response']['Results']['FareBreakdown'][1]['PassengerCount'];
$Infant = @$getInnerBoundRecord['Response']['Results']['FareBreakdown'][2]['PassengerCount'];

} /* Closed IB here*/

} else {

$fareRule = array(
"EndUserIp" => "203.122.11.211",
"TokenId" => $selectValue['TokenId'],
"TraceId" => base64_decode($selectValue['traceID']),
"ResultIndex" => base64_decode($selectValue['ResultIndexkey'])
);







$header_fareRule = array( "cache-control: no-cache", "content-type: application/json" );
$url_fateRule ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareRule/";
$str_data_fareRule = json_encode($fareRule);

$method_fareRule = "POST";
$ch_fareRule = curl_init();
curl_setopt($ch_fareRule, CURLOPT_HEADER, false);
curl_setopt($ch_fareRule, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_fareRule, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch_fareRule, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch_fareRule, CURLOPT_HTTPHEADER, $header_fareRule);
curl_setopt($ch_fareRule, CURLOPT_CUSTOMREQUEST, $method_fareRule);
curl_setopt($ch_fareRule, CURLOPT_POSTFIELDS, $str_data_fareRule);
curl_setopt($ch_fareRule, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch_fareRule, CURLOPT_URL, $url_fateRule);
$response_fareRule = curl_exec($ch_fareRule);
//print_r($response_fareRule);
$fare_rule = json_decode($response_fareRule,TRUE);


$FareQuote = array(
"EndUserIp" => "203.122.11.211",
"TokenId" => $selectValue['TokenId'],
"TraceId" => $fare_rule['Response']['TraceId'],
//"TraceId" => base64_decode($selectValue['traceID']),
"ResultIndex" => base64_decode($selectValue['ResultIndexkey'])
);

$FlightSearchResult = \App\Model\FlightFareQuote::where(array(
		'from_location'=>"DEL",
		'to_location'=>"BOM",
		))->first();

$alreadyExists = count($FlightSearchResult);

//dd($FlightSearchResult);
if($alreadyExists == 0) {
$header = array( "cache-control: no-cache", "content-type: application/json" );
$url ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote/";
$str_data_fare = json_encode($FareQuote);
//echo "<pre>";print_r($str_data_fare); die;

$method = "POST";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str_data_fare);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);
$responsedare = curl_exec($ch);
$rowRecord = json_decode($responsedare,TRUE);


$apiDataRecord = new \App\Model\FlightFareQuote;
$apiDataRecord->apiData 	  = $responsedare;
$apiDataRecord->from_location = "DEL";
$apiDataRecord->to_location   = "BOM";
$apiDataRecord->save();

} else {
$rowRecord = json_decode($FlightSearchResult->apiData,TRUE);
}


echo base64_decode(@$selectValue['international']);

if($rowRecord['Response']['ResponseStatus'] == 1) {
$BaseFare      = $rowRecord['Response']['Results']['Fare']['BaseFare'];
$Tax           = $rowRecord['Response']['Results']['Fare']['Tax'];
//$PublishedFare = $rowRecord['Fare']['PublishedFare'];

$iSLCC    = $rowRecord['Response']['Results']['IsLCC'];
$TraceId  = $rowRecord['Response']['TraceId'];

$Baggage               = $rowRecord['Response']['Results']['Segments']['0']['0']['Baggage'];
$CabinBaggage          = $rowRecord['Response']['Results']['Segments']['0']['0']['CabinBaggage'];

$AirlineCode           = $rowRecord['Response']['Results']['Segments']['0']['0']['Airline']['AirlineCode'];
$AirlineName           = $rowRecord['Response']['Results']['Segments']['0']['0']['Airline']['AirlineName'];
$FlightNumber          = $rowRecord['Response']['Results']['Segments']['0']['0']['Airline']['FlightNumber'];
$FareClass             = $rowRecord['Response']['Results']['Segments']['0']['0']['Airline']['FareClass'];

$Depart_DepTime        = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['DepTime'];
$Depart_AirportCode    = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['AirportCode'];
$Depart_AirportName    = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['AirportName'];
$Depart_Terminal       = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['Terminal'];
$Depart_CityCode       = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['CityCode'];
$Depart_CityName       = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['CityName'];
$Depart_CountryCode    = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['CountryCode'];
$Depart_CountryName    = $rowRecord['Response']['Results']['Segments']['0']['0']['Origin']['Airport']['CountryName'];

$Arival_ArrTime        = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['ArrTime'];
$Arival_AirportCode    = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['Airport']['AirportCode'];
$Arival_AirportName    = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['Airport']['AirportName'];
$Arival_Terminal       = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['Airport']['Terminal'];
$Arival_CityCode       = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['Airport']['CityCode'];
$Arival_CityName       = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['Airport']['CityName'];
$Arival_CountryName    = $rowRecord['Response']['Results']['Segments']['0']['0']['Destination']['Airport']['CountryName'];

$Adult  = @$rowRecord['Response']['Results']['FareBreakdown'][0]['PassengerCount'];
$Child  = @$rowRecord['Response']['Results']['FareBreakdown'][1]['PassengerCount'];
$Infant = @$rowRecord['Response']['Results']['FareBreakdown'][2]['PassengerCount'];

$OtherCharges = $rowRecord['Response']['Results']['Fare']['OtherCharges'];

$AdultFare                = @$rowRecord['Response']['Results']['FareBreakdown'][0]['BaseFare'];
$AdultTax                 = @$rowRecord['Response']['Results']['FareBreakdown'][0]['Tax'];

$ChildFare                = @$rowRecord['Response']['Results']['FareBreakdown'][1]['BaseFare'];
$ChildTax                 = @$rowRecord['Response']['Results']['FareBreakdown'][1]['Tax'];

$InfantFare               = @$rowRecord['Response']['Results']['FareBreakdown'][2]['BaseFare'];
$InfantTax                = @$rowRecord['Response']['Results']['FareBreakdown'][2]['Tax'];

$departTime  = date("g:i A", strtotime($Depart_DepTime));
$arivalTime  = date("g:i A", strtotime($Arival_ArrTime));

$getDepartDate = date("jS F, Y", strtotime($Depart_DepTime));
$getArivalDate = date("jS F, Y", strtotime($Arival_ArrTime));


$Duration = $rowRecord['Response']['Results']['Segments']['0']['0']['Duration'];
$hours = floor($Duration / 60);
$minutes = $Duration % 60;
}
}
?>

<div class="container">
    <div class="col-md-9 ">
    <?php if(@$selectValue['FlightWay']!=2){ ?>    
        <div class="flight_details_main_right">
            <div class="flight_details_right_top">
                <ul>
                    <li>
                        <div class="flight_spriz">
                            <img src="<?php echo base_url(); ?>assets/images/airline/airline.jpg" alt="cruise">
                            <p><?php echo @$AirlineName; ?>
                                <br> <?php echo @$AirlineCode; ?>-<?php echo @$FlightNumber; ?></p>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li>
                        <div class="flight_del">
                            <h4><?php echo @$Depart_AirportCode; ?> </h4>
                            <p><?php echo @$getDepartDate; ?> | <?php echo @$departTime; ?></p>
                            <div><?php echo @$Depart_AirportName; ?></div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li>
                        <div class="flight_del">
                            <h4><?php echo @$Arival_AirportCode; ?> </h4>
                            <p><?php echo @$getArivalDate; ?> | <?php echo @$arivalTime; ?></p>
                            <div><?php echo @$Arival_AirportName; ?></div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="flight_del_bor_none">
                        <div class="flight_del">
                            <h4>Duration : </h4>
                            <p><?php echo @$hours.'h'.'&nbsp;'. @$minutes.'m'; ?></p>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

        </div>
         <div class="enter_passernger">
            <h5>Enter Passenger Details</h5> <span>(Check-In Baggage:<?php echo @$Baggage; ?>)</span>
            <a href="#"> Choose Another Fare</a>
              <p>(please add coreect details of the Passenger as mentioned)</p>
        </div>

<?php } else { ?>

<?php
//echo "<pre>"; print_r($getOutBoundRecord); die; 
$OBAirlineCode = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['AirlineCode'];
$OBAirlineName = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['AirlineName'];
$OBFlightNumber = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['FlightNumber'];
$OBFareClass = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['FareClass'];

$OBAirport   = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['Airport']['AirportCode'];
$OBCityName  = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['Airport']['CityName'];
$OBDepTime 	 = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['DepTime'];
$OBArrTime 	 = $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Destination']['ArrTime'];

$IBAirlineCode 	= $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['AirlineCode'];
$IBAirlineName 	= $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['AirlineName'];
$IBFlightNumber = $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['FlightNumber'];
$IBFareClass 	= $getOutBoundRecord['Response']['Results']['Segments'][0][0]['Airline']['FareClass'];

$IBAirportCode 	= $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['Airport']['AirportCode'];
$IBCityName 	= $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['Airport']['CityName'];
$IBDepTime 		= $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['DepTime'];
$IBArrTime 		= $getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Destination']['ArrTime'];
$OBgetDepartDate = @date("jS F, Y", strtotime($OBDepTime));
$IBgetDepartDate = @date("jS F, Y", strtotime($IBDepTime));

$getAdults 	 = @$getOutBoundRecord['Response']['Results']['FareBreakdown']['0']['PassengerCount'];
$getChilds 	 = @$getOutBoundRecord['Response']['Results']['FareBreakdown']['1']['PassengerCount'];
$getInfants  = @$getOutBoundRecord['Response']['Results']['FareBreakdown']['2']['PassengerCount'];


?>
 <div class="flight_details_round_trip_right">
    <div class="flight_details_round_trip">
        <div class="col-md-1 pad0">
            <div class="flight_spriz_round">
                <img src="<?php echo base_url(); ?>assets/images/airline/airline.jpg" alt="cruise">
                <p><?php echo $OBAirlineName; ?>
                    <br> <?php echo $OBAirlineCode; ?>- <?php echo $OBFlightNumber;?></p>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-3 pad0">
            <div class="round_trip_deit">
                <h5><?php echo $OBAirport; ?> <span>(<?php echo $OBCityName; ?>)</span></h5>

                <span><?php echo $OBgetDepartDate; ?></span>
                <div class="round_time">
                	<?php echo @date("g:i A", strtotime($OBDepTime)). ' - ' .@date("g:i A", strtotime($OBArrTime)); ?>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="round_trip_arrow"><img src="<?php echo base_url(); ?>assets/images/arrow_round.png" alt="cruise">
            </div>
        </div>
        <div class="col-md-1 pad0">
            <div class="flight_spriz_round">
                <img src="<?php echo base_url(); ?>assets/images/airline/airline.jpg" alt="cruise">
                <p><?php echo $IBAirlineName; ?>
                    <br> <?php echo $IBAirlineCode; ?>-<?php echo $IBFlightNumber; ?></p>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-3 pad0">
            <div class="round_trip_deit">
                <h5><?php echo $IBAirportCode; ?> <span>(<?php echo $IBCityName; ?>)</span></h5>
                <span><?php echo $IBgetDepartDate; ?></span>
                <div class="round_time"><?php echo @date("g:i A", strtotime($IBDepTime)). ' - ' .@date("g:i A", strtotime($IBArrTime)); ?></div>
            </div>
        </div>
        <div class="col-md-3 round_border_left">
            <div class="round_trip_trav">
                <h5> Traveller</h5>
                <p> <?php echo $getAdults . 'Adult'; ?>  
                	<?php if($getChilds!=""):?><?php echo $getChilds. ' Child,'; ?><?php endif; ?>
                	<?php if($getInfants!=""):?><?php echo $getInfants. ' Infant,'; ?><?php endif; ?>
                </p>
            </div>
        </div>

    </div>
    <div class="clearfix"></div>
</div>

<?php } ?>

 <form name="booked" id="booked" action="flightBooked.php" method="POST">
        <div class="passernger_box">
            <div class="passernger_adult1">
             <?php for( $i=1; $i<=@$Adult; $i++ ): ?>
                <div class="passernger_adult">
                    <h2>Passenger <?php echo $i; ?> - <span>(Adult <?php echo $i; ?>)</span></h2>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">First Name</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control form_control" required name="adulttitle[]">
                                      <option selected="selected" value="">Title</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>   
                                        <option value="Mstr">Mstr</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input id="adultfirstname" required name="adultfirstname[]" type="text" placeholder="Your name" class="form-control form_control">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">Gender</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form_control" required name="adultgender[]" required>
                                       <option value="0">Choose</option>
                                        <option value="1"> Male </option>
                                        <option value="2"> Female </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">D.O.B :</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" required name="adultDOB[]" id="adultDOB" value="" placeholder="MM-DD-YY">
                                </div>

                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">Address:</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="adultaddress" required name="adultaddress[]" type="text" placeholder="--XX--" class="form-control form_control">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel"></label>
                                </div>
                                <div class="col-md-9">
                                    <input id="adultaddress1" required name="adultaddress1[]" type="text" placeholder="" class="form-control form_control">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">City</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="adultcity" required name="adultcity[]" type="text" placeholder="" class="form-control form_control">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Last Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="adultlastname" required name="adultlastname[]" type="text" placeholder="Last name" class="form-control form_control">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Mobile:</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="adultmobile" required name="adultmobile[]" type="text" placeholder="Mobile No" class="form-control form_control">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Email:</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="adultemail" required name="adultemail[]" type="email" placeholder="Email ID" class="form-control form_control">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Country:</label>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control form_control" name="adultcountry[]">
                                        <option value="India"> India </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php endfor; ?>

                <div class="flight_gst_details">
                    <h2><input type="checkbox" name="vehicle" value="Bike"> GST Detail <span>(Not:Please fill GST Details only for corporate customer)</span></h2>

                    <div class="row passernger_adult_row">
                        <div class="col-md-3">
                            <label class="passernger_gst_lebel">Select Excess Baggage (Extra charges will be applicable):</label>
                        </div>
                        <div class="col-md-5">
                            <label class="gst_lebel">DEL-BOM</label>
                            <select class="form-control form_control" name="ExtraBaggage">
                                <option value="India"> No Excess/Extra Baggage </option>

                            </select>
                        </div>
                    </div>

                    <div class="row passernger_adult_row">
                        <div class="col-md-3">
                            <label class="passernger_gst_lebel">Meal Preferences :</label>
                        </div>
                        <div class="col-md-5">
                            <label class="gst_lebel">DEL-BOM</label>
                            <select class="form-control form_control" name="MealPreferences">
                                <option value="India"> Add No Meal Rs.-0 </option>

                            </select>
                        </div>
                    </div>

                    <div class="row passernger_adult_row">
                        <div class="col-md-12">
                            <div class="gst_update">
                                <input class="gst_updatecheckbox" type="checkbox" name="vehicle" value="Bike">
                                <p> Save/Update Customer in "My Customer" List.</p>
                                </label>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        <?php for( $j=1; $j<=@$Child; $j++ ): ?>
            <div class="passernger_adult1">
                <div class="passernger_adult">
                    <h2>Passenger <?php echo $j; ?> - <span>(Child <?php echo $j; ?>)</span></h2>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">First Name</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control form_control" required name="childtitle[]">
                                        <option selected="selected" value="">Title</option>                     
                                        <option value="Mr">Mr</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>   
                                        <option value="Mstr">Mstr</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input id="childfirstname" required name="childfirstname[]" placeholder="First name" class="form-control form_control" type="text">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">Gender</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form_control" required name="childgender[]">
                                       <option value="0">Choose</option>
                                        <option value="1"> Male </option>
                                        <option value="2"> Female </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">D.O.B :</label>
                                </div>

                                <div class="col-md-3">
                                   <input type="date" name="childDBO[]" required id="childDBO" value="" placeholder="DD-MM-YY">
                                </div>

                                

                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Last Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="childlastname" name="childlastname[]" required placeholder="Last name" class="form-control form_control" type="text">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Country:</label>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control form_control" name="childcountry[]" required>
                                        <option value="India"> India </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
<?php endfor; ?>
        <?php for( $k=1; $k<=@$Infant; $k++ ): ?>
            <div class="passernger_adult1">
                <div class="passernger_adult">
                    <h2>Passenger <?php echo $k; ?> - <span>(Infant <?php echo $k; ?>)</span></h2>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">First Name</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control form_control" required name="infanttitle[]">
                                        <option selected="selected" value="">Title</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>   
                                        <option value="Mstr">Mstr</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input id="infantfirstname" name="infantfirstname[]" required placeholder="First name" class="form-control form_control" type="text">
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">Gender</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form_control" required name="infantgender[]">
                                       <option value="0">Choose</option>
                                        <option value="1"> Male </option>
                                        <option value="2"> Female </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row passernger_adult_row">
                                <div class="col-md-3">
                                    <label class="passernger_adult_lebel">D.O.B :</label>
                                </div>
                                <div class="col-md-3">
                                   <input type="date" id="infantDBO" name="infantDBO[]" value="" required placeholder="DD-MM-YY">
                                </div>

                            </div>

                          
                        </div>
                        <div class="col-md-6">

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Last Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <input id="infantlastname" name="infantlastname[]" required placeholder="Last name" class="form-control form_control" type="text">
                                </div>
                            </div>

                           

                            <div class="row passernger_adult_row">
                                <div class="col-md-4">
                                    <label class="passernger_adult_lebel">Country:</label>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-control form_control" name="infantcountry[]" required>
                                        <option value="India"> India </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

            </div>
           <?php endfor; ?>
          <?php if(@$selectValue['FlightWay']!=2) {  ?>
            <div class="btnrow" style="text-align: right;">
              <input type="hidden" name="isLCC" value="<?php echo @$iSLCC; ?> ">
               <input type="hidden" name="Traceid" value="<?php echo @$TraceId; ?>">
               <input type="hidden" name="TokenId" value="<?php echo @$selectValue['TokenId']; ?>"> 
               <input type="hidden" name="bookingId" value="<?php echo str_random(8); ?>">
               <input type="hidden" name="jsonData" value="<?php echo base64_encode(json_encode($rowRecord)); ?>">
               <input type="submit" name="ProceedBooking" class="btn-primary" value="Proceed to Booking" style="padding: 10px;">
            </div>
            <?php } else { ?>
        <input type="hidden" name="TokenId" value="<?php echo $selectValue['TokenId']; ?>">
        <input type="hidden" name="bookingId" value="<?php echo str_random(8); ?>">
        <input type="hidden" name="OutBoundData" value="<?php echo base64_encode($responseOutBound);?>">
        <input type="hidden" name="InnerBoundData" value="<?php echo base64_encode($responsedareInnerBound);?>">
        <input type="hidden" name="getInnerBoundIndex" value="<?php echo $getInnerBoundIndex;?>">
        <input type="hidden" name="getTraceId" value="<?php echo $getTraceId;?>">
        <input type="hidden" name="getOutBoundIndex" value="<?php echo $getOutBoundIndex;?>">
        <input type="hidden" name="getOutBoundLcc" value="<?php echo $getOutBoundLcc;?>">
        <input type="hidden" name="getInnerBoundLcc" value="<?php echo $getInnerBoundLcc;?>">
        <input type="hidden" name="wayType" value="2">
        <input type="submit" name="ProceedBooking" class="btn-primary" value="Proceed to Booking" style="padding: 10px;">
            <?php } ?>
          </form>
        </div>

    </div>
        <!-- END: FLIGHT details AREA -->

 
<?php if(@$selectValue['FlightWay']!=2) { ?>
<?php 
$getDepartDate1 = date("d M y,", strtotime(@$Depart_DepTime));
$getArivalDate1 = date("d M y", strtotime(@$Arival_ArrTime));

?>
<div class="col-md-3 ">
        <div class="flight_details_main_left">
            <h3>Sale Summary</h3>
            <div class="flight_details_left_top">
                <div class="flight_details_left">
                    <ul>
                        <div class="row flight_details_row flight_details_bottom">
                            <li><?php echo @$getDepartDate1; ?></li>
                            <li class="flight_det_tex_alin1"><?php echo @$AirlineCode; ?>-<?php echo @$FlightNumber; ?></li>
                            <li class="flight_det_tex_alin2"><?php echo @$FareClass ; ?> Class</li>
                        </div>
                        <li>Dept:</li>
                        <li class="flight_det_tex_alin1"> <?php echo @$Depart_AirportCode; ?></li>
                        <li class="flight_det_tex_alin2">
                            @<?php echo @$departTime; ?></li>

                        <li>Arr:</li>
                        <li class="flight_det_tex_alin1"> <?php echo @$Arival_AirportCode; ?></li>
                        <li class="flight_det_tex_alin2">

                            @<?php echo @$arivalTime; ?></li>

                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="flight_fare">
                    <h5>Fare / Pax Type </h5>
                    <div class="flight_fare_opn">
                        <ul>
                            <li>
                                <p>Adult x <?php echo @$Adult;?></p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php 
                                $adultsAmount = @$AdultFare; 
                                echo number_format(@$adultsAmount,2);
                                ?></span>
                            </li>
                            <li>
                                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format(@$AdultTax,2); ?></span>
                            </li>

                            <li>
                                <p>T. Fee </p><span><i class="fa fa-inr" aria-hidden="true"></i><?php echo number_format($OtherCharges,2); ?></span>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>
                      <?php 
                      $adult_total =  @$AdultFare + @$AdultTax + @$OtherCharges; 
                        echo number_format($adult_total,2);
                      ?></code>
                        </h5>
                    <?php if(@$Child!=""): ?>   
                    <div class="flight_fare_opn">
                        <ul>
                            <li>
                                <p>Child x <?php echo @$Child;?></p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php 
                                $ChildsAmount = @$ChildFare;
                                echo number_format($ChildsAmount,2);
                                 ?></span>
                            </li>
                            <li>
                                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($ChildTax,2); ?></span>
                            </li>

                            <li>
                                <p>Child </p><span><i class="fa fa-inr" aria-hidden="true"></i> 0.00</span>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                      <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>
                        <?php 
                      $child_total = @$ChildFare + @$ChildTax;
                      echo number_format($child_total,2);
                       ?>
                      </code></h5>
                      <?php endif; ?>  
                      <?php if(@$Infant!=""):?>
                      <div class="flight_fare_opn">
                        <ul>
                            <li>
                                <p>Infant x <?php echo @$Infant;?></p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php 
                                $InfantsTotal =  @$InfantFare;
                                echo number_format($InfantsTotal,2);
                                ?></span>
                            </li>
                            <li>
                                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($InfantTax,2); ?></span>
                            </li>

                            <li>
                                <p>Infant </p><span><i class="fa fa-inr" aria-hidden="true"></i> 0.00</span>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                      <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>
                        <?php 
                         $infant_adult = @$InfantFare + @$InfantTax; 
                         echo number_format($infant_adult,2);
                         ?></code></h5>
                    <?php endif; ?>
                     <h5 class="flight_far">Sub Total: <code><i class="fa fa-inr" aria-hidden="true"></i>
                        <?php 
                         $subtotot =  $adult_total + @$child_total + @$infant_adult;
                         echo number_format($subtotot,2); 

                         ?></code></h5>
                </div>
            </div>

        </div>
</div>
<?php } else { ?>
<?php
//echo '<pre>';print_r($getOutBoundRecord);
$Adult_OB_baseFare 	= @$getOutBoundRecord['Response']['Results']['FareBreakdown']['0']['BaseFare'];
$Adult_OB_Tax 		= @$getOutBoundRecord['Response']['Results']['FareBreakdown']['0']['Tax'];
$child_OB_baseFare 	= @$getOutBoundRecord['Response']['Results']['FareBreakdown']['1']['BaseFare'];
$child_OB_Tax 		= @$getOutBoundRecord['Response']['Results']['FareBreakdown']['1']['Tax'];
$infant_OB_baseFare = @$getOutBoundRecord['Response']['Results']['FareBreakdown']['2']['BaseFare'];
$infant_OB_Tax 		= @$getOutBoundRecord['Response']['Results']['FareBreakdown']['2']['Tax'];


$OBAirport1   = @$getOutBoundRecord['Response']['Results']['Segments'][0][0]['Destination']['Airport']['AirportCode'];
$IBAirport1   = @$getInnerBoundRecord['Response']['Results']['Segments'][0][0]['Origin']['Airport']['AirportCode'];
?>
<div class="col-md-3 ">
    <div class="flight_details_main_left">
        <h3>Sale Summary</h3>
        <div class="flight_details_left_top">
            <div class="flight_details_left">
                <ul>
                    <div class="row flight_details_row flight_details_bottom">
                        <li><?php echo @date("d M, y", strtotime($OBDepTime)); ?></li>
                        <li class="flight_det_tex_alin1"><?php echo $OBAirlineCode; ?>-<?php echo $OBFlightNumber; ?></li>
                        <li class="flight_det_tex_alin2"><?php echo $OBFareClass; ?> Class</li>
                    </div>
                    <li>Dept:</li>
                    <li class="flight_det_tex_alin1"><?php echo $OBAirport; ?></li>
                    <li class="flight_det_tex_alin2">@<?php echo @date("g:i A", strtotime($OBDepTime)); ?></li>

                    <li>Arr:</li>
                    <li class="flight_det_tex_alin1"><?php echo $OBAirport1; ?></li>
                    <li class="flight_det_tex_alin2">@<?php echo @date("g:i A", strtotime($OBArrTime)); ?></li>

                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="flight_details_left round_tri_mar">
                <ul>
                    <div class="row flight_details_row flight_details_bottom">
                        <li><?php echo @date("d M, y", strtotime($IBgetDepartDate)); ?></li>
                        <li class="flight_det_tex_alin1"><?php echo $IBAirlineCode; ?>-<?php echo $IBFlightNumber; ?></li>
                        <li class="flight_det_tex_alin2"><?php echo $IBFareClass; ?> Class</li>
                    </div>
                    <li>Dept:</li>
                    <li class="flight_det_tex_alin1"> <?php echo $OBAirport1; ?> </li>
                    <li class="flight_det_tex_alin2">@<?php echo @date("g:i A", strtotime($IBDepTime)); ?></li>

                    <li>Arr:</li>
                    <li class="flight_det_tex_alin1"><?php echo $OBAirport; ?></li>
                    <li class="flight_det_tex_alin2">@<?php echo @date("g:i A", strtotime($IBArrTime)); ?></li>
                </ul>
                <div class="clearfix"></div>
            </div>

<div class="flight_fare">
<h5>Fare / Pax Type </h5>
    <div class="flight_fare_opn">
        <ul>
            <li>
                <p>Adult x <?php echo $getAdults; ?></p><span><i class="fa fa-inr" aria-hidden="true"></i>
                	<?php echo $Adult_OB_baseFare; ?></span>
            </li>
            <li>
                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i><?php echo $Adult_OB_Tax; ?></span>
            </li>
            <li>
                <p>T. Fee </p><span><i class="fa fa-inr" aria-hidden="true"></i>0.00</span>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>

        <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>
        	<?php
        	 $adultfaretotal =  $Adult_OB_baseFare+$Adult_OB_Tax; 
        	 echo $adultfaretotal;
        	?>
        </code></h5>
<?php if($getChilds!=""):?>
<div class="flight_fare_opn">
    <ul>
        <li>
            <p>Child x <?php echo $getChilds; ?></p><span><i class="fa fa-inr" aria-hidden="true"></i><?php echo $child_OB_baseFare; ?></span>
        </li>
        <li>
            <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i><?php echo $child_OB_Tax; ?></span>
        </li>

        <li>
            <p>Child </p><span><i class="fa fa-inr" aria-hidden="true"></i>0.00</span>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>
        <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>

        	<?php
        	 $childfaretotal =  $child_OB_baseFare+$child_OB_Tax;
        	 echo $childfaretotal;
        	?>
        </code></h5>
<?php endif;?>

<?php if($getInfants!=""):?>
<div class="flight_fare_opn">
        <ul>
            <li>
                <p>Infant x <?php echo $getInfants; ?></p><span><i class="fa fa-inr" aria-hidden="true"></i><?php echo $infant_OB_baseFare; ?></span>
            </li>
            <li>
                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i><?php echo $infant_OB_Tax;?></span>
            </li>
            <li>
                <p>Infant </p><span><i class="fa fa-inr" aria-hidden="true"></i>0.00</span>
            </li>
        </ul>		
        <div class="clearfix"></div>
</div>
        <h5 class="flight_far">Total: 
        	<code>
        	<i class="fa fa-inr" aria-hidden="true"></i>
        	<?php 
        	$infantfaretotal = $infant_OB_baseFare+$infant_OB_Tax;
        	echo $infantfaretotal;
        	?>
        </code>
    </h5>
   <?php endif; ?> 
</div>
            <div class="round_sub_total">
                <h4>Sub Total</h4>
                <span><i class="fa fa-inr" aria-hidden="true"></i>
                	<?php echo $adultfaretotal + @$childfaretotal + @$infantfaretotal; ?>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>            

<?php } ?>



        <!-- END: FLIGHT details LEFT SIDE AREA -->
    </div> <?php  /*else { ?>
<div id="container">
  <div id="fof">
    <div class="hgroup">
      <h1 style="color:#979797;">Something Just Went Wrong 1!</h1>
      <h2>404 Error</h2>
    </div>
    <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
    <p>Go <a style="color:#ff3366" href="javascript:history.go(-1)">Back</a> or <a style="color:#ff3366" href="<?php echo base_url();?>">Home</a></p>
  </div>
</div>

    <?php } */ ?>
<?php } /* end Class FlightPassengerDetails here*/
 
public function BookedParameter($bookedValue) {

//echo "<pre";print_r($bookedValue); die;

//echo '<pre>';print_r($selecedValueOutBound); 
//echo "<hr>";
//echo '<pre>';print_r($selecedValueInnerBound); 

//echo '<pre>'; print_r($bookedValue); die('am working here do not disturb');

$IsLCC      = trim(@$bookedValue['isLCC']); 
//$Traceid    = $bookedValue['Traceid']; 
//$bookingId  = $bookedValue['bookingId']; 

//echo "<pre>";print_r($bookedValue);



$userId = 1;   

if(@$bookedValue['wayType']!=2){ 
$selecedValue = json_decode(base64_decode($bookedValue['jsonData']));  
$FlightBooked = \App\Model\FlightBookingParameter::FirstOrNew(['booking_id'=>$bookedValue['bookingId']]);
//$FlightBooked = new \App\Model\FlightBookingParameter;
$FlightBooked->booking_id = @$bookedValue['bookingId'];
$FlightBooked->user_id = @$userId;
$FlightBooked->bookedData = json_encode(@$selecedValue);
$FlightBooked->TraceId = @$bookedValue['Traceid'];
$FlightBooked->TokenId = @$bookedValue['TokenId'];
$FlightBooked->save();

$NoOfadult  = @$selecedValue->Response->Results->FareBreakdown[0]->PassengerCount;
$NoOfchild  = @$selecedValue->Response->Results->FareBreakdown[1]->PassengerCount;
$NoOfinfant = @$selecedValue->Response->Results->FareBreakdown[2]->PassengerCount;
} else {

$selecedValueOutBound = json_decode(base64_decode($bookedValue['OutBoundData']));
$selecedValueInnerBound = json_decode(base64_decode($bookedValue['InnerBoundData']));

$FlightBooked = \App\Model\FlightBookingParameter::FirstOrNew(['booking_id'=>$bookedValue['bookingId']]);
//$FlightBooked = new \App\Model\FlightBookingParameter;
$FlightBooked->booking_id = @$bookedValue['bookingId'];
$FlightBooked->user_id = @$userId;
$FlightBooked->OutBoundRecord = json_encode(@$selecedValueOutBound);
$FlightBooked->InnerBounRecord = json_encode(@$selecedValueInnerBound);
$FlightBooked->TraceId = @$bookedValue['getTraceId'];
$FlightBooked->TokenId = @$bookedValue['TokenId'];
$FlightBooked->save();

//$selecedValueOutBound = json_decode(base64_decode($bookedValue['OutBoundData']));  
$NoOfadult  = @$selecedValueOutBound->Response->Results->FareBreakdown[0]->PassengerCount;
$NoOfchild  = @$selecedValueOutBound->Response->Results->FareBreakdown[1]->PassengerCount;
$NoOfinfant = @$selecedValueOutBound->Response->Results->FareBreakdown[2]->PassengerCount;
}
   
//echo "<pre>";print_r($selecedValue); die;

   for($i = 0; $i < $NoOfadult; $i++) {
    //$AdultValues = \App\Model\FlightAdultPassenger::FirstOrNew(['booking_id'=>$bookedValue['bookingId']]);
    $AdultValues = new \App\Model\FlightAdultPassenger;
     $AdultValues->booking_id = $bookedValue['bookingId'];
     $AdultValues->user_id = $userId;
     $AdultValues->title = $bookedValue['adulttitle'][$i];
     $AdultValues->firstName = $bookedValue['adultfirstname'][$i];
     $AdultValues->lastName = $bookedValue['adultlastname'][$i];
     $AdultValues->gender = $bookedValue['adultgender'][$i];
     $AdultValues->mobile = $bookedValue['adultmobile'][$i];
     $AdultValues->adultDOB = $bookedValue['adultDOB'][$i];
     $AdultValues->email = $bookedValue['adultemail'][$i];
     $AdultValues->address1 = $bookedValue['adultaddress'][$i];
     $AdultValues->address2 = $bookedValue['adultaddress1'][$i];
     $AdultValues->city = $bookedValue['adultcity'][$i];
     $AdultValues->country = $bookedValue['adultcountry'][$i];
     $AdultValues->save();
}
  
    for($j = 0; $j < $NoOfchild; $j++) { 
     //$ChildValues = \App\Model\FlightChildPassenger::FirstOrNew(['booking_id'=>$bookedValue['bookingId']]);
     $ChildValues = new \App\Model\FlightChildPassenger;
     $ChildValues->booking_id = $bookedValue['bookingId'];
     $ChildValues->user_id = $userId;
     $ChildValues->title = @$bookedValue['childtitle'][$j];
     $ChildValues->firstName = @$bookedValue['childfirstname'][$j];
     $ChildValues->lastName = @$bookedValue['childlastname'][$j];
     $ChildValues->gender = @$bookedValue['childgender'][$j];
     $ChildValues->childdob = @$bookedValue['childDBO'][$j];
     $ChildValues->country = @$bookedValue['childcountry'][$j];
     $ChildValues->save();
  }
   
    for($k = 0; $k < $NoOfinfant; $k++) { 
     //$InfantValues = \App\Model\FlightInfantPassenger::FirstOrNew(['booking_id'=>$bookedValue['bookingId']]);
     $InfantValues = new \App\Model\FlightInfantPassenger;
     $InfantValues->booking_id = $bookedValue['bookingId'];
     $InfantValues->user_id = $userId;
     $InfantValues->title = @$bookedValue['infanttitle'][$k];
     $InfantValues->firstName = @$bookedValue['infantfirstname'][$k];
     $InfantValues->lastName = @$bookedValue['infantlastname'][$k];
     $InfantValues->gender = @$bookedValue['infantgender'][$k];
     $InfantValues->infantbob = @$bookedValue['infantDBO'][$k];
     $InfantValues->country = @$bookedValue['infantcountry'][$k];
     $InfantValues->save(); 
 }




//echo "<pre>";print_r($selecedValue); die;
if(@$bookedValue['wayType']!=2) { 
$AirlineCode  = @$selecedValue->Response->Results->Segments[0][0]->Airline->AirlineCode;
$AirlineName  = @$selecedValue->Response->Results->Segments[0][0]->Airline->AirlineName;
$FlightNumber = @$selecedValue->Response->Results->Segments[0][0]->Airline->FlightNumber;
$FareClass    = @$selecedValue->Response->Results->Segments[0][0]->Airline->FareClass;

$depart_AirportCode  = @$selecedValue->Response->Results->Segments[0][0]->Origin->Airport->AirportCode;
$depart_AirportName  = @$selecedValue->Response->Results->Segments[0][0]->Origin->Airport->AirportName;
$depart_CityCode     = @$selecedValue->Response->Results->Segments[0][0]->Origin->Airport->CityCode;
$depart_CityName     = @$selecedValue->Response->Results->Segments[0][0]->Origin->Airport->CityName;
$depart_DepTime      = @$selecedValue->Response->Results->Segments[0][0]->Origin->DepTime;


$arival_AirportCode  = @$selecedValue->Response->Results->Segments[0][0]->Destination->Airport->AirportCode;
$arival_AirportName  = @$selecedValue->Response->Results->Segments[0][0]->Destination->Airport->AirportName;
$arival_CityCode     = @$selecedValue->Response->Results->Segments[0][0]->Destination->Airport->CityCode;
$arival_CityName     = @$selecedValue->Response->Results->Segments[0][0]->Destination->Airport->CityName;
$arival_Arrtime      = @$selecedValue->Response->Results->Segments[0][0]->Destination->ArrTime;

$Adult_baseFare      = @$selecedValue->Response->Results->FareBreakdown[0]->BaseFare;
$Adult_tax           = @$selecedValue->Response->Results->FareBreakdown[0]->Tax;

$child_baseFare      = @$selecedValue->Response->Results->FareBreakdown[1]->BaseFare;
$child_tax           = @$selecedValue->Response->Results->FareBreakdown[1]->Tax;

$infant_baseFare     = @$selecedValue->Response->Results->FareBreakdown[2]->BaseFare;
$infant_tax          = @$selecedValue->Response->Results->FareBreakdown[2]->Tax;

$getDepartDate1 = date("d M y,", strtotime($depart_DepTime));
$getArivalDate1 = date("d M y,", strtotime($arival_Arrtime));


$departTime  = date("g:i A", strtotime($depart_DepTime));
$arivalTime  = date("g:i A", strtotime($arival_Arrtime));
} else {

$selectedTBLRecord = \App\Model\FlightBookingParameter::where([
	'booking_id'=>$bookedValue['bookingId'],
	'user_id'=> $userId
])->first();

//dd($selectedTBLRecord->OutBoundRecord);

$OutBoundRecordtbl 	= json_decode($selectedTBLRecord->OutBoundRecord);
$InnerBounRecordtbl 	= json_decode($selectedTBLRecord->InnerBounRecord);
//echo "<pre>";print_r($OutBoundRecordtbl); die;
//echo "<pre>";print_r($InnerBounRecordtbl);
//echo base64_decode($selectedTBLRecord['TokenId']); 
$AirportCode_Ob  =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Origin->Airport->AirportCode;
$CityName_Ob 	 =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Origin->Airport->CityName;
$DepTime_Ob 	 =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Origin->DepTime;
$ArrTime_Ob  	 =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Destination->ArrTime;

$AirlineCode_Ob  =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Airline->AirlineCode;
$AirlineName_Ob  =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Airline->AirlineName;
$FlightNumber_Ob =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Airline->FlightNumber;
$FareClass_Ob 	 =  $OutBoundRecordtbl->Response->Results->Segments[0][0]->Airline->FareClass;


$AirportCode_Ib  =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Origin->Airport->AirportCode;
$CityNameOB_Ib 	 =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Origin->Airport->CityName;
$DepTimeOB_Ib 	 =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Origin->DepTime;
$ArrTimeOB_Ib 	 =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Destination->ArrTime;

$AirlineCode_Ib  =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Airline->AirlineCode;
$AirlineName_Ib  =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Airline->AirlineName;
$FlightNumber_Ib =  $InnerBounRecordtbl->Response->Results->Segments[0][0]->Airline->FlightNumber;
$FareClass_Ib 	 = $InnerBounRecordtbl->Response->Results->Segments[0][0]->Airline->FareClass;

}

$getAdultPsngr = \App\Model\FlightAdultPassenger::where(['booking_id'=> $bookedValue['bookingId'],'user_id'=> $userId])->get();

$getChildPsngr = \App\Model\FlightChildPassenger::where(['booking_id'=> $bookedValue['bookingId'],'user_id'=> $userId])->get();

$getInfantPsngr = \App\Model\FlightInfantPassenger::where(['booking_id'=> $bookedValue['bookingId'],'user_id'=> $userId])->get();

?>
<div class="row">
        <div class="container">    
    <!-- START: FLIGHT details RIGHT SIDE AREA -->
<div class="col-md-9 ">
      <div class="flight_details_main_right ht_detlcontainer">
                <div class=" pa_none" id="rev_flight_top">
                    <div class="booking_d_c" id="inner_con_rev">
                        <div class="booking_hd_det">
                            <h1>Booking Details</h1>
                          </div>
                    </div>
                </div>
          <div class="clearfix"></div>
      </div>
      
      
<div class="htd_head"><h1>Flight Information</h1></div>
<div class="clearfix"></div>
<?php if(@$bookedValue['wayType']!=2) : ?>
<div class="htd_databox">      
  <div class="htd_databoxin">
    <table class="htd_table mobile_not" width="100%" cellspacing="0" cellpadding="0">
      <tbody><tr>
            <th align="left" width="90">Flight No</th>
            <th align="left">Origin</th>
            <th align="left">Destination</th>
            <th align="left">Dep Date Time</th>
            <th align="left">Arr Date Time</th>                  
            <th align="left">Class</th>
        </tr>
        <tr>
            <td valign="top"><?php echo @$AirlineCode; ?> - <?php echo @$FlightNumber; ?></td>
            <td><span><?php echo @$depart_AirportCode; ?></span> </td>
            <td><span><?php echo @$arival_AirportCode; ?></span> </td>
            <td><span><?php echo @$depart_DepTime; ?></span> </td>
            <td><span><?php echo @$arival_Arrtime; ?></span> </td>                    
            <td valign="top"><span><?php echo @$FareClass; ?></span></td>
        </tr>
        
    </tbody>
    </table>
  </div>      
</div>
<?php else: 

?>
<div class="flight_details_round_trip_right">

                    <div class="flight_details_round_trip">

                        <div class="col-md-1 pad0">
                            <div class="flight_spriz_round">
                                <img src="<?php echo base_url(); ?>assets/images/airline/airline.jpg" alt="cruise">
                                <p><?php echo $AirlineName_Ob; ?>
                                    <br> <?php echo $AirlineCode_Ob; ?>-<?php echo $FlightNumber_Ob; ?></p>
                                <div class="clearfix"></div>
                            </div>

                        </div>

                        <div class="col-md-3 pad0">
                            <div class="round_trip_deit">
                                <h5><?php echo $AirportCode_Ob; ?> <span>(<?php echo $CityName_Ob; ?>)</span></h5>
                                <span><?php echo  @date("jS F, Y", strtotime($DepTime_Ob));;?></span>
                                <div class="round_time">
                                	<?php echo @date("g:i A", strtotime($DepTime_Ob)). ' - ' . @date("g:i A", strtotime($ArrTime_Ob)) ; ?></div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="round_trip_arrow"><img src="<?php echo base_url(); ?>assets/images/arrow_round.png" alt="cruise">
                            </div>
                        </div>


                        <div class="col-md-1 pad0">
                            <div class="flight_spriz_round">
                                <img src="<?php echo base_url(); ?>assets/images/airline/airline.jpg" alt="cruise">
                                <p><?php echo $AirlineName_Ib; ?>
                                    <br> <?php echo $AirlineCode_Ib; ?>-<?php echo $FlightNumber_Ib; ?></p>
                                <div class="clearfix"></div>
                            </div>

                        </div>

                        <div class="col-md-3 pad0">
                            <div class="round_trip_deit">
                                <h5><?php echo $AirportCode_Ib; ?> <span>(<?php echo $CityNameOB_Ib; ?>)</span></h5>

                                <span><?php echo  @date("jS F, Y", strtotime($DepTimeOB_Ib));;?></span>
                                <div class="round_time">
                                	<?php echo @date("g:i A", strtotime($DepTimeOB_Ib)). ' - ' .@date("g:i A", strtotime($ArrTimeOB_Ib))  ?>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-3 round_border_left">
                            <div class="round_trip_trav">
                                <h5>1 Traveller</h5>
                                <p> 1 Adult, 0 Child, 0 Infant</p>
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>

<?php endif; ?>

<div class="clearfix"></div>
            
<div class="booking_dat">
  <h5>Passenger Details</h5> <a href="#">Choose Another Fare</a>
  <div class="clearfix"></div>
</div>
      
      
                          <div class="passernger_box">
                        <div class="passernger_adult1">
                          <?php $i=1; foreach ($getAdultPsngr as $adult) : //echo "<pre>";print_r($adult); ?>
                            <div class="passenger_Details">
                                <h2>Passenger <?php echo $i; ?> - <span>(Adult <?php echo $i; ?>)</span></h2>
                                <div class="htd_frmrow2">
                                    <div class="htd_frmrow">
                                        <label>Name :</label>
                                        <code><?php echo $adult->title. '&nbsp;'.$adult->firstName.'&nbsp;'.$adult->lastName; ?> </code>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>Gender : </label>
                                        <code><?php if($adult->gender==1){ echo "Male";} else { echo "Female";}?></code>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>D.O.B : </label><code><?php echo $adult->adultDOB; ?></code>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>Address : </label>
                                        <code><?php echo $adult->address1.'&nbsp;'.$adult->address2 .'&nbsp;'.$adult->city . '&nbsp;'. $adult->country; ?> </code>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                          <?php $i++; endforeach; ?>    
                          
                          <?php $j=1; foreach ($getChildPsngr as $child) : ?>
                            <div class="passenger_Details">
                                <h2>Passenger <?php echo $j; ?> - <span>(Child <?php echo $j; ?>)</span></h2>
                                <div class="htd_frmrow2">
                                    <div class="htd_frmrow">
                                        <label>Name :</label>
                                        <code><?php echo $child->title. '&nbsp;'.$child->firstName.'&nbsp;'.$child->lastName; ?> </code>

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>Gender : </label>
                                        <code><?php if($child->gender==1){ echo "Male";} else { echo "Female";}?> </code>

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>D.O.B : </label>
                                        <code><?php echo $child->childdob; ?></code>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                          <?php endforeach; ?>    

               <?php $k=1; foreach ($getInfantPsngr as $Infant) : ?>
                            <div class="passenger_Details">
                                <h2>Passenger <?php echo $k; ?> - <span>(Infant <?php $k; ?>)</span></h2>
                                <div class="htd_frmrow2">
                                    <div class="htd_frmrow">
                                        <label>Name :</label>
                                        <code><?php echo $Infant->title. '&nbsp;'.$Infant->firstName.'&nbsp;'.$Infant->lastName; ?></code>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>Gender : </label>
                                        <code><?php if($Infant->gender==1){ echo "Male";} else { echo "Female";}?></code>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="htd_frmrow">
                                        <label>D.O.B : </label>
                                        <code><?php echo $Infant->infantbob; ?></code>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                             <?php endforeach; ?>
                        </div>
                    </div>
      
      <div class="htd_head pos_rel">
                            <h1>Term &amp; Conditions:</h1>
                        </div>
      
                            <div class="clearfix"></div>
                               <div class="htd_databox_term">
                                 <input id="termsConditions" name="termsConditions" class="fleft mr5 check_flo" style="margin-top:-2px;" type="checkbox"> 
                                 <div class="fleft width_84 ml5">
                                   I have reviewed and agreed on the fares and commission offered for this booking.
                                 </div>
                                          
                <div class="clearfix"></div>
                               </div>
                              
        <div class="btnrow">
          <span id="btnPanel" class="fright">
            <form name="confirmFlight" id="confirmFlight" method="POST" action="confirmFlight.php">
            <input type="hidden" name="bookingId" value="<?php echo base64_encode($bookedValue['bookingId']);?>"> 
            <input type="hidden" name="userId" value="<?php echo base64_encode($userId);?>"> 
            <input type="hidden" name="isLCC" value="<?php echo trim($IsLCC); ?>">
            <input class="btn_main_btn fright mr" type="submit" value="Book Flight" name="confirmFlight">
           <?php if(@$bookedValue['wayType']==2):?> <input type="hidden" name="wayType" value="2"> <?php endif;?>
            </form>
         </span>         
        </div>

                  <div class="clearfix"></div>
</div>
        <!-- END: FLIGHT details AREA -->
    <?php if(@$bookedValue['wayType']!=2) { ?>
    <div class="col-md-3 ">
      <div class="flight_details_main_left">
            <h3>Sale Summary</h3>
            <div class="flight_details_left_top">
                
                <div class="flight_details_left">
                    <ul>
                        <div class="row flight_details_row flight_details_bottom">
                            <li><?php echo $getDepartDate1;?></li>
                            <li class="flight_det_tex_alin1"><?php echo $AirlineCode; ?> - <?php echo $FlightNumber; ?></li>
                            <li class="flight_det_tex_alin2"><?php echo $FareClass; ?> Class</li>
                        </div>
                        <li>Dept:</li>
                        <li class="flight_det_tex_alin1"><?php echo $depart_AirportCode; ?></li>
                        <li class="flight_det_tex_alin2">@<?php echo $departTime; ?></li>

                        <li>Arr:</li>
                        <li class="flight_det_tex_alin1"><?php echo $arival_AirportCode; ?></li>
                        <li class="flight_det_tex_alin2">@<?php echo $arivalTime; ?></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="flight_fare">
                    <h5>Fare / Pax Type </h5>
                    <div class="flight_fare_opn">
                        <ul>
                            <li>
                                <p>Adult</p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $Adult_baseFare; ?></span>
                            </li>
                            <li>
                                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $Adult_tax; ?></span>
                            </li>

                            <li>
                                <p>T. Fee </p><span><i class="fa fa-inr" aria-hidden="true"></i> 0.00</span>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $Adult_tax + $Adult_baseFare; ?></code>
                        </h5>
                     <?php if($child_baseFare!=""):?> 
                      <div class="flight_fare_opn">
                        <ul>
                            <li>
                                <p>Child </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $child_baseFare; ?></span>
                            </li>
                            <li>
                                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $child_tax; ?></span>
                            </li>

                            <li>
                                <p>Child </p><span><i class="fa fa-inr" aria-hidden="true"></i> 0.00</span>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                      <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $child_baseFare; + $child_tax; ?></code></h5>
                    <?php endif; ?>
                      <?php if($infant_baseFare!=""):?>  
                      <div class="flight_fare_opn">
                        <ul>
                            <li>
                                <p>Infant </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $infant_baseFare; ?></span>
                            </li>
                            <li>
                                <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $infant_tax; ?></span>
                            </li>

                            <li>
                                <p>Infant </p><span><i class="fa fa-inr" aria-hidden="true"></i> 0.00</span>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                      <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i><?php echo $infant_tax+$infant_baseFare; ?></code></h5>

                    <?php endif; ?>    
                        
                       <h5 class="flight_far">Sub Total: <code><i class="fa fa-inr" aria-hidden="true"></i>
                      <?php echo $Adult_tax+$Adult_baseFare+$child_baseFare+$child_tax+$infant_tax+$infant_baseFare; ?>
                    </code></h5>   
                </div>

            </div>

        </div>
              
            </div>
<?php } else { ?>

  <div class="col-md-3 ">
                <div class="flight_details_main_left">
                    <h3>Sale Summary</h3>
                    <div class="flight_details_left_top">
                        <!--<div class="flight_details_left">
                            <ul>
                                <div class="row flight_details_row flight_details_bottom">
                                    <li>20 Dec 17,</li>
                                    <li class="flight_det_tex_alin1">SG-153</li>
                                    <li class="flight_det_tex_alin2">B Class</li>
                                </div>
                                <li>Dept:</li>
                                <li class="flight_det_tex_alin1"> DEL</li>
                                <li class="flight_det_tex_alin2">
                                    @5:45 AM</li>

                                <li>Arr:</li>
                                <li class="flight_det_tex_alin1"> BOM</li>
                                <li class="flight_det_tex_alin2">

                                    @7:50 AM</li>

                            </ul>
                            <div class="clearfix"></div>
                        </div> -->


                        <!-- <div class="flight_details_left round_tri_mar">
                            <ul>
                                <div class="row flight_details_row flight_details_bottom">
                                    <li>20 Dec 17,</li>
                                    <li class="flight_det_tex_alin1">SG-153</li>
                                    <li class="flight_det_tex_alin2">B Class</li>
                                </div>
                                <li>Dept:</li>
                                <li class="flight_det_tex_alin1"> DEL</li>
                                <li class="flight_det_tex_alin2">
                                    @5:45 AM</li>

                                <li>Arr:</li>
                                <li class="flight_det_tex_alin1"> BOM</li>
                                <li class="flight_det_tex_alin2">

                                    @7:50 AM</li>

                            </ul>
                            <div class="clearfix"></div>
                        </div> -->
<?php
//echo "<pre>";print_r($OutBoundRecordtbl);
//echo "<pre>";
//echo "<pre>";print_r($InnerBounRecordtbl);
//$OutBoundRecordtbl->

?>

        <div class="flight_fare">
            <h5>Fare / Pax Type </h5>
            <div class="flight_fare_opn">
                <ul>
                    <li>
                        <p>Adult </p><span><i class="fa fa-inr" aria-hidden="true"></i>2300</span>
                    </li>
                    <li>
                        <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i>407</span>
                    </li>
                    <li>
                        <p>T. Fee </p><span><i class="fa fa-inr" aria-hidden="true"></i>0.00</span>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>2707</code>
        </h5>
            <div class="flight_fare_opn">
                <ul>
                    <li>
                        <p>Child </p><span><i class="fa fa-inr" aria-hidden="true"></i>2300</span>
                    </li>
                    <li>
                        <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i>407</span>
                    </li>

                    <li>
                        <p>Child </p><span><i class="fa fa-inr" aria-hidden="true"></i>0.00</span>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>2707</code></h5>
            <div class="flight_fare_opn">
                <ul>
                    <li>
                        <p>Infant </p><span><i class="fa fa-inr" aria-hidden="true"></i>1000</span>
                    </li>
                    <li>
                        <p>Tax and S.Charges </p><span><i class="fa fa-inr" aria-hidden="true"></i>0</span>
                    </li>

                    <li>
                        <p>Infant </p><span><i class="fa fa-inr" aria-hidden="true"></i>0.00</span>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <h5 class="flight_far">Total: <code><i class="fa fa-inr" aria-hidden="true"></i>1000</code></h5>
        </div>

                        <div class="round_sub_total">
                            <h4>Sub Total</h4>
                            <span><i class="fa fa-inr" aria-hidden="true"></i> 50000</span>
                            <div class="clearfix"></div>
                        </div>

                    </div>

                </div>

            </div>        
<?php } ?>

            <!-- END: FLIGHT details LEFT SIDE AREA -->

            
    </div>
    </div>




<?php }
public function confirmFlight($isConfirm){
if(@$isConfirm['wayType']==2){

echo '<pre>';print_r($isConfirm); die('am working here');

} else {

$BookedParameter = \App\Model\FlightBookingParameter::where('booking_id',base64_decode($isConfirm['bookingId']))->first();
$tblRecord = json_decode($BookedParameter->bookedData);
$BookedAdultPass = \App\Model\FlightAdultPassenger::where('booking_id',base64_decode($isConfirm['bookingId']))->first();
$BookedChildPass = \App\Model\FlightChildPassenger::where('booking_id',base64_decode($isConfirm['bookingId']))->get();
$BookedInfantPass = \App\Model\FlightInfantPassenger::where('booking_id',base64_decode($isConfirm['bookingId']))->get();
$NoOfadult  = @$tblRecord->Response->Results->FareBreakdown[0]->PassengerCount;
$NoOfchild  = @$tblRecord->Response->Results->FareBreakdown[1]->PassengerCount;
$NoOfinfant = @$tblRecord->Response->Results->FareBreakdown[2]->PassengerCount;
$BaseFare   = @$tblRecord->Response->Results->Fare->BaseFare;
$TAX        = @$tblRecord->Response->Results->Fare->Tax;
$YQTax                  = @$tblRecord->Response->Results->Fare->YQTax;
$AdditionalTxnFeeOfrd   = @$tblRecord->Response->Results->Fare->AdditionalTxnFeeOfrd;
$AdditionalTxnFeePub    = @$tblRecord->Response->Results->Fare->AdditionalTxnFeePub;
$OtherCharges           = @$tblRecord->Response->Results->Fare->OtherCharges;
$Baggage = @$tblRecord->Response->Results->Segments[0][0]->Baggage;
$Origin = @$tblRecord->Response->Results->Segments[0][0]->Origin->Airport->AirportCode;
$Destination = @$tblRecord->Response->Results->Segments[0][0]->Destination->Airport->AirportCode;
$Currency  = @$tblRecord->Response->Results->Fare->Currency;
$seletedFlightData = json_decode($BookedParameter->bookedData,TRUE);

if((int)$isConfirm['isLCC'] === 1) {
$params = array(
"PreferredCurrency" => null,
"ResultIndex" => $tblRecord->Response->Results->ResultIndex,
"AgentReferenceNo" => "sonam1234567890",
  "Passengers"=> array(array(
      "Title" => $BookedAdultPass->title,
      "FirstName" => $BookedAdultPass->firstName,
      "LastName" => $BookedAdultPass->lastName,
      "PaxType" => 1,
      "DateOfBirth" => "1987-12-06T00:00:00",
      "Gender" => $BookedAdultPass->gender,
      "PassportNo" => "KJHHJKHKJH",
      "PassportExpiry" => "2020-12-06T00:00:00",
      "AddressLine1" => $BookedAdultPass->address1,
      "AddressLine2" => $BookedAdultPass->address2,
        "Fare" => array(
          "BaseFare" => $BaseFare,
          "Tax" => $TAX,
          "YQTax" => $YQTax,
          "AdditionalTxnFeePub" => $AdditionalTxnFeePub,
          "AdditionalTxnFeeOfrd" => $AdditionalTxnFeeOfrd,
          "OtherCharges" => $OtherCharges,
        ),
        "City" => $BookedAdultPass->city,
        "CountryCode" => $Currency,
        "CountryName" => $BookedAdultPass->country,
        "ContactNo" => $BookedAdultPass->mobile,
        "Email" => $BookedAdultPass->email,
        "IsLeadPax" => true,
        "FFAirlineCode" => "6E",
        "FFNumber" => "123",
        "Passengers"=> array(array(
          "WayType" =>  "0",
          "code" => "0",
          "Description" => "0",
          "Weight" => $Baggage,
          "BaseCurrencyPrice" => "5.000",
          "BaseCurrency" => $Currency,
          "Currency" => $Currency,
          "Price" => "5.00",
          "Origin" => $Origin,
          "Destination" => $Destination
        )),
        "MealDynamic" => array(
          "WayType" => "1",
          "Code" => "NLD4",
          "Description" => "0",
          "AirlineDescription" => "CHICKEN KATHI ROLL",
          "Quantity" => "1",
          "BaseCurrency" => $Currency,
          "BaseCurrencyPrice" => "275.00000",
          "Currency" => $Currency,
          "Price" => "275.00",
          "Origin" => $Origin,
          "Destination" => $Destination
        ),
        "SeatDynamic" => array(),
        "GSTCompanyAddress" => "D103, 2nd Floor, Okhla Industrial Area, Phase-1 New Delhi-110020",
        "GSTCompanyContactNumber" => "9810669916",
        "GSTCompanyName" => "Happiness Easy Life Services Pvt. Ltd.",
        "GSTNumber" => "07AADCH9391R2ZM",
        "GSTCompanyEmail" => "jd@happypesa.com"
    )),
"EndUserIp" => "203.122.11.211",
"TokenId" => $BookedParameter->TokenId,
"TraceId" => $BookedParameter->TraceId
);
     
//echo "<pre>";print_r($params); die;
$header = array( "cache-control: no-cache", "content-type: application/json" );
$url ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Ticket/";
$str_data = json_encode($params);
$method = "POST";
//echo "<pre>";print_r($str_data); die;
$chNew = curl_init();
curl_setopt($chNew, CURLOPT_HEADER, false);
curl_setopt($chNew, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chNew, CURLINFO_HEADER_OUT, 1);
curl_setopt($chNew, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($chNew, CURLOPT_HTTPHEADER, $header);
curl_setopt($chNew, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($chNew, CURLOPT_POSTFIELDS, $str_data);
curl_setopt($chNew, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chNew, CURLOPT_URL, $url);
$responseData = curl_exec($chNew);

$searchResult = json_decode($responseData,TRUE);

if(!$searchResult['Response']['Error']['ErrorCode']){
$updateSQL = \App\Model\FlightBookingParameter::where(['booking_id'=> base64_decode($isConfirm['bookingId'])])->first();
$updateSQL->FlightResponseData = $responseData;
$updateSQL->save();

$this->getInvoices($searchResult,$BookedAdultPass->email);
}

} elseif($isConfirm['isLCC'] == "") {

$params2 = array(
"ResultIndex" => $tblRecord->Response->Results->ResultIndex,
  "Passengers"=> array(array(
      "Title" => $BookedAdultPass->title,
      "FirstName" => $BookedAdultPass->firstName,
      "LastName" => $BookedAdultPass->lastName,
      "PaxType" => 1,
      "DateOfBirth" => "1987-12-06T00:00:00",
      "Gender" => $BookedAdultPass->gender,
      "PassportNo" => "KJHHJKHKJH",
      "PassportExpiry" => "2020-12-06T00:00:00",
      "AddressLine1" => $BookedAdultPass->address1,
      "AddressLine2" => $BookedAdultPass->address2,
        "Fare" => array(
          "Currency" => $Currency,
          "BaseFare" => $tblRecord->Response->Results->Fare->BaseFare,
          "Tax" => $tblRecord->Response->Results->Fare->Tax,
          "YQTax" => $tblRecord->Response->Results->Fare->YQTax,
          "AdditionalTxnFeePub" => $tblRecord->Response->Results->Fare->AdditionalTxnFeePub,
          "AdditionalTxnFeeOfrd" => $tblRecord->Response->Results->Fare->AdditionalTxnFeeOfrd,
          "OtherCharges" => $tblRecord->Response->Results->Fare->OtherCharges,
          "Discount" => $tblRecord->Response->Results->Fare->Discount,
          "PublishedFare" => $tblRecord->Response->Results->Fare->PublishedFare,
          "OfferedFare" => $tblRecord->Response->Results->Fare->OfferedFare,
          "TdsOnCommission" => $tblRecord->Response->Results->Fare->TdsOnCommission,
          "TdsOnPLB" => $tblRecord->Response->Results->Fare->TdsOnPLB,
          "TdsOnIncentive" => $tblRecord->Response->Results->Fare->TdsOnIncentive,
          "ServiceFee" => $tblRecord->Response->Results->Fare->ServiceFee
        ),
        "City" => $BookedAdultPass->city,
        "CountryCode" => $Currency,
        "CountryName" => $BookedAdultPass->country,
        "Nationality" => "IN",
        "ContactNo" => $BookedAdultPass->mobile,
        "Email" => $BookedAdultPass->email,
        "IsLeadPax" => true,
        "FFAirlineCode" => null,
        "FFNumber" => "",
        "GSTCompanyAddress" => "D103, 2nd Floor, Okhla Industrial Area, Phase-1 New Delhi-110020",
        "GSTCompanyContactNumber" => "9810669916",
        "GSTCompanyName" => "Happiness Easy Life Services Pvt. Ltd.",
        "GSTNumber" => "07AADCH9391R2ZM",
        "GSTCompanyEmail" => "jd@happypesa.com"
    )),
    "EndUserIp" => "203.122.11.211",
    "TokenId" => $BookedParameter->TokenId,
    "TraceId" => $BookedParameter->TraceId
);

//echo "<pre>";print_r($params);
$header2 = array( "cache-control: no-cache", "content-type: application/json" );
$urlbook2 ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Book/";
$str_data2 = json_encode($params2);
$method2 = "POST";
//echo "<pre>";print_r($str_data); die;
$chNew = curl_init();
curl_setopt($chNew, CURLOPT_HEADER, false);
curl_setopt($chNew, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chNew, CURLINFO_HEADER_OUT, 1);
curl_setopt($chNew, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($chNew, CURLOPT_HTTPHEADER, $header2);
curl_setopt($chNew, CURLOPT_CUSTOMREQUEST, $method2);
curl_setopt($chNew, CURLOPT_POSTFIELDS, $str_data2);
curl_setopt($chNew, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chNew, CURLOPT_URL, $urlbook2);
$responseData = curl_exec($chNew);
//echo "<pre>";print_r($responseData); die;
$IsLccSearchResult = json_decode($responseData,TRUE);

if($IsLccSearchResult['Response']['Error']['ErrorCode']==0)
{

  $non_lcc_params = array(
    "EndUserIp" => "203.122.11.211",
    "TokenId" => $BookedParameter->TokenId,
    "TraceId" => $IsLccSearchResult['Response']['TraceId'],
    "PNR" => $IsLccSearchResult['Response']['Response']['PNR'],
    "BookingId" => $IsLccSearchResult['Response']['Response']['BookingId'],
  );
  //echo "<pre>";print_r($non_lcc_params); 
  $header1 = array( "cache-control: no-cache", "content-type: application/json" );
  $urlbooklcc ="http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Ticket/";
  $str_data_islcc = json_encode($non_lcc_params);
  $methodlcc = "POST";
  //echo $str_data_islcc; die;

  $chlcc = curl_init();
  curl_setopt($chlcc, CURLOPT_HEADER, false);
  curl_setopt($chlcc, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($chlcc, CURLINFO_HEADER_OUT, 1);
  curl_setopt($chlcc, CURLOPT_BINARYTRANSFER, 1);
  curl_setopt($chlcc, CURLOPT_HTTPHEADER, $header1);
  curl_setopt($chlcc, CURLOPT_CUSTOMREQUEST, $methodlcc);
  curl_setopt($chlcc, CURLOPT_POSTFIELDS, $str_data_islcc);
  curl_setopt($chlcc, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($chlcc, CURLOPT_URL, $urlbooklcc);

  $responseDatanonLcc = curl_exec($chlcc);
  $IsnonLccSearchResult = json_decode($responseDatanonLcc,TRUE);

if(!$IsnonLccSearchResult['Response']['Error']['ErrorCode']){
$updateSQL = \App\Model\FlightBookingParameter::where(['booking_id'=> base64_decode($isConfirm['bookingId'])])->first();
$updateSQL->FlightResponseData = $responseDatanonLcc;
$updateSQL->save();
$this->getInvoices($IsnonLccSearchResult,$BookedAdultPass->email);
}


} else { ?>
 <div id="container">
  <div id="fof">
    <div class="hgroup">
      <h1 style="color:#979797;text-transform: uppercase;">Something Just Went Wrong1 !</h1>
      <h2>404 Error</h2>
    </div>
    <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
    <p>Go <a style="color:#ff3366" href="javascript:history.go(-1)">Back</a> or <a style="color:#ff3366" href="<?php echo base_url();?>">Home</a></p>
  </div>
</div>
 <?php }

} else { ?>
    <div id="container">
  <div id="fof">
    <div class="hgroup">
      <h1 style="color:#979797;text-transform: uppercase;">Something Just Went Wrong2 !</h1>
      <h2>404 Error</h2>
    </div>
    <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
    <p>Go <a style="color:#ff3366" href="javascript:history.go(-1)">Back</a> or <a style="color:#ff3366" href="<?php echo base_url();?>">Home</a></p>
  </div>
</div>
 <?php }

}  /* closed else condition */


}



protected function getInvoices($searchResult,$email) {

$PNR        = @$searchResult['Response']['Response']['PNR'];
$BookingId = @$searchResult['Response']['Response']['BookingId'];

$ResponseStatus = @$searchResult['Response']['Response']['ResponseStatus'];

$InvoiceNo = @$searchResult['Response']['Response']['FlightItinerary']['InvoiceNo'];
//$Passenger = $searchResult['Response']['Response']['FlightItinerary']['Passenger'];

$TicketId = @$searchResult['Response']['Response']['FlightItinerary']['Passenger'][0]['Ticket']['TicketId'];
$TicketNumber = @$searchResult['Response']['Response']['FlightItinerary']['Passenger'][0]['Ticket']['TicketNumber'];
$IssueDate = @$searchResult['Response']['Response']['FlightItinerary']['Passenger'][0]['Ticket']['IssueDate'];
$PublishedFare = @$searchResult['Response']['Response']['FlightItinerary']['Passenger'][0]['Fare']['PublishedFare'];



$AirlineCode = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Airline']['AirlineCode'];
$CityName = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Airline']['CityName'];
$AirlineName = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Airline']['AirlineName'];
$FlightNumber = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Airline']['FlightNumber'];
$FareClass = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Airline']['FareClass'];


$depart_Terminal = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Origin']['Airport']['Terminal'];
$depart_AirportCode = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Origin']['Airport']['AirportCode'];

$depart_AirportName = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Origin']['Airport']['AirportName'];
$depart_DepTime = @$searchResult['Response']['Response']['FlightItinerary']['Segments']['0']['Origin']['DepTime'];
$depart_CityName = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['Segments']['0']['Origin']['Airport']['CityName'];



$Title = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['0']['Title'];
$FirstName = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['0']['FirstName'];
$LastName = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['0']['LastName'];


$arival_Terminal = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['Segments']['0']['Destination']['Airport']['Terminal'];
$arival_AirportCode = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['Segments']['0']['Destination']['Airport']['AirportCode'];
$arival_AirportName = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['Segments']['0']['Destination']['Airport']['AirportName'];
$arival_CityName = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['Segments']['0']['Destination']['Airport']['CityName'];
$arival_arivalTime = @$searchResult['Response']['Response']['FlightItinerary']['Passenger']['Segments']['0']['Destination']['ArrTime'];


$BaseFare = @$searchResult['Response']['Response']['FlightItinerary']['Fare']['BaseFare'];
$Tax = @$searchResult['Response']['Response']['FlightItinerary']['Fare']['Tax'];

//echo "<pre>";print_r($searchResult);

echo '<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td align="center" valign="top" style="background-color:#ffffff" width="100%">

                <center>
                    <table cellspacing="0" cellpadding="0" width="60%" class="w320">
                        <tr>
                            <td align="center" valign="top" style="border: solid 1px #f0eded;">

                                <table class="force-full-width" cellspacing="0" cellpadding="0">
                                    <tr style="background-color: #3b3b3b; border-bottom: solid 2px #fff;">
                                        <td style="color:#fff;padding-bottom: 10px;" class="logo">

                                            <span style=" color:#fff;font-size: 36px;">Invoice </span>
                                            Invoice No <strong style="font-size: 20px;color: #ff6501;"> - '.$InvoiceNo.' | PNR '.$PNR.' booking Id '.$BookingId.'</strong><br> Service Tax No -
                                             07AADCH9391R2ZM
                                            <br>


                                        </td>
                                        <td style="float: right;
padding-right: 30px;padding-top: 23px;">
                                            <img src="http://10.107.4.8/cashcoin/assets/images/flight-img/logo.png" alt=" logo" title="logo" />
                                        </td>
                                    </tr>

                                </table>

                                <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#232925">
                                    <tr>
                                        <td style="padding: 0 30px; background-color: #fff;">
                                            <table class="table table-striped" style="width: 100%;border: solid 1px #ccc;margin: 12px 0;">
                                                <thead>
                                                    <tr style="background-color: #f3f3f3;">
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;">Booked by</th>
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;">Booking Id </th>
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;">Booking Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="padding-left: 8px;padding-bottom: 7px;border-right: solid 1px #ccc;">('.$email.')</td>
                                                        <td style="padding-left: 8px;padding-bottom: 7px;border-right: solid 1px #ccc;">
                                                            '.$TicketId.' '.$TicketNumber.'</td>
                                                        <td style="padding-left: 8px;padding-bottom: 7px;border-right: solid 1px #ccc;">'.$IssueDate.'</td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>



                                <table class="force-full-width" cellspacing="0" cellpadding="30">
                                    <tr>
                                        <td style="padding-top: 6px; padding-bottom: 10px;">
                                            <table cellspacing="0" cellpadding="0" class="force-full-width" style="border: solid 1px #ccc;">
<tr>
  <h2 style="margin: 0;color: #ff6501;font-size: 18px;padding-bottom: 7px;"> Flight Details</h2>
<td>
<img src="http://10.107.4.8/cashcoin/assets/images/flight-img/flight1.jpg" alt="flight" title="flight" />
</td>
<td style="text-align:left; vertical-align:top;padding: 13px  5px; font-size:18px;">
<span  style=" font-size:18px;">'.$AirlineName.'</span>
<br> '.$AirlineCode.'- '.$FlightNumber.'
<br>
</td>

<td style="text-align:left; vertical-align:top;padding: 13px  5px; font-size:18px;">
<span style=" font-size:18px;">'.$depart_AirportName.'</span>
<br> '.$depart_CityName.'
<br> '.$depart_DepTime.'
</td>

<td style="text-align:left; vertical-align:top;padding: 13px  5px; font-size:18px;">
<img src="http://10.107.4.8/cashcoin/assets/images/flight-img/arrow_right.png" alt="arrow" title="arrow">
</td>

<td style="text-align:left; vertical-align:top;padding: 13px  5px; font-size:18px;">
    <span style=" font-size:18px;">'.$arival_AirportCode.'</span>
    <br> '.$arival_CityName.'
    <br> '.$arival_arivalTime.'
</td>
</tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>

                                <table class="force-full-width" cellspacing="0" cellpadding="30">
                                    <tr>
                                        <td style="padding-top: 6px; padding-bottom: 15px;">
                                            <table cellspacing="0" cellpadding="0" class="force-full-width" style="border: solid 1px #ccc;">
                                                <tr>
                                                    <h2 style="margin: 0;color: #ff6501;font-size: 18px;padding-bottom: 7px;">Passengers :</h2>
                                                    <td style="text-align:left;width: 50%; vertical-align:top;padding: 13px  5px;border-right: solid 1px #ccc; font-size:18px;">
                                                        <span style=" font-size:18px; padding-left: 9px;">'.$Title.' '.$FirstName.' '.$LastName.'</span>

                                                    </td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>

                                <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#232925">
                                    <tr>

                                        <td style="padding: 0 30px; background-color: #fff;">
                                            <h2 style="margin: 0;color: #ff6501;font-size: 18px;padding-bottom: 0px;">Fare Details :</h2>
                                            <table class="table table-striped" style="width: 100%;border: solid 1px #ccc;margin: 12px 0;">
                                                <thead>
                                                    <tr style="background-color: #f3f3f3;">
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;">Fare / Charges
                                                        </th>
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;text-align: center;"></th>
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;text-align: center;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="border-bottom: solid 1px #ccc;">
                                                        <td style="border-right: solid 1px #ccc;padding: 10px;">Base Fare </td>
                                                        <td style="border-right: solid 1px #ccc;padding: 10px;text-align: right;">
                                                           '.$BaseFare.'</td>
                                                        <td style="border-right: solid 1px #ccc;padding: 10px;text-align: right;">
                                                        '.$BaseFare.'</td>
                                                    </tr>

                                                    <tr style="border-bottom: solid 1px #ccc;">
                                                        <td style="border-right: solid 1px #ccc;padding: 10px;background-color: #f2f2f2;"><strong> Tax</strong> </td>
                                                        <td style="border-right: solid 1px #ccc;padding: 10px;text-align: right;">
                                                            '.$Tax.'</td>
                                                        <td style="border-right: solid 1px #ccc;padding: 10px;text-align: right;">'.$Tax.'</td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#232925">
                                    <tr>
                                        <td style="padding: 0 30px; background-color: #fff;">
                                            <table class="table table-striped" style="width: 100%;border: solid 1px #ccc;margin: 12px 0;">
                                                <thead>
                                                   
                                                  
                                                    <tr style="background-color: #f3f3f3;">
                                                        <th style="padding: 9px;border-right: solid 1px #ccc;"><strong>Grand Total</strong> </th>

                                                        <th style="padding: 9px;border-right: solid 1px #ccc; text-align: right;"><img style="width: 11px;" src="http://10.107.4.8/cashcoin/assets/images/flight-img/rupes.png" alt="" title="" />'.$PublishedFare.'</th>
                                                    </tr>

                                                </thead>

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                
                <table class="force-full-width" cellspacing="0" cellpadding="20" bgcolor="#2b934f">
                                    <tr>
                                        <td style="background-color:#03509c; color:#fff; font-size: 18px; text-align: center;">
                                            © 2017 All Rights Reserved
                                        </td>
                                    </tr>
                                </table>


                            </td>
                        </tr>
                    </table>

                </center>
            </td>
        </tr>
    </table>';

}



} ?>
