<?php
$jsword = "<script> var _route = ".$_GET['route'].";";
$jsword .= "var _direct = ".$_GET['direct'].";</script>";
echo $jsword;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<!--<link type="text/css" rel="Stylesheet" href="EX5.css" />-->
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
	<style>
	td{
		font-size:150%;
	}
	</style>
  </head>
  <body>
  <div id='main'></div>
   <!--地圖主體-->
   <!--<div id="map"></div>-->
   <!--附屬資訊_介紹欄位-->
   <script>
	function wrap_td(str){
		return '<td>'+str+'</td>';
	}
	function wrap_tr(str){
		return '<tr>'+str+'</tr>';
	}
	function wrap_tb(str){
		return '<table border=1>'+str+'</table>';
	}
	function renew(){//ajax抓值
		var cnt = 0;
		var cnt_total = 0;
		
		var context = '';//stop name
		var context2 = '';//stop num
		var context22 = '';//stop UID
		var context3 = '';//total
		
		//

		$.getJSON( "crawler/motc_bus_stop.php?route="+_route+"&direct="+_direct+"", function( data ) {
			console.log(data);
			cnt = 0;
			for(key in data){
				for(keysu in data[key]['Stops']){
					cnt_total++;
					cnt++;
					var StopName = data[key]['Stops'][keysu]['StopName']['Zh_tw'];
					var StopIndex = data[key]['Stops'][keysu]['StopSequence'];
					var StopUID = data[key]['Stops'][keysu]['StopUID'];
					context = wrap_td(StopName);
					context2 = wrap_td(StopIndex);	
					context22 = wrap_td(StopUID);	
					context3 += wrap_tr(context2+context/*+context22*/);
				}
			}
				$('#main').html(wrap_tb(context3));
			});
	}
	////
	  $(function(){
		 renew();
	});
	
	  
    </script>
  </body>
</html>