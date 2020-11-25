<?php

	trait Messages{

		public static  function msg($msg = '', $type = ''){

			if($type === 'success'){
		
				$class = 'msg success-msg';
		
			}else if($type === 'error'){
		
				$class = 'msg error-msg';
		
			}else if($type === 'info' || $type === 'no-data'){
		
				$class = 'msg info-msg';
		
			}else{

				$class = 'msg';
			
			}
		
			return  '<div class="'.$class.'" align="center">
	                 	
	                 	<strong>'.$msg.'</strong>
	            	
	            	</div>';
		
		}


    	public static function status_notice($status = "", 
    		                                 $notice =  ""){

      		if($status == "" ){
				
				$message = Messages::warning_msg('Sin status');      		

      		}else{

	      		switch($status){

	      			case"done":

	      				$message = Messages::success_msg($notice);
	      			
	      			break;
	      			case"no-data":

	      				$message = Messages::info_msg($notice);

	      			break;
	      			break;
	      			case"error":

	      				$message = Messages::danger_msg($notice);

	      			break;
	      			default:
						
	      				$message = Messages::danger_msg("Invalid status");

	      			break;
	      		
	      		}

	      	}	

      		return $message; 

    	}

    	public static function server_message($message){

			return "<div style='font-size:200%;'>
						<strong>$message</strong>
					</div>";

    	}

    	public static function success_msg($message){

			return "<div class='msg success-msg'>	
						$message						
					</div>";
					
    	}

    	public static function warning_msg($message){

			return "<div class='msg warning-msg'>	
						$message						
					</div>";
					
    	}

    	public static function danger_msg($message){

			return "<div class='msg error-msg'>	
						$message						
					</div>";
					
    	}

    	public static function info_msg($message){

			return "<div class='msg info-msg'>	
						$message						
					</div>";
					
    	}


	}


?>