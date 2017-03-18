<?php   
	include 'header.php';
	
function db_connect1(){
	$db_host = ' ';
	$db_user= ' ';
	$db_pass= ' ';
	//$db_pass='';
	//$db_name= ' ';
	$db_name= ' ';
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

		/* <p>Are you spending to much on beer? Are you looking to save money and time without cutting back on beer consumption? 
		If you answered yes to one of these questions, then you have come to the right place. Cheapbeerprices.com is a community that
		allows you to see how much others paid for a case of beer in you area. 
		</p> */


echo"<main id='center' class='column'>
	<article>	
		<h1>Find beer prices near you!</h1>
		<center><div id='indexMap'></div></center>";

	   //graphable vendors
    	   $query = "SELECT ID,Name,location,
	   latitude,longitude 
	   FROM vendors
	   WHERE latitude <> 0
	   AND longitude <> 0";
	   

	   $vendors = mysql_query($query);
	   $numvendors = (!is_null($vendors))?mysql_numrows($vendors):0;
	   
	   //ARRAYS TO JSON ENCODE
	   $toFill=array();        //Local database columns (name, lat, long)
	   $idToFill=array();	//array to hold id's of places to fill
	   
	   for($v=0;$v<$numvendors;++$v){
		   $location = mysql_result($vendors,$v,"location"); //find each location for each vendor
		   $id = mysql_result($vendors,$v,"ID"); //gets the vendor id of each location
		   $validateLocation="select 1 from `$location` WHERE VendorID = ".$id." LIMIT 1"; //checks if there are prices for the vendor
		   $locWithPriceQuery=mysql_query($validateLocation);
		   if($locWithPriceQuery){ //this is how i got this part to work. 
		   if(mysql_numrows($locWithPriceQuery)){ //only pushes vendors with prices
			   array_push($idToFill,$id); //holds all ids for local fill
			   array_push($toFill,array(  ///all information
			"name" => mysql_result($vendors,$v,"Name"),
			"latitude"=>mysql_result($vendors,$v,"latitude"),
			"longitude"=>mysql_result($vendors,$v,"longitude"),
			));  
		   }
		   }
		   
	   }
?>
	<script type="text/javascript">
	
	
	var toFill =<?php echo json_encode($toFill); ?>; //Local database columns
	var idToFill =<?php echo json_encode($idToFill); ?>; //array to hold id's of places to fill
	
	var numFill=<?php echo json_encode($numvendors); ?>; //int number of business to fill
	
	
var indexMap;

function initMap() {
	var infowindow = new google.maps.InfoWindow;
	indexMap = new google.maps.Map(document.getElementById('indexMap'), {
	  zoom: 11,
	  center: new google.maps.LatLng(40, -75), //should be set to client's closest location
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	
	var showPosition = function (position) 
           {indexMap.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), 11);}
	navigator.geolocation.getCurrentPosition(showPosition);  
	
	var marker, i;
	for (i = 0; i < numFill; i++) { 
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(toFill[i]['latitude'], toFill[i]['longitude']),
			map: indexMap });
		google.maps.event.addListener(marker, 'click', (function(marker, i,infowindow) {
		     return function() {
			indexMap.setCenter(new google.maps.LatLng(toFill[i]['latitude'], toFill[i]['longitude']));
			var contentString = '<div id="content">'+
			'<h1 id="firstHeading" class="firstHeading">'+toFill[i]['name']+'</h1>'+
			'<a href="storeproducts.php?ID='+idToFill[i]+''+'"><input type="button" value="Prices"></a>'+
			'<a href="storeSummary2.php?ID='+idToFill[i]+''+'"><input type="button" value="Store Summary"></a>'+
			'</div>';
			infowindow.setContent(contentString);
			infowindow.open(indexMap, marker);
		 }
		 })(marker, i,infowindow));
	}	
}
    </script>

<h1> Saving time and money! </h1>
<p>It's amazing that two stores less than 5 minutes apart can have dramatic differences in prices. Our research indicates that prices can vary
by 20% for the same case of beer in a single geographic location. Cheapbeerprices.com aims to guide you to find the cheapest cheapest beverages, so you can save money. </p>
<!--<h3> Share what you paid! </h3>
 <p> Beer buddy is a community that relies on people like you to share how much you paid for a case of brew.
Let us know how much you paid for a case at a specific vendor, and we will publish it on our website.<p>
<p> Email the location, price, and type of beer to:</p>
<p> beerpriceindex@gmail.com </p> -->
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
<script src="https://maps.googleapis.com/maps/api/js?key= &signed_in=true&libraries=places&callback=initMap" async defer></script>
<?php 
db_close1();
	include 'footer.php';
?>
	
