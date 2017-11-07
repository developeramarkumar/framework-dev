<?php
namespace Core;
use Illuminate\Events\Dispatcher;
class Event
{
	private $_dispatcher = null;
	protected $listen = [
        \App\Events\SmsEvent::class => 
        \App\Listeners\SmsListener::class,
        \App\Events\UserBonusEvent::class => 
        \App\Listeners\UserBonusListner::class,
    ];
	function __construct($event)
	{
		$this->_dispatcher = new Dispatcher();
		foreach ($this->listen as $key => $value) {
			$this->_dispatcher->listen([$key], $value);
		}
		$this->_dispatcher->fire($event);
	}
	public static function fire($event){
		return new Event($event);
	}

}