<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link type="text/css" rel="Stylesheet" href="main.css" />
	<link type="text/css" rel="Stylesheet" href="scrollbar.css" />
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
	<script>
	//v0.15
	//footer 點車牌觸發關閉->取消
	//footer 預設隱藏->改成顯示
	//footer 新增鎖定功能 鎖 定 ★/☆&#9734;
	var ttb_iframe = '';
	var firsttime = true;//to make history trace effect
	var btn_value;
	var btn_route_value;
	var btn_direction_value;
	var btn_marker;
	var arrive_msg;
	var map;//
	var markers = [];
	var stop_markers = [];
	var infos = [];
	var stop_infos = [];
	var car_no_filter = ["009-FV","010-FV","011-FV","075-FV","076-FV","EAA-283","EAA-285","EAA-286","EAA-287","EAA-288","EAA-289","EAA-290","EAA-291","EAA-293","EAA-295","EAA-296"]
	
	//站牌用圖
	var stop_icon_url = 'pic/station.png';
	//公車(現在位置)用圖
	//var now_icon_url = 'http://maps.google.com/mapfiles/ms/icons/bus.png';	
	var now_icon_url = 'pic/busicon.png';
	//公車(過去位置)用圖
	//var past_icon_url = 'http://maps.google.com/mapfiles/ms/icons/red.png';
	var past_icon_url = 'pic/reddot.png';
	
	var mark_icon = [stop_icon_url,now_icon_url,past_icon_url];
	//var mark_icon = ['pic/station.png','pic/busicon.png','pic/reddot.png'];
	</script>
  </head>
  <body>
   <!--標頭-->
   <div id="foobar"  class='toggle' style='display:block;'>
   
		<div id="foobar_left"></div>
		<div id="foobar_right">
		<div id='home' class='btn_func'>&nbsp鎖&nbsp定&nbsp<span id='lock'>&#9733;</span></div>
		<div id='close' class='btn_func'>&nbsp關&nbsp閉&nbsp</div>
		</div>
   </div>
   <!--地圖主體-->
   <div id="map"></div>
   <!--時刻表主體-->
   <div id="timetable">
   <!--<iframe id='ttb' frameborder="0" height='100%' marginwidth="0" marginheight="0" scrolling="auto" onload="" allowtransparency="false" src="../MOTC/show_stops_dynamic_part_ui.php?route=303&direct=0&citycode=HualienCounty" frameborder="0"></iframe></div>-->
   <iframe id='ttb' frameborder="0" height='100%' marginwidth="0" marginheight="0" scrolling="auto" onload="" allowtransparency="false" src="dummy.php" frameborder="0"></iframe>
   </div>
    <!--附屬資訊_介紹欄位-->
   <div id="footer0"></div>
   <div id="footer1" class="bigger">
  <!--<div class='box'>車號</div>-->
   <div class='box'>車速</div>
   <div class='box'>抵達時間</div>
   <div class='box'>經緯度</div>
   </div>
   <!--附屬資訊_內容-->
   <div id="footer2">
   <!--<div id='car_name' class='box bigger'></div>-->
   <div id='speed' class='box bigger'></div>
   <div id='arrive_time' class='box smaller'></div>
   <div id='latlng' class='box smaller'></div>
   </div>
    <script>//html js 互動 dom 版面
	
	  function runEffect() {
      // Run the effect
      $( ".toggle" ).toggle(100);
    };

	$('#home').on('click', function(){
		if(lock == false){
			//console.log($('#lock').html(''));
			$('#lock').html('★');
			lock = true;
		}else{
			$('#lock').html('☆');
			lock = false;
		}
		panto_muti_marker(markers);
	});
	$('#close').on('click', function(){
		$( ".toggle" ).hide(100,false); 
	});
	$('#map').on('click', function(){
	/*auto hide cancel
	  if(($('#foobar').is(":hidden")) == true){
		$( ".toggle" ).hide(100,false);  
	}*/
	;
	});
	$('#foobar').on('click', '.btn_group', function(){//泛用的按鈕觸發
		if($(this).hasClass( "inactive" )){
			return 0;
		}
		var data_val = $(this).attr('data-val');
		var data_route_value = $(this).attr('data-route-val');
		var data_direction_value = $(this).attr('data-direction-val');
		if(btn_value!==null){
			$(".btn_group").each(function() {
				$(this).removeClass('chose');
			});
			
		}
		$(this).addClass('chose');
		//setTimeout(function(){ runEffect(); }, 800);///auto hide cancel
		lock = true;
		toggle = false;
		btn_direction_value = data_direction_value;
		btn_route_value = data_route_value;
		btn_value = data_val;
		//時刻表切換
		var ttb_src = "../GMAP/show_stops_dynamic_part_ui.php?route="+btn_route_value+"&direct="+btn_direction_value+"&citycode=HualienCounty";
		console.log(ttb_src);
		ttb_iframe.src = ttb_src;
		//時刻表切換 END
		
		firsttime = true;
		clear_bus();
		clear_stop();
		renew();
		setTimeout(function(){ panto_muti_marker(markers); }, 800);
		//???
		
		//map.setZoom(14);

	});
    </script>
    <script>
	function time_to_word(sec){
		var m = Math.floor(sec/60,1);
		var s = sec%60;
		return m +'分'+ s + '秒';
	}
	function btn_group_css_render(val){//判定css
		if(val == -1){
			return 'btn_group inactive';
		}
		if(btn_value==val){
			return 'btn_group chose';
		}else{
			return 'btn_group active';
		}
	}
	/*
	function btn_css_render(val){//判定css
	function btn_type_css_render(val){//判定css//btn group用
	皆刪除，只需最外層變色即可
	*/
	function renew(){
		if(firsttime == false){//非第一次撈
			clear_bus();//清除上一次資料
			bus_ajax();
			if(lock == true){
				;
			}
		}else{//第一次撈
		//console.log(markers);
		stop_ajax();
		bus_ajax();
		setTimeout(function(){ panto_muti_marker(markers); }, 800);//延遲800ms才能讀到 markers ？？待解決
			firsttime = false;
		}
	}
	function stop_ajax(){
		console.log('車站抓值');
		if(btn_route_value == undefined){
			console.log('未選擇路線，取消動作');
		}
		else{
		
		//btn_route_value = '301';
		//btn_direction_value = '0';
		//console.log("crawler/outter_motc_bus_stop.php?citycode=HualienCounty&route="+btn_route_value+"&direction="+btn_direction_value);
		
		$.getJSON( "crawler/outter_motc_bus_stop.php?citycode=HualienCounty&route="+btn_route_value+"&direction="+btn_direction_value, function( data ) {
			$.each( data, function( key, val ) {
				for(key2 in val['Stops']){
					//console.log(val['Stops'][key2]);
					var tmpLatLng = {lat : val['Stops'][key2]['StopPosition']['PositionLat'],lng :　val['Stops'][key2]['StopPosition']['PositionLon']};//google map latlng obj 
					var tmptitle = val['Stops'][key2]['StopName']['Zh_tw'];//google map marker.title 
					var tmpcontent = "" + val['Stops'][key2]['StopName']['Zh_tw']; //站名
					//console.log(tmptitle);
					var marker = add_marker(map,tmpLatLng,tmptitle, 0);
					var info = add_info(map,tmpLatLng,tmpcontent);
					info.close();
					/*marker.addListener('click', function() {
						info.open(map, marker);
					});*/
					stop_markers.push(marker);
					stop_infos.push(info);					
				}
				
			});
		});
		}
	}
	function refresh_footer(speed,car_no,lat,lon){
			$('#speed').html(speed+"KM");
			$('#car_name').html(car_no);
			$('#latlng').html( lat+'<br>'+lon);
			setTimeout(function(){
				$('#arrive_time').html('抵達'+arrive_msg['stopname']+'\n預計時間：'+time_to_word(arrive_msg['time']));
			}, 800);
	}

	function bus_arrive_ajax(){
		var arrive_msg_obj={'time':'','stopname':''};
		/*
		btn_direction_value;//direction
		btn_route_value;//route
		btn_value;//platenumb
		*/
		console.log('到站抓值');
		console.log("crawler/outter_motc_bus_arrive.php?citycode=HualienCounty&platenumb="+btn_value+"&route="+btn_route_value+"&direction="+btn_direction_value);
		var promise
		$.getJSON( "crawler/outter_motc_bus_arrive.php?citycode=HualienCounty&platenumb="+btn_value+"&route="+btn_route_value+"&direction="+btn_direction_value, function( data ) {
			$.each( data, function( key, val ) {
				//console.log(val['EstimateTime']);
				arrive_msg_obj['time'] = val['EstimateTime'];
				arrive_msg_obj['stopname'] = val['StopName']['Zh_tw'];
				return false; 
			});
		});
		return arrive_msg_obj;

		//btn_route_value = '301';
		//btn_direction_value = '0';
		//console.log("crawler/outter_motc_bus_stop.php?citycode=HualienCounty&route="+btn_route_value+"&direction="+btn_direction_value);
		
		//return '抵達'+arrive_msg_obj['stopname']+'\n預計時間：'+time_to_word(arrive_msg_obj['time']);
	}
	function bus_ajax(){//ajax抓值
		console.log('公車抓值');
		/*var data_obj={
			id:[],
			outer_class:[],
			inner_btn_class:[],
			inner_btn_type_class:[],
			inner_btn_type_val:{
				direction_word:[]	
			},			
			outer_val:{
				direction:[],	
				route:[],	
				num:[]	
			}
		};*/
		$.getJSON( "crawler/cr_motc_bus.php", function( data ) {
				$.each( data, function( key, val ) {
				//console.log(data);
				var car_route = data[key]['RouteName']['En'];//路名
				var car_direction = data[key]['Direction'];//方向
				var tmpdirect =(car_direction == '1') ? '去' : '返';
				var car_no = data[key]['PlateNumb'];//頻繁使用車號
				var tmpcontent =car_no;
				var car_no_index = car_no_filter.indexOf(car_no);
				if(car_no_index==-1){//過濾不是本車隊的車號(放在car_no_filter)，
				//REF:http://www.victsao.com/blog/81-javascript/159-javascript-arr-indexof
					return;
				}
				//data[key]['PlateNumb']

				/*todo*/
				$("#_"+car_no_index).attr('data-direction-val',car_direction);
				$("#_"+car_no_index).attr('data-route-val',car_route);
				$("#_"+car_no_index).attr('data-val',car_no);
				$("#_"+car_no_index).removeClass('chose inactive');
				$("#_"+car_no_index).addClass(btn_group_css_render(car_no));//
				//$("#_"+car_no_index+" > :nth-child(1)").removeClass('chose inactive');
				//$("#_"+car_no_index+" > :nth-child(1)").addClass(btn_css_render(car_no));
				$("#_"+car_no_index+" > :nth-child(2)").html(car_route+'('+tmpdirect+')');
				//$("#_"+car_no_index+" > :nth-child(2)").removeClass('chose inactive');
				//$("#_"+car_no_index+" > :nth-child(2)").addClass(btn_type_css_render(car_no));
				/*
				$( "#foobar_left" ).append( "<div id='_"+key+"' class='"+btn_group_css_render(car_no)+"' data-direction-val='"+car_direction+"' data-route-val='"+car_route+"' data-val='"+car_no+"'></div>" );//按鈕生成,觸發自訂義
				$( "#_"+key+"" ).append( "<div class='"+btn_css_render(car_no)+"'>&nbsp;"+car_no+""+tmpdirect+"</div>" );//按鈕生成,觸發自訂義
				$( "#_"+key+"" ).append( "<div class='"+btn_type_css_render(car_no)+"'>&nbsp;"+car_route+"</div>" );//按鈕生成,觸發自訂義
				*/
				
				if(car_no == btn_value){
				
				tmpLatLng = {lat : data[key]['BusPosition']['PositionLat'],lng :　data[key]['BusPosition']['PositionLon']};//google map latlng obj 
				//var tmptitle = {name:data[key]['PlateNumb'],time:data[key]['GPSTime']};//google map marker.title 
				//name:data[key]['PlateNumb'],time:data[key]['GPSTime']
				var tmptitle = data[key]['PlateNumb'];//google map marker.title 
				//var tmpcontent = "時速: " + data[key]['Speed'] +"km"+  '<br></h3>' + "車號" + car_no; 
				
				//給附屬資訊_內容
				//$('#speed').html(data[key]['UpdateTime']);
				//setTimeout(function(){
					refresh_footer(data[key]['Speed'],car_no,data[key]['BusPosition']['PositionLat'],data[key]['BusPosition']['PositionLon']) 
				//}, 800);//延遲800ms才能讀到 markers ？？待解決
				
				var marker = add_marker(map,tmpLatLng,tmptitle,1);
				if(lock==true){
					panto_single_marker(marker);	
				}
				arrive_msg = bus_arrive_ajax();
							
				//var tmpcontent = '抵達'+arrive_msg_obj['stopname']+'\n預計時間：'+time_to_word(arrive_msg_obj['time']);
				
			
				/*marker.infowindow = new google.maps.InfoWindow(
				{
					content: tmpcontent
				});*/
				markers.push(marker);
				//info shows num
				var info = add_info(map,tmpLatLng,tmpcontent);
				infos.push(info);

				}else{
					;//非選中車號，不動作
				}
				
			});//$.each END
			
		});
	}
		function clear_bus(){
			for(index in markers){
				markers[index].setMap(null);
				infos[index].setMap(null);
			}
			infos = [];
			markers = [];
			console.log('消除公車');
		}
		function clear_stop(){
			for(index in stop_markers){
				stop_markers[index].setMap(null);
				stop_infos[index].setMap(null);
			}
			stop_markers = [];
			stop_infos = [];
			console.log('消除車站');
		}
		function footer_initail(bus_list){
			//footer_initail(car_no_filter)
			//btn_group_css_render/btn_css_render/btn_type_css_render
			for(key in bus_list){
				$( "#foobar_left" ).append( "<div id='_"+key+"' class='"+btn_group_css_render(-1)+"' data-direction-val='' data-route-val='' data-val=''></div>" );//按鈕生成,觸發自訂義
				$( "#_"+key+"" ).append( "<div class='btn_self'>&nbsp;"+bus_list[key]+"</div>" );//按鈕生成,觸發自訂義
				$( "#_"+key+"" ).append( "<div class='btn_type'>&nbsp;</div>" );//按鈕生成,觸發自訂義
			}
		}
		function panto_single_marker(pmarker){//single point panTo
			var point = new google.maps.LatLng(
				parseFloat(pmarker.getPosition().lat()),
				parseFloat(pmarker.getPosition().lng()));
		   // myLatLng = new google.maps.LatLng(lat, lon);
			 map.panTo(point);
			 map.setZoom(18);
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
				console.log('沒有公車！');
			}
		}

		function add_info(a_map,a_latlng,a_content){
			var infowindow = new google.maps.InfoWindow({                
				disableAutoPan: true,
				map: a_map,
				position: a_latlng,
				pixelOffset: new google.maps.Size(0,-25),
				content: a_content
			});
			return infowindow;
		}
		function add_marker(a_map,a_latlng,a_title,a_icon_id){
			//alert(a_icon_id);
			var marker = new google.maps.Marker({
				position: a_latlng,
				map: a_map,
				title : a_title,
				//icon : 'pic/station.png'
				icon : (mark_icon[a_icon_id])
			});
			 
			//map.panTo(tmpLatLng);
			//bindInfoWindow(marker, map, infowindow, '<b>'+places[p].name + "</b><br>" + places[p].geo_name);
			// not currently used but good to keep track of markers
			//markers[data[key]['PlateNumb']].push(marker);
			//http://maps.google.com/mapfiles/ms/icons/blue-dot.png
			//console.log(markers);
			return marker;
		}
      function initMap() {

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
		//
		//
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
		//console.log(olat);
		//console.log(lat);
		//console.log(olng);
		//console.log(lng);
		if(olat.toFixed(3) == lat.toFixed(3) && olng.toFixed(3) == lng.toFixed(3)){
			return false;//not moving
		}else{
			return true;//deed moving!
		}
		  
	 }
	  //https://stackoverflow.com/questions/26453741/how-do-i-add-multiple-overlay-markers-in-google-maps-api
	  
	  function HTML_marker_initial(){
	  ///	  HTML_marker_initail
	  	  function HTMLMarker(lat, lng) {
        this.lat = lat;
        this.lng = lng;
        this.pos = new google.maps.LatLng(lat, lng);
    }

    HTMLMarker.prototype = new google.maps.OverlayView();
    HTMLMarker.prototype.onRemove = function () {}

    //init your html element here
    HTMLMarker.prototype.onAdd = function () {
        div = document.createElement('DIV');
        div.style.position='absolute';
        div.className = "htmlMarker";
        div.innerHTML = "<img src='"+now_icon_url+"' alt='Mountain View' style='width:60px;height:60px'>"+'車速：20km<br>'+'車號：222-NNN';
        var panes = this.getPanes();
        panes.overlayImage.appendChild(div);
        this.div=div;
    }

    HTMLMarker.prototype.draw = function () {
        var overlayProjection = this.getProjection();
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);
        var panes = this.getPanes();
        this.div.style.left = position.x + 'px';
        this.div.style.top = position.y - 30 + 'px';
    }
	/*
	//to use it
    var htmlMarker = new HTMLMarker(52.323907, -150.109291);
    htmlMarker.setMap(map);
    var htmlMarker = new HTMLMarker(52.323907, -151.109291);
    htmlMarker.setMap(map);
	*/
}
	  ////
	  $(function(){
		$(window).on('load',function(){
		HTML_marker_initial();
		footer_initail(car_no_filter);
		ttb_iframe = document.getElementById('ttb');
			renew();
         setInterval("renew()",10000);//Here is my logic now
		});
	});
	
	  
    </script>
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0bdKmBEMTJH7qsTjjG_1rfteVrNXzxQk&callback=initMap"></script>
  </body>
</html>