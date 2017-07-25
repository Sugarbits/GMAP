<?php header("Content-Type:text/html; charset=UTF-8"); ?> 
<?php
    //$url = "https://www.thsrc.com.tw/tw/TimeTable/SearchResult";
    //$uri = "http://ptx.transportdata.tw/MOTC/v2/Bus/RealTimeByFrequency/City/HualienCounty?$top=30&$format=JSON";
    //$uri = "https://www.zhihu.com/question/21471960";
	//$url = "http://ptx.transportdata.tw/MOTC/v2/Bus/RealTimeByFrequency/City/Taichung?$top=30&$format=JSON";//台中
	//$url = "temp.html";//
	$url = "http://ptx.transportdata.tw/MOTC/v2/Bus/RealTimeByFrequency/City/HualienCounty?$top=30&$format=JSON";//花蓮
	//init curl
	$ch = curl_init();
	//set curl options 設定你要傳送參數的目的地檔案
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_URL, "http://ptx.transportdata.tw/MOTC/v2/Bus/RealTimeByFrequency/City/Taipei?$top=30&$format=json");
	curl_setopt($ch, CURLOPT_HEADER, false);   
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	//execute curl
	$dom = curl_exec($ch);
	//close curl
	curl_close($ch);
	printf($dom);

 

	//echo $encode;
	if($encode!='UTF-8'){
		$html = mb_convert_encoding($dom,$encode,"UTF-8");
	}
    
?>
