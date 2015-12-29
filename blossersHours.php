<?php
	$day = date('D');
	$hour = date('G');
	$time;

	if($day=="Mon" || $day=="Tue" || $day=="Wed" || $day=="Thu"){
		//open 8-9
		if($hour<21 && $hour>=8){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}

	else if ($day=="Fri" || $day=="Sat"){
		//open 8-10
		if($hour<22 && $hour>=8){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}

	else{
		//open 12-5
		if($hour<17 && $hour>=12){
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
