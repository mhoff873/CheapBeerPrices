<?php
	$day = date(D);
	$hour = date(G);
	$time;

	if($day=="Mon" || $day=="Tue" || $day=="Wed" || $day=="Thu"){
		//open 9-9
		if($hour<21 && $hour>=9){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}

	else if ($day=="Fri" || $day=="Sat"){
		//open 9-10
		if($hour<22 && $hour>=9){
			$time = "open";
		}
		else{
			$time = "closed";
		}
	}

	else{
		//open 11-6
		if($hour<18 && $hour>=11){
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
