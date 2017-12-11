<?php
    namespace App\Http\Library;
    trait Cryption
	{
		
		var $base_encryption_array = array(
			'0'=>'a1a',
			'1'=>'b2a',
			'2'=>'c3a',
			'3'=>'d4a',
			'4'=>'e52',
			'5'=>'f61',
			'6'=>'a70',
			'7'=>'a89',
			'8'=>'a98',
			'9'=>'a07',
			
			'a'=>'11a',
			'b'=>'22a',
			'c'=>'33a',
			'd'=>'44a',
			'e'=>'552',
			'f'=>'661',
			'g'=>'770',
			'h'=>'889',
			'i'=>'998',
			'j'=>'117',
			'k'=>'a2a',
			'l'=>'b3a',
			'm'=>'c4a',
			'n'=>'d5a',
			'o'=>'e62',
			'p'=>'f71',
			'q'=>'a80',
			'r'=>'a99',
			's'=>'a08',
			't'=>'a01',
			'u'=>'a12',
			'v'=>'b23',
			'w'=>'c34',
			'x'=>'d45',
			'y'=>'e56',
			'z'=>'f67',
			
			'A'=>'a19',
			'B'=>'b28',
			'C'=>'c36',
			'D'=>'d45',
			'E'=>'e54',
			'F'=>'f63',
			'G'=>'a72',
			'H'=>'b81',
			'I'=>'v98',
			'J'=>'c07',
			'K'=>'x1a',
			'L'=>'z2a',
			'M'=>'a3a',
			'N'=>'u4a',
			'O'=>'v52',
			'P'=>'o61',
			'Q'=>'p70',
			'R'=>'k89',
			'S'=>'l98',
			'T'=>'g07',
			'U'=>'q1a',
			'V'=>'r2a',
			'W'=>'b3a',
			'X'=>'s4a',
			'Y'=>'v52',
			'Z'=>'d61',
			
			'@'=>'qw2'
		);
		
		function encodeStr($string)
		{
			$string = (string)$string;
			$length = strlen($string);
			$hash = '';
			
			for ($i=0; $i<$length; $i++) 
			{
				if(isset($string[$i]))
				{
					$hash .= $this->base_encryption_array[$string[$i]];
				}
			}
			
			$this->encodeStr=$hash;
			//return $hash;
			//echo 'str='.$this->encodeStr;die;
       }


       function decodeStr($hash)
       {
		  // echo $hash;die;
			$this->base_encryption_array ;
			$this->base_encryption_array = array_flip($this->base_encryption_array);

			$hash = (string)$hash;
			$length = strlen($hash);
			$string = '';
            
			for ($i=0; $i<$length; $i=$i+3) 
			{
				 //echo '<br/>====$string';print_r($hash[$i+2]);
				if(isset($hash[$i]) && isset($hash[$i+1]) && isset($hash[$i+2]) && isset($this->base_encryption_array[$hash[$i].$hash[$i+1].$hash[$i+2]]))
				{
					
					$string .= $this->base_encryption_array[$hash[$i].$hash[$i+1].$hash[$i+2]];
				}
			}
			//echo '$string='.$string;die;
			$this->decodeStr=$string;
       }
	    
	}

?>
