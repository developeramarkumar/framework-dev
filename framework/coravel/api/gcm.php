<?php 
	
	
	if($_REQUEST['points']!='' && $_REQUEST['points']!=0 &&  $_REQUEST['reg_id']!='')
	{
		
		$activityid=$_REQUEST['activityid'];
		if($activityid=='7')
		{
			$logc= "for Play ";
		}
		if($activityid=='6')
		{
			$logc= "for Video ";
		}
		if($activityid=='5')
		{
			$logc= "for Survey ";
		}
		if($activityid=='4')
		{
			$logc= "for Lead ";
		}
		if($activityid=='3')
		{
			$logc= "for Download ";
		}
		if($activityid=='1')
		{
			$logc= "for Coupon ";
		}
		if($activityid=='2')
		{
			$logc= "for Deal ";
		}
		
		$msgsend="Hurray! You earned ".$_REQUEST['points']." points   ".$logc.". Keep Earning..!";
		//second way
		
		#API access key from Google API's Console
		define( 'API_ACCESS_KEY', 'AIzaSyDtRK3vvSlZgd85-zFgrEvX9pijZsi2C8U' );
		$registrationIds = $_REQUEST['reg_id'];
		#prep the bundle
		$msg = array
		(
		'body' 	=> $msgsend,
		'title'	=> 'Congratulations!'  
		);
		$fields = array
		(
		'to'		=> $registrationIds,
		'notification'	=> $msg
		);
		
		
		$headers = array
		(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
		);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		#Echo Result Of FireBase Server
		echo $result;
	}
	
	
	
	
	if($_REQUEST['msg']!='' &&  $_REQUEST['reg_id']!='')
	{
		
		$msgsend=$_REQUEST['msg'];
		
		#API access key from Google API's Console
		define( 'API_ACCESS_KEY', 'AIzaSyDtRK3vvSlZgd85-zFgrEvX9pijZsi2C8U' );
		$registrationIds = $_REQUEST['reg_id'];
		#prep the bundle
		$msg = array
		(
		'body' 	=> $msgsend,
		'title'	=> 'Congratulations!'  
		);
		$fields = array
		(
		'to'		=> $registrationIds,
		'notification'	=> $msg
		);
		
		
		$headers = array
		(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
		);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		#Echo Result Of FireBase Server
		echo $result;
	}
?>