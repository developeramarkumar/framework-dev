<?php
namespace App\Listeners;
use App\Events\SmsEvent;
class SmsListener
{
    /**
     * @param TestEvent $event
     */
    public function handle(SmsEvent $event)
    {
        
		$url='https://control.msg91.com/api/sendhttp.php?authkey=151937AGgEw9R4nO5912d4ed&mobiles='.$event->mobile.'&message='.urlencode($event->message).'&sender=ETDOGE&route=4&country=91&response=json';
		$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HEADER, false);
		$output=curl_exec($ch);
		curl_close($ch);
    }
}