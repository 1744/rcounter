// JavaScript Document<!--
var RANDOM_LENGTH=10000000000;
function get_disp(page,mode) {

	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else if(window.ActiveXObject){
		xmlhttp = new ActiveXObject ("Microsoft.XMLHTTP");
	} else {
		//xmlhttp = new XMLHttpRequest();
		xmlhttp = fales;
	}
	if (xmlhttp) {
		
		xmlhttp.onreadystatechange = check_disp;
		var rand = Math.floor( Math.random() * RANDOM_LENGTH );
		xmlhttp.open('GET', 'counter.php?p='+page+'&m='+mode+'&h='+rand, true);
		xmlhttp.send(null);
	}
}

function check_disp() {
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		document.getElementById('disp').innerHTML = xmlhttp.responseText;
	}
}

// -->