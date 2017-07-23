<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link type="text/css" rel="Stylesheet" href="EX4.css" />
	
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>

    <meta charset="utf-8">
    <title>Simple markers</title>
	<script>
	var firsttime = true;//to make history trace effect
	var btn_value;
	var map;//
	var markers = [];
	var infos = [];
	//現在的位置用圖
	//var now_icon_url = 'http://maps.google.com/mapfiles/ms/icons/bus.png';	
	var now_icon_url = 'pic/busicon.png';
	//過去的位置用圖
	//var past_icon_url = 'http://maps.google.com/mapfiles/ms/icons/red.png';
	var past_icon_url = 'pic/reddot.png';
	</script>
  </head>
  <body>
   <!--標頭-->
   <div id="foobar"  class='toggle' style='display:none;'>
   
		<div id="foobar_left">123</div>
		<div id="foobar_right">
		<div id='home' class='side'>&nbsp首&nbsp頁&nbsp</div>
		<div id='close' class='side'>&nbsp關&nbsp閉&nbsp</div>
		</div>
   </div>

   <!--地圖主體-->
   <div id="map"></div>
   <!--附屬資訊_介紹欄位-->
    <script>//html js 互動 dom 版面
	
	  function runEffect() {
      // Run the effect
      $( ".toggle" ).toggle(100);
    };

	$('#home').on('click', function(){
		panto_muti_marker(markers);
	});
	$('#close').on('click', function(){
		$( ".toggle" ).hide(100,false); 
	});
	$('#map').on('click', function(){
		console.log($('#foobar').is(":hidden"));
	  if(($('#foobar').is(":hidden")) == true){
		$( ".toggle" ).hide(100,false);  
	  }
	});
	$('#foobar').on('click', '.btn', function(){//泛用的按鈕觸發
		var data_val = $(this).attr('data-val');
		if(btn_value!==null){
			$(".btn").each(function() {
				$(this).removeClass('chose');
			});
			
		}
		$(this).addClass('chose');
		setTimeout(function(){ runEffect(); }, 800);
		toggle = false;
		btn_value = data_val;
		initail();
		renew();
		console.log(data_val);
	});
    </script>
    <script>
	function btn_css_render(val){//判定css
		if(btn_value==val){
			return 'btn chose';
		}else{
			return 'btn';
		}
	}
	function renew(){//ajax抓值
		$.getJSON( "crawler/cr_motc_bus.php", function( data ) {
		$( "#foobar_left" ).html('');
				if(firsttime == true){//第一次撈
					firsttime = false;
					}else{	
					initail();//清除上一次資料
				}
				$.each( data, function( key, val ) {
				//console.log(data);
				var car_no = data[key]['PlateNumb'];//頻繁使用車號
				//data[key]['PlateNumb']
				$( "#foobar_left" ).append( "<div class='"+btn_css_render(car_no)+"' data-val='"+car_no+"'>&nbsp;&nbsp;"+car_no+"</div>" );//按鈕生成,觸發自訂義
				//add_button(data[key]['PlateNumb']);
				/*
				console.log(data[key]);
				console.log(data[key]['BusPosition']['PositionLat']);
				console.log(data[key]['BusPosition']['PositionLon']);
				console.log(data[key]['GPSTime']);
				console.log(data[key]['PlateNumb']);
				*/
				//create gmap latlng obj
				tmpLatLng = {lat : data[key]['BusPosition']['PositionLat'],lng :　data[key]['BusPosition']['PositionLon']};//google map latlng obj 
				var tmptitle = {name:data[key]['PlateNumb'],time:data[key]['GPSTime']};//google map marker.title 
				var tmpcontent = "時速: " + data[key]['Speed'] +"km"+  '<br></h3>' + "車號" + car_no; 
				
				//給附屬資訊_內容
				//$('#speed').html(data[key]['UpdateTime']);
				$('#speed').html(data[key]['Speed']+"KM");
				$('#car_name').html(car_no);
				$('#latlng').html( data[key]['BusPosition']['PositionLat']+'<br>'+data[key]['BusPosition']['PositionLon']);
				var marker = add_marker(map,tmpLatLng,tmptitle,tmpcontent);
				var info = add_info(map,tmpLatLng,tmpcontent);
				marker.infowindow = new google.maps.InfoWindow(
				{
					content: tmpcontent
				});
				markers.push(marker);
				infos.push(info);
				//
			});//$.each END
		});
	}
		function initail(){
			for(index in markers){
				markers[index].setMap(null);
			}
			for(index in markers){
				infos[index].setMap(null);
			}
			infos = [];
			markers = [];
			console.log('消除');
			console.log(markers);
		}
		function panto_muti_marker(pmarkers){
			if(pmarkers.length != 0){
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < pmarkers.length; i++) {
				var point = new google.maps.LatLng(
					parseFloat(pmarkers[i].getPosition().lat()),
					parseFloat(pmarkers[i].getPosition().lng()));
					// add each marker's location to the bounds
					bounds.extend(point);
			}
			map.fitBounds(bounds);
			}else{
				alert('沒有公車！');
			}
		}
		function add_info(a_map,a_latlng,a_content){
			var infowindow = new google.maps.InfoWindow({                
				map: a_map,
				position: a_latlng,
				pixelOffset: new google.maps.Size(0,-25),
				content: a_content
			});
			return infowindow;
		}
		function add_marker(a_map,a_latlng,a_title){
			var marker = new google.maps.Marker({
				position: a_latlng,
				map: a_map,
				title : a_title['name'] + "\n" + a_title['time'],
				icon : (now_icon_url)
			});
			 
			//map.panTo(tmpLatLng);
			//bindInfoWindow(marker, map, infowindow, '<b>'+places[p].name + "</b><br>" + places[p].geo_name);
			// not currently used but good to keep track of markers
			//markers[data[key]['PlateNumb']].push(marker);
			//http://maps.google.com/mapfiles/ms/icons/blue-dot.png
			console.log(markers);
			return marker;
		}
      function initMap() {
       /* var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });*/
		
		 function CenterControl(controlDiv, map) {

        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '22px';
        controlUI.style.textAlign = 'left';
        controlUI.title = 'Click to recenter the map';
        controlDiv.appendChild(controlUI);

        // Set CSS for the control interior.
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(25,25,25)';
        controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlText.style.fontSize = '16px';
        controlText.style.lineHeight = '38px';
        controlText.style.paddingLeft = '5px';
        controlText.style.paddingRight = '5px';
        controlText.innerHTML = '...';
        controlUI.appendChild(controlText);

        // Setup the click event listeners: simply set the map to Chicago.
		 map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);
        controlUI.addEventListener('click', function() {
		runEffect();
        //map.setCenter(chicago);
        });

      }		
		function CenterControl2(controlDiv, map) {

        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '22px';
        controlUI.style.textAlign = 'left';
        controlUI.title = 'Click to recenter the map';
        controlDiv.appendChild(controlUI);

        // Set CSS for the control interior.
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(25,25,25)';
        controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlText.style.fontSize = '16px';
        controlText.style.lineHeight = '38px';
        controlText.style.paddingLeft = '5px';
        controlText.style.paddingRight = '5px';
        controlText.innerHTML = 'home';
        controlUI.appendChild(controlText);

        // Setup the click event listeners: simply set the map to Chicago.
		 map.controls[google.maps.ControlPosition.TOP_RIGHT ].push(centerControlDiv);
        controlUI.addEventListener('click', function() {
		panto_muti_marker(markers);
        //map.setCenter(chicago);
        });

      }
	    var myLatLng = {lat: 23.7, lng: 121.4};

        map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: myLatLng
        });
		// Create the DIV to hold the control and call the CenterControl()
        // constructor passing in this DIV.
		

        var centerControlDiv = document.createElement('div');
		centerControlDiv.index = 1; 
		var centerControl = new CenterControl(centerControlDiv, map);

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