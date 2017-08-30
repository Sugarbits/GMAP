<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php header("Content-Type:text/html; charset=UTF-8"); ?> 
<?php
	$index = $_GET['index'];
	$citycode = $_GET['citycode'];
	

	$UrlArray=['http://ptx.transportdata.tw/MOTC/v2/Bus/Route/City/'.$citycode.'?$format=JSON'];
	//$UrlParaArray=["",""];
	/*foreach ($UrlParaArray as &$value) {
		//$value = $value * 2;
		//$value = urlencode($value);
		str_replace('%2F', '=', urlencode($value));
	}*/
	//die($UrlArray[$index]);
	//$NameArray=['花蓮302站牌資訊','花蓮302公車動態'];
	//init curl
	$ch = curl_init();
	//set curl options 設定你要傳送參數的目的地檔案
	curl_setopt($ch, CURLOPT_URL, $UrlArray[$index]);
	curl_setopt($ch, CURLOPT_HEADER, false);   
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	//execute curl
	$dom = curl_exec($ch);
	//close curl
	curl_close($ch);
	//echo($dom);
	//echo  $NameArray[$index].'@5@'.$dom;
	echo  $dom;

 

	//echo $encode;
	if($encode!='UTF-8'){
		$html = mb_convert_encoding($dom,$encode,"UTF-8");
	}
    
?>
