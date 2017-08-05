<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<!--<link type="text/css" rel="Stylesheet" href="EX4.css" />-->
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">
    <title>Simple markers</title>
  </head>
  <body>
   <!--地圖主體-->
   <!--<div id="map"></div>-->
   <!--附屬資訊_介紹欄位-->
   <script>
	function renew(){//ajax抓值
		$.getJSON( "crawler/cr_motc_bus_stop.php", function( data ) {
				//$.each( data, function( key, val ) {
				var txt = '';
				for(key in data){
					if(key>0){//只挑第一個
						break;
					}
					for(key2 in data[key]){
						//(key2 == 'Stops') ? console.log(data[key][key2]) : '' ;//找到所有Stops資訊
						if(key2 == 'Stops'){
							var cnt =  0;
							var odd_text ='';
							var even_text ='';
							var midde_line = '<div class="m fill relative"><div class="stopIconEnd"><div class="busl" id="busi_0"></div></div><div class="buslo" id="buso_0"></div></div>';
							for(key_su in data[key][key2]){//su=StopUint
								//console.log(key_su);
								//console.log(data[key][key2][key_su]['StopName']['Zh_tw']);
								var StopName = data[key][key2][key_su]['StopName']['Zh_tw'];
								console.log('cnt'+cnt);
									if(Math.floor(cnt/7)==0){
										console.log('偶數排');
										if(cnt==0){//偶數段開始
										console.log('E 1st');
											txt += even_text+'<br>';
											even_text = '';
											console.log(txt);
										}
										even_text += '--'+' → '+'('+cnt+')'+StopName;
									}else if( Math.floor(cnt/7)==1){
										console.log('奇數排');
										if(cnt==7){//奇數段開始
										console.log('O 1st');
										
											txt += odd_text+'<br>';
											odd_text = '';
										}
										odd_text = '--'+' ← '+'('+cnt+')'+StopName+odd_text;
										console.log(txt);
										
									}else{
										alert('err');
									}
									
									cnt++;
									if(cnt%13==0){
										cnt = 0;
									}
								
							}
						}
						//console.log(data[key]);
					}
				}
				document.write(txt);
					
				//
			//});//$.each END
		});
	}

	  ////
	  $(function(){
		 renew();
		/*$(window).on('load',function(){
         setInterval("renew()",1000);//Here is my logic now
		});*/
	});
	
	  
    </script>
  </body>
</html>