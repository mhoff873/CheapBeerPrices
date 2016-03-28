<?php 
	include 'header.php';
	//RETURNS TRUE WHEN COOKIES ARE SET
	//function cookies(){
	//	return (isset($_COOKIE['time_zone_offset'],$_COOKIE['time_zone_dst']));
	//}
	function db_connect1(){
		$db_host = 'localhost';
		$db_user= 'root';
		$db_pass= 'Beer1234';
		//$db_pass='';
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

	$place_id=(isset($_GET['place_id'])&&!isset($_GET['ID']))?$_GET['place_id']:null;
	$id=(isset($_GET['ID'])&&!isset($_GET['place_id']))?$_GET['ID']:null;

	$getDetails=(!is_null($id))?
	"SELECT ID,Name,
	address,phoneNumber,website,latitude,longitude,Location,State,County,
	openSun,closeSun,openMon,closeMon,openTue,closeTue,
	openWed,closeWed,openThu,closeThu,openFri,closeFri,openSat,closeSat
	FROM Vendors
	WHERE ID = ".$id."
	AND patchHours = 1":null; 
	
	$fill = (!is_null($getDetails))?mysql_query($getDetails):null;
	//if (!$fill) {
	//    die('Could not query:' . mysql_error());
	//}
	$callGoogle=(!is_null($place_id))?
	"SELECT ID,place_id,Name,Location,State
	FROM Vendors 
	WHERE place_id = '".$place_id."'
	AND patchHours = 0":null;
	
	$lookup =(!is_null($callGoogle))? mysql_query($callGoogle):null;
	//if (!$lookup) {
	 //  die('Could not query:' . mysql_error());
	//}

 	$data=array();//"Id"=> 'boner',
			//"name" => 'bonerstore',
			//"location"=> 'deep in my pants',
			//"state"=>'underware'); 

		$data=(!is_null($fill))?array(
			"Id"=> mysql_result($fill,0,"ID"),
			"name" => mysql_result($fill,0,"Name"),
			"location"=> mysql_result($fill,0,"Location"),
			"state"=>mysql_result($fill,0,"State"),
			"phoneNumber"=>mysql_result($fill,0,"phoneNumber"),
			"website"=>mysql_result($fill,0,"website"),
			"latitude"=>mysql_result($fill,0,"latitude"),
			"longitude"=>mysql_result($fill,0,"longitude"),
			"county"=>mysql_result($fill,0,"County"),
			"address" => mysql_result($fill,0,"address"),
			"phoneNumber" => mysql_result($fill,0,"phoneNumber"),
			"website" => mysql_result($fill,0,"website"),
			"openMon" => mysql_result($fill,0,"openMon"),
			"closeMon" => mysql_result($fill,0,"closeMon"),
			"openTue" => mysql_result($fill,0,"openTue"),
			"closeTue" => mysql_result($fill,0,"closeTue"),
			"openWed" => mysql_result($fill,0,"openWed"),
			"closeWed" => mysql_result($fill,0,"closeWed"),
			"openThu" => mysql_result($fill,0,"openThu"),
			"closeThu" => mysql_result($fill,0,"closeThu"),
			"openFri" => mysql_result($fill,0,"openFri"),
			"closeFri" => mysql_result($fill,0,"closeFri"),
			"openSat" => mysql_result($fill,0,"openSat"),
			"closeSat" => mysql_result($fill,0,"closeSat"),
			"openSun" => mysql_result($fill,0,"openSun"),
			"closeSun" => mysql_result($fill,0,"closeSun")):$data;

		$data=(!is_null($lookup))?array(
			"Id"=> mysql_result($lookup,0,"ID"),
			"name" => mysql_result($lookup,0,"Name"),
			"location"=> mysql_result($lookup,0,"Location"),
			"state"=>mysql_result($lookup,0,"State")):$data;

	//IF THERE ARE COOKIES, SET CORRECT TIMEZONE, OTHERWISE TIMEZONE IS "America/New_York"
	//cookies()?
	//timezone_name_from_abbr('', -$_COOKIE['time_zone_offset']*60, $_COOKIE['time_zone_dst']) : $townTimezone);
	//$dateer = date_create("now", timezone_open($time_zone_name));
	//$hour24 = date_format($dateer, 'H'); //24 hour clock
	//$today = date_format($dateer, 'D'); //
	//$minute = date_format($dateer, 'i');
	//IF THE HOUR IS 12am IT IS NOT 0am
	//$hour12=(!($hour24%12))?12:$hour24%12; //12 hour clock conversion
	//AM OR PM
	//$dayTime = date_format($dateer, 'a');//(
	//used for simply indexing days of week
	//$days=array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	//
/* 	function isOpen($storeQueryRow){
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
	} */ /*END OF isOpen() */
	////INDENTATION REDUCED 
	
	//place_id=".$_GET['place_id']."
	//ID=".$_GET['ID']."
echo"	<main id='center' class='column'>
<article>
<h1 style='text-align:center;font-size:1.65em;'><b>".$data['name']." -  ".$data['location'].", ".$data['state'].".</b></h1>
<ul style='width:100%;list-style-type: none;margin: 0;padding: 0;'>
	<li class='tabLeft' style='border-bottom:none;background-color:silver;border-right:none;'><h3>Store Summary</h3></li>
	<a href=storeProducts.php?";
	//the tab to Products may pass and accept either a local "id" or Google place_id
	if(isset($_GET['ID'])&&!isset($_GET['place_id'])){
		echo"ID=".$_GET['ID'];
	}
	if(isset($_GET['place_id'])&&!isset($_GET['ID'])){
		echo"place_id=".$_GET['place_id'];
	}
	echo"><li class='tabRight' style='background-color:#707070;color:white;height:2.38em;border-bottom:none;border-left:none;'><h3>Store Products</h3></li></a>
</ul>
<br>";
echo"<div style='float:none;background-color:#707070;padding-bottom:1.25em;'>
<p style='height:0.2em;'><br></p>
<div style='background-color:silver;width:98%;margin-right:1%;margin-left:1%;padding-bottom:1em;'>
<br><center><div id='map' style='height:24em;width:98%;margin-right:1%;margin-left:1%;'></div>
<div id='info' style='height:24em;width:98%;margin-right:1%;margin-left:1%;'></div>";

?>
	<script>
	days=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];	
	<?php
	if(isset($_GET['ID'])&&!isset($_GET['place_id'])){
		$vendorID = json_encode($_GET['ID']);
		echo "var ID = ". $vendorID . ";\n";
	}
	if(!isset($_GET['ID'])&&isset($_GET['place_id'])){
		$vendorID = json_encode($_GET['place_id']);
		echo "var place_id = ". $vendorID . ";\n";
	}
		echo "var data = ".json_encode($data).";\n";
	?>

var map;
var infowindow;
function initMap() {
	
	  map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 15,
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  });
	  var service = new google.maps.places.PlacesService(map);
	  
	  if(!(typeof ID === 'undefined')){
		  map.setCenter(new google.maps.LatLng(data['latitude'], data['longitude']));
		  var id = ID;
		  var opening = new Date();
		  var today = opening.getDay();
		  var open = "open"+days[today];
		  var name = "name";
		  var close = "close"+days[today];
		  var address = "address";
		  var phoneNumber = "phoneNumber";
		  var website = "website";
		  //var service = new google.maps.places.PlacesService(map);
		  if (data.hasOwnProperty(open)&&data.hasOwnProperty(close)){
			  timeText = theTimeSlingingSlasher(data[open],data[close]);
			  constructFromBackend(id,data[name],data[address],data[phoneNumber],data[website],timeText);
		  }else{
			  alert("does not contain "+name+"'s hours in the database "+open);
		  }
		   var marker = new google.maps.Marker({
			position: new google.maps.LatLng(data['latitude'], data['longitude']),
			map: map});
		   var infowindow= new google.maps.InfoWindow();
		   marker.addListener('click', function() {
		   var contentString = '<div id="content">'+
		      '<h1 id="firstHeading" class="firstHeading">test</h1>'+
		      '</div>';
		      infowindow.setContent(contentString);
		      infowindow.open(map, marker);   

		   });
	  }
	  if(!(typeof place_id === 'undefined')){
		  service.getDetails({placeId: place_id},detailsCallback );
	  }
}


function detailsCallback(place, status) {
	if (status === google.maps.places.PlacesServiceStatus.OK) {
		map.setCenter(place.geometry.location);
		var wrapper = document.getElementById('info');
		//var open = place.opening_hours.open_now ? "<img src='photos/open2.png' style='width:60%;margin-top:0;margin-bottom:0;padding:0;margin-left:auto;margin-right:auto;height:auto%;'/>" : "Closed";
		var open = place.opening_hours.open_now ? "They are open!" : "They are closed!";
		             
		//OPENING TIME STUFF
 		var opening = new Date();
		/*var openHours = place.opening_hours.periods[opening.getDay()].open.time.substring(0,2);
		var openMin = place.opening_hours.periods[opening.getDay()].open.time.substring(3);
		opening.setHours(openHours,openMin,0);
		var openTimeText = (openHours % 12 == 0)?12:openHours%12;
		openTimeText += ":";
		openTimeText += (openMin <10)?"0" + openMin: openMin;
		openTimeText += (openHours < 12)?" am":" pm";
		//OPENING TIME STUFF
		var closing = new Date();
		var closeHours = place.opening_hours.periods[closing.getDay()].close.time.substring(0,2);
		var closeMin = place.opening_hours.periods[closing.getDay()].close.time.substring(3);
		closing.setHours(closeHours,closeMin,0);
		var closeTimeText = (closeHours % 12 == 0)?12:closeHours%12;
		closeTimeText += ":";
		closeTimeText += (closeMin <10)?"0" + closeMin: closeMin;
		closeTimeText += (closeHours < 12)?" am":" pm"; */

		/* var contentString = '<div style="margin:none;padding:none;width:100%;height:100%;"><h3 style="text-align:center;">'+place.name+'</h3>'+
		'<h4 style="text-align:center;width:100%;">'+open+'</h4><center style="font-size:0.8em;">*click here for more info*</center>'+
		'</div>'+
		'<table style="margin:0;padding:0;padding-top:0px;width:100%;background-color:white;text-align:center;">'+
		'<tr ><td><span style="text-align:center">HOURS TODAY: '+openTimeText+' - '+closeTimeText+'</span></td></tr>'+
		'<tr ><td> <span style="text-align:center">CALL: <a href="tel:'+place.international_phone_number+'">'+place.formatted_phone_number+'</a></span></td></tr>'+
		'<tr ><td><span style="text-align:center">Visit their <a href="'+place.website+'" style="color:blue;" target="_blank">Website</a></span></td></tr>'+
		'<tr ><td><span style="text-align:center">'+place.vicinity+'</span></td></tr>'+
		'</table>'; */
		var topTableString = '<table style="text-align:center;width:96%;border-bottom:solid;border-top:solid;border-width:thin;margin-right:2%;margin-left:2;">'+
			'<tr >'+
			'<td style="width:33%;"><h3>Directions</h3></td>'+
			'<td style="width:33%;border-right:solid;border-left:solid;border-width:thin;text-align:center"><a href="tel:'+place.international_phone_number+'"><h3>'+place.formatted_phone_number+'</h3></a></td>'+
			'<td style="width:33%;text-align:center"><h3>Visit their <a href="'+place.website+'" style="color:blue;" target="_blank">website</a></h3></a></a></td>'+
			'</tr>'+
			'</table>';
			
		var hourTableString='<table style="border-style:groove;width:60%;margin-top:0;"><tr><br></tr><tr><td colspan="3" style="text-align:center;border-style:none;"><b><u><h2>Store Hours</h2></u></b></td></tr>';
		var today = opening.getDay();	
			
		for(var d = 0;d<7;d++){
			var formattedTime = place.opening_hours.weekday_text[((today+d)-1)%7];
			if((today+d)%7==today){
					//alert ('today = '+today+'\n day of week is '+dayofweek);
				timeText = '<h2>TODAY: ' + formattedTime.substring(formattedTime.indexOf(":")+2,formattedTime.length)+'</h2>';
			}else{
					timeText = days[(today+d)%7]+': ' + formattedTime.substring(formattedTime.indexOf(":")+2,formattedTime.length);
			}
			hourTableString+='<tr><td colspan="3" style="text-align:center;border-style:none;"><center>'+timeText+'</center></td></tr>';
		}	
			
		hourTableString+='</table>';
		//var newElement = document.createElement('div');
		var openBox = document.createElement('div');
		var topTray = document.createElement('div');
		var hourTable = document.createElement('div');
			//newElement.id = place.place_id;
		topTray.id = 'topTray';
		hourTable.id = 'hourTable';
		openBox.id = 'openBox';
		openBox.style.width = "42%";
/* 		newElement.id = place.name;
		
		newElement.style.margin = "1%";
		newElement.style.display= "inline-block";
		newElement.style.verticalalign =  "top";
		newElement.style.border = "solid";
		newElement.style.borderWidth = "thin";
		newElement.style.borderColor = "silver";
		newElement.style.paddingTop = "0";
		newElement.innerHTML = contentString; */
		openBox.innerHTML = open;
		topTray.innerHTML = topTableString;
		hourTable.innerHTML = hourTableString;
		wrapper.appendChild(topTray);
		wrapper.appendChild(openBox);
		wrapper.appendChild(hourTable);
		//wrapper.appendChild(newElement);
		wrapper.style.height = "100%";
			
		createMarker(place);
	}else{
		 alert("shit");
	}
}



function constructFromBackend(id,name,address,phoneNumber,website,timeText){
	var contentString = '<a href="storeSummary2.php?ID='+id+'" style="color:black;background-color:lightblue;text-decoration:none;display:inline-block;width:100%;">'+
		              '<div style="margin:none;padding:none;width:100%;height:100%;"><h3 style="text-align:center;">'+name+'</h3>'+
			        '<h4 style="text-align:center;width:100%;">'+timeText+'</h4><center style="font-size:0.8em;">*click here for more info*</center>'+
			      '</div></a>'+
		       '<table style="margin:0;padding:0;padding-top:0px;width:100%;background-color:white;text-align:center;">'+
			 '<tr ><td><span style="text-align:center" id="timeText">'+timeText+'</span></td></tr>'+
			 '<tr ><td> <span style="text-align:center">CALL: <a href="tel:'+phoneNumber+'">'+phoneNumber+'</a></span></td></tr>'+
			 '<tr ><td><span style="text-align:center">Visit their <a href="'+website+'" style="color:blue;" target="_blank">Website</a></span></td></tr>'+
			 '<tr ><td><span style="text-align:center">'+address+'</span></td></tr>'+
			 '</table>';
			 
			 var topTableString = '<table style="text-align:center;width:96%;border-bottom:solid;border-top:solid;border-width:thin;margin-right:2%;margin-left:2;">'+
				'<tr >'+
				'<td style="width:33%;"><h3>Directions</h3></td>'+
				'<td style="width:33%;border-right:solid;border-left:solid;border-width:thin;text-align:center"><h3>6106839600</h3></td>'+
				'<td style="width:33%;text-align:center"><a href="http://www.kutztowntavern.com"><h3>kutztowntavern.com</h3></a></td>'+
				'</tr>'+
				'</table>';
				
			var hourTableString='<table style="border-style:groove;width:60%;">'+
				'<tr><br></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><b><u><h2>Store Hours</h2></u></b></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Monday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Tuesday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Wednesday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Thursday:</td><td> 4pm - midnight</td></tr></table></center></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Friday:</td><td> 11m - 11pm</td></tr></table></center></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Saturday:</td><td> 11m - 11pm</td></tr></table></center></td></tr>'+
				'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>Sunday:</td><td> 11m - 11pm</td></tr></table></center></td></tr>'+
				'</table>';
			//alert(numVendors);
			var wrapper = document.getElementById('info');
		      var newElement = document.createElement('li');
		      var topTray = document.createElement('div');
		      var hourTable = document.createElement('div');
			//newElement.id = place.place_id;
			topTray.id = 'topTray';
			hourTable.id = 'hourTable';
			newElement.id = id;
			newElement.className = "distributor";

			newElement.innerHTML = contentString;
			topTray.innerHTML = topTableString;
			hourTable.innerHTML = hourTableString;
			wrapper.appendChild(topTray);
			wrapper.appendChild(hourTable);
			wrapper.appendChild(newElement);
			//var elementExists = document.getElementById("find-me");
			//wrapper.insertBefore(newElement,document.getElemenById("distributor"));
			wrapper.style.height = "100%";
			//sortStore(wrapper);
}

function theTimeSlingingSlasher(open,close){
	var opening = new Date();
	var closing = new Date();
	//OPENING STUFF
	var openHours = open.substring(0,2);
	var openMin = open.substring(2);
	opening.setHours(openHours,openMin,0); 
	var openTimeText = (openHours % 12 == 0)?12:openHours%12;
	openTimeText += ":";
	openTimeText += (openMin <10)?openMin: openMin;
	openTimeText += (openHours < 12)?" am":" pm";
	//CLOSING STUFF
	var closeHours = close.substring(0,2);
	var closeMin = close.substring(2);
	closing.setHours(closeHours,closeMin,0);
	var closeTimeText = (closeHours % 12 == 0)?12:closeHours%12;
	closeTimeText += ":";
	closeTimeText += (closeMin <10)?closeMin: closeMin;
	closeTimeText += (closeHours < 12)?" am":" pm";
	//FINAL TIME STRING
	return 'HOURS TODAY: ' +openTimeText + ' - ' + closeTimeText;
}
	
function createMarker(place) {
	  var placeLoc = place.geometry.location;
	  var infowindow = new google.maps.InfoWindow();
	  var marker = new google.maps.Marker({
	    map: map,
	    position: place.geometry.location
	  });
	  google.maps.event.addListener(marker, 'click', function() {
	  var contentString = '<div id="content">'+
	      '<h1 id="firstHeading" class="firstHeading">'+place.name+'</h1>'+'</div>';
	      
	      
	    infowindow.setContent(contentString);
	    infowindow.open(map, this);
	  }); 
}
    </script>    
<?php echo"
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBmv08kSZpkCuBwQEUsqqnxJ7m_ZBro_OQ&signed_in=true&libraries=places&callback=initMap' async defer></script>




</center>
</div>
</div>
 
	</article>								
	</main>";db_close1(); 
	include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>