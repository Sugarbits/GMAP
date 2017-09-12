<html>
<head>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
</head>
<div id="pbar_outerdiv" style="width: 100%; height: 10px; border: 0px solid grey; z-index: 1; position: relative; border-radius: 5px; -moz-border-radius: 5px;">
<div id="pbar_innerdiv" style="background-color: lightblue; z-index: 2; height: 100%; width: 0%;"></div>
<div id="pbar_innertext" style="z-index: 3; position: absolute; top: 0; left: 0; width: 100%; height: 100%; color: black; font-weight: bold; text-align: center;"></div>
</div>
</html>
<script>
var timer = 0,
    perc = 0,
    timeTotal = 500,
    timeCount = 1,
    cFlag;

function updateProgress(percentage) {
    var x = (percentage/timeTotal)*100,
        y = x.toFixed(3),
        z = (percentage/(1000/timeCount)).toFixed(1);
    $('#pbar_innerdiv').css("width", x + "%");
    //$('#pbar_innertext').text(y + "%");
    //$('#pbar_innertext').text(z + "sec");
}

function animateUpdate() {
    if(perc < timeTotal) {
        perc++;
        updateProgress(perc);
        timer = setTimeout(animateUpdate, timeCount);
    }else if(perc >= timeTotal){
		<?php 
			//if (count(get_included_files()) == 1){
				echo 'ssdp.renew_new();';
				//echo "alert('trigger');";
			//}
		?>
	}
}

function clearbar() {
    $('#pbar_innerdiv').css("width", 0 + "%");
	clearTimeout(timer);
	perc = 0;
    cFlag = true;
}



$(document).ready(function() {
   // $('#pbar_outerdiv').click(function() {
        if (cFlag == undefined) {
            clearTimeout(timer);
            perc = 0;
            cFlag = true;
            //animateUpdate();
        }
        else if (!cFlag) {
            cFlag = true;
            //animateUpdate();
        }
        else {
            clearTimeout(timer);
            cFlag = false;
        }
    //});
}); 
</script>