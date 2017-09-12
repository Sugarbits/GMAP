<?php header("Content-Type:text/html; charset=UTF-8"); ?> 
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
	/*
	if($_GET['touch']=='true'){
		//$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$format=JSON';//動態的資料，待修改(要配合 get parameter)	
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$select=UpdateTime%2CDirection%2CStopSequence&$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$top=1&$format=JSON';
	}
	else{
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$format=JSON';//動態的資料，待修改(要配合 get parameter)	
	}
	*/
	@$func = $_GET['func'];
	@$test = $_GET['test'];
	if($func == '-1'){
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/Schedule/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$format=JSON';
	}
	else if($func == '0'){
		//$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$select=RouteName%2CDirection%2CStopSequence&$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$top=1&$format=JSON';	
		//$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/StopOfRoute/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$select=RouteName%2COperatorID%2CDirection%2CStops&$format=JSON';
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/StopOfRoute/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$format=JSON';
	}
	else if($func == '1'){
		//$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$format=JSON';//動態的資料，待修改(要配合 get parameter)	
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$select=UpdateTime%2CDirection%2CStopSequence&$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$top=1&$format=JSON';
	}
	else if($func == '2'){
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/RealTimeByFrequency/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$format=JSON';
	}
	else{
		$url = 'http://ptx.transportdata.tw/MOTC/v2/Bus/EstimatedTimeOfArrival/City/'.$_GET['citycode'].'/'.$_GET['route'].'?$filter=Direction%20eq%20%27'.$_GET['direct'].'%27&$orderby=StopSequence%20asc&$format=JSON';//動態的資料，待修改(要配合 get parameter)	
	}
	if(isset($test)){
		die($url);
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
	/*
	if( isset( json_encode($dom)['message'] ) ){
		if(json_encode($dom)['message'] == '發生錯誤。'){
				//todo
		}
	}
	*/

 

	//echo $encode;
	if($encode!='UTF-8'){
		$html = mb_convert_encoding($dom,$encode,"UTF-8");
	}
    printf($dom);
?>
