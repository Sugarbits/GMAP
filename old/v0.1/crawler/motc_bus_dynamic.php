<?php header("Content-Type:text/html; charset=UTF-8"); ?> 
<?php
	if($_GET['touch']=='true'){
		//$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$format=JSON';//動態的資料，待修改(要配合 get parameter)	
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$select=UpdateTime%2CDirection%2CStopSequence&$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$top=1&$format=JSON';
	}
	else{
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$format=JSON';//動態的資料，待修改(要配合 get parameter)	
	}
	//die($url);
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
