<?php
$jsword = "<script> var _route = '".$_GET['route']."';";
$jsword .= "var _citycode = '".$_GET['citycode']."';";
$jsword .= "var _direct = '".$_GET['direct']."';</script>";
echo $jsword;
?>
<?php include('timebar.php'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<!--<link type="text/css" rel="Stylesheet" href="EX5.css" />-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="clock.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
	<style>
	body{
		background-color:#59BABA;
	}
	#tabs{
		background-color:#59BABA;
	}
	#loading_mask{
		opacity: 1;
		position:absolute;
		top:0%;
		left:0%;
		z-index:999;
		height:100vh;
		width:100vw;
		background-color:#59BABA;
	}
	.masked{
		-webkit-filter: blur(5px); /* Chrome, Safari, Opera */
		filter: blur(5px);
	}
	td{
		font-size:3vh;
		min-width:2em;
	}
 .rcorners1 {
    border-radius: 25px;
    padding: 20px; 
    width: 85vw;
    height: 5vh;    
	line-height: 6vh; 
	}
.rcorners1 tr{
	background-color: transparent;
	color: white;
	font-weight: bold;
}
.rcorners1 table{
	margin-top: -10;
}
.rcorners2 {
    border-radius: 25px;
    border: 2px solid #73AD21;
    padding: 20px; 
    width: 200px;
    height: 150px;
	}

.now_bus{
	height:2em;
	width:auto;
}
#timetable
{
    position: relative;
}
  div.inactive
{
    background: #ffffff;
}
 #timetable div.inactive:nth-child(even)
{
    background: #888;
}
#timetable div.inactive:nth-child(odd)
{
	background: #A8A8A8;
}
#timetable div:nth-child(even)
{
    background: #F79646;
}
#timetable div:nth-child(odd)
{
	background: #9BBB59;
}
.timetable_btn{
		border: 2px solid #2B6686; 
		background: #95B3D5;
		width: 100px;
		height: 2em; 
		line-height:2em;
		border-radius: 25px;
		text-align: center;
		vertical-align: text-top;
}
.timetable_btn2{
		font-size:15px;
		border: 2px solid #95B3D5; 
		background: #95B3D5;
		width: 200px;
		height: 2em; 
		line-height:2em;
		border-radius: 25px;
		text-align: center;
		vertical-align: text-top;
}
//ui-design END
  </style>
  </head>
  <body>
  <h1 class='timetable_btn' id='routecode'></h1>
  <h1 class='timetable_btn2' id='nextbus'></h1>
  <span id='open_self' class="ui-icon ui-icon-document-b"></span>

  <!--<h1 id='routename'></h1>-->
<div id='loading_mask'>
  <h1 style='color:white'>Loading ...</h1>
  </div>
  <div id='main'></div>
  <BR>
  <!---->
 <!-- <div id="tabs">
  <ul>
    <li><a href="#tabs-1">下一班時間</a></li>
    <li><a href="#tabs-2">14:00</a></li>
  </ul>
  <div id="tabs-1">
    <p></p>
  </div>
  <div id="tabs-2">
    <p></p>
  </div>-->
  <?php 
  $content = '<div id="timetable">';
  $content .= '</div>';
  echo $content;
  ?>
  </div>
  <!---->
  
	<!-- <div>上次更新時間：<span id='remain'></span><span>秒</span></div>	-->
   <!--地圖主體-->
   <!--<div id="map"></div>-->
   <!--附屬資訊_介紹欄位-->
   <script>
   var ssdp={
	   organ_stops:[],
	   wrap_div:function(str,id = false,_class = 'rcorners1'){
		   if(id!=false){
		   return "<div id='"+id+"' class='"+_class+"'>"+str+"</div>";
		   }else{
		   return "<div class='"+_class+"'>"+str+"</div>";
		   }
	   },
	   wrap_td:function(str,percent){
		   return '<td width='+percent+'>'+str+'</td>';
	   },
	   wrap_tr:function(str){
		   return '<tr>'+str+'</tr>';
	   },
	   wrap_tb:function(str){
		   return '<table width=100% border=0>'+str+'</table>';
	   },
	   word_trans_time:function(t,last){
		if(t==-1){
			if(last==true){
				return '末班車已過';
			}else{
				return '已離站';	
			}
		}
		else if(t<=120){
			return '進站中';
		}
		else if(t<=300){
			return '即將進站中';
		}
		else if(t>300){
			return Math.floor(t/60)+'分鐘';
			}
		
		},
		initial:function(){//bus stops
			//ajax once data
			var RouteName = '';
			var con_saver = '';
			$.getJSON( "crawler/motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"&func=0", function( data ) {
			//console.log(JSON.stringify(data));
			console.log('initial');
			console.log(data);
			for(rkey in data){
				RouteName = data[rkey]['RouteName']['Zh_tw'];
				for(key in data[rkey]['Stops']){
					var StopName = data[rkey]['Stops'][key]['StopName']['Zh_tw'];
					var StopUID = data[rkey]['Stops'][key]['StopUID'];
					//
					(ssdp.organ_stops).push(StopUID);
					//
					var left = ssdp.wrap_td(StopName,'60%');
					var middle = ssdp.wrap_td(StopUID,'25%');
					var right = ssdp.wrap_td('讀取中...&nbsp;','15%');
					var id = StopUID;
					console.log(StopName+':'+StopUID);
					con_saver += ssdp.draw_div(left,middle,right,id);
					}
				}
			})
			.done(function(){
				console.log((location.pathname.substring(location.pathname.lastIndexOf('/')+1))+': ajax complete!');
				$("#loading_mask").stop().fadeOut();
				$('#timetable').html(con_saver);
				$('#routecode').html(RouteName);
				ssdp.renew_new();
			});
			},
			initial2:function(){//bus schedule
			//ajax once data 
			var Now =  new Date();
			var WeekDayArray = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
			var RouteID = '';
			var con_saver = '今日末班車已過';
			var PreArrivalTime = 86400;
			$.getJSON( "crawler/motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"&func=-1", function( data ) {
			//console.log(JSON.stringify(data));
			console.log('initial2');
			console.log(data);
			for(rkey in data){
				RouteID = data[rkey]['RouteID'];//feels like it's canbe use in 備份的資料連結(同公總代碼？待驗證)
				for(key in data[rkey]['Timetables']){
					//con_saver += 'weekday(1/0):'+data[rkey]['Timetables'][key]['ServiceDay'][WeekDayArray[Now.getDay()]];
					if(data[rkey]['Timetables'][key]['ServiceDay'][WeekDayArray[Now.getDay()]] == 1){//表示有營運
						
						var ArrivalTime = data[rkey]['Timetables'][key]['StopTimes'][0]['ArrivalTime'];
						ArrivalTime = ArrivalTime.split(":")[0]*3600+ArrivalTime.split(":")[1]*60;
						ArrivalTime -= Now.getSeconds()*1+Now.getMinutes()*60+Now.getHours()*3600;
						
						//con_saver = Now.getSeconds()*1+Now.getMinutes()*60+Now.getHours()*3600
						if(ArrivalTime > PreArrivalTime && ArrivalTime>=0 ){//極限是23:59 86399，因此第一個PreArrivalTime一定最大，快到班條件即是：最小值且不小於零
							PreArrivalTime = ArrivalTime;
							con_saver = '下班發車：'+ArrivalTime;
						}
						
					}
					
					//console.log(StopName+':'+StopUID);
					console.log(con_saver+':'+PreArrivalTime);
					
					}
					if(PreArrivalTime = 86400){
						$('#nextbus').html(con_saver);
					}
				}
			})
			.done(function(){
				console.log((location.pathname.substring(location.pathname.lastIndexOf('/')+1))+': ajax initial2 complete!');
				//$("#loading_mask").stop().fadeOut();
				//$('#timetable').html(con_saver);
				//$('#routecode').html(RouteName);
				//ssdp.renew_new();
			});
		},
		touch:function(){//ajax 試探值的變化
		$.getJSON( "crawler/motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"&func=1", function( data ) {
			console.log("crawler/motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"&func=1");
			console.log(data);
			var UpdateTime = '';
			for(key in data){
				UpdateTime = data[key]['UpdateTime'];
			}
			console.log(Date.parse(UpdateTime)-sys.UpdateTime);
			if(sys.UpdateTime == ''){
				sys.UpdateTime = Date.parse(UpdateTime);
				//if(sys.Timer == ''){
				renew();
				sys.Timer = startTimer(sys.UpdateTime);
				//}
			}
			else if(sys.UpdateTime != Date.parse(UpdateTime)){
				sys.UpdateTime = Date.parse(UpdateTime);
				renew();
			}
			else{//no change
				;
			}
			});
		},
		renew:function(){//ajax抓值
			console.log('renew()');
			var cnt = 0;
			var cnt_total = 0;
			var con_stopname = '';//stop name
			var con_busplate = '';//stop bus num
			var con_estimatetime = '';//stop bus predict time
			var con_saver = '';//total
			var has_car = false;//for scroll
			$.getJSON( "crawler/motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"", function( data ) {
				//console.log(JSON.stringify(data));
				cnt = 0;
				var first_car = true;
				var RouteName = '';
				var UpdateTime = '';
				var PreStopName = '';//存取前一個站名
				var PreEstimateTime = -999;//存取前一個到站時間預估
				for(key in data){
					cnt_total++;
					cnt++;
					var div_id = false;
					var StopName = data[key]['StopName']['Zh_tw'];
					var StopIndex = data[key]['StopSequence'];
					//var StopUID = data[key]['StopUID'];
					var PlateNumb = data[key]['PlateNumb'];
					if((data[key]).hasOwnProperty('EstimateTime')){
						var EstimateTime = data[key]['EstimateTime'];
					}else{
						var EstimateTime = -1;
					}
					
					var IsLastBus = data[key]['IsLastBus'];
					
					con_stopname = ssdp.wrap_td(StopName,'60%');	
					var con_icon = '&nbsp;';
					if(first_car == true){
						if(PlateNumb != -1){//老司機在這兒
							first_car = false;
							has_car = true;
							div_id = 'now_stop';
							con_icon = "<img class='now_bus' src='pic/ex_bus.png' />";
						}
					}else{
						;
					}
					if((PreEstimateTime > EstimateTime) && PreEstimateTime !=(-999)){
					//EstimateTime
					con_icon = "<img class='now_bus' src='pic/alert.png' />不合理值："+EstimateTime;
					}
					console.log(StopName+':'+EstimateTime);
					PreEstimateTime = EstimateTime;
					con_busplate = ssdp.wrap_td(con_icon,'15%');	
					con_estimatetime = ssdp.wrap_td(ssdp.word_trans_time(EstimateTime,IsLastBus),'25%');	
					con_saver += ssdp.wrap_div(ssdp.wrap_tb(ssdp.wrap_tr(con_stopname+con_busplate+con_estimatetime)),div_id);
			}		
				//$('#timetable').html(con_saver);
			})
			.done(function(){
				console.log((location.pathname.substring(location.pathname.lastIndexOf('/')+1))+': ajax complete!');
				//$("#loading_mask").stop().fadeOut();
				<?php echo 'animateUpdate();';?>
				/*if(has_car)
				ssdp.scroll_to('#now_stop');*/
			});
			<?php 
			echo 'clearbar();';
			echo 'animateUpdate();';
			?>
			return true;	
	},//ssdp.renew() end
	renew_new:function(){//ajax抓值
			console.log('renew_new()');
			var key_cnt = 0;
			var con_stopname = '';//stop name
			var con_busplate = '';//stop bus num
			var con_estimatetime = '';//stop bus predict time
			var con_saver = '';//total
			var has_car = false;//for scroll
			$.getJSON( "crawler/motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"", function( data ) {
				//console.log("motc_bus_dynamic.php?route="+_route+"&direct="+_direct+"&citycode="+_citycode+"");
				console.log(data);
				if(data==''){
					//alert('empty');
					for(pnkey in ssdp.organ_stops){
						ssdp.fix_div(ssdp.organ_stops[pnkey],3,'未發車');
					}
				}else{
				cnt = 0;
				var con_obj={};
				var first_car = true;
				var RouteName = '';
				var UpdateTime = '';
				var _icon = "<img class='now_bus' src='pic/ex_bus.png' />";
				//var PreStopName = '';//存取前一個站名
				//var PreEstimateTime = -999;//存取前一個到站時間預估
				for(key in data){
					key_cnt++;
					var StopUID = data[key]['StopUID'];
					var PlateNumb = data[key]['PlateNumb'];
					var IsLastBus = false;
					//以車號為引索，分類車站(UID)
					/*
					if(StopUID in con_obj){
						(con_obj[StopUID]).push(PlateNumb);
					}else{
						con_obj[StopUID] = [PlateNumb];	
					}
					*/
					/*
					if(PlateNumb in con_obj){
						(con_obj[PlateNumb]).push(StopUID);
					}else{
						con_obj[PlateNumb] = [StopUID];	
					}
					*/
					//END
					//預估到站時間，沒有就賦予-1 並且判斷是否為末班車
					/*if('EstimateTime' in data[key]){
						var EstimateTime = data[key]['EstimateTime'];
					}else{
						var IsLastBus = data[key]['IsLastBus'];
						var EstimateTime = -1;
					}*/
					

					var StopName = data[key]['StopName']['Zh_tw'];
					console.log(key_cnt+'.'+StopName+':'+StopUID);
					/*
					var div_id = false;
					
					var StopIndex = data[key]['StopSequence'];
					var StopUID = data[key]['StopUID'];
					var PlateNumb = data[key]['PlateNumb'];
					if((data[key]).hasOwnProperty('EstimateTime')){
						var EstimateTime = data[key]['EstimateTime'];
					}else{
						var EstimateTime = -1;
					}
					
					var IsLastBus = data[key]['IsLastBus'];
					
					con_stopname = ssdp.wrap_td(StopName,'60%');	
					var con_icon = '&nbsp;';
					if(first_car == true){
						if(PlateNumb != -1){//老司機在這兒
							first_car = false;
							has_car = true;
							div_id = 'now_stop';
							con_icon = "<img class='now_bus' src='pic/ex_bus.png' />";
						}
					}else{
						;
					}
					if((PreEstimateTime > EstimateTime) && PreEstimateTime !=(-999)){
					//EstimateTime
					con_icon = "<img class='now_bus' src='pic/alert.png' />不合理值："+EstimateTime;
					}
					//console.log(StopName+':'+StopUID+'/'+EstimateTime);
					console.log(StopName+':'+StopUID+'/'+PlateNumb);
					PreEstimateTime = EstimateTime;
					con_busplate = ssdp.wrap_td(con_icon,'15%');	
					con_estimatetime = ssdp.wrap_td(ssdp.word_trans_time(EstimateTime,IsLastBus),'25%');	
					con_saver += ssdp.wrap_div(ssdp.wrap_tb(ssdp.wrap_tr(con_stopname+con_busplate+con_estimatetime)),div_id);
					*/
					//Notice 這是全部讀完一次更新喔！
					for(pnkey in con_obj){//pn= plate number
						if(pnkey==-1){
							ssdp.fix_div(con_obj[pnkey],3,ssdp.word_trans_time(EstimateTime,IsLastBus));
						}else{
							ssdp.fix_div(con_obj[pnkey],2,_icon+pnkey);
							ssdp.fix_div(con_obj[pnkey],3,ssdp.word_trans_time(EstimateTime,IsLastBus));
						}
						
					}
				}
			}	
					
							
				//$('#timetable').html(con_saver);
				console.log(con_obj);
			})
			.done(function(){
				console.log((location.pathname.substring(location.pathname.lastIndexOf('/')+1))+': ajax complete!');
				//$("#loading_mask").stop().fadeOut();
				<?php 
				echo 'clearbar();';
				echo 'animateUpdate();';
				?>
				/*if(has_car)
				ssdp.scroll_to('#now_stop');*/
			});

			return true;	
	},//ssdp.renew() end
	scroll_to:function(scrollTo){//'#now_stop'
        if (scrollTo != null && scrollTo != '') {
            $('html, body').animate({
                scrollTop: $(scrollTo).offset().top
            }, 1500);
        }
	},
	draw_div:function(left,middle,right,id){
				return ssdp.wrap_div(ssdp.wrap_tb(ssdp.wrap_tr(left+middle+right)),id,'rcorners1');
	},
	fix_div:function(id,td_nth,context){
				$('#'+id+' td:nth-child('+td_nth+')').html(context);
	}
   }
   var sys={
	   UpdateTime:'',
	   Timer:''
   };
	////
	  $(function(){
		 ssdp.initial();
		 ssdp.initial2();
		 //setInterval(function(){ ssdp.touch(); }, 3000);
		$( "#tabs" ).tabs({
			//disabled: [0,1]
		});
		$( "#open_self" ).click(function() {
			window.open(window.location.href,);
		});
	});
 
    </script>
  </body>

</html>