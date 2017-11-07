<?php
namespace App\Events;

class UserBonusEvent
{
    /**
     * @var string
     */
    public $type;
    public $user;
    /**
     * TestEvent constructor.
     *
     * @param string $message
     */
    public function __construct($type,$user)
    {
        $this->type = $type;
        $this->user = $user;
    }
   
    
}