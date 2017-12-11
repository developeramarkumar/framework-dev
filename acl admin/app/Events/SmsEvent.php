<?php
namespace App\Events;

class SmsEvent
{
    /**
     * @var string
     */
    public $message;
    public $mobile;
    /**
     * TestEvent constructor.
     *
     * @param string $message
     */
    public function __construct($mobile,$message)
    {
        $this->message = $message;
        $this->mobile = $mobile;
    }
    /**
     * @return string
     */
    // public function getMessage()
    // {
    //     return $this->message;
    // }
}