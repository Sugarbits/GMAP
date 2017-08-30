<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Tabs - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <style>
 .rcorners1 {
    border-radius: 25px;
    padding: 20px; 
    width: 30%;
    height: 3em;    
    font-size: 30px;    
	line-height: 3em; 
	}
.rcorners2 {
    border-radius: 25px;
    border: 2px solid #73AD21;
    padding: 20px; 
    width: 200px;
    height: 150px;
	}
#timetable div:nth-child(even)
{
    //color: Green;
    background: #F79646;
}
#timetable div:nth-child(odd)
{
    //color: Red;
	background: #9BBB59;
}

  </style>
  <script>
  $( function() {
    $( "#tabs" ).tabs({
		//disabled: [0,1]
	});
	
  } );
  </script>
</head>
<body>
 
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">下一班時間</a></li>
    <li><a href="#tabs-2">14:00</a></li>
  </ul>
  <div id="tabs-1">
    <p></p>
  </div>
  <div id="tabs-2">
    <p></p>
  </div>
  <?php 
  $content = '<div id="timetable">';
  for($i=0;$i<10;$i++){
	$content .= '<div class="rcorners1">Item '.$i.'</div>';
  }
  $content .= '</div>';
  echo $content;
  ?>
</div>
 
 
</body>
</html>