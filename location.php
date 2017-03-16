<?php 
	include 'header.php';
	function db_connect1(){
		$db_host = 'localhost';
		$db_user= 'root';
		$db_pass= 'Beer1234';
		//$db_pass='';
		$db_name= 'CheapBeer';
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
	//LOCATION VARS (only assign if set)
	$location=$_GET['loc'];
	$state=$_GET['state'];
echo"<main id='center' class='column'>
	<article>
	
	
	<h1 style='text-align:center;'>".$location.", ".$state.".</h1><hr>
	<div style='padding: 0;border: 0;margin: 0;float:none;background-color:#707070;padding-bottom:1.25em;'>
	<table style='width:100%;margin: 0;padding: 0;'> 
		<tr>
			<td style='text-align:center;width:20%;background-color:silver;color:white;border-bottom:none;border-left:none;'>
				<a href=location.php?";
				//setting the link to correct vendor's summary page
				if(isset($_GET['loc'])){
					if(isset($_GET['state'])){ //only redirects when all required GETs are known
						echo"loc=".$_GET['loc'];
						echo"&state=".$_GET['state'];
						echo"&view=stat";
					}
				} 
				echo" style='width: 100%;height: 100%;'>
					<h3>Location Statistics</h3></a>

			</td>
			
			<td style='text-align:center;width:20%;background-color:#707070;border-bottom:none;border-left:none;'>
				<h1 style='color:white'>Stores</h1>
			</td>
			<td style='text-align:center;width:20%;background-color:silver;color:white;border-bottom:none;border-left:none;'>
				<a href=location.php?"; //redirect to correct page with correct loc and state variables
				//setting the link to correct vendor's summary page
				if(isset($_GET['loc'])){
					if(isset($_GET['state'])){ //only redirects when all required GETs are known
						echo"loc=".$_GET['loc'];
						echo"&state=".$_GET['state'];
						echo"&view=prices";
					}
				} 
				echo" style='width: 100%;height: 100%;'>
				<h3>Prices</h3>
			</td>
		</tr>
	</table>
	<br>
	<div style='background-color:silver;width:98%;margin-right:1%;margin-left:1%;padding-bottom:1em;'>
	<ul id='wrapper' style='padding:0;margin:0;width:100%;height:100%;list-style-type:none;'></ul>"; //dis ul is the whole freaking thing
		
	  $query = "SELECT ID,
	   address,phoneNumber,website,
	   location,state,latitude,longitude,County,
	   openSun,closeSun,
	   openMon,closeMon,
	   openTue,closeTue,
	   openWed,closeWed,
	   openThu,closeThu,
	   openFri,closeFri,
	   openSat,closeSat,
	   Name,patchHours 
	   FROM vendors 
	   WHERE Location = '".$_GET['loc']."' 
	   AND patchHours = 1";
	   $locationvendorsToFill = mysql_query($query);
	   $numLocationvendorsToFill = mysql_numrows($locationvendorsToFill);
	   
/* 	   $query = "SELECT ID,place_id
	   FROM vendors 
	   WHERE Location = '".$_GET['loc']."' 
	   AND patchHours = 0";
	   $locationvendorsLookup = mysql_query($query);
	   $numLocationvendorsLookup = mysql_numrows($locationvendorsLookup); */
	   //ARRAYS TO JSON ENCODE
	  // $vendorPlaceID=array(); //Google place_id
	   $toFill=array();        //Local database columns
	   $idToFill=array();	//array to hold id's of places to fill
	   //$idToLookup=array();	//array to hold id's of places to lookup
	   
	   
	  /*  for($v=0;$v<$numLocationvendorsLookup;++$v){
		   $id = mysql_result($locationvendorsLookup,$v,"ID");
		   array_push($vendorPlaceID,mysql_result($locationvendorsLookup,$v,"place_id"));
		   array_push($idToLookup,$id); //holds all ids for google lookup
	   } */
	   for($v=0;$v<$numLocationvendorsToFill;++$v){
		   //this page does not need store hours for everyday. just "today"
		   $id = mysql_result($locationvendorsToFill,$v,"ID");
		   array_push($idToFill,$id); //holds all ids for local fill
		   array_push($toFill,array(  ///all information
			$id."name" => mysql_result($locationvendorsToFill,$v,"Name"),
			$id."location"=> mysql_result($locationvendorsToFill,$v,"Location"),
			$id."state"=>mysql_result($locationvendorsToFill,$v,"State"),
			$id."phoneNumber"=>mysql_result($locationvendorsToFill,$v,"phoneNumber"),
			$id."website"=>mysql_result($locationvendorsToFill,$v,"website"),
			$id."latitude"=>mysql_result($locationvendorsToFill,$v,"latitude"),
			$id."longitude"=>mysql_result($locationvendorsToFill,$v,"longitude"),
			$id."county"=>mysql_result($locationvendorsToFill,$v,"County"),
			$id."address" => mysql_result($locationvendorsToFill,$v,"address"),
			$id."phoneNumber" => mysql_result($locationvendorsToFill,$v,"phoneNumber"),
			$id."website" => mysql_result($locationvendorsToFill,$v,"website"),
			$id."openMon" => mysql_result($locationvendorsToFill,$v,"openMon"),
			$id."closeMon" => mysql_result($locationvendorsToFill,$v,"closeMon"),
			$id."openTue" => mysql_result($locationvendorsToFill,$v,"openTue"),
			$id."closeTue" => mysql_result($locationvendorsToFill,$v,"closeTue"),
			$id."openWed" => mysql_result($locationvendorsToFill,$v,"openWed"),
			$id."closeWed" => mysql_result($locationvendorsToFill,$v,"closeWed"),
			$id."openThu" => mysql_result($locationvendorsToFill,$v,"openThu"),
			$id."closeThu" => mysql_result($locationvendorsToFill,$v,"closeThu"),
			$id."openFri" => mysql_result($locationvendorsToFill,$v,"openFri"),
			$id."closeFri" => mysql_result($locationvendorsToFill,$v,"closeFri"),
			$id."openSat" => mysql_result($locationvendorsToFill,$v,"openSat"),
			$id."closeSat" => mysql_result($locationvendorsToFill,$v,"closeSat"),
			$id."openSun" => mysql_result($locationvendorsToFill,$v,"openSun"),
			$id."closeSun" => mysql_result($locationvendorsToFill,$v,"closeSun")));
	   }
	   
	   
	   
	   

		echo"</div>
		</div>";

?>
<script type="text/javascript">
	//regular javascript globals
	totalvendors = 0;
	timeText = "";
	days=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];		  
	<?php
	//globals coming from php
		if($toFill){
			echo "var idToFill  = ".json_encode($idToFill, JSON_PRETTY_PRINT) . ";\n";
			echo "var toFill = ". json_encode($toFill, JSON_PRETTY_PRINT) . ";\n";
			echo "var numLocalvendorsToFill = ". json_encode($numLocationvendorsToFill) . ";\n";
		}else{echo"var numLocalvendorsToFill = 0;";}
		/* if($vendorPlaceID){
			echo "var idToLookup  = ".json_encode($idToLookup, JSON_PRETTY_PRINT) . ";\n";
			echo "var localvendorsIDLookup = ". json_encode($vendorPlaceID) . ";\n";
			echo "var numLocalvendorsLookup = ". json_encode($numLocationvendorsLookup) . ";\n";
		}else{echo"var numLocalvendorsLookup = 0;";} */
?>
function initMap() {
	  //var map = new google.maps.Map(document.createElement('div'));
	  //var infowindow = new google.maps.InfoWindow();
	  //var service = new google.maps.places.PlacesService(map);
	  
	  for (var v = 0; v < numLocalvendorsToFill;v++){
		  var id = idToFill[v];
		  var opening = new Date();
		  var today = opening.getDay();
		  var open = id+"open"+days[today];
		  var name = id+"name";
		  var close = id+"close"+days[today];
		  var address = id+"address";
		  var phoneNumber = id+"phoneNumber";
		  var website = id+"website";
		  
		  if (toFill[v].hasOwnProperty(open)&&toFill[v].hasOwnProperty(close)){
			  timeText = theTimeSlingingSlasher(toFill[v][open],toFill[v][close]);
			  constructFromBackend(id,toFill[v][name],toFill[v][address],toFill[v][phoneNumber],toFill[v][website],timeText);
		  }else{
			  alert("does not contain "+name+"'s hours in the database "+open);
		  }
		  totalvendors+=1;
	  }
/* 	  for (var v = 0; v < numLocalvendorsLookup;v++){
		service.getDetails({placeId: localvendorsIDLookup[v]},detailsCallback );
		totalvendors+=1;
	  } */
	}//END OF INITMAP

/* function detailsCallback(place, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			var opening = new Date();
			var closing = new Date();
			var today = opening.getDay();
			var wrapper = document.getElementById('wrapper');
			var open  = "empty";
			open = place.opening_hours.open_now ? "<img src='photos/open2.png' style='width:60%;margin-top:0;margin-bottom:0;padding:0;margin-left:auto;margin-right:auto;height:auto%;'/>" : "Closed";
			var formattedTime = place.opening_hours.weekday_text[today];
			timeText = 'HOURS TODAY: ' + formattedTime.substring(formattedTime.indexOf(":")+2,formattedTime.length)
			constructFromGoogle(place,open,timeText);		
		}else{
			alert("Error with business Lookup");
		}
	} */
	  
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
			 '</table>'+
			'';
			//alert(numvendors);
		      var newElement = document.createElement('li');
			//newElement.id = place.place_id;
			newElement.id = id;
			newElement.className = "distributor";

			newElement.innerHTML = contentString;

			wrapper.appendChild(newElement);
			//var elementExists = document.getElementById("find-me");
			//wrapper.insertBefore(newElement,document.getElemenById("distributor"));
			wrapper.style.height = "100%";
			//sortStore(wrapper);
}

	  
/* function constructFromGoogle(place,open,timeText){
	var contentString = '<a href="storeSummary2.php?place_id='+place.place_id+'" style="color:black;background-color:lightblue;text-decoration:none;display:inline-block;width:100%;">'+
		              '<div style="margin:none;padding:none;width:100%;height:100%;"><h3 style="text-align:center;">'+place.name+'</h3>'+
			        '<h4 style="text-align:center;width:100%;">'+open+'</h4><center style="font-size:0.8em;">*click here for more info*</center>'+
			      '</div></a>'+
		       '<table style="margin:0;padding:0;padding-top:0px;width:100%;background-color:white;text-align:center;">'+
			 '<tr ><td><span style="text-align:center" id="timeText">'+timeText+'</span></td></tr>'+
			 '<tr ><td> <span style="text-align:center">CALL: <a href="tel:'+place.international_phone_number+'">'+place.formatted_phone_number+'</a></span></td></tr>'+
			 '<tr ><td><span style="text-align:center">Visit their <a href="'+place.website+'" style="color:blue;" target="_blank">Website</a></span></td></tr>'+
			 '<tr ><td><span style="text-align:center">'+place.vicinity+'</span></td></tr>'+
			 '</table>';
			//alert(numvendors);
		      var newElement = document.createElement('li');
			//newElement.id = place.place_id;
			newElement.id = place.place_id;
			newElement.className = "distributor";

			newElement.innerHTML = contentString;

			wrapper.appendChild(newElement);
			//var elementExists = document.getElementById("find-me");
			//wrapper.insertBefore(newElement,document.getElemenById("distributor"));
			wrapper.style.height = "100%";
			//sortStore(wrapper);
} */

function sortStore(ul){
	//alert("sorting");
	var lis = ul.getElementsByClassName("distributor");
	var ali = Array.prototype.slice.call(lis);
	ali.sort(liSort);
	for (var i = 0; i < ali.length; i++) {
	    ul.appendChild(ali[i]);
	}
}

function liSort(one, two) {
    return one.id < two.id ? -1 : ( one.id > two.id ? 1 : 0 );
}

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmv08kSZpkCuBwQEUsqqnxJ7m_ZBro_OQ&signed_in=true&libraries=places&callback=initMap" async defer></script>
	<?php
	echo"</article>								
	</main>";
	db_close1(); 
	include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>