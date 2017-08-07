function popUp(URL,Obj) 
	{
			Ran = parseInt(Math.random()*100)+'20';
			URL = URL+"&Ran="+Ran;
			MyObjek = new Object();
			window.showModalDialog(URL, MyObjek , 'dialogWidth:100;dialogHeight:100;resizeable:no;moveable:no;status:no;scrollbars:yes;dialogTop:1;dialogLeft:1;help:no;title:Daftar');
			//MyObjek.Test = "Aku";
			Obj.value = MyObjek.Test;
	}
function popUpCari(URL,IDNYA,NAMANYA) 
	{
			Ran = parseInt(Math.random()*100)+'20';
			URL = URL+"&Ran="+Ran;
			MyObjek = new Object();
			MyObjek.IDNYA = "";
			MyObjek.NAMANYA = "";
			window.showModalDialog(URL, MyObjek , 'dialogWidth:100;dialogHeight:100;resizeable:no;moveable:no;status:no;scrollbars:yes;dialogTop:1;dialogLeft:1;help:no;title:Daftar');
			IDNYA.value = MyObjek.IDNYA;
			NAMANYA.value = MyObjek.NAMANYA;
	}
function popUpCariUntukDetil(URL,IDNYA,NAMANYA,TOMBOLDETIL) 
	{
			Ran = parseInt(Math.random()*100)+'20';
			URL = URL+"&Ran="+Ran;
			MyObjek = new Object();
			MyObjek.IDNYA = "";
			MyObjek.NAMANYA = "";
			window.showModalDialog(URL, MyObjek , 'dialogWidth:100;dialogHeight:100;resizeable:no;moveable:no;status:no;scrollbars:yes;dialogTop:1;dialogLeft:1;help:no;title:Daftar');
			IDNYA.value = MyObjek.IDNYA;
			NAMANYA.value = MyObjek.NAMANYA;
			TOMBOLDETIL.click();
	}

function TampilkanIFrame(oFrame)
{
	oFrame.style.visibility='visible';
	
	/*posY = window.event.clientY + document.body.scrollTop;
	posX = window.event.clientX + document.body.scrollLeft;	
	oFrame.style.width = document.body.clientWidth;
	oFrame.style.height = document.body.clientHeight;
	oFrame.style.top = document.body.scrollTop;
	oFrame.style.left = document.body.scrollLeft;
	*/
	document.body.style.overflow='hidden';
	oFrame.style.width = '100%';
	oFrame.style.height = '100%'; 
	oFrame.style.position = 'fixed';
	oFrame.style.top = 0;
	oFrame.style.left = 0;
	oFrame.style.bottom = 0;
	oFrame.style.right = 0;
	oFrame.contentDocument.body.scrollTop=0;
	oFrame.style.margin='auto';
	oFrame.style.boxShadow=' 0px 0px 25px rgba(0, 0, 0, 0.8)';
	/*
	oFrame.style.width= document.all.documentElement.clientWidth;
	oFrame.style.height= document.all.documentElement.clientHeight;
	*/
	//oFrame.style.top=posY;
	//oFrame.style.left=document.all.documentElement.scrollLeft;;
	
}

function TampilkanIFrameDKB(oFrame){ //add tahun
	var thn = document.getElementById('fmTAHUN').value;
	//alert(oFrame.src);
	oFrame.src = insertParamUrl ( oFrame.src,'thn',thn);
	//alert(oFrame.src);
	TampilkanIFrame(oFrame);
}
function TampilkanIFrameDKPB(oFrame){ //add tahun
	var thn = document.getElementById('fmTAHUN').value;
	oFrame.src = insertParamUrl ( oFrame.src,'thn',thn);
	TampilkanIFrame(oFrame);
}
function TampilkanIFrameDPB(oFrame){ //add tahun
	var thn = document.getElementById('fmTAHUN').value;
	oFrame.src = insertParamUrl ( oFrame.src,'thn',thn);
	TampilkanIFrame(oFrame);
}


function gotoHalaman(oNama,Hal)
	{
		//var hsl = eval("adminForm."+oNama+".value = "+Hal);
		document.getElementById(oNama).value = Hal;
		//document.getElementsByName(oNama).value = Hal;
		//adminForm.target='_self';
		//adminForm.action='?Pg=$Pg&SPg=$SPg'; 
		adminForm.submit();
	}




//Fungsi Format titik pada ribuan
//Parameter : obj objek Fire, isi : ya isinya
/*function TampilUang(obj,isi){	
	xx = isi.length;
	zz = "";
	for (n=xx; n > 0; n--){		
		if((n) % 3==0){
			zz += ".";
		}
		zz += isi.substr(xx-n,1);
	}
	if(xx % 3 == 0){
		zz = zz.substr(1,zz.length-1);
	}
	obj.innerHTML = zz;
}
*/

function Kali( el_a, el_b, el_c){
	var a = document.getElementById(el_a).value;
	var b = document.getElementById(el_b).value;
	var c = document.getElementById(el_c);
	/*if (c.type == 'text'){
		
	}*/
	hsl_ = a*b;
	hsl = numToUang( hsl_.toString());
	c.value = hsl;
	return hsl;
}

function numToUang(isi){
	
	//obj = document.getElementById(namaobj);	
	xx = isi.length;
	for (n=0; n <= xx; n++){
		if( isi.substr(n,1) == '.' ){ //format indonesia pemisah pecahan koma
			break;
		}
	}
	pecah = isi.substr(n+1, xx-n);
	if(pecah != ''){
		pecah = ','+pecah;
	}
	
	isi =  isi.substr(0, n);
	
	xx = isi.length;
	zz = "";
	for (n=xx; n > 0; n--){		
		if((n) % 3==0){
			zz += ".";
		}
		zz += isi.substr(xx-n,1);
	}
	if(xx % 3 == 0){
		zz = zz.substr(1,zz.length-1);
	}
	
	return zz+pecah;

}

function TampilUang(namaobj,isi){
	obj = document.getElementById(namaobj);	
	xx = isi.length;
	for (n=0; n <= xx; n++){
		if( isi.substr(n,1) == '.' ){ //format indonesia pemisah pecahan koma
			break;
		}
	}
	pecah = isi.substr(n+1, xx-n);
	if(pecah != ''){
		pecah = ','+pecah;
	}
	
	isi =  isi.substr(0, n);
	
	xx = isi.length;
	zz = "";
	for (n=xx; n > 0; n--){		
		if((n) % 3==0){
			zz += ".";
		}
		zz += isi.substr(xx-n,1);
	}
	if(xx % 3 == 0){
		zz = zz.substr(1,zz.length-1);
	}
	
	obj.innerHTML = zz+pecah;	
	
	
}

/*
function isNaN_( $var ) {
     return !preg_match ("/^[-]?[0-9]+([\.][0-9]+)?$/", $var);
}*/

String.prototype.trim = function() {
/* hapus spasi */
	a = this.replace(/^\s+/, '');
	return a.replace(/\s+$/, '');
};

function removeSpaces(string) {
 return string.split(' ').join('');
}
function removeCR(string) {
 //string.split('\r').join('');
 return string.replace('\n','','g');
}

function showMapDlg(namakoord, koord){//use in edit barang
	//x = event.clientX;
	//y = event.clientY;
	//alert(x+' '+y);window.scroll(0,0);
	koord = document.getElementById('koordinat_gps').value;
	koord = koord.replace(' ','');
	if (!(koord == '' || koord=='-')){
	nmbrg = namakoord;//'Barang';	
	//src = 'http://maps.google.com/maps?q='+nmbrg+'@'+koord+'&amp;z=16&amp;iwloc=0&amp;output=embed';
	src = 'http://maps.google.com/maps?q='+koord+'&amp;z=16&amp;iwloc=0&amp;output=embed';
	document.getElementById('mapContent').innerHTML = 
	'<small><a target="blank" href="'+ src +'" style="color:#0000FF;text-align:left">View Larger Map</a></small><br>'+
	'<iframe width="400" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+src+'">'+
	'</iframe>';
	//alert(koord);
	/*dialogShowModal('mapDlg',640,500);//,y+400,x);
	document.getElementById('mapDlg_content').innerHTML = 
	'<div style="padding:10 10 10 10" >'+
	'<input type ="button" value="Back" onclick="mapDlgClose()" >&nbsp&nbsp<small><a target="blank" href="http://maps.google.com/maps?q='+koord+'&amp; t=h&amp;z=17&amp;iwloc=A&amp;output=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small><br><iframe width="620" height="440" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q='+koord+'&amp; t=h&amp;z=17&amp;iwloc=A&amp;output=embed"></iframe>'+
	'</div>';*/
	
	}else{
		document.getElementById('mapContent').innerHTML = '';
	}
	
}

/*
function showMapDlg2(namakoord, koord){//use in edit barang
	
	var skoord = document.getElementById('koordinat_gps').value;//skoord = skoord.replace(' ','');
	var akoord = skoord.split(',');
	var koord_lat = akoord[0];
	var koord_lng = akoord[1];
	
	var sbidang = document.getElementById('koordinat_bidang').value;
	var abidang = sbidang.split(';');
	
	var center_lat = koord_lat;
	var center_lng = koord_lng;
	
	//map ---------
	google.load('maps', '2');
	var map;
	var earth;

}
*/

function mapDlgClose(){
	dialogClose('mapDlg');
}

function getMap(koord, koordbid){//use in viewer detail dialog
	//alert('tes');	
	//koord='-6.90207,107.618332';
	/*nmbrg='Barang';
	str = document.getElementById('contentshow').innerHTML;
	src = 'http://maps.google.com/maps?q='+koord+'&amp;z=16&amp;iwloc=0&amp;output=embed';
	document.getElementById('contentshow').innerHTML = 	
		'<input type ="button" value="Back" onclick="backFromMap()" >&nbsp&nbsp<small><a target="blank" href="'+src+'" style="color:#0000FF;text-align:left">View Larger Map</a></small><br><iframe width="620" height="440" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+src+'"></iframe>';
	//document.getElementById('contentshow').innerHTML = 	' <div id="mapviewlarge"></div> <div id="mapContent"> </div>';
	document.getElementById('contenthide').innerHTML = str;
	*/
	var height = 390;//420;
	str = document.getElementById('contentshow').innerHTML;
	src = 'pages.php?Pg=map&SPg=01&byget=1&lok='+koord+'&bid='+koordbid;
	
	document.getElementById('contentshow').innerHTML = 	
		'<input type ="button" value="Back" onclick="backFromMap()" >'+
		'&nbsp&nbsp<small><a target="blank" href="'+src+'" style="color:#0000FF;text-align:left">View Larger Map</a></small>'+
		'<br>'+
		'<form id="mapform" target="iframe"  method="post" action="pages.php?Pg=map&SPg=01"	>'+
		    '<input type="hidden" id=koordinat_gps" name="koordinat_gps" value="'+koord+'"/>'+
		    '<input type="hidden" id=koord_bidang" name="koord_bidang" value="'+koordbid+'"/>'+
		'</form>'+
		'<iframe name="iframe" width="620" height="'+height+'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" ></iframe>';
		//'<iframe width="620" height="'+height+'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+src+'"></iframe>';
	//document.getElementById('contentshow').innerHTML = 	' <div id="mapviewlarge"></div> <div id="mapContent"> </div>';
	document.getElementById('contenthide').innerHTML = str;
	document.getElementById('mapform').submit();
	
	
}

function backFromMap(){
	
	document.getElementById('contentshow').innerHTML = document.getElementById('contenthide').innerHTML;
	document.getElementById('contenthide').innerHTML = '';
}


/*
function NumericValidation(event)
{
var keycode;

if(event.keyCode) //For IE
keycode = event.keyCode;
else if(event.Which)
keycode = event.Which; // For FireFox
else
keycode = event.charCode; // Other Browser

if (keycode < 44 || keycode > 57 || keycode==45 || keycode==47) {
	//alert(keycode);
	return false;
}


}

function IsNumeric(sText){
var ValidChars = "0123456789";
for (i = 0; i < sText.length; i++) {
	if (ValidChars.indexOf(sText.charAt(i)) == -1) {
		alert('tes');
		return false;
	}
}
return true;
}

function isNumbers(e){
var keynum;
var keychar;
var numcheck;

if(window.event) // IE
	{
	keynum = e.keyCode;
	}
else if(e.which) // Netscape/Firefox/Opera
	{
	keynum = e.which;
	}
alert(keynum);
if (!(keynum == 8 || keynum== 46  || keynum==35 || keynum==9 || (keynum>=37 && keynum<=40 ) ) ){

keychar = String.fromCharCode(keynum);
alert(keychar);
numcheck = /\d/;
return numcheck.test(keychar);
	//return false
}
}
*/

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
    //if (charCode > 31 && (charCode < 48 || charCode > 57 ))   return false;
	//if  return false
    return (charCode >=8 && charCode <=57);//true;
}
/*
function isNumberKey(evt, value){
	var charCode = (evt.which) ? evt.which : evt.keyCode
    //if (charCode > 31 && (charCode < 48 || charCode > 57 ))   return false;
	//if  return false
	
	if(charCode >=8 && charCode <=57){
		str = value	+char
	}
	
    return 
}*/
function TglEntry_createtgl(elName){	
	tgl = document.getElementById(elName+'_tgl').value; //tgl = '.$fmName.'.'.$elName.'_tgl.value;
	if (tgl.length==1){
		tgl ='0'+tgl;
	}
	document.getElementById(elName).value= 
		document.getElementById(elName+'_thn').value+"-"+
		document.getElementById(elName+'_bln').value+"-"+
		tgl;
}
function TglEntry_cleartgl(elName){
	document.getElementById(elName+'_thn').value = "";
	document.getElementById(elName+'_bln').value = "";
	document.getElementById(elName+'_tgl').value = "";
	document.getElementById(elName).value = "";
	if(document.getElementById(elName+'_kosong')) document.getElementById(elName+'_kosong').value=1;
}

function get_url_param( name )
{ //name -> param name
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

showAsetLain =function(){
			document.getElementById('fmKONDBRG').value = 3;		
			adminForm.action='?Pg=05&SPg=03';
			adminForm.target='';
			adminForm.submit();
		}


function insertParamUrl( url, key, value){ //url
	//kvp = url
	var hsl = '';
    key = escape(key); value = escape(value);

    //var kvp = url.search().substr(1).split('&');
	var kvp = url.split('&');

    var i=kvp.length; var x; while(i--) 
    {
    	x = kvp[i].split('=');

    	if (x[0]==key)
    	{
    		x[1] = value;
    		kvp[i] = x.join('=');
    		break;
    	}
    }

    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

    //this will reload the page, it's likely better to store this until finished
    //document.location.search = kvp.join('&'); 
	hsl = kvp.join('&'); 
	return hsl;
}