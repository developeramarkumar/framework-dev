<?php
    namespace App\Http\Library;
    
	trait Validation
	{
		var $emptyMsg='Please fill all fields.';
		var $emailMsg='Please fill correct email address.';
		var $dateMsg= 'Please fill correct date formate (DD/MM/YYYY).';
		var $numMsg=  'Please fill numeric data.';
		var $noRecMsg= 'No Records.';
		var $encrypted_key='happypesa@123456';
		
		var $validation_error=0;
		var $is_empty=0; 
		var $error_msg=''; 

	    function clean_input($post)
		{
			$post=array_map('trim', $post);
			$post=array_map('stripslashes', $post);
            $post=array_map(function($v){return strip_tags($v);}, $post);
			return $post;
		}
		
		function _clean(&$value) 
		{
			//echo 'callllllllllllllllllllllllll';die;
            $value = htmlspecialchars($value);
        }
        
        function validate($post)
        {

			foreach($post as $key=>$val)
			{
				 switch($key)
				 {
					case 'number_validate':
					$this->number_validate($val);
					break;
					
					case 'number_validate1':
					$this->number_validate($val);
					break;
					
					case 'email_validate':
					$this->email_validate($val);
					break;
					
					case 'date_validate':
					$this->date_validate($val);
					break;
					
					case 'date1_validate':
					$this->date_validate($val);
					break;
					 
				}
				if($this->validation_error) 
				{
					break;
				}
			}
			
	    }
	    
	    function is_empty($post)
        {
			if(count($post)!=count(array_filter($post)))
			{
				$this->is_empty=1;
				$this->msg=$this->emptyMsg;
		    }
	    }
	    
	    function email_validate($email)
        {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				$this->msg=$this->emailMsg;
				++$this->validation_error;
            }
	    }
	    
	    function number_validate($number)
        {
			if (!is_numeric($number)) 
			{
				$this->msg=$this->numMsg;
				++$this->validation_error;
			}
	    }
	    
	    function date_validate($date)
        {
			if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/",$date)) 
			{
				$this->msg=$this->dateMsg;
				++$this->validation_error;
			}
	    }
	    
	}

?>
