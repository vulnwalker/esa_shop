
function replaceAll(str, find, replace) {
	  return str.replace(new RegExp(find, 'g'), replace);
}

function ajaxError(request, type, errorThrown)
{
	var message = "Proses gagal! Mohon diulang kembali.\n";
	switch (type) {
		case 'timeout':
			message += "The request timed out.";
			break;
		case 'notmodified':
			message += "The request was not modified but was not retrieved from the cache.";
			break;
		case 'parsererror':
			message += "XML/Json format is bad.";
			break;
		default:
			message += "HTTP Error (" + request.status + " " + request.statusText + ").";
	}
	message += "\n";
	alert(message);
}

formatRupiah=function(objek, separator) {
	  a = objek.value;
	  //b = a.replace(/[^\d/,]/g,"");
	  b = a.replace(/[^\d]/g,"");
	  c = "";
	  panjang = b.length;
	  j = 0;
	  for (i = panjang; i > 0; i--) {
	    j = j + 1;
	    if (((j % 3) == 1) && (j != 1)) {
	      c = b.substr(i-1,1) + separator + c;
	    } else {
	      c = b.substr(i-1,1) + c;
	    }
	  }
	  objek.value = c;
   }

formatNumber = function(num,c, d, t){
var n = num, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };


var DaftarObj = function( 
		url_, formName_, cntdaftar_, cntsum_, cnthal_, elCurrPage_,
		AfterHapus_, elJmlCek_)
{
	this.elCurrPage = elCurrPage_;//'HalDefault'
	this.formName = formName_; //'adminForm'
	this.url = url_; //'index.php?Op='+KodeI+'&Pg=2',
	this.load_que = { idprs: 0, daftarProses: new Array('list','sumhal')};
	this.cntdaftar = cntdaftar_; //'cntHitungDaftar'
	this.cntsum = cntsum_;
	this.cnthal = cnthal_;
	this.cover = this.formName+'_cover';
	this.elJmlCek = elJmlCek_;
	this.AfterHapus = 		
		"delElem('"+this.cover+"');"+
		"document.body.style.overflow='auto'; "+
		"if(resp.ErrMsg==''){"+
			AfterHapus_+
		"}else{"+
			"alert(resp.ErrMsg);"+
		"}";
	
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
	this.OnSuccess = function() {
		var resp = eval('(' + this.GetResponseText() + ')'); 
		if (resp.ErrMsg ==''){
			switch (resp.daftarProses[resp.idprs]){									
			case 'list': 
				if (resp.content.list != ''){
					//delElem(this.cover);
					document.getElementById(this.cntdaftar).innerHTML = resp.content.list; 
				}						
			break;				
			case 'sumhal':			
				if (resp.content.sum != null && this.cntsum != ''){		
					document.getElementById(this.cntsum).innerHTML= resp.content.sum;
				}
				if (resp.content.hal != null && this.cnthal != ''){		
					document.getElementById(this.cnthal).innerHTML = resp.content.hal;
				}						
			break;								
			}	
			if(!resp.stop) {  resp.stop = false; }
			if(!resp.next) {
				resp.idprs++; //auto next proses jika next kosong
			}else{
				resp.idprs = resp.next ; //next proses ditentukan
			}
			if (resp.ErrMsg =='' && !resp.stop){ //if eror next proses di hentikan				
				//next ---	
				//resp.idprs++;			
				if (resp.idprs < resp.daftarProses.length){	
					this.sendReq( {	idprs: resp.idprs,	daftarProses: resp.daftarProses } );	
				}
				
			}						
		}else{
			alert(resp.ErrMsg);
		}
			
	}
	//this.sendReq = function(url_,jsonobj,form){
	this.sendReq = function(jsonobj){
		if (jsonobj.daftarProses[jsonobj.idprs]=='list'){
			var renderTo = this.cntdaftar;
		}else{
			var renderTo = this.cnthal;
		}
		if (renderTo != null)
			addCoverPage2(
					this.cover,
					1, 
					true, 
					true,
					{renderTo: renderTo,
					imgsrc: 'images/wait.gif',
					style: {position:'absolute', top:'5', left:'5'}
					}
				);
		
		var optstring = JSON.stringify( jsonobj ); 
		if (this.url == ''){
			var url = this.url+'?opt='+optstring+'&ajx=1'; 	
		}else{
			var url = this.url+'&opt='+optstring+'&ajx=1'; 
		}		
		this.InitializeRequest('POST', url); 
		var Params = this.form != '' ? createParamByForm(this.formName) : 'null';//alert(Params);
		this.Commit(Params);
	}
	this.loading=function(){
		//var KodeI = document.getElementById('fmOp').value;
		this.sendReq(
			//this.url, //'index.php?Op='+KodeI+'&Pg=2',
		 	this.load_que//{ idprs: 0, daftarProses: new Array('list','sumhal')},
			//this.formName
		);
	}	
	this.refreshList = function( resetPageNo){
		if (resetPageNo) document.getElementById(this.elCurrPage).value=1;
		this.loading();		
	}	
	this.gotoHalaman= function(nothing, Hal){
		document.getElementById(this.elCurrPage).value = Hal;
		this.loading();
	}
	this.hapus=function(){
		if(document.getElementById(this.elJmlCek)){
			var jmlcek = document.getElementById(this.elJmlCek).value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Hapus '+jmlcek+' Data ?')){
				document.body.style.overflow='hidden'; 
				addCoverPage2(this.cover,1,true,false);
				AjxPost2(
					this.url+'&ajx=1&idprs=hapus',
					this.formName,			
					this.AfterHapus,//'',
					true,
					false,
					'Pindahtangan_hapus'				
				);
			}	
		}
		
	}
	this.cetakHal= function(){		
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&idprs=cetak_hal';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	this.cetakAll= function(){
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&idprs=cetak_all';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
}
DaftarObj.prototype = new ajax();




DaftarObj2 = function(params_){
	this.params=params_;
	/*daftar params default */
	//this.elCurrPage = 'HalDefault';
	this.prefix = 'daftar';// 'pbb_penetapan_daftar';
	this.url = 'index.php?Op=9&Pg=sppt';
	this.formName = 'adminForm';
	this.filterCbxChecked = false;
	this.daftarPilih= new Array();	
	this.withPilih = false;
	this.jmlPerHal = 25;
	this.rowID = 0;
	this.tampilFilterColapse = 0; 
	this.tampilFilterColapseMin = 37;
	this.rowHead = 1;
	
	//row on click -------------------------
	this.rowOnClickEfek = function(th){
		var tabelID = this.prefix+'_table';
	
		document.getElementById(tabelID).rows[this.rowID+this.rowHead-1].style.backgroundColor= '';
		this.rowID = th.getAttribute('id');
		this.rowID++;
		document.getElementById(tabelID).rows[this.rowID+this.rowHead-1].style.backgroundColor= '#CCCCCC';//'rgb(0, 255, 31)';
		
	}
	this.rowOnClick = function(th){
		//alert('tesrowclick');
		//var val = th.getAttribute('value'); alert(val);
	}	
	
	this.rowOnDblClick = function(th){
		
	}
	//multi select ------------------------
	this.cbxFilterKlik = function(th){		
		this.filterCbxChecked = th.checked;
		this.refreshList(true);
	}
	this.cbxPilih = function(th){
		if(this.withPilih){
			if(th.checked)	{				
				this.daftarPilih[this.daftarPilih.push()]=th.value				
			}else	{
				var idx=this.daftarPilih.indexOf(th.value)				
				this.daftarPilih.splice(idx,1)
			}			
			this.setCookie(this.prefix+"_DaftarPilih",this.daftarPilih.join()) //setcookie
			
		 	var jmlpilih = this.daftarPilih.length;	 //alert(jmlpilih);
		 	if( document.getElementById(this.prefix+'_pilihan_msg') ) document.getElementById(this.prefix+'_pilihan_msg').innerHTML = this.tampilPilihFilter();
		 }
	}
	this.checkAll = function(PagePerHal,idcb,idtoggle,jmlcek) { 
		if(this.withPilih){
			//alert(2);
			var toggle = document.getElementById(idtoggle)
			for(var i=0;i<PagePerHal;i++){
				var cb = document.getElementById(idcb+i.toString());
				if(cb) //jika cb ada
				{
					if(toggle.checked)
					{
						console.log(i.toString()+ " "+cb.value)
											
						if(this.daftarPilih.indexOf(cb.value)<0)
						{
							this.daftarPilih[this.daftarPilih.push()]=cb.value
						}
						
					}
					else
					{
						var idx=this.daftarPilih.indexOf(cb.value)
				
						this.daftarPilih.splice(idx,1)
					}
					
				}
				
			}
			
			//alert(this.daftarPilih.join())
			this.setCookie(this.prefix+"_DaftarPilih",this.daftarPilih.join()) //setcookie
			var jmlpilih = this.daftarPilih.length;	 //alert(jmlpilih);
		 	document.getElementById(this.prefix+'_pilihan_msg').innerHTML = this.tampilPilihFilter();
				//'Terpilih: '+jmlpilih.toString();	
		
		}
	}	 
	this.tampilPilihFilter = function(){
		var jmlpilih = this.daftarPilih.length;	// alert(jmlpilih);
		var checked = '';
		if( this.filterCbxChecked ) checked = "checked='true'";
		
		var str= 
			"<div style='float:left;padding:2'>Terpilih: </div><div id='"+this.prefix+"_ms_jmlpilih' name='"+this.prefix+"_ms_jmlpilih' style='float:left;padding:2'>"+jmlpilih.toString()+"</div>"+
			"<div style='float:left'><input type='checkbox' id='"+this.prefix+"_cbxpilihfilter' name='"+this.prefix+"_cbxpilihfilter' value='1' "+checked+" onclick='"+this.prefix+".cbxFilterKlik(this)'></div>";
		return str;
	}
	this.resetPilih = function(){
			this.daftarPilih=[]; //kosongkan Array
			//delete cookie
			this.setCookie(this.prefix+'_DaftarPilih','') //ambil cookies	
			this.filterCbxChecked= false;
	}
	this.cbTampil = function(){ //fungsi untuk dipanggil di daftarRender() untuk Cookie
		
		//if(document.getElementById('formcaribi')){
		if(this.withPilih){
			var getCook = this.getCookie(this.prefix+'_DaftarPilih') //ambil cookies
			
			if(getCook)//jika ada nilai
			{	
				var jmPerHal = this.jmlPerHal;// document.getElementById('jmPerHal').value;
			    //merubah string ke array
				this.daftarPilih=getCook.split(',')
				
				//pengecekan jumlah checkbox
				 for(var i=0;i<jmPerHal;i++)
				{
					 if(document.getElementById(this.prefix+'_cb'+i.toString()))
					 {
						var cbx = document.getElementById(this.prefix+'_cb'+i.toString()); //ambil value checkbox
						var val = cbx.value;
					   	//jika value ada di daftar
						if(this.daftarPilih.indexOf(val)>=0) //pengecekan checkbox
						{
							cbx.checked=true;
						}
					 }
				 }
			 }	
		 
		 
		 	 //alert(jmlpilih);
			// var jmlpilih = this.daftarPilih.length;
		 	document.getElementById(this.prefix+'_pilihan_msg').innerHTML = this.tampilPilihFilter();
			
				//'Terpilih: '+jmlpilih.toString();	
		 }
		 
	}
	
	this.getCookie = function(c_name){
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++)
		  {
		  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		  x=x.replace(/^\s+|\s+$/g,"");
		  if (x==c_name)
		    {
		    return unescape(y);
		    }
		  }
	}	
	this.setCookie = function(c_name,value){
		//var exdate=new Date();
		//exdate.setDate(exdate.getDate() + exdays);
		//var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		var c_value=escape(value);
		document.cookie=c_name + "=" + c_value;
	}	
		
	//this.elJmlCek = '_jmlcek' ;
	this.cetakHal= function(){		
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=cetak_hal';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	this.cetakAll= function(){
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe=cetak_all';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	/**this.exportXls= function(){ 
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe=exportXls';
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	**/
	this.exportXls= function(mode){ //mode = 2 --> hal, 3= semua
		if (mode == null) mode = 3;
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe=exportXls&mode='+mode;
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	this.gotoHalaman= function(nothing, Hal){	
		document.getElementById(this.prefix+'_hal').value = Hal;
		this.loading();
	}
	
	this.daftarRenderAfter = function(resp){
		
	}
			
	this.daftarRender = function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  	url: this.url+'&tipe=daftar',
		 	type:'POST', 
			data:$('#'+this.formName).serialize(), 
			//contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",	
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				if(me.withPilih) me.cbTampil();	
				me.daftarRenderAfter(resp);	
				me.sumHalRender();
			
		  	},
			error: ajaxError
		});
	}
	this.daftarKosongRender = function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  	url: this.url+'&tipe=daftarkosong',
		 	type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				if(me.withPilih) me.cbTampil();				
				me.sumHalRenderKosong();
		  	},
			error: ajaxError
		});
	}
	this.sumHalRenderKosong = function(){
		var me =this; //render sumhal
		addCoverPage2(
			'daftar_cover',	1, 	true, 	true,{renderTo: this.prefix+'_cont_hal',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  url: this.url+'&tipe=sumhalkosong',
		  type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_hal').innerHTML = resp.content.hal;
			if (document.getElementById(me.prefix+'_cont_sum')){
				if(resp.content.sums[0]){
					document.getElementById(me.prefix+'_cont_sum').innerHTML = resp.content.sums[0];		
				}else{
					document.getElementById(me.prefix+'_cont_sum').innerHTML = '';	
				}
				
			}
			for (var i=1; i<resp.content.sums.length; i++){
				if (document.getElementById(me.prefix+'_cont_sum'+i))
					document.getElementById(me.prefix+'_cont_sum'+i).innerHTML = resp.content.sums[i];	
			
			}
			
		  },
		  error: ajaxError
		});	
	}
	
	this.sumHalRenderAfter=function(resp){
		
	}
	this.sumHalRender = function(){
		var me =this; //render sumhal
		addCoverPage2(
			'daftar_cover',	1, 	true, 	true,{renderTo: this.prefix+'_cont_hal',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  url: this.url+'&tipe=sumhal',
		  type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			if(document.getElementById(me.prefix+'_cont_hal') ) document.getElementById(me.prefix+'_cont_hal').innerHTML = resp.content.hal;
			if (document.getElementById(me.prefix+'_cont_sum')){
				if(resp.content.sums[0]){
					document.getElementById(me.prefix+'_cont_sum').innerHTML = resp.content.sums[0];		
				}else{
					document.getElementById(me.prefix+'_cont_sum').innerHTML = '';	
				}
				
			}
			for (var i=1; i<resp.content.sums.length; i++){
				if (document.getElementById(me.prefix+'_cont_sum'+i))
					document.getElementById(me.prefix+'_cont_sum'+i).innerHTML = resp.content.sums[i];	
			
			}
			me.sumHalRenderAfter(resp);
			
		  },
		  error: ajaxError
		});	
	}
	this.daftarOpsiColapse= function(heightmin){	//alert('tess');
		
		var icon_up='images/tumbs/up_2.png';
		var icon_down = 'images/tumbs/down_2.png';
		var div_id =  this.prefix+'_cont_opsi' ;//'daftaropsi_div';
		var div_slide =  this.prefix+'_slide_img';
		
		var div= document.getElementById(div_id);
		var img = document.getElementById(div_slide);
		var img_src=img.getAttribute('src');
		//if( img_src =='images/tumbs/down.png' ){
		if( img_src == icon_down ){
			img.setAttribute('src', icon_up);
			div.style.height='';
			div.style.overflowY = 'hidden';
		}else{
			img.setAttribute('src', icon_down);
			div.style.height=heightmin;
			div.style.overflowY = 'hidden';
		}
		
	} 
	this.topBarRenderAfter = function(){
		if ( this.tampilFilterColapse==1) this.daftarOpsiColapse(this.tampilFilterColapseMin);
	}
	this.topBarRender = function(){
		var me=this;
		//render subtitle
		$.ajax({
		  url: this.url+'&tipe=subtitle',
		  type:'POST', 
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_title').innerHTML = resp.content;
			me.topBarRenderAfter()
		  },
		  error: ajaxError
		});
	}
	this.filterRenderAfter = function(){
		
	}
	
	
	this.daftarOpsiColapse= function(heightmin){	//alert('tess');
		
		var icon_up='images/tumbs/up_2.png';
		var icon_down = 'images/tumbs/down_2.png';
		var div_id =  this.prefix+'_cont_opsi' ;//'daftaropsi_div';
		var div_slide =  this.prefix+'_slide_img';
		
		var div= document.getElementById(div_id);
		var img = document.getElementById(div_slide);
		var img_src=img.getAttribute('src');
		//if( img_src =='images/tumbs/down.png' ){
		if( img_src == icon_down ){
			img.setAttribute('src', icon_up);
			div.style.height='';
			div.style.overflowY = 'hidden';
		}else{
			img.setAttribute('src', icon_down);
			div.style.height=heightmin;
			div.style.overflowY = 'hidden';
		}
		
	} 
	
	this.filterRender = function(){
		var me=this;
		//render filter
		$.ajax({
		  url: this.url+'&tipe=filter',
		  type:'POST', 
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_opsi').innerHTML = resp.content;
			me.filterRenderAfter()
		  },
		  error: ajaxError
		});
	}
	this.loading = function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	}
	this.refreshList = function( resetPageNo){
		if (resetPageNo && document.getElementById(this.prefix+'_hal') ) document.getElementById(this.prefix+'_hal').value=1;
		this.daftarRender();
		this.filterRender();
		//this.rowID=0;
		//this.sumHalRender();						
	}
	this.Close = function(tipe){//alert(this.elCover);
		var cover = this.prefix+'_formcover';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
		
	}
	this.CekCheckbox=function(prefix){//alert(this.elJmlCek);
		var errmsg = '';		
		//console.log(prefix);
		if (prefix == null) prefix = this.prefix;
		if( document.getElementById(prefix+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById(prefix+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById(prefix+'_jmlcek').value == 0)||(document.getElementById(prefix+'_jmlcek').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	}
	this.GetCbxChecked=function(prefix){
		if (prefix == null) prefix = this.prefix;
		var jmldata= document.getElementById( prefix+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			if(document.getElementById( prefix+'_cb' + i)){
				var box = document.getElementById( prefix+'_cb' + i);
				if( box.checked){ 
					break;
				}	
			}
		}
		return box;			
	}	
	
	this.CekCheckboxBi = function(prefix){//alert(this.elJmlCek);
		var errmsg = '';		
		//console.log(prefix);
		if (prefix == null) prefix = this.prefix;
		if( document.getElementById('boxchecked')){
			if((errmsg=='')  && (document.getElementById('boxchecked').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById('boxchecked').value == 0)||(document.getElementById('boxchecked').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	}
	this.GetCbxCheckedBi = function(prefix){
		if (prefix == null) prefix = this.prefix;
		var jmldata= document.getElementById( 'jmPerHal' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( 'cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	}
	
	this.AfterFormBaru = function(resp){
		//document.getElementById('no_sppt').focus()		
	};
	this.AfterFormEdit = function(resp){
		//document.getElementById('no_sppt').focus()		
	};
	this.Baru = function(){
		var me = this;
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=formBaru',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if (resp.err ==''){
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru(resp);	
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}			
				
		  	},
			error: ajaxError
		});
	}	
	this.Edit = function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEdit',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormEdit(resp);
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}
			  	},
				error: ajaxError
			});
		}else{
			alert(errmsg);
		}
		
	}
	this.AfterSimpan = function(){
		this.refreshList(false);
	}
	this.Simpan = function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					me.Close();
					me.AfterSimpan();
				}else{
					alert(resp.err);
				}
		  	},
			error: ajaxError
		});
	}	
	this.AfterHapus = function(){
		alert('Sukses Hapus Data');
		this.refreshList(true);
	}
	this.Hapus = function(){
		var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Hapus '+jmlcek+' Data ?')){
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_hapuscover';
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=hapus',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){							
							me.AfterHapus();	
						}else{
							alert(resp.err);
						}							
						
				  	},
					error: ajaxError
				});
				
			}	
		}
	}
	this.initial = function(){	
		//change param default	
		for (var name in this.params) {
			eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	}
	this.initial();
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

