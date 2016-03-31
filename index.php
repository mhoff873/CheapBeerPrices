<?php   
	include 'header.php';
	
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

		


echo"<main id='center' class='column'>
	<article>	
		<h1>Find Cheap beer near you!</h1>
		<p>Are you spending to much on beer? Are you looking to save money and time without cutting back on beer consumption? 
		If you answered yes to one of these questions, then you have come to the right place. Cheapbeerprices.com is a community that
		allows you to see how much others paid for a case of beer in you area. 
		</p>	
		
    <center><div id='indexMap'></div></center>";

	   //graphable vendors
    	   $query = "SELECT ID,place_id,Name,
	   latitude,longitude 
	   FROM vendors
	   WHERE latitude <> 0
	   AND longitude <> 0";
	   
/* 	   "SELECT ID,place_id,
	   address,phoneNumber,website,
	   location,state,latitude,longitude,County,
	   Name,patchHours 
	   FROM Vendors 
	   WHERE patchHours = 1"; */
/* 	   	   $locationVendorsToFill = mysql_query($query);
	   $numLocationVendorsToFill = (!is_null($locationVendorsToFill))?mysql_numrows($locationVendorsToFill):0; */
	   $vendors = mysql_query($query);
	   $numVendors = (!is_null($vendors))?mysql_numrows($vendors):0;
/* 	   if (!$locationVendorsToFill) {
	    die('Could not query:' . mysql_error());
	} */
	    $query = "SELECT ID,place_id
	   FROM vendors 
	   WHERE patchHours = 0
	   AND latitude <> 0
	   AND longitude <> 0";
	   $locationVendorsLookup = mysql_query($query);
	   $numLocationVendorsLookup = (!is_null($locationVendorsLookup))?mysql_numrows($locationVendorsLookup):0;
/* 	   if (!$locationVendorsLookup) {
	    die('Could not query:' . mysql_error());
	}  */
	   
	   //ARRAYS TO JSON ENCODE
	   $vendorPlaceID=array(); //Google place_id array
	   $toFill=array();        //Local database columns
	   $idToFill=array();	//array to hold id's of places to fill
	   $idToLookup=array();	//array to hold id's of places to lookup
	   
	   
 	   for($v=0;$v<$numLocationVendorsLookup;++$v){
		   $id = mysql_result($locationVendorsLookup,$v,"ID");
		   array_push($vendorPlaceID,mysql_result($locationVendorsLookup,$v,"place_id"));
		   array_push($idToLookup,$id); //holds all ids for google lookup
	   } 
	   for($v=0;$v<$numVendors;++$v){
		   //this page does not need store hours for everyday. just "today"
		   $id = mysql_result($vendors,$v,"ID");
		   array_push($idToFill,$id); //holds all ids for local fill
		   array_push($toFill,array(  ///all information
			"name" => mysql_result($vendors,$v,"Name"),
			"latitude"=>mysql_result($vendors,$v,"latitude"),
			"longitude"=>mysql_result($vendors,$v,"longitude"),
			
			/*$id."location"=> mysql_result($locationVendorsToFill,$v,"Location"),
			$id."state"=>mysql_result($locationVendorsToFill,$v,"State"),
			$id."phoneNumber"=>mysql_result($locationVendorsToFill,$v,"phoneNumber"),
			$id."website"=>mysql_result($locationVendorsToFill,$v,"website"),
			$id."county"=>mysql_result($locationVendorsToFill,$v,"County"),
			$id."address" => mysql_result($locationVendorsToFill,$v,"address"),
			$id."phoneNumber" => mysql_result($locationVendorsToFill,$v,"phoneNumber"),
			$id."website" => mysql_result($locationVendorsToFill,$v,"website")*/
			
			));
	   }
	   
	   
/* $query= "SELECT Name FROM vendors";
//$result=mysql_query($query);
//	$i=0;
//	$nameArray = array();
   // while($row = mysql_fetch_assoc($result)) {
	//	$nameArray[$i]=$row["Name"];
	//	$i=$i+1;
    } */
	
/* $query= "SELECT latitude FROM vendors";
//$result=mysql_query($query);
//	$i=0;
//	$latitudeArray = array();
 //   while($row = mysql_fetch_assoc($result)) {
//		$latitudeArray[$i]=$row["latitude"];
//		$i=$i+1;
  //  } */
	
/* $query= "SELECT longitude FROM vendors";
//$result=mysql_query($query);
//	$i=0;
//	$longitudeArray = array();
    while($row = mysql_fetch_assoc($result)) {
		$longitudeArray[$i]=$row["longitude"];
		$i=$i+1;
    } */
?>
	<script type="text/javascript">
	
	var toLookup =<?php echo json_encode($vendorPlaceID); ?>; //Google place_id array
	var toFill =<?php echo json_encode($toFill); ?>; //Local database columns
	var idToFill =<?php echo json_encode($idToFill); ?>; //array to hold id's of places to fill
	var idToLookup =<?php echo json_encode($idToLookup); ?>; //array to hold id's of places to lookup
	var numFill=<?php echo json_encode($numVendors); ?>; //int number of business to fill
	var numLookup=<?php echo json_encode($numLocationVendorsLookup); ?>; //int number of business to fill
	
/* 	
	var nameArray = [];
	nameArray = <?php echo json_encode($nameArray); ?>;
	//for(i=0;i<5;i++){
	//	document.write(nameArray[i]);
	//}
	
	var latitudeArray = [];
	latitudeArray = <?php echo json_encode($latitudeArray); ?>;
	//for(i=0;i<5;i++){
	//	document.write(latitudeArray[i]);
	//}
	
	var longitudeArray = [];
	longitudeArray = <?php echo json_encode($longitudeArray); ?>; */
	//for(i=0;i<5;i++){
	//	document.write(longitudeArray[i]);
	//}
	
var indexMap;
//var infowindow = new google.maps.InfoWindow;
function initMap() {
	indexMap = new google.maps.Map(document.getElementById('indexMap'), {
	  zoom: 10,
	  center: new google.maps.LatLng(40, -75), //should be set to client's closest location
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	
	var showPosition = function (position) 
           {
            indexMap.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), 10);
		}
	             navigator.geolocation.getCurrentPosition(showPosition);  
	
	var marker, i;
	for (i = 0; i < numFill; i++) { 
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(toFill[i]['latitude'], toFill[i]['longitude']),
			map: indexMap });
			
		
		/* var showPosition = function(position)
		{
			indexMap.setCenter(new google.maps.LatLng(position.coords.latitude,position.coords.longitude),10);
		}
			navigator.geolocation.getCurentPosition(showPosition); */
/* 		marker.addListener('click',function(){
			infowindow.open(map,marker);
		});  */
		 google.maps.event.addListener(marker, 'click', (function(marker, i) {
		 return function() {
			 indexMap.setCenter(new google.maps.LatLng(toFill[i]['latitude'], toFill[i]['longitude']));
			 //indexMap.setZoom(indexMap.getZoom()+2);
			 //map.setZoom()
			var contentString = '<div id="content">'+
			'<h1 id="firstHeading" class="firstHeading">'+toFill[i]['name']+
			'<a href="storeSummary2.php?ID='+idToFill[i]+''+'">this is a link</a></h1></div>';
			var infowindow = new google.maps.InfoWindow({content: contentString});
			// infowindow.content: contentString});
			infowindow.setContent(contentString);
			infowindow.open(indexMap, marker);
		 }
		 })(marker, i));
	}	
	
/* 	for (i = 0; i < numLookup; i++) {
		constructFromGoogle(toLookup[i],i);
	} */
}

function detailsCallback(place, status) {
	if (status === google.maps.places.PlacesServiceStatus.OK) {
		//var opening = new Date();
		//var closing = new Date();
		//var today = opening.getDay();
		//var open  = "empty";
		//open = place.opening_hours.open_now ? "<img src='photos/open2.png' style='width:60%;margin-top:0;margin-bottom:0;padding:0;margin-left:auto;margin-right:auto;height:auto%;'/>" : "Closed";
		//var formattedTime = place.opening_hours.weekday_text[today];
		//timeText = 'HOURS TODAY: ' + formattedTime.substring(formattedTime.indexOf(":")+2,formattedTime.length)
		//constructFromGoogle(place);		
	}else{
		//alert("Error with Lookup");
		if (status === google.maps.places.PlacesServiceStatus.OVER_QUERY_LIMIT) {
			
			alert("Over query limit! Error!");
		}
		if (status === google.maps.places.PlacesServiceStatus.UNKNOWN_ERROR) {
			alert("Unknown Error!");
		}
		if (status === google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
			alert("No results! Error!");
		}
		if (status === google.maps.places.PlacesServiceStatus.INVALID_REQUEST) {
			alert("Invalid Request! Error!");
		}
		if (status === google.maps.places.PlacesServiceStatus.REQUEST_DENIED){
			alert("Request Denied! Error!");
		}
	}
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


/* function constructFromBackend(id,name,address,phoneNumber,website,timeText){
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
		      var newElement = document.createElement('li');
			newElement.id = id;
			newElement.className = "distributor";

			newElement.innerHTML = contentString;

			wrapper.appendChild(newElement);
			//wrapper.style.height = "100%";
} */

	  
function constructFromGoogle(place_id,i){
		var service = new google.maps.places.PlacesService(indexMap); 
	//var infowindow=new google.maps.InfoWindow();
	
	
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(toFill[i]['latitude'], toFill[i]['longitude']),
		map: indexMap });
/*  		marker.addListener('click',function(){
			infowindow.open(map,marker);
		});   */
		 google.maps.event.addListener(marker, 'click', (function(marker) {
		 return function() {
			 service.getDetails({placeId: place_id},detailsCallback );
			var contentString = '<div id="content">'+
			'<h1 id="firstHeading" class="firstHeading">'+'hey nothing flashy here'+
			'</h1></div>';
			var infowindow = new google.maps.InfoWindow({content: contentString});
			infowindow.open(indexMap, marker);
		 }
		 })(marker));
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    </script>

				
				<h1> Saving time and money! </h1>
				<p>It's amazing that two stores less than 5 minutes apart can have dramatic differences in prices. Our research indicates that prices can vary
				by 20% for the same case of beer in a single geographic location. Cheapbeerprices.com aims to guide you to find the cheapest cheapest beverages, so you can save money. </p>
				
				<h3> Let us know what you paid! </h3>
				<p> Beer buddy is a community that relies on people like you to share how much you paid for a case of brew.
				Let us know how much you paid for a case at a specific vendor, and we will publish it on our website.<p>
				<p> Email the location, price, and type of beer to:</p>
				<p> beerpriceindex@gmail.com </p>
<script src="http://coinwidget.com/widget/coin.js"></script>
<script>
CoinWidgetCom.go({
	wallet_address: "1McsfnMLkF4eiL7yxg3thSAU61sGPsBvLN"
	, currency: "bitcoin"
	, counter: "count"
	, alignment: "bl"
	, qrcode: true
	, auto_show: false
	, lbl_button: "Donate"
	, lbl_address: "My Bitcoin Address:"
	, lbl_count: "donations"
	, lbl_amount: "BTC"
});
</script>
			</article>								
		
		</main>
		
		<script>
  		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  		ga('create', 'UA-71053763-2', 'auto');
  		ga('send', 'pageview');

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmv08kSZpkCuBwQEUsqqnxJ7m_ZBro_OQ&signed_in=true&libraries=places&callback=initMap" async defer></script>
<?php 
db_close1();
	include 'footer.php';
?>
	
