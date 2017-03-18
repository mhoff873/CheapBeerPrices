<?php 
	include 'header.php';
	function db_connect1(){
		$db_host = ' ';
		$db_user= ' ';
		$db_pass= ' ';
		//$db_pass='';
		$db_name= ' ';
		//$db_name= ' ';
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

	$id=(isset($_GET['ID']))?$_GET['ID']:null;

	$getDetails=(!is_null($id))?
	"SELECT ID,Name,
	address,phoneNumber,website,latitude,longitude,Location,State,County,
	openSun,closeSun,openMon,closeMon,openTue,closeTue,
	openWed,closeWed,openThu,closeThu,openFri,closeFri,openSat,closeSat
	FROM vendors
	WHERE ID = ".$id."
	AND patchHours = 1":null; 
	
	$fill = (!is_null($getDetails))?mysql_query($getDetails):null;

 	$data=array();
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

	echo"	<main id='center' class='column'>
	<article>
	<h1 style='text-align:center;font-size:1.65em;'><b>".$data['name']." -  ".$data['location'].", ".$data['state'].".</b></h1>";
	
/* 	<ul style='width:100%;list-style-type: none;margin: 0;padding: 0;'>
	<li class='tabLeft' style='border-bottom:none;background-color:silver;border-right:none;'><h3>Store Summary</h3></li>
	<a href=storeProducts.php?";
	
	if(isset($_GET['ID'])){
		echo"ID=".$_GET['ID'];
	}
	//ELSE:

	echo"><li class='tabRight' style='background-color:#707070;color:white;height:2.38em;border-bottom:none;border-left:none;'><h3>Store Products</h3></li></a>
	</ul>
	<br>"; */
	
	echo"<div style='float:none;background-color:#707070;padding-bottom:1.25em;'>
	
	
	<table style='width:100%;margin: 0;padding: 0;'> 
		<tr>
			<td class='tabLeft' style='background-color:707070;color:white;display:block;border-bottom:none;border-left:none;'>
				
					<h1>Store Summary</h1></a>

			</td>
			
			<td class='tabRight' style='background-color:silver;'>
			<a href=storeProducts.php?";
				//setting the link to correct vendor's summary page
				if(isset($_GET['ID'])){
					echo"ID=".$_GET['ID'];
				} 
				echo" style='width: 50%;height: 100%;'>
				<h3>Store Products</h3></a>
			</td>
		</tr>
	</table>
	<br>
	
	
	
	
	<div style='background-color:silver;width:98%;margin-right:1%;margin-left:1%;padding-bottom:1em;'>
	<br><center><div id='map' style='height:24em;width:98%;margin-right:1%;margin-left:1%;'></div>
	<div id='info' style='height:24em;width:98%;margin-right:1%;margin-left:1%;'></div>";

?>
<script>
	//PHP GLOBALS	
<?php
	echo "var data = ".json_encode($data).";\n";
	if(isset($_GET['ID'])){
		$vendorID = json_encode($_GET['ID']);
		echo "var ID = ". $vendorID . ";\n";
	}
	//ELSE:
	
	
?>

days=['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; //for retrieving data from database
dayNames=['Sunday','Monday','Tuesday','Wednesday','Thusday','Friday','Saturday']; //for printing menu
var map;
var infowindow;

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	var service = new google.maps.places.PlacesService(map);
	//if(!(typeof ID === 'undefined')){
	map.setCenter(new google.maps.LatLng(data['latitude'], data['longitude']));
	var opening = new Date();
	var timeNow = '';
	timeNow += opening.getHours();
	timeNow += opening.getMinutes();
	var today = opening.getDay();
	var open = "open"+days[today];
	var name = data['name'];
	var close = "close"+days[today];
	var address = data['address'];
	var phoneNumber = data['phoneNumber'];
	var website = data['website'];
	if(open>close){  //opens during day, closes during the early AM hours
		var status = (timeNow>=open||timeNow<=close)?'<img src="images/open.png" width="80%" height="auto">':'<img src="images/closed.png" width="80%" height="auto">';
	}
	if(close>open){  //opens during day, closing later on same day
		var status = (timeNow>=open&&timeNow<=close)?'<img src="images/open.png" width="80%" height="auto">':'<img src="images/closed.png" width="80%" height="auto">';
	}
	timeText = theTimeSlingingSlasher(data[open],data[close]);
	//table with phonenumber, open now, and website link
	var topTableString = '<table style="text-align:center;width:96%;border-bottom:solid;border-top:solid;border-width:thin;margin-right:2%;margin-left:2;">'+
		'<tr >'+
		'<td style="width:33%;"><h3><a href="tel:'+phoneNumber+'">'+phoneNumber+'</a></h3></td>'+
		'<td style="width:33%;border-right:solid;border-left:solid;border-width:thin;text-align:center"><h3>'+status+'</h3></td>'+
		'<td style="width:33%;text-align:center"><a href="'+website+'" target="_blank"><h3>Visit their website</h3></a></td>'+
		'</tr>'+
		'</table>';
	
	//begining of time table with today's times first
	var hourTableString='<table style="border-style:groove;width:60%;">'+
		'<tr><br></tr>'+
		'<tr><td colspan="3" style="text-align:center;border-style:none;"><b><u><h2>Hours</h2></u></b></td></tr>'+
		'<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td><b>Hours Today: '+timeText+'</b></td></tr></table></center></td></tr>';
	for ($x = 1; $x <= 6; $x++) { //must start at 1 so first row after today is today+1
		open = "open"+days[(today+$x)%7];
		close = "close"+days[(today+$x)%7];
		timeText = theTimeSlingingSlasher(data[open],data[close]);
		hourTableString+='<tr><td colspan="3" style="text-align:center;border-style:none;"><center><table><tr><td>'+dayNames[(today+$x)%7]+': '+timeText+'</td></tr></table></center></td></tr>';
	}
	//end of the time table	
	hourTableString+='</table>';
			
	var wrapper = document.getElementById('info');
	var topTray = document.createElement('div');
	var hourTable = document.createElement('div');

	topTray.id = 'topTray';
	hourTable.id = 'hourTable';

	topTray.innerHTML = topTableString;
	hourTable.innerHTML = hourTableString;
	wrapper.appendChild(topTray);
	wrapper.appendChild(hourTable);
	wrapper.style.height = "100%";

	//MARKER FOR STORE
	var marker = new google.maps.Marker({
	position: new google.maps.LatLng(data['latitude'], data['longitude']),
	map: map});
	var infoWindow= new google.maps.InfoWindow();
 	marker.addListener('click', function() {
		var contentString = '<div id="content">'+
		'<h1 id="firstHeading" class="firstHeading">'+name+'</h1>'+
		'<center><p>'+data['address']+', '+data['location']+' '+data['state']+'</p></center>'
		'</div>';
		infoWindow.setContent(contentString);
		infoWindow.open(map, marker);  
		map.setCenter(marker.getPosition());		
	}); 
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
	return openTimeText + ' - ' + closeTimeText;
}

    </script>    
<?php echo"
<script src='https://maps.googleapis.com/maps/api/js?key= &signed_in=true&libraries=places&callback=initMap' async defer></script>
</center>
</div>
</div>
 
	</article>								
	</main>";db_close1(); 
	include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>
