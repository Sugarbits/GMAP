<?php header("Content-Type:text/html; charset=UTF-8"); ?> 
<?php
	$url = "http://ptx.transportdata.tw/MOTC/v2/Bus/StopOfRoute/City/Taipei/620?$top=30&$format=JSON";
	//init curl
	$ch = curl_init();
	//set curl options 設定你要傳送參數的目的地檔案
	curl_setopt($ch, CURLOPT_URL, $url);
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
