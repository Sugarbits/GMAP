<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link type="text/css" rel="Stylesheet" href="EX2.css" />
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
	<script>
	var btn_value = null ;//for saving value
	var markers = [];//save all markers
	var marker_old = null;//to make history trace effect
	var map;//
	//var now_icon_url = 'http://maps.google.com/mapfiles/ms/icons/bus.png';
	var now_icon_url = 'pic/bus-1.png';
	//var past_icon_url = 'http://maps.google.com/mapfiles/ms/icons/red.png';
	var past_icon_url = 'pic/dot.png';
	</script>
  </head>
  <body>
   <div id="foobar"></div>
   <div id="map"></div>
   <div id="footer1">
   <div class='box bigger'>車號</div>
   <div class='box bigger'>更新時間</div>
   <div class='box bigger'>經緯度</div>
   </div>
   <div id="footer2">
   <div id='car_name' class='box'></div>
   <div id='renew_time' class='box'></div>
   <div id='latlng' class='box'></div>
   </div>
    <script>
	$('#foobar').on('click', '.btn', function(){
		var data_val = $(this).attr('data-val');
		if(btn_value!==null){
			$(".btn").each(function() {
				$(this).removeClass('chose');
			});
			
		}
		$(this).addClass('chose');
		btn_value = data_val;
		initail();
		renew();
		console.log(data_val);
	});
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
	function btn_css_render(val){
		if(btn_value==val){
			return 'btn chose';
		}else{
			return 'btn';
		}
	}
	function renew(){
		$.getJSON( "crawler/cr_motc_bus.php", function( data ) {
		$( "#foobar" ).html('');
		var items = [];
		$.each( data, function( key, val ) {
			//items.push( "<li id='" + key + "'>" + val + "</li>" );
			console.log(data);
			var car_no = data[key]['PlateNumb']
			//data[key]['PlateNumb']
			//$( "#foobar" ).append( "<div class='btn'>".123."</div>" );
				$( "#foobar" ).append( "<div class='"+btn_css_render(car_no)+"' data-val='"+car_no+"'>&nbsp;&nbsp;"+car_no+"</div>" );
			//add_button(data[key]['PlateNumb']);
			//if(markers_pro[data[key]['PlateNumb']] === undefined
			if(car_no==btn_value){
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
			$('#renew_time').html(data[key]['UpdateTime']);
			$('#car_name').html(car_no);
			$('#latlng').html( data[key]['BusPosition']['PositionLat']+'<br>'+data[key]['BusPosition']['PositionLon']);
			
			// make and place map maker.
			if(marker_old == null){
				console.log('第一筆，新增資料!');
				var marker = add_marker(map,tmpLatLng,tmptitle);
				markers.push(marker);
				marker_old = marker;
				}else{
					if(compare_latlng(marker_old,data[key]['BusPosition']['PositionLat'],data[key]['BusPosition']['PositionLon'])){
						console.log('定位不相同，新增資料!');
						var marker = add_marker(map,tmpLatLng,tmptitle);
						marker_old.setIcon(past_icon_url);
						marker_old.setOptions({'opacity': 1});
						marker_old = marker;
						//marker_old.setMap(null);
					}else{
						console.log('定位相同，無新資料');
					}
				}
				
			}
				
			});
		});
	}
		function initail(){
			for(index in markers){
				markers[index].setMap(null);
			}
			markers = [];
			marker_old = null;
		}
		function add_marker(a_map,a_latlng,a_title){
			var marker = new google.maps.Marker({
				position: a_latlng,
				map: a_map,
				title : a_title['name'] + "\n" + a_title['time'],
				icon : (now_icon_url)
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
		
		console.log(olat);
		console.log(lat);
		console.log(olng);
		console.log(lng);
		
		if(olat.toFixed(3) == lat.toFixed(3) && olng.toFixed(3) == lng.toFixed(3)){
			return false;//not moving
		}else{
			return true;//deed moving!
		}
		  
	  }
	  
	  setInterval("renew()",10000);
	  
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0bdKmBEMTJH7qsTjjG_1rfteVrNXzxQk&callback=initMap">
    </script>
  </body>
</html>