<?php 
	include 'header.php';
	//RETURNS TRUE WHEN COOKIES ARE SET
	function cookies(){
		return (isset($_COOKIE['time_zone_offset'],$_COOKIE['time_zone_dst']));
	}
	function db_connect1(){
		$db_host = 'localhost';
		$db_user= 'root';
		$db_pass= 'Beer1234';
		//$db_pass='';
		//$db_name= 'CheapBeer';
		$db_name= 'cheapbeerprices';
		global $connection;
		$connection = mysql_connect($db_host,$db_user,$db_pass) 
			or die ("cannot connect to $db_host as $db_user".mysql_error());
		mysql_select_db($db_name) 
			or die ("Cannot open $db_name:".mysql_error());
		return $connection;
	}
	function db_close1(){
		global $connection;
		mysql_close($connection);
	}
	db_connect1(); //CONNECT TO DATABASE RIGHT AWAY
	//LOCATION VARS
	$town=$_POST['location'];
	$state=$_POST['state'];
	$townTimezone="America/New_York";
	$time_zone_name = ( 
	//IF THERE ARE COOKIES, SET CORRECT TIMEZONE, OTHERWISE TIMEZONE IS "America/New_York"
	cookies()?
	timezone_name_from_abbr('', -$_COOKIE['time_zone_offset']*60, $_COOKIE['time_zone_dst']) : $townTimezone);
	$dateer = date_create("now", timezone_open($time_zone_name));
	$hour24 = date_format($dateer, 'H'); //24 hour clock
	$today = date_format($dateer, 'D'); //
	$minute = date_format($dateer, 'i');
	//IF THE HOUR IS 12am IT IS NOT 0am
	$hour12=(!($hour24%12))?12:$hour24%12; //12 hour clock conversion
	//AM OR PM
	$dayTime = date_format($dateer, 'a');//(
	//used for simply indexing days of week
	$days=array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	//
	function isOpen($storeQueryRow){
		$today=$GLOBALS['today'];
		$hour = $GLOBALS['hour24']; //private $hour is based off 24 hour clock of client
		$minute = $GLOBALS['minute'];
		$query = "SELECT * FROM Vendors WHERE Town = '$_POST[location]'";
		$townVendors = mysql_query($query);
		//OPENING TIME
		$H24Open = mysql_result($townVendors,$storeQueryRow,"H24Open".$today);
		$MOpen=mysql_result($townVendors,$storeQueryRow,"MOpen".$today);
		//CLOSING TIME
		$H24Close = mysql_result($townVendors,$storeQueryRow,"H24Close".$today);
		$MClose=mysql_result($townVendors,$storeQueryRow,"MClose".$today);
		//Checkmark and X for open and close
		$open=json_decode('"\u2713"');
		$closed=json_decode('"\u2718"');
		
		if($H24Open<= $hour && $hour <= $H24Close ){
			if($H24Open== $hour || $hour == $H24Close ){
				if($MOpen<= $minute || $minute < $MClose )
					echo"<p style='color:lightgreen;font-size:1.5em;font-weight: bold'>".$open."</p>";
				else
					echo"<p style='color:red;font-size:1.5em;'>".$closed."</p>";
			}else{
				echo"<p style='color:lightgreen;font-size:1.5em;font-weight: bold'>".$open."</p>";
			}
		}else{
			echo"<p style='color:red;font-size:1.5em;'>".$closed."</p>";
		}	
	} /*END OF isOpen() */
	////INDENTATION REDUCED 
echo"	<main id='center' class='column'>
<article>
<h1 style='text-align:center;'>Cheap beer prices of ".$town.", ".$state.".</h1><hr>
<h3><center>Store Hours</center></h3>";

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
    //gets the vendor information for the rows of rhte table
    $query = "SELECT * FROM Vendors WHERE Town = '$town' AND State ='$state'";
    $townVendors = mysql_query($query);
    $numStores = mysql_numrows($townVendors);
	
    for($i = 0; $i < 7; ++$i) {
	    echo"<th scope='col'>".$days[$i]."</th>";
    }
    echo"</tr>";
	for ($stores = 0; $stores < $numStores; ++$stores) {
		
		$storeName = mysql_result($townVendors,$stores,"Name");//gets the name of each store from townvendors
		echo"<tr><th scope='col'>".$storeName."</th><td>";
		isOpen($stores);    
		echo"</td>";
		for($i = 0; $i < 7; ++$i) {
			echo"<td>";
			//STORE OPENING CLOCKS
			$H24Open = mysql_result($townVendors,$stores,"H24Open".$days[$i]);
			$H12Open = (!($H24Open%12))?12:$H24Open%12;
			$MOpen=mysql_result($townVendors,$stores,"MOpen".$days[$i]);
			//STORE CLOSING CLOCKS
			$H24Close = mysql_result($townVendors,$stores,"H24Close".$days[$i]);
			$H12Close = (!($H24Close%12))?12:$H24Close%12;
			$MClose=mysql_result($townVendors,$stores,"MClose".$days[$i]);
			//OPENING OR CLOSING IN AM OR PM
			$OdayTime = ($H24Open >= 12 ? "pm" : "am" );
			$CdayTime = ($H24Close >= 12 ? "pm" : "am" );
			//OPENING TIME
			if($MOpen == 0){
				if($H24Open == 0){
					echo "mid<br>";
				}elseif ($H24Open == 12){
					echo "noon<br>";
				}else{
					echo $H12Open." ".$OdayTime."<br>";
				}
			}else
				echo $H12Open.":".$MOpen." ".$OdayTime."<br>";
			//CLOSING TIME
			if($MClose == 0){
				if($H24Close == 0){
					echo "mid";
				}elseif ($H24Close == 12){
					echo "noon";
				}
				else{
					echo $H12Close." ".$CdayTime;
				}
			}else
				echo $H12Close.":".$MClose." ".$CdayTime;
			echo"</td>";
		}
	}
	echo"</tr>
	</tbody>
	</table>
	</div>
	</li>";
				
				/* <h3>How much does a 30 cost in Carlisle, PA?</h3>
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
				<p> beerpriceindex@gmail.com </p> "*/		
 
	echo"</article>								
	</main>";db_close1(); 
	include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>