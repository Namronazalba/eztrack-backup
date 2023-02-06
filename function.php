<?php
function fordate($date)
    {
		
				date_default_timezone_set('Singapore');
				$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
				$lengths = array("60","60","24","7","4.35","12","10"); 		
							
				$date_now = time();
				$unix_date = strtotime($date);
				
				if($date_now > $unix_date) {
				$difference = $date_now - $unix_date;
				$tense = "ago";
		 
				} else {
				$difference = $unix_date -$date_now;
				$tense = "from now";
				}
				for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
				$difference /= $lengths[$j];
				}
		 
				$difference = round($difference);
		 
				if($difference != 1) {
				$periods[$j].= "s";
				}
					
		 
				return "$difference $periods[$j] {$tense}";			
				
		
	}
	
	

	?>
	
    