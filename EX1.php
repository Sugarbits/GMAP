<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
   <div id="foobar"></div>
   <div id="map"></div>
    <script>
   function add_button(val) {
      //Create an input type dynamically.   
     var element = document.createElement("input");
     //Assign different attributes to the element. 
     element.type = 'button';
     element.value = val; // Really? You want the default value to be the type string?
     //element.name = type; // And the name too?
     element.onclick = function() { // Note this is a function
     alert("blabla");
   };

  var foo = document.getElementById("foobar");
  //Append the element in page (in span).  
  foo.appendChild(element);
}
    </script>
    <script>
	var markers = [];
	var marker_old = null;
	 var map;
	 
	function renew(){
		$.getJSON( "crawler/cr_motc_bus.php", function( data ) {
		var items = [];
		$.each( data, function( key, val ) {
			//items.push( "<li id='" + key + "'>" + val + "</li>" );
			console.log(data);
			//add_button(data[key]['PlateNumb']);
			//if(markers_pro[data[key]['PlateNumb']] === undefined
			if(data[key]['PlateNumb']=="EAA-289"){
			/*
			console.log(data[key]);
			console.log(data[key]['BusPosition']['PositionLat']);
			console.log(data[key]['BusPosition']['PositionLon']);
			console.log(data[key]['GPSTime']);
			console.log(data[key]['PlateNumb']);
			*/
			//create gmap latlng obj
			tmpLatLng = {lat : data[key]['BusPosition']['PositionLat'],lng :　data[key]['BusPosition']['PositionLon']};
			tmptitle = {name:data[key]['PlateNumb'],time:data[key]['GPSTime']};
			
			// make and place map maker.
			if(marker_old == null){
				console.log('第一筆，新增資料!');
				var marker = add_marker(map,tmpLatLng,tmptitle);
				marker_old = marker;
				}else{
					if(compare_latlng(marker_old,data[key]['BusPosition']['PositionLat'],data[key]['BusPosition']['PositionLon'])){
						console.log('定位不相同，新增資料!');
						var marker = add_marker(map,tmpLatLng,tmptitle);
						marker_old.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
						marker_old.setOptions({'opacity': 0.2});
						marker_old = marker;
					}else{
						console.log('定位相同，無新資料');
					}
				}
				
			}
				
			});
		});
	}
		function add_marker(a_map,a_latlng,a_title){
			var marker = new google.maps.Marker({
				position: a_latlng,
				map: a_map,
				title : a_title['name'] + "\n" + a_title['time'],
				icon : ('http://maps.google.com/mapfiles/ms/icons/red-dot.png')
			});
			map.panTo(tmpLatLng);
			//bindInfoWindow(marker, map, infowindow, '<b>'+places[p].name + "</b><br>" + places[p].geo_name);
			// not currently used but good to keep track of markers
			//markers[data[key]['PlateNumb']].push(marker);
			//http://maps.google.com/mapfiles/ms/icons/blue-dot.png
			markers.push(marker);
			console.log(markers);
			return marker;
		}
      function initMap() {
        var myLatLng = {lat: 23.7, lng: 121.4};

          map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: myLatLng
        });
       /* var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });*/
		renew();
      }
	  function compare_latlng(omk,lat,lng){
		var olat = omk.getPosition().lat();
		var olng = omk.getPosition().lng();
		/*
		console.log(olat);
		console.log(lat);
		console.log(olng);
		console.log(lng);
		*/
		if(olat.toFixed(3) == lat.toFixed(3) && olng.toFixed(3) == lng.toFixed(3)){
			return false;//not moving
		}else{
			return true;//deed moving!
		}
		  
	  }
	  
	  setInterval("renew()",10000);
	  
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0bdKmBEMTJH7qsTjjG_1rfteVrNXzxQk&callback=initMap">
    </script>
  </body>
</html>