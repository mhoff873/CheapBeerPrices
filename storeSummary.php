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
		$db_name= 'CheapBeer';
		//$db_name= 'cheapbeerprices';
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
	//$town=$_POST['location'];
	//$state=$_POST['state'];
	$town='Kutztown';
	$state='PA';
	$storeName='Kutztown Tavern';
	//$storename=$_POST['storeName'];
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
<h1 style='text-align:center;'><u>".$storeName." -  ".$town.", ".$state.".</u></h1>
<ul style='width:100%;list-style-type: none;margin: 0;padding: 0;'>
	<form id='storeSummary'><li class='tabLeft' style='border-bottom:none;background-color:silver;border-right:none;'><h3>Store Summary</h3></li></form>
	<form id='storeProducts' ><a href=storeProducts.php><li class='tabRight' style='background-color:#707070;color:white;height:2.38em;border-bottom:none;border-left:none;'><h3>Store Products</h3></li></a></form>
</ul>
<br>";
echo"<div style='float:none;background-color:#707070;padding-bottom:1.25em;'>
<p style='height:0.2em;'><br></p>
<div style='background-color:silver;width:98%;margin-right:1%;margin-left:1%;padding-bottom:1em;'>
<br><center>
	<script>
	var map;
	var infowindow;
	//var centerLat = 40.519240;
	//var centerLng=-75.778799;
	  var tavLat = 40.516264;
	  var tavLng=-75.778176;
	function initMap() {
	  var theCenter = {lat: tavLat, lng: tavLng};

	  map = new google.maps.Map(document.getElementById('map'), {
	    center: theCenter,
	    zoom: 17
	  });

	  infowindow = new google.maps.InfoWindow();

	  //40.5162058,-75.7782205
	  var liquorStores = new google.maps.places.PlacesService(map);
	  liquorStores.nearbySearch({
	    location: theCenter,
	    radius: 1500,
	    types: ['liquor_store']
	  }, callback); 
	}

	function callback(results, status) {
	  if (status === google.maps.places.PlacesServiceStatus.OK) {
	    for (var i = 0; i < results.length; i++) {
	      createMarker(results[i]);
	    }
	  }
	}

	function createMarker(place) {
	  var placeLoc = place.geometry.location;
	  var marker = new google.maps.Marker({
	    map: map,
	    position: place.geometry.location
	  });

	  google.maps.event.addListener(marker, 'click', function() {
	    infowindow.setContent(place.name);
	    infowindow.open(map, this);
	  });
	  

	  var tavLatLng = {lat: tavLat, lng: tavLng};
	  var marker = new google.maps.Marker({
	    position: tavLatLng,
	    map: map,
	    title: 'Ktown Tavern, eh'
	  });
	}
	
	
    </script>    
<div id='map' style='height:24em;width:98%;margin-right:1%;margin-left:1%;'></div>
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBmv08kSZpkCuBwQEUsqqnxJ7m_ZBro_OQ&signed_in=true&libraries=places&callback=initMap' async defer></script>

<table style='text-align:center;width:96%;border-bottom:solid;border-top:solid;border-width:thin;margin-right:2%;margin-left:2;'>
<tr >
<td style='width:33%;'><h3>Directions</h3></td>
<td style='width:33%;border-right:solid;border-left:solid;border-width:thin;text-align:center'><h3>6106839600</h3></td>
<td style='width:33%;text-align:center'><a href='http://www.kutztowntavern.com'><h3>kutztowntavern.com</h3></a></td>
</tr>
</table>
<table style='border-style:groove;width:60%;'>
<tr><br></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><b><u><h2>Store Hours</h2></u></b></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Monday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Tuesday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Wednesday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Thursday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Friday:</td><td> 11m - 11pm</td></tr></table></center></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Saturday:</td><td> 11m - 11pm</td></tr></table></center></td></tr>
<tr><td colspan='3' style='text-align:center;border-style:none;'><center><table><tr><td>Sunday:</td><td> 11m - 11pm</td></tr></table></center></td></tr>
</table>
</center>
</div>
</div>
 
	</article>								
	</main>";db_close1(); 
	include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>