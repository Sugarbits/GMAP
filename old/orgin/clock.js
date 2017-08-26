//ref:https://www.w3schools.com/js/tryit.asp?filename=tryjs_timing_clock
function startClock() {//clock
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('remain').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startClock, 500);
}
function startTimer(ms) {//timer count asc in UTC
    var today = +new Date();
	var s =  Math.floor((today - ms)/1000,0);
    /*var h = Math.floor(ms/3600);
    var m = Math.floor(ms/60);
    var s = (ms % 60);
    h = checkTime(h);
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('txt').innerHTML =
    h + ":" + m + ":" + s;*/
	document.getElementById('remain').innerHTML = s;
	var t = setTimeout(startTimer, 1000,sys.UpdateTime);		  
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
