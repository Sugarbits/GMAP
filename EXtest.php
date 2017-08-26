<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link type="text/css" rel="Stylesheet" href="EX5.css" />
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
  </head>
  <body>
  <div id='main'></div>
   <!--地圖主體-->
   <!--<div id="map"></div>-->
   <!--附屬資訊_介紹欄位-->
   <script>

	function renew(){//ajax抓值
		var cnt = 0;
		var cnt_total = 0;
		
		var context = '';
		
		var bus_status = '';//bind with StopUID
		var stop_no = '';
		var stop_name = '';
		//
		var bus_status_o = '';
		var bus_status_e = '';
		//
		var stop_no_o = '';
		var stop_no_e = '';
		//
		var stop_name_o = '';
		var stop_name_e = '';

		$.getJSON( "crawler/cr_motc_bus_test.php?index=0", function( data ) {
			console.log(data);
			for(key in data){
				cnt = 0;
				for(key2 in data[key]){
				//(key2 == 'Stops') ? console.log(data[key][key2]) : '' ;//找到所有Stops資訊
					if(key2 == 'Stops'){
						for(key_su in data[key][key2]){//su=StopUint
							var StopName = data[key][key2][key_su]['StopName']['Zh_tw'];
							var StopUid = data[key][key2][key_su]['StopUID'];
							cnt_total++;
							cnt++;
							console.log(StopName+'|'+cnt);
							}
						}
					}
				}
				$('#main').html(context);
			});
	}
	
	function renew2(){//ajax抓值
		var cnt = 0;
		var cnt_total = 0;
		
		var context = '';
		
		var bus_status = '';//bind with StopUID
		var stop_no = '';
		var stop_name = '';
		//
		var bus_status_o = '';
		var bus_status_e = '';
		//
		var stop_no_o = '';
		var stop_no_e = '';
		//
		var stop_name_o = '';
		var stop_name_e = '';

		$.getJSON( "crawler/cr_motc_bus_test.php?index=1", function( data ) {
			console.log(data);
			for(key in data){
							cnt_total++;
							cnt++;
							var StopName = data[key]['StopName']['Zh_tw'];
							var PlateNumb = data[key]['PlateNumb'];
							var UpdateTime = data[key]['UpdateTime'];
							//var StopUid = data[key][key_su]['StopUID'];
							console.log(PlateNumb+'\n'+UpdateTime+'\n'+StopName+'|'+cnt);
							}
				$('#main').html(context);
			
			});
	}
	
	  ////
	  $(function(){
		 renew2();
	});
	
	  
    </script>
  </body>
</html>