<?php   
	include 'header.php';
?>
		
<script>
  		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  		ga('create', 'UA-71053763-2', 'auto');
  		ga('send', 'pageview');

</script>

		<main id="center" class="column">
			<article>
			
				<h1>Find Cheap beer near you!</h1>
				<p>Are you spending to much on beer? Are you looking to save money and time without cutting back on alcohol consumption? 
				If you answered yes to one of these questions, then you have come to the right place. Cheapbeerprices.com is a community that
				allows you to see how much others paid for a case of beer in you area. 
				</p>
				
				<center><div id="map"></div></center>
				
				<script>
// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.

function initMap() {
	var OleyBeerMart = {lat: 40.386098, lng: -75.794555};
	var AlsaceAles = {lat: 40.401534, lng: -75.857628};
	
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 12
  });
  //var infoWindow = new google.maps.InfoWindow({map: map});

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      map.setCenter(pos);
	//show oley beer mart on the map
	  var OleyBeerMarker = new google.maps.Marker({
    position: OleyBeerMart,
    map: map,
    title: 'Oley Beer Mart'
  });
	//show alsace ales
	var OleyBeerMarker = new google.maps.Marker({
    position: AlsaceAles,
    map: map,
    title: 'Alsace Ales'
  });
	  
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
}

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmv08kSZpkCuBwQEUsqqnxJ7m_ZBro_OQ&signed_in=true&callback=initMap"
        async defer>
    </script>
				
				<h1> 15 seconds could save you 15 percent or more! </h1>
				<p>It's amazing that two stores less than 5 minutes apart can have dramatic differences in prices. Our research indicates that prices can vary
				by 20% for the same case of beer in a single geographic location. Cheapbeerprices.com aims to guide you to find the cheapest cheapest beverages, so you can save money. </p>
				
				<h3> Let us know what you paid! </h3>
				<p> Beer buddy is a community that relies on people like you to share how much you paid for a case of brew.
				Let us know how much you paid for a case at a specific vendor, and we will publish it on our website.<p>
				<p> Email the location, price, and type of beer to:</p>
				<p> beerpriceindex@gmail.com </p>
			</article>								
		
		</main>

<?php 
	include 'footer.php';
?>
	