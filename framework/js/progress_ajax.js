// Get the HTTP Object




function getHTTPObject(){
   if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
   else if (window.XMLHttpRequest) return new XMLHttpRequest();
   else {
      alert("Your browser does not support AJAX.");
      return null;
   }
}   

// Change the value of the outputText field
function setOutput(){
   if(httpObject.readyState == 4){
 	document.getElementById("hasil_content").className = "loadings-visible";
 	document.getElementById("loader").className = "loadings-invisible";   
      document.getElementById('hasil_content').innerHTML = httpObject.responseText;
   }
}

// Implement business logic
function loadSite(url){
   document.getElementById("loader").className = "loadings-visible";
   url="viewer/drawchart.php"+"?SBID="+adminform.fmBIDANG.value+"&SOPD"+adminform.fmSKPD.value;
   httpObject = getHTTPObject();
   if (httpObject != null) {
//		httpObject.addEventListener("progress", onUpdateProgress, true);
//		httpObject.addEventListener("load", onTransferComplete, false);
//		httpObject.addEventListener("error", onTransferFailed, false);
//		httpObject.addEventListener("abort", onTransferAborted, false);
		httpObject.open("GET","viewer/drawchart.php", true);
		httpObject.send(null);
		httpObject.onreadystatechange = setOutput;
   }
}

 function onUpdateProgress(e) {
 	document.getElementById("hasil_content").className = "loading-invisible";
	document.getElementById("loader").className = "loading-visible";
} 
 function onTransferComplete(e) {
 	document.getElementById("hasil_content").className = "loading-visible";

 	document.getElementById("loader").className = "loading-invisible";
 }

function onTransferFailed(e)   { 
}

function onTransferAborted(e)  { 
}

function showprogress()
{
   document.getElementById("loader").className = "loadings-visible";	
   document.getElementById("hasil_content").className = "loadings-invisible";
   document.getElementById("print_content").className = "loadings-invisible";		
	return true;
}	
function hideprogress()
{
   document.getElementById("loader").className = "loadings-invisible";	
}
 
function  printmypage()
{
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("hasil_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>ATISISBADA (Aplikasi Teknologi Informasi Siklus Barang Daerah)</title>');
	docprint.document.write('<link rel="stylesheet" href="css/viewer.css" type="text/css" />');
   docprint.document.write('<link rel="stylesheet" href="css/sislog.css" type="text/css" />');
   docprint.document.write('<link rel="stylesheet" href="css/template_css.css" type="text/css" />');
   docprint.document.write('<link rel="stylesheet" href="menu/menu_style.css" type="text/css" />');
   docprint.document.write('<link rel="stylesheet" href="css/progress_style.css" type="text/css" />');

   docprint.document.write('</head><body ><center>');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</center></body></html>'); 
   docprint.document.close();
   docprint.focus();
   docprint.moveTo( 0, 0 );  
   docprint.resizeTo( screen.availWidth, screen.availHeight );
     
}
var httpObject = null;
