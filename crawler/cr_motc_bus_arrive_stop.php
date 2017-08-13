<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php header("Content-Type:text/html; charset=UTF-8"); ?> 
<?php
	//$get = "$format=JSON&$filter=RouteID eq '15334' and Direction eq '0'";
	$get = '$format=JSON&$filter=RouteID%20eq%20%2715334%27%20and%20Direction%20eq%20%270%27';
	$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/Taipei?'.$get;
	//die($url);
	//$url = "http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/Taipei?$filter=RouteID%20eq%20%2715334%27%20and%20Direction%20eq%20%270%27";
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
	echo($dom);

 

	//echo $encode;
	if($encode!='UTF-8'){
		$html = mb_convert_encoding($dom,$encode,"UTF-8");
	}
    
?>
