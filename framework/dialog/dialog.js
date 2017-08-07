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
    document.body.style.overflow='auto';//alert('tes');
}

function dialogClose2(formID){
    var idOverlay = formID+'_overlay';
    var idShadow = formID+'_shadow';
    document.getElementById(idShadow).innerHTML = '';
    document.getElementById(idOverlay).innerHTML = '';	//alert('tes');
    document.getElementById(idOverlay).style.visibility='hidden';
}

function dialogShowModal(formID, width, height ){
		
    if (isIPad()){ //alert('ipad');
        window.scroll(0,0);
    }else{
    //alert('bukanipad');
    }
    document.body.style.overflow='hidden';
		
    var idOverlay = formID+'_overlay';
    if ( document.getElementById(idOverlay ) == null ){
        document.body.innerHTML = '<div id="'+idOverlay+'" class="pnlOverlay2" style=""></div>'+document.body.innerHTML ;
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


function addCoverPage(id, zindex ){
    var divTag = document.createElement('div');
    divTag.setAttribute('id',id);
    divTag.style.position = 'fixed';
    divTag.style.top = 0;
    divTag.style.left = 0;
    divTag.style.right = 0;
    divTag.style.bottom = 0;
    divTag.style.zIndex = zindex;
    divTag.style.backgroundColor = 'rgba(0,0,0,0.5)';
			
    //divTag.innerHTML="<img id='"+id+"_imgload' src='images/administrator/images/loading.gif' style='position:absolute;top:48%;left:48%'>";
			
    document.body.appendChild(divTag);
}

var CoverObj = function ( params_ ){
	this.params = params_;
	/*this.id = id_;
	this.zindex = zindex_;
	this.withwait = withwait_; 
	this.alpha= alpha_;*/
	
	this.initial = function(){		
		for (var name in this.params) {
			eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	}
	this.initial();
	
	this.show = function (){//id, zindex, withwait, alpha, params){
	
		var divTag = document.createElement('div');
		divTag.setAttribute('id',this.id);
		divTag.style.zIndex = this.zindex;//divTag.style= params.style;
		if (this.alpha){
			divTag.style.backgroundColor = 'rgba(0,0,0,0.5)';	
		}			
		
		//dimensi ----
		if(this.params==null || this.style==null){
			divTag.style.position = 'fixed';
			divTag.style.top = 0;
			divTag.style.left = 0;
			divTag.style.right = 0;
			divTag.style.bottom = 0;			
		}else{
			for (var name in this.style) {
			  eval( 'divTag.style.'+name+' = this.style.'+name+';')
			}
		}
		
		//image -------
		if(this.withwait){
			if(this.params == null || this.imgsrc==null){
				var imgsrc = 'images/loading.gif';
				var img = 
					"<img id='"+this.id+
					"_imgload' src='"+imgsrc+
					"' style='position:absolute;top:48%;left:48%'>";
			}else{
				var imgsrc = this.imgsrc;
				var img=
					"<img id='"+this.id+
					"_imgload' src='"+imgsrc+
					"' style='width:24;position:absolute;top:48%;left:48%'>";
			}		
			divTag.innerHTML=img;
		}
		
		//render ------
		if( this.params==null|| this.renderTo == null || 
			this.renderTo =='body' || this.renderTo=='' )
		{
			var renderTo_el = document.body;
		}else{
			if( document.getElementById(this.renderTo)){
				var renderTo_el = document.getElementById(this.renderTo);
			}else{
				alert(this.renderTo+' Tidak Ada !' );
			}
		}
		renderTo_el.appendChild(divTag);
	}	
}


function addCoverPage2( id_, zindex_, withwait_, alpha_, params_ ){
	var params = {id:id_, zindex:zindex_, withwait:withwait_, alpha:alpha_};
	
	var  i;
	for(i in params_) params[i]=params_[i];
	var cover = new CoverObj(params);
	cover.show();//(id, zindex, withwait, alpha, params);
}

/*
function addCoverPage2(id, zindex, withwait, alpha ){
    var divTag = document.createElement('div');
    divTag.setAttribute('id',id);
    divTag.style.position = 'fixed';
    divTag.style.top = 0;
    divTag.style.left = 0;
    divTag.style.right = 0;
    divTag.style.bottom = 0;
    divTag.style.zIndex = zindex;
    if (alpha){
        divTag.style.backgroundColor = 'rgba(0,0,0,0.5)';
    }
    if(withwait){
        divTag.innerHTML="<img id='"+id+"_imgload' src='images/administrator/images/loading.gif' style='position:absolute;top:48%;left:48%'>";
    }
    document.body.appendChild(divTag);
}
*/

function delElem(elID){
    if (document.getElementById(elID)){
        var el = document.getElementById(elID);
        document.body.removeChild(el);
    }else{
        alert ('gagal hapus, element tidak ada!');
    }
	
}


AjxRefreshObj = function( idAjaxPrs_, elCover_, elContainer_, arrParamSend_) {	
    this.idAjaxPrs = idAjaxPrs_;
    this.elCover = elCover_;
    this.arrParamSend = arrParamSend_;
    this.elContainer= elContainer_;
    this.Refresh = function (){
        document.body.style.overflow='hidden';
        addCoverPage2(this.elCover,1,true, false);
        this.uri = 'ajaxprs.php?prs='+this.idAjaxPrs+ createParamSend(this.arrParamSend) ;//'&idbi_awal='+idbi_awal;
        this.GetData();
    }
    this.OnSuccess = function() {
        var resp = this.GetResponseText();//alert(resp);
        document.getElementById(this.elContainer).innerHTML =resp;
        delElem(this.elCover);
        document.body.style.overflow='auto';
    }
    this.GetData = function() {
        this.InitializeRequest('GET', this.uri);
        this.Commit(null);
    }
}
AjxRefreshObj.prototype = new ajax();		
		
AjxFormObj = function( idAjaxPrs_, elCover_, elJmlCek_, elJmlDataTampil_, elCheckBox_, arrParamSend_, afterSucces_) {
    this.idAjaxPrs = idAjaxPrs_;
    this.elCover = elCover_;
    this.elJmlCek = elJmlCek_; //'Pelihara_checkbox'
    this.elJmlDataTampil = elJmlDataTampil_;//'jmlTampilPLH'
    this.elCheckBox = elCheckBox_; //'cbPLH'
    this.arrParamSend = arrParamSend_; //new Array('idbi','idbi_awal')
    this.afterSucces = afterSucces_;
    this.Close = function(){
        delElem(this.elCover);
        document.body.style.overflow='auto';
    }
    this.Edit = function() {
        errmsg = '';
        if((errmsg=='') && (document.getElementById(this.elJmlCek).value >1 )){
            errmsg= 'Pilih Hanya Satu Data!';
        }
        if((errmsg=='') && ( (document.getElementById(this.elJmlCek).value == 0)||(document.getElementById(this.elJmlCek).value == '')  )){
            errmsg= 'Data belum dipilih!';
        }
        if(errmsg ==''){
            var jmldata= document.getElementById( this.elJmlDataTampil ).value;
            for(var i=0; i < jmldata; i++){
                var box = document.getElementById( this.elCheckBox + i);
                if( box.checked){
                    document.body.style.overflow='hidden';
                    //addCoverPage2(this.elCover,1,true,true);
                    addCoverPage2(this.elCover,1,true,false);
                    this.uri = 'ajaxprs.php?prs='+this.idAjaxPrs+'&idplh='+box.value + createParamSend(this.arrParamSend);
                    this.GetData();
                }
            }
        }else{
            alert(errmsg);
        }
    }
    this.Baru = function(){
        //var idbi= document.getElementById('idbi').value;	//var idbi_awal =  document.getElementById('idbi_awal').value;
        document.body.style.overflow='hidden'; //alert('tes');
        addCoverPage2(this.elCover,1,true,false);
        //this.uri = 'ajaxprs.php?prs=1&fmst=1&idbi='+idbi+'&idbi_awal='+idbi_awal;
        this.uri = 'ajaxprs.php?prs='+this.idAjaxPrs+'&fmst=1'+ createParamSend(this.arrParamSend);
        this.GetData();
    }
    this.OnSuccess = function() {
        var resp = this.GetResponseText();
        document.getElementById(this.elCover).innerHTML = '<table width=\'100%\' height=\'100%\'><tr><td align=\'center\'> '+resp+'</td></tr></table>';
        eval(this.afterSucces);//document.getElementById('fmTANGGALPEMELIHARAAN_tgl').focus();
    }
    this.GetData = function() {
        this.InitializeRequest('GET', this.uri);
        this.Commit(null);
    }
}
AjxFormObj.prototype = new ajax();		
						
AjxSimpanObj = function(idAjaxPrs_, elCover_, paramSend_, afterSucces_) {
    this.idAjaxPrs = idAjaxPrs_;//'2'
    this.elCover = elCover_;//'Pelihara_cover';
    this.arrParams = paramSend_;
    this.afterSucces = afterSucces_;
    this.Simpan = function(){
        document.body.style.overflow='hidden';
        addCoverPage2(this.elCover,1,true, false);
        var params = 'prs='+this.idAjaxPrs; //alert(this.arrParams);
        params += createParamSend(this.arrParams);
        this.uri = 'ajaxprs.php?'+params;  //alert(this.uri);
        this.GetData();
    }
    this.OnSuccess = function() {
        var resp = trim(this.GetResponseText());
        delElem(this.elCover);
        if(resp==''){
            alert('Sukses Simpan Data');
            eval(this.afterSucces);
        }else{
            alert(resp);
        }
    }
    this.GetData = function() {
        this.InitializeRequest('GET', this.uri);
        this.Commit(null);
    }
}
AjxSimpanObj.prototype = new ajax();
				
AjxHapusObj = function(idAjaxPrs_, elCover_, elJmlCek_, elJmlDataTampil_, elCheckBox_, nmParamSend_,afterSucces_) {	
    this.idAjaxPrs = idAjaxPrs_;
    this.elJmlCek = elJmlCek_;
    this.elJmlDataTampil = elJmlDataTampil_;
    this.elCheckBox = elCheckBox_;
    this.nmParamSend = nmParamSend_;
    this.elCover= elCover_;
    this.afterSucces = afterSucces_;
    this.Hapus = function (){
        var jmlcek = document.getElementById(this.elJmlCek).value;
        if ( jmlcek > 0 ){
            if(confirm('Yakin '+jmlcek+' data akan di hapus?')){
                var jmldata= document.getElementById(this.elJmlDataTampil).value;
                var params = '';
                for(var i=0; i < jmldata; i++){
                    var box = document.getElementById(this.elCheckBox + i);
                    if( box.checked){
                        params +='&'+this.nmParamSend+'[]='+box.value;
                    }
                }
                document.body.style.overflow='hidden';
                addCoverPage2(this.elCover,1,true, true);
                this.uri = 'ajaxprs.php?prs='+this.idAjaxPrs+params;
                this.GetData();
            }
        }
    }
    this.OnSuccess = function() {
				
					
        var resp = trim(this.GetResponseText());
        document.body.style.overflow='auto';
        delElem(this.elCover);
        if(resp==''){
        //alert('Sukses Hapus Data');
        }else{
            alert(resp);
        }
							
        eval(this.afterSucces);
				
    }
    this.GetData = function() {
        this.InitializeRequest('GET', this.uri);
        this.Commit(null);
    }
}
AjxHapusObj.prototype = new ajax();

function getCbxCheckedValue(cbxName){ //butuh jquery
	var errmsg = ''; 
	var value='';	
	var cbx=$("input[name='"+cbxName+"']:checked");	
	if((errmsg=='') && (cbx.length >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }	
	if((errmsg=='') && ( cbx.length == 0  )){	errmsg= 'Data belum dipilih!';	}
	if(errmsg ==''){ 		
		value = $("input[name='"+cbxName+"']:checked").val();		
	}else{
		alert(errmsg);
	}
	return value;
}

AjxFormObj2 = function(
		dlgName_,
		url_, 
		formDaftar_, 
		afterBaru_, 
		afterEdit_, 
		afterSimpan_, 
		afterBatal_ 
		//elCover_,  elJmlCek_,  elJmlDataTampil_, elCheckBox_, afterSucces_
	) {			
	this.dlgName = dlgName_;
	this.url = url_;	
	this.formDaftar = formDaftar_;// 'adminForm'; //form list
	this.formDialog = this.dlgName+'_form';//formDialog_ //this.formSimpan = formSimpan_ ;//formEntry	
	this.elJmlCek = this.dlgName+'_jmlcek' ;// elJmlCek_;
	this.elJmlDataTampil = this.dlgName+'_jmldatapage';//elJmlDataTampil_;
	this.elCheckBox = this.dlgName+'_cb';//elCheckBox_; 	//id			
	
	this.OnErrorClose =true;
	this.afterSukses= '';//afterSukses_;
	this.afterBaru = afterBaru_;
	this.afterEdit = afterEdit_;
	this.afterSimpan = afterSimpan_;
	this.afterBatal = afterBatal_;
	this.elCover = this.dlgName+'_cover_back';//elCover_; 
	this.elCoverForm = this.dlgName+'_cover_form';
	
	this.Close = function(){//alert(this.elCover);
		if(document.getElementById(this.elCover)) delElem(this.elCover);			
		document.body.style.overflow='auto';
		//eval(this.afterClose);			
	}
	this.Batal = function(){
		this.Close();
		eval(this.afterBatal);
	} 
	this.CekCheckbox=function(){//alert(this.elJmlCek);
		var errmsg = '';
		/*var cbx=$("input[name='"+cbxName+"']:checked");	
		if((errmsg=='') && (cbx.length >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }	
		if((errmsg=='') && ( cbx.length == 0  )){	errmsg= 'Data belum dipilih!';	}*/
		//alert(this.elJmlCek);
		if( document.getElementById(this.elJmlCek)){
			if((errmsg=='')  && (document.getElementById(this.elJmlCek).value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById(this.elJmlCek).value == 0)||(document.getElementById(this.elJmlCek).value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	}
	this.GetCbxChecked=function(){
		var jmldata= document.getElementById( this.elJmlDataTampil ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( this.elCheckBox + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	}
	
	this.Edit = function() {				
		
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			this.Show ('formedit',{idplh:box.value}, false, true);			
		}else{
			alert(errmsg);
		}
	}		
	this.Baru = function(jsonparams_){	
		//alert('tes');
		this.OnErrorClose = true;		
		this.Show('formbaru', jsonparams_, false, true);		
	}	
	this.Show = function(nmproses,jsonparams_, withcek, OnErrorClose_){
		var errmsg='';
		if (withcek)errmsg=this.CekCheckbox();
		if (errmsg==''){
			param = { idprs: 0, daftarProses: new Array(nmproses)};
			var  i;
			for(i in jsonparams_) param[i]=jsonparams_[i];
			//alert(this.elCover);
			document.body.style.overflow='hidden'; //alert('tes');
			addCoverPage2(this.elCover,1,true,false);											
			this.OnErrorClose = OnErrorClose_;
			this.sendReq(
				this.url,
				param,
				this.formDaftar);	
		}else{
			alert(errmsg);
		}
	}
	
	this.Simpan = function(){	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		addCoverPage2(this.elCoverForm,1,true,false);	
		this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);	
	}	
	this.OnSuccess = function() {  								   		
		//var resp = this.GetResponseText();//document.getElementById(this.elCover).innerHTML = '<table width=\'100%\' height=\'100%\'><tr><td align=\'center\'> '+resp+'</td></tr></table>';
		//alert(this.GetResponseText());
		var resp = eval('(' + this.GetResponseText() + ')'); 
		
		if (resp.ErrMsg =='')	{	
			switch (resp.daftarProses[resp.idprs]){									
				case 'formbaru': 					
					document.getElementById(this.elCover).innerHTML = resp.content;
					eval(this.afterBaru);					
				break;
				case 'formedit': 					
					document.getElementById(this.elCover).innerHTML = resp.content;
					eval(this.afterEdit);					
				break;
				case 'simpan': //langsung close dialog
					delElem(this.elCoverForm);			
					document.body.style.overflow='auto';					
					this.Close();
					eval(this.afterSimpan);				
				break;
				default :	//show			
					document.getElementById(this.elCover).innerHTML = resp.content;
					eval(this.afterSukses);
				break;				
			}
		}else
		{	
			/*if(document.getElementById(this.elCover)){
				delElem(this.elCover);			
				document.body.style.overflow='auto';
			}*/
			if(document.getElementById(this.elCoverForm)){
				delElem(this.elCoverForm);			
				document.body.style.overflow='auto';
			}
			alert(resp.ErrMsg);//+' '+ this.OnErrorClose);
			if(this.OnErrorClose) this.Close();
			
		}	
		
	}
	this.sendReq = function(url, opt, formName) {
		//url = 'index.php?Op=pay&SPg=2&opt='
		var optstring = JSON.stringify( opt ); 
		var url = url+'opt='+optstring+'&ajx=1';
		this.InitializeRequest('POST', url);				
		var Params = formName != '' ? createParamByForm(formName) : 'null';
		//alert(Params);
		this.Commit(Params);//this.Commit(null);
	}
}
AjxFormObj2.prototype = new ajax();		

function checkAll3( n, fldName ,elHeaderChecked, elJmlCek) {
	
  if (!fldName) {
     fldName = 'cb';
  }
   if (!elHeaderChecked) {
     elHeaderChecked = 'toggle';
  }
	//var f = document.adminForm;
	//var c = eval('f.'+fldChecked+'.checked');
	var c = document.getElementById(elHeaderChecked).checked;
	var n2 = 0;
	//var n = document.getElementById(elJmlData).value;
	for (i=0; i < n; i++) {
		//alert(fldName+i+' '+c);		
		cb = document.getElementById(fldName+i);
		if (cb) {
			cb.checked = c;
			n2++;
		}
	}
	if (c) {		
		document.getElementById(elJmlCek).value = n2;
	} else {		
		document.getElementById(elJmlCek).value = 0;
	}
}


function BarPanel_expand(barName, barHeight){
	//var barHeight=31;
	var tab = document.getElementById(barName);
	var tabhead= document.getElementById(barName+'_head');
	var img = document.getElementById(barName+'_ico');
	if (tab.style.height != (barHeight+1)+'px'){
		tab.style.height = (barHeight+1)+'px';
		img.src = 'images/menu/right.png';
	}else{
		tab.style.removeProperty('height');
		img.src = 'images/menu/down.png';
	}
}


storeobj = function(params_){
	this.params = params_;
	this.url = '';//"index.php?Op=9&Pg=kec&prs=getall";
	this.rows=Array();
	this.idx=0;
	/*rows: [
			{name: '20121102_617187_881305435.jpg' },
			{name: '20121120_10254_1621872527.jpg' }
		]*/
	this.afterGetData = function(rows){
		
	}
	this.getdata=function(){		
		
		var me= this;
		
		if(this.url != ''){ //jika server
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),		
				//url: "index.php?Op=9&Pg=kec&prs=getCombo&onchg=fmKec.pilihKec(\'cbxKel\')",
				url: this.url,
				success: function(response) {					
					var resp = eval('(' + response + ')');								
					if(resp.err==''){ 
						me.idx=0;
						me.rows=Array();
						me.rows = resp.content.rows;
						me.afterGetData(me.rows);		
					}
				}
			});
		}else{ //lokal
			me.afterGetData(me.rows);
		}
			
	}
	this.initial = function(){		
		//if(this.params.width != null) this.width= this.params.width; 
		for (var name in this.params) {
		  eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	}
	this.initial();	
}


comboboxobj =function(params_){
	this.params = params_;
	this.def = 'Semua Kecamatan';
	this.name = 'fmPilKec';
	this.store = null;// 
	//this.store =Array({id:1,nama:'satu'},{id:2, nama:'dua'});
	this.renderTo='cbxKec';
	this.fieldKey='KD_KECAMATAN';
	this.fieldValue='NM_KECAMATAN';
	this.fieldName = 'cmbKecEl',
	this.defValue = '',
	this.tes = function(){
		alert('tes');
	}
	this.onchange = function(){
		//alert('change');
	}
	this.render = function(){
		var me = this;
		this.store.afterGetData = function(rows){
			//alert('def='+me.defValue);
			var opsi = "<option value=''>"+me.def+"</option>";
			for(var i=0;i<rows.length;i++) { 
				//Sel = $Hasil[$fieldKey]==$value?"selected":""; 
				key = eval("rows["+i+"]."+me.fieldKey); //data[i].id;
				val = eval("rows["+i+"]."+me.fieldValue);//data[i].nama;
				
				Sel = '';
				if (me.defValue != ''){
					Sel = key==me.defValue?"selected":"";
				}//alert('sel=' +Sel+' val='+val);
				opsi += "<option "+Sel+" value='"+key+"'>"+val+"</option>"; 
			} 
			var cbx= "<select name='"+me.elname+"' id='"+me.elname+"' onchange='"+me.name+".onchange()'>"+opsi+"</select >";
			document.getElementById(me.renderTo).innerHTML = cbx;
		}
		this.store.getdata();
		
		
	}
	this.initial = function(){		
		//if(this.params.width != null) this.width= this.params.width; 
		for (var name in this.params) {
		  eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	}
	this.initial();	
	
}


function Entry( name, value, param, tipe){
	hsl ='';
	switch(tipe){	
		case 1: case 'hidden': hsl = "<input type='hidden' id='"+name+"' name='"+name+"' value='"+value+"' "+param+">" ;break;
		case 2: case 'text': hsl = "<input type='text' id='"+name+"' name='"+name+"' value='"+value+"' "+param+">" ;break;
		//case 3: case 'date': hsl = createEntryTgl3($val, $name, false) ;break;		
		case 4: case 'number': 
			hsl = 
				"<input type='text' onkeypress='return isNumberKey(event)' "+				
					"value='"+value+"' "+
					"name='"+name+"' id='"+name+"' "+ 
					param+
				">";
			break;					
		
		default: hsl = "<input type='text' id='"+name+"' name='"+name+"' value='"+value+"' "+param+">" ;break;
		
	}
	return hsl;
}
