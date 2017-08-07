var rekapdkbSkpd = new SkpdCls({
	prefix : 'rekapdkbSkpd', 
	formName: 'rekapdkbForm',
});

var rekapdkb = new DaftarObj2({
	prefix : 'rekapdkb',
	url : 'pages.php?Pg=rekapdkb', 
	formName : 'rekapdkbForm',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	},
		
	daftarRender:function(){
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
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				me.sumHalRender();
		  	}
		});
	},
	
	Lampiran: function(){		
		var me = this;
		errmsg = this.CekCheckbox();	
		var berdasarkan = document.getElementById("fmBerdasarkan").value;
		if(berdasarkan==2){
			kode1="k";
			kode2="l";
			kode3="m";
			kode4="n";
			kode5="o";	
		}else{
			kode1="f";
			kode2="g";
			kode3="h";
			kode4="i";
			kode5="j";		
		}
		//alert(berdasarkan);
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			var getCB = box.value;
			var cb = getCB.split(" ");
			var aForm = document.getElementById(this.formName);		
			aForm.action= 'pages.php?Pg=rekapdkb_lampiran&c='+cb[0]+'&d='+cb[1]+'&'+kode1+'='+cb[2]+'&'+kode2+'='+cb[3]+'&'+kode3+'='+cb[4]+'&'+kode4+'='+cb[5]+'&'+kode5+'='+cb[6]+'&tahun='+cb[7]+'&berdasarkan='+berdasarkan;//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
		}else{
			alert(errmsg);
		}	
	},	
	
	Baru: function(jns){
		if(jns==0){
			id_jmlcek = "rkb_jmlcek";
		}else{
			id_jmlcek = "rkpb_jmlcek";			
		}
		
		var me = this;
		if(document.getElementById(id_jmlcek)){
			var jmlcek = document.getElementById(id_jmlcek).value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Pilih '+jmlcek+' Data ?')){
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url+'&tipe=formBaru&jns='+jns,
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
			  	}
			});
			}else{
				alert(errmsg);
			}
		}
	},
	
	AfterFormBaru:function(){ 
		idrka = document.getElementById('RKA_idplh').value;
		document.getElementById('rkadetail').innerHTML = 
			//"<div id='DistribusiDetail_cont_title' style='position:relative'></div>"+
			"<div id='RKADetail_cont_opsi' style='position:relative'>"+
			"<input type='hidden' name='idrka' id='idrka' value='"+idrka+"'>"+
			"</div>"+
			"<div align='right'>"+					
			//"<input type='button' name='Tambah' id='Tambah' value='Tambah'  onclick=\"javascript:DistribusiDetail.Baru()\" >"+
			//"<input type='button' name='Edit' id='Edit' value='Edit'  onclick=\"javascript:DistribusiDetail.Edit()\" >"+	
			//"<input type='button' name='Hapus' id='Hapus' value='Hapus'  onclick=\"javascript:DistribusiDetail.Hapus()\" >"+
			"</div>"+
			"<div id='RKADetail_cont_daftar' style='position:relative'></div>"+
			"<div id='RKADetail_cont_hal' style='position:relative'></div>"
			;
		//generate data
	   RKADetail.loading();
	},	
	
	/*CekCheckbox:function(){//alert(this.elJmlCek);
		var errmsg = '';		

		if( document.getElementById('rka'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('rka'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById('rkb'+'_jmlcek').value == 0)||(document.getElementById('rkb'+'_jmlcek').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	},*/
	
	GetCbxChecked:function(){
		var jmldata= document.getElementById( this.prefix+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( this.prefix+'_cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	},
	
	Edit: function(){
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
			  	}
			});
		}else{
			alert(errmsg);
		}
		
	},					
	
	AfterSimpan : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(true);}
	},
	
	Simpan: function(){
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
					alert('Data berhasil disimpan');
					me.Close();
					//me.refreshList(true);
					//me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	}		
});
