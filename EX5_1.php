<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link type="text/css" rel="Stylesheet" href="EX5_1.css" />
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
	var icon_div = {
		line:'<div class="m fill relative"></div>',
		stop:'<div class="stopIconEnd"></div>',
		stopR:'<div class="stopIconR"></div>',
		stopL:'<div class="stopIconL"></div>'
	};
	function padLeft(str,lenght){
		var str = str.toString();
		console.log(str.length +':'+ lenght+'|'+str);
		if(str.length >= lenght)
			return str;
		else
			return padLeft("0" +str,lenght);
	}
	function wrap_td_with_id(str,id){
		return "<td id='"+id+"'>"+str+'</td>';
	}
	function wrap_td(str){
		return '<td>'+str+'</td>';
	}
	function wrap_tr(str){
		return '<tr>'+str+'</tr>';
	}
	function wrap_tb(str){
		return '<table border=1>'+str+'</table>';
	}
	function put_data_on(uid,car_no,time){
		console.log("#"+uid);
		//$('#main #TPE30123').html('XXX');
		if ($("#"+uid).length ) {
			$("#"+uid).html(car_no+' 還有 <br>'+time+' 秒到站');
			$("#"+uid).removeClass('fill_empty');
			$("#"+uid).addClass('fill_sth');
		}else{
			$("#"+uid).removeClass('fill_sth');
			$("#"+uid).addClass('fill_empty');
		}
	}
	function renew2(){
			$.getJSON( "crawler/cr_motc_bus_arrive_stop.php", function( data ) {
				for(key in data){
						//console.log(data);
						var uid = '';
						var car_no = '車號(???)';
						var time = -1;
						for(key2 in data[key]){
							if(key2 == 'StopUID'){
								//console.log(data[key][key2]);
								uid = data[key][key2];
							}else if(key2 == 'EstimateTime'){
								//console.log(data[key][key2]);
								time = data[key][key2];
							}/*else if(key2 == 'StopUID'){
							
							}*/else{
								;//...
							}
						}
						if(time !== -1){
							put_data_on(uid,car_no,time);
						}
					}
				});
			}
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

		$.getJSON( "crawler/cr_motc_bus_stop.php", function( data ) {
			console.log(data);
			for(key in data){
					if(key>0){//只挑第一個
						break;
					}
			for(key2 in data[key]){
				//(key2 == 'Stops') ? console.log(data[key][key2]) : '' ;//找到所有Stops資訊
				if(key2 == 'Stops'){
					for(key_su in data[key][key2]){//su=StopUint
							var StopName = data[key][key2][key_su]['StopName']['Zh_tw'];
							var StopUid = data[key][key2][key_su]['StopUID'];
							//stop_no += wrap_td(cnt_total);
							if(cnt<=7 && cnt>=0){//include start and even and end
								if(cnt_total == (data[key][key2].length-1)){//end
									sign = ' | ';
								}else{
									sign = ' → ';	
								}
								bus_status += wrap_td_with_id('&nbsp',StopUid);
								stop_name += wrap_td(StopName);
								stop_no += wrap_td(cnt_total+sign);
							}else if(cnt<=14 && cnt>=8){//include odd and end
								if(cnt_total == (data[key][key2].length-1)){//end
									sign = ' | ';
								}else{
									sign = ' ← ';
								}
								bus_status = wrap_td_with_id('&nbsp',StopUid)+bus_status;
								stop_name = wrap_td(StopName)+stop_name;
								stop_no = wrap_td(cnt_total+sign)+stop_no;
							}					
							if(cnt==7){
								if(cnt_total==7){
									var more_blank = '';
								}else{
									var more_blank = wrap_td('');
								}
								bus_status = wrap_tr(more_blank+bus_status);
								stop_name = wrap_tr(more_blank+stop_name);
								stop_no = wrap_tr(more_blank+stop_no);
								context += bus_status+stop_no+stop_name;
								bus_status = '';
								stop_name = '';
								stop_no = '';
							}else if(cnt==14){
								var more_blank = wrap_td('');
								bus_status = wrap_tr(more_blank+bus_status);
								stop_name = wrap_tr(more_blank+stop_name);
								stop_no = wrap_tr(more_blank+stop_no);
								context += bus_status+stop_no+stop_name;
								cnt = 0;
								bus_status = '';
								stop_name = '';
								stop_no = '';
							}else if(cnt_total == (data[key][key2].length-1)){//end
								var more_blank = '';
								var blank_num = (14-cnt);//此列中缺幾塊，補上
								for(var i=0;i<=blank_num;i++){
									more_blank += wrap_td('');
								}
								bus_status = wrap_tr(more_blank+bus_status);
								stop_name = wrap_tr(more_blank+stop_name);
								stop_no = wrap_tr(more_blank+stop_no);
								context += bus_status+stop_no+stop_name;
								cnt = 0;
								bus_status = '';
								stop_name = '';
								stop_no = '';
							}
							
							/*if(cnt_total == 7){
								break;
							}*/
							cnt_total++;
							cnt++;
							console.log(StopName);
							}
						}
					}
				}
				context = wrap_tb(context);
				$('#main').html(context);
				renew2();
			});
	}
	/*
	function renew(){//ajax抓值
		$.getJSON( "crawler/cr_motc_bus_stop.php", function( data ) {
				//$.each( data, function( key, val ) {
				var txt = '';
				console.log(data);
				for(key in data){
					if(key>0){//只挑第一個
						break;
					}
					for(key2 in data[key]){
						//(key2 == 'Stops') ? console.log(data[key][key2]) : '' ;//找到所有Stops資訊
						if(key2 == 'Stops'){
							var cnt =  0;
							var cnt_total =  0;
							var odd_text ='';
							var even_text ='';
							for(key_su in data[key][key2]){//su=StopUint
								var StopName = data[key][key2][key_su]['StopName']['Zh_tw'];
								cnt_total++;
									if(cnt_total == 1){//第一個
										//document.write('1st<br>');
										txt += '|('+padLeft(cnt_total,2)+')';
									}else if(key_su == (data[key][key2].length-1)){//最後一個
										//document.write('last<br>');
										if(Math.floor(cnt/7)==0){
											sign = ' → ';
											txt += sign+'('+cnt_total+'['+cnt+']|)';
										}else if(Math.floor(cnt/7)==1){
											if(cnt%14==0){//往左生第八個
												sign = ' ← ';
												//odd_text = '+|('+cnt_total+'['+cnt+'])'+')'+sign+odd_text;		
												odd_text = '+|('+padLeft(cnt_total,2)+')'+sign+odd_text;		
											}else{//一般的左方
												sign = ' ← ';
												//odd_text = '|('+cnt_total+'['+cnt+'])'+')'+sign+odd_text;	
												odd_text = '|('+padLeft(cnt_total,2)+')'+sign+odd_text;	
											}
											if(even_text==''){
												txt += odd_text;
											}else{
												txt += even_text+'<br>'+odd_text;
											}
											
										}
										//txt += 'TAIL('+cnt_total+')';
									}else if(Math.floor(cnt/7)==0){//偶數
										//document.write('even<br>');
										if(cnt==1){//偶數第一個
											txt += even_text;
											if(cnt_total !=1+2){
												//txt += '<br>'+'EX='+cnt_total;
												txt += '<br>';
											}
											even_text = '';
										}
										sign = ' → ';
										even_text += sign+'('+padLeft(cnt_total,2)+'])';
										cnt++;
										//even_text += '--'+' → '+'('+cnt+')'+StopName;
									}else if( Math.floor(cnt/7)==1){//奇數
										//document.write('odd<br>');
										if(cnt==7){//奇數段開始
											txt += odd_text;
											if(cnt_total !=7+2){
												//txt += '<br>'+'OX='+cnt_total;	
												txt += '<br>';	
											}
											odd_text = '';
										}
										sign = ' ← ';
										odd_text = '('+padLeft(cnt_total,2)+')'+sign+odd_text;
										cnt++;
										//odd_text = '--'+' ← '+'('+cnt+')'+StopName+odd_text;
										if(cnt%14==0){//一輪結束
											cnt = 1;
										}
									}else{
										alert('err');
									}
									
								//console.log(txt);
							}
						}
					}
				}
				$('#main').html(txt);
					
				//
			//});//$.each END
		});
	}
	*/

	  ////
	  $(function(){
		 renew();
		 
		/*$(window).on('load',function(){
         setInterval("renew2()",3000);//Here is my logic now
		});*/
	});
	
	  
    </script>
  </body>
</html>