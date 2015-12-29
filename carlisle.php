<?php 
	include 'header.php';
	function cookies(){
		return (isset($_COOKIE['time_zone_offset'],$_COOKIE['time_zone_dst']));
	}
	function db_connect(){
		$db_host = 'localhost';
		$db_user= 'root';
		$db_pass= 'Beer1234';
		$db_name= 'CheapBeer';
		global $connection;
		$connection = mysql_connect($db_host,$db_user,$db_pass) 
			or die ("cannot connect to $db_host as $db_user".mysql_error());
		mysql_select_db($db_name) 
			or die ("Cannot open $db_name:".mysql_error());
		return $connection;
	}
	function db_close(){
		global $connection;
		mysql_close($connection);
	}
	//LOCATION VARS
	$town="Carlisle";
	$state="PA";
	$townTimezone="America/New_York";
	$time_zone_name = ( 
	//IF THERE ARE COOKIES, SET CORRECT TIMEZONE, OTHERWISE TIMEZONE IS "America/New_York"
	cookies()?
	timezone_name_from_abbr('', -$_COOKIE['time_zone_offset']*60, $_COOKIE['time_zone_dst']) : $townTimezone);
	$dateer = date_create("now", timezone_open($time_zone_name));
	$hour24 = date_format($dateer, 'H'); //24 hour clock
	$today = date_format($dateer, 'l'); //
	$minute = date_format($dateer, 'i');
	//IF THE HOUR IS 12am IT IS NOT 0am
	$hour12=(!($hour24%12))?12:$hour24%12; //12 hour clock conversion
	//AM OR PM
	$dayTime = (
	$hour24>=12 ?
	"pm" : "am" );
	//storeNames WILL BE GENERATED FROM AN SQL QUERY
	//$storeNames=array("Stans Beverages","Beverage Express","Beer and Cigar","Beer Through");
	//$numStores= 4; //FOREACH NAME
	//
	$StansBevHOpen=array('Monday' => '8','Tuesday' => '8','Wednesday' => '8','Thursday' => '8','Friday' => '8','Saturday' => '8','Sunday' => '10');
	$StansBevMOpen=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');
	$StansBevHClose=array('Monday' => '21','Tuesday' => '21','Wednesday' => '21','Thursday' => '21','Friday' => '22','Saturday' => '22','Sunday' => '19');
	$StansBevMClose=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');
	//
	$bevExpHOpen=array('Monday' => '8','Tuesday' => '8','Wednesday' => '8','Thursday' => '8','Friday' => '8','Saturday' => '8','Sunday' => '9');
	$bevExpMOpen=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');
	$bevExpHClose=array('Monday' => '21','Tuesday' => '21','Wednesday' => '21','Thursday' => '21','Friday' => '22','Saturday' => '22','Sunday' => '21');
	$bevExpMClose=array('Monday' => '30','Tuesday' => '30','Wednesday' => '30','Thursday' => '30','Friday' => '30','Saturday' => '30','Sunday' => '0');
	//	
	$beerAndCigarHOpen=array('Monday' => '9','Tuesday' => '9','Wednesday' => '9','Thursday' => '9','Friday' => '9','Saturday' => '9','Sunday' => '11');
	$beerAndCigarMOpen=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');
	$beerAndCigarHClose=array('Monday' => '21','Tuesday' => '21','Wednesday' => '21','Thursday' => '21','Friday' => '22','Saturday' => '22','Sunday' => '18');
	$beerAndCigarMClose=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');
	//	
	$beerThroughHOpen=array('Monday' => '8','Tuesday' => '8','Wednesday' => '8','Thursday' => '8','Friday' => '8','Saturday' => '8','Sunday' => '12');
	$beerThroughMOpen=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');
	$beerThroughHClose=array('Monday' => '21','Tuesday' => '21','Wednesday' => '21','Thursday' => '21','Friday' => '22','Saturday' => '22','Sunday' => '17');
	$beerThroughMClose=array('Monday' => '0','Tuesday' => '0','Wednesday' => '0','Thursday' => '0','Friday' => '0','Saturday' => '0','Sunday' => '0');	
	//
	//Every single store's time. based on numstores?
	$HOpen=array($StansBevHOpen,$bevExpHOpen,$beerAndCigarHOpen,$beerThroughHOpen);
	$HClose=array($StansBevHClose,$bevExpHClose,$beerAndCigarHClose,$beerThroughHClose);
	$MOpen=array($StansBevMOpen,$bevExpMOpen,$beerAndCigarMOpen,$beerThroughMOpen);
	$MClose=array($StansBevMClose,$bevExpMClose,$beerAndCigarMClose,$beerThroughMClose);
	//
	//days IS COMPARED WITH THE OPEN/CLOSE ARRAYS KEY. IT IS ALSO USED TO GENERATE TABLE HEADERS
	$days=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	//
	function isOpen($storeID){
		$HOpen=$GLOBALS['HOpen'];
		$HClose=$GLOBALS['HClose'];
		$MOpen=$GLOBALS['MOpen'];
		$MClose=$GLOBALS['MClose'];
		$today=$GLOBALS['today'];
		$hour = $GLOBALS['hour24']; //private $hour is based off 24 hour clock of client
		$minute = $GLOBALS['minute'];
		$open='\u2713';
		$closed='\u2718';
		if($HOpen[$storeID][$today]<= $hour && $hour <= $HClose[$storeID][$today] ){
			if($HOpen[$storeID][$today]== $hour || $hour == $HClose[$storeID][$today] ){
				if($MOpen[$storeID][$today]<= $minute || $minute < $MClose[$storeID][$today] ){
					echo"<p style='color:lightgreen;font-size:1.5em;font-weight: bold'>".json_decode('"'.$open.'"')."</p>";
				}else{
					echo"<p style='color:red;font-size:1.5em;'>".json_decode('"'.$closed.'"')."</p>";
				}
			}else{
				echo"<p style='color:lightgreen;font-size:1.5em;font-weight: bold'>".json_decode('"'.$open.'"')."</p>";
			}
		}else{
			echo"<p style='color:red;font-size:1.5em;'>".json_decode('"'.$closed.'"')."</p>";
		}	
	} /*END OF isOpen() */
	////INDENTATION REDUCED 
echo"	<main id='center' class='column'>
<article>
<h1 style='text-align:center;'>Cheap beer prices of ".$town.", ".$state.".</h1><hr>
<h3><center>Store Hours</center></h3>";
db_connect();
	$query = "SELECT * FROM Vendors WHERE Town = 'Carisle'";
	$result = mysql_query($query);
	$numStores = mysql_numrows($result);
	
/* DETECTS IF COOKIES WERE SETS
   DEFAULT TIMEZONE IS TIMEZONE OF STORE */
if(cookies())
	echo"<u ><span style='color:black;font-size:1em;'><b>Location: </b></span><span style='color:green;font-size:0.75em;line-height:1em;'>".$time_zone_name." </span>";
else	
	echo"<p style='color:orange;font-size:1em;text-align:center;'>(Cookies are not enabled in this browser)</p>
	     <span style='color:black;font-size:1em;'><u>Default Timezone: </span><span style='color:green;font-size:0.75em;line-height:1em;'>".$time_zone_name." </span>";
echo"<span style='color:black;font-size:1em;'><b>Time: </b></span><span style='color:green;font-size:0.75em;line-height:1em;'>".$today." ".$hour12.":".$minute." ".$dayTime."</span></u>
    <li id='hours'>
    <div style='width:100%'>
    <table width='100%' border='1'><tbody>
    <tr><th scope='col'>Store</th><th scope='col'>Open?</th>";
    for($i = 0; $i < 7; ++$i) {
	    echo"<th scope='col'>".$days[$i]."</th>";
    }
    echo"</tr>";
	for ($stores = 0; $stores < $numStores; ++$stores) {
		$storeName = mysql_result($result,$stores,"Name");
		echo"<tr><th scope='col'>".$storeName." ".$storeName."</th><td>";
		isOpen($stores);    
		echo"</td>";
		for($i = 0; $i < 7; ++$i) {
			echo"<td>";
			//STORE OPENING CLOCKS
			$HOpen12=(!($HOpen[$stores][$days[$i]]%12))?12:$HOpen[$stores][$days[$i]]%12;
			$HOpen24=$HOpen[$stores][$days[$i]];
			$MOpen60=$MOpen[$stores][$days[$i]];
			//STORE CLOSING CLOCKS
			$MClose60=$MClose[$stores][$days[$i]];
			$HClose12=(!($HClose[$stores][$days[$i]]%12))?12:$HClose[$stores][$days[$i]]%12;
			$HClose24=$HClose[$stores][$days[$i]];
			//OPENING OR CLOSING IN AM OR PM
			$OdayTime = ($HOpen24>=12 ? "pm" : "am" );
			$CdayTime = ($HClose24>=12 ? "pm" : "am" );
			//OPENING TIME
			if($MOpen60 == 0){
				if($HOpen24 == 0){
					echo "mid<br>";
				}elseif ($HOpen24 == 12){
					echo "noon<br>";
				}else{
					echo $HOpen12." ".$OdayTime."<br>";
				}
			}else
				echo $HOpen12.":".$MOpen60." ".$OdayTime."<br>";
			//CLOSING TIME
			if($MClose60 == 0){
				if($HClose24 == 0){
					echo "mid";
				}elseif ($HClose24 == 12){
					echo "noon";
				}
				else{
					echo $HClose12." ".$CdayTime;
				}
			}else
				echo $HClose12.":".$MClose60." ".$CdayTime;
			echo"</td>";
		}
	}
	echo"</tr>
	</tbody>
	</table>
	</div>
	</li>
				
				<h3>How much does a 30 cost in Carlisle, PA?</h3>
				<table>
					<tr>
						<td>Beer</td>
						<td>Beverage Express</td>
						<td>Stan's Beverages</td>
						<td>Blosser's Brew-Thru</td>
						<td>Beer &amp Cigar</td>
					</tr>
					<tr>
						<td>Natty</td>
						<td>14.99</td>
						<td>N/A</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>Bud Light</td>
						<td>23.99</td>
						<td>20.75</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>Budweiser</td>
						<td>23.99</td>
						<td>20.75</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>Miller Light</td>
						<td>23.99</td>
						<td>20.75</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>Coor's Light</td>
						<td>23.99</td>
						<td>20.75</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>Busch Light</td>
						<td>18.49</td>
						<td>17.46</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>PBR</td>
						<td>19.34</td>
						<td>N/A</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
					<tr>
						<td>Lion's Head (24)</td>
						<td>11.79</td>
						<td>N/A</td>
						<td>N/A</td>
						<td>N/A</td>
					</tr>
				</table>
				<p>* prices last updated 12/22/2015</p>
				<h3> Let us know what you paid! </h3>
				<p> Beer buddy is a community that relies on people like you to share how much you paid for a case of brew.
				Let us know how much you paid for a case at specific vendor, and we will publish it on our website.<p>
				<p> Email the location, price, and type of beer to:</p>
				<p> beerpriceindex@gmail.com </p>		

	</article>								
	</main>";
	include 'footer.php';
?>