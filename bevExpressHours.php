<?php
	$day = date(D);
	$hour = date(G);
	$min = date(i);
	$time;

	if($day=="Mon" || $day=="Tue" || $day=="Wed" || $day=="Thu"){
		//open 8-9:30
		if($hour<21 && $hour>=8){
			$time = "open";
		}
		else if($hour==21 && $min<=29){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}

	else if ($day=="Fri" || $day=="Sat"){
		//open 8-10:30
		if($hour<22 && $hour>=8){
			$time = "open";
		}
		else if($hour==22 && $min<=29){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}

	else{
		//open 9-9
		if($hour<21 && $hour>=9){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}
	
	if($time=="open"){
		echo "<font color='green'> $time </font>";
	}
	else if($time=="closed"){
		echo "<font color='red'> $time </font>";
	}
?>
