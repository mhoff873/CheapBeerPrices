	function initMap() {
	  var map = new google.maps.Map(document.createElement('div'));
	  var infowindow = new google.maps.InfoWindow();
	  var service = new google.maps.places.PlacesService(map);
	  var vendorPlaceID={placeId: 'ChIJC7oQaSceyYkRWNjrs7K2c44'};//MUST PULL ALL FROM LOCATION
	  service.getDetails(vendorPlaceID, 
	   function(place, status) {
	    if (status === google.maps.places.PlacesServiceStatus.OK) {
	       //alert("no shit, it's "+place.name);
	       var mapDiv = document.getElementById('map');
	       var contentString = '<div id="content">'+
	       '<h1 id="firstHeading" class="firstHeading">'+place.name+'</h1>'+
	       '<form><a href="storeSummary.php" style="color:black;text-decoration:none;">'+
	       '<table style="width:100%;border-style:solid;border-width:thin;text-align:center;">'+
	       '<tr>'+
	         '<th scope="col" style="width:10%;">Open?</th>'+
		 '<th scope="col" style="width:15%">Hours Today</th>'+
		 '<th scope="col" style="width:15%">Phone Number</th>'+
		 '<th scope="col" style="width:20%">Address</th>'+
		 '<th scope="col" style="width:20%">Website</th>'+
	       '</tr><tr>'+
	         '<td style="width:10%;">'+place.opening_hours.open_now+'</td>'+
	         '<td style="width:15%">'+place.opening_hours.periods[3].open.time+' - '+place.opening_hours.periods[3].close.time+'</td>'+
	         '<td style="width:15%">'+place.international_phone_number+'</td>'+
	         '<td style="width:20%">'+place.vicinity+'</td>'+
	         '<td style="width:20%">'+place.website+'</td>'+
	       '<tr></table></a></form>'+
	      '</div>';
	      mapDiv.innerHTML = contentString;
	    }else{
	      alert("shit");
	    }
	  }); //END OF GETDETAILS
	}
	function callback(results, status) {
	  if (status === google.maps.places.PlacesServiceStatus.OK) {
	    for (var i = 0; i < results.length; i++) {
	      createMarker(results[i]);
	    }
	  }
	}