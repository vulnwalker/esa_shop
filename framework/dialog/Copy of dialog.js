function loadingShow(formID, imgSrc){
	var idOverlay = formID+'_overlay';
	if ( document.getElementById(idOverlay ) == null ){
			document.body.innerHTML += '<div id="'+idOverlay+'" class="pnlOverlay"></div>'	
	}
	var dlgover = document.getElementById(idOverlay);
	//dlgover.setAttribute('style','visibility:visible;z-index:99; cursor:wait');
	dlgover.style.visibility='visible';
	dlgover.innerHTML = ""+
			"<table width='100%' height='100%'><tr><td align=center>"+
			
			//rgb(14, 31, 91)
			
			setShadowForm(
			"<div style='background-color:white; display:block; width:60; margin:0 0 0 0; padding:10 10 10 10; "+ 
				"border-color: rgb(153, 170, 189);border-width:1 2 2 1px;border-style:solid;'>"+
			"<table width='60' height='45'><tr><td align=center>"+
			"<img src='"+imgSrc+"' >"+
			"</td></tr><tr><td align=center>Waiting ..."+
			
			"</td></tr></table>"+
			"</div>")+
			
			"</td></tr></table>";
	//this.formID = 'dlgDeviceForm';
	//this.show(mode, event);
}

function loadingClose(formID){
	var idOverlay = formID+'_overlay';
	var dlgover = document.getElementById(idOverlay);
	dlgover.innerHTML = '';
	//dlgover.setAttribute('style','visibility:hidden;z-index:-99');
	dlgover.style.visibility='hidden';
}

function setShadowForm(theform){
	var str = 		
	"<table CELLSPACING='0' CELLPADDING='0'><tr><td><!--the form-->"+
	
	theform+
	
	"</td><td width=4 height='100%' valign=top>"+
	
	//kanan
	"<table style='width:100%;height:100%;'  CELLSPACING='0' CELLPADDING='0'><tr><td height=8></td></tr> <tr>"+
		"<td style='background-color:gray;opacity:0.5;"+
		//"border-right-color: silver;border-right-style: solid;border-right-width: 1px;"+
	" '> </td></tr></table>"+
		
	"</td></tr>"+
	"<tr><td height=4 colspan=2>"+
	
	//bawah
	"<table width='100%' height='100%' CELLSPACING='0' CELLPADDING='0'><tr><td width=8> </td>"+
	"<td style='background-color:gray;opacity:0.5;'> </td></tr></table>"+
	
	"</td></tr>"+
	
	"</table>";
	
	/* //chrome
	var str = 		
		"<table CELLSPACING='0' CELLPADDING='0'>"+
		"<tr><td colspan='2' rowspan='2'><!--the form-->"+
			theform+
		"</td><td width='4' height='8'></td></tr>"+
		"<tr><td style='background-color:gray;opacity:0.5;'></td></tr>"+
		"<tr><td width='8' height='4'></td> <td colspan='2' style='background-color:gray;opacity:0.5;'></td></tr></table>";
	*/
	
	/*var str =
		"<table> "+
			"<tr><td><!--the form-->"+
			theform+"</td> "+
			"<td><!--kanan--> "+
			"<table width='4' height='100%'><tr height='10'><td></td></tr><tr><td style='background-color:gray;opacity:0.5;'></td></tr></table>"+
			"</td></tr>"+
			"<tr><tr colspan=2><!--bawah-->"+
			"<table width='100%' height='4'><tr><td width='10'></td><td><td style='background-color:gray;opacity:0.5;'></tr></table>"+
			"<td></tr>"+
		"</table>";	
	*/		
	return str;
}

function setShadowForm2(theform){
	var str = 		
	'<div id="'+formID+'_shadow" class="dialogstyle" style="z-index:99;width:'+width+';height:'+height+';position:absolute;top:'+top+';left:'+left+';">'+
	"<table CELLSPACING='0' CELLPADDING='0'><tr><td><!--the form-->"+
	
	theform+
	
	"</td><td width=4 height='100%' valign=top>"+
	
	//kanan
	"<table style='width:100%;height:100%;'  CELLSPACING='0' CELLPADDING='0'><tr><td height=8></td></tr> <tr>"+
		"<td style='background-color:gray;opacity:0.5;"+
		//"border-right-color: silver;border-right-style: solid;border-right-width: 1px;"+
	" '> </td></tr></table>"+
		
	"</td></tr>"+
	"<tr><td height=4 colspan=2>"+
	
	//bawah
	"<table width='100%' height='100%' CELLSPACING='0' CELLPADDING='0'><tr><td width=8> </td>"+
	"<td style='background-color:gray;opacity:0.5;'> </td></tr></table>"+
	
	"</td></tr>"+
	
	"</table></div>";
	
	
	return str;
}

function dialogClose(formID){
	var idOverlay = formID+'_overlay';	
	document.getElementById(idOverlay).innerHTML = '';	//alert('tes');
	document.getElementById(idOverlay).style.visibility='hidden';
}

function dialogClose2(formID){
	var idOverlay = formID+'_overlay';
	var idShadow = formID+'_shadow';		
	document.getElementById(idShadow).innerHTML = '';
	document.getElementById(idOverlay).innerHTML = '';	//alert('tes');
	document.getElementById(idOverlay).style.visibility='hidden';
}

function dialogShowModal(formID, width, height ){
		var idOverlay = formID+'_overlay';
		if ( document.getElementById(idOverlay ) == null ){
			document.body.innerHTML = '<div id="'+idOverlay+'" class="pnlOverlay"></div>'+document.body.innerHTML ;	
		}
		
		document.getElementById(idOverlay ).style.visibility='visible';	
		document.getElementById(idOverlay ).innerHTML = 		
			'<table width="100%" height="100%" cellspacing="0" cellpadding="0"><tr><td></td></tr>'+
			'<tr><td align="center" >'+		
				setShadowForm(
				'<div class="dialogstyle" style="width:'+width+';height:'+height+';">'+
				'<table width="100%" height="100%" cellspacing="0" cellpadding="0">'+
					'<tr><td valign="top">'+
						'<div id="'+formID+'_content" style="width:100%;height:100%;">'+
							'tes'+
						'</div>'+
					'</td></tr>'+
					'<!--<tr height="40"><td align="right" style="padding:4 8 4 4"> '+
						'<input class="button_std" type="button" value="Cetak" onclick="'+formID+'.print()"> '+
						'<input class="button_std" type="button" value="Close" onclick="'+formID+'.close()"> '+
					'</td></tr>--></table>'+
				'</div>')+		
			'</td></tr>'+
			'<tr><td></td></tr>'
			;
		
}

function dialogShowModal2(formID, width, height,top,left ){
		var idOverlay = formID+'_overlay';
		var idShadow = formID+'_shadow';		
		if ( document.getElementById(idOverlay ) == null ){
			document.body.innerHTML += '<div id="'+idOverlay+'" class="pnlOverlay"></div>';
		if ( document.getElementById(idShadow ) == null ){
			document.body.innerHTML += '<div id="'+idShadow+'"></div>';				
		}
		document.getElementById(idShadow ).innerHTML = 	
			'<div  style="z-index:99;width:'+(width+6)+';height:'+(height+6)+
					';position:absolute;top:'+top+';left:'+left+';">'+				
				setShadowForm(
				
				'<table width="100%" height="100%" cellspacing="0" cellpadding="0">'+
					'<tr><td valign="top">'+
						'<div id="'+formID+'_content" style="width:100%;height:100%;" class="dialogstyle">'+
							'tes'+
						'</div>'+
					'</td></tr>'+
					'<!--<tr height="40"><td align="right" style="padding:4 8 4 4"> '+
						'<input class="button_std" type="button" value="Cetak" onclick="'+formID+'.print()"> '+
						'<input class="button_std" type="button" value="Close" onclick="'+formID+'.close()"> '+
					'</td></tr>--></table>'+
				'</div>')+
				'</div>';
				
		}
		
		document.getElementById(idOverlay ).style.visibility='visible';	
		/*document.getElementById(idOverlay ).innerHTML = 		
			'<table width="100%" height="100%" cellspacing="0" cellpadding="0"><tr><td></td></tr>'+
			'<tr><td align="center" >'+		
				setShadowForm(
				'<div class="dialogstyle" style="width:'+width+';height:'+height+';">'+
				'<table width="100%" height="100%" cellspacing="0" cellpadding="0">'+
					'<tr><td valign="top">'+
						'<div id="'+formID+'_content" style="width:100%;height:100%;">'+
							'tes'+
						'</div>'+
					'</td></tr>'+
					'<!--<tr height="40"><td align="right" style="padding:4 8 4 4"> '+
						'<input class="button_std" type="button" value="Cetak" onclick="'+formID+'.print()"> '+
						'<input class="button_std" type="button" value="close" onclick="'+formID+'.close()"> '+
					'</td></tr>--></table>'+
				'</div>')+		
			'</td></tr>'+
			'<tr><td></td></tr>'
			;
		*/
}

dlgFormObj = function(){
	this.uri = 'viewer/view_cari_det.php';	
	this.formID = 'dlgDetail';
	this.left=100;
	this.top=100;
	this.height= '550px';
	this.width= '640px';
	this.fmCaption ='';	
	this.FormMethod = 'GET';
	
	this.close = function(){
		document.body.style.overflow='auto';//alert('tes');
		dialogClose(this.formID);		
	}		
	this.showModal = function(){	//alert('tes');	
		//dialogShowModal(this.formID, this.width, this.height );		
		document.body.style.overflow='hidden';//alert('tes');
		dialogShowModal(this.formID, this.width, this.height, this.left,this.top );		
		document.getElementById(this.formID+'_content').innerHTML =this.GetResponseText();
		//document.getElementById(this.formID+'_content').innerHTML =
		//	'<textArea >'+this.GetResponseText()+'</textarea>';
	}	
    this.OnSuccess = function() {
        // document.getElementById('TextDiv').innerHTML = this.GetResponseText();
		//alert(this.GetResponseText());
		loadingClose('dlgProgress');
		this.showModal( );	
		this.OnShow();		
    }
	this.OnShow = function() {
	}
    this.GetData = function() {
		
		this.uri += '&randNum=' + new Date().getTime();
		//alert(this.uri);
		loadingShow('dlgProgress','images/administrator/images/loading.gif');
    	this.InitializeRequest(this.FormMethod, this.uri);
        this.Commit(null);
     }
}
dlgFormObj.prototype = new ajax();
dlgForm = new dlgFormObj();