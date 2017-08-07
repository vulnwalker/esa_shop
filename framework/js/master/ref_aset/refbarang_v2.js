var RefBarang_v2 = new DaftarObj2({
	prefix : 'RefBarang_v2',
	url : 'pages.php?Pg=refbarang_v2', 
	formName : 'RefBarang_v2Form',
	el_IDBARANG : '',
	el_NMBARANG: '',
	el_kode_account : '',
	el_nama_account : '',
	el_tahun_account : '',
	el_kode_rekening : '',
	el_nama_rekening: '',
	el_tahun_rekening:'',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
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
	
	Baru:function(){
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
				
		  	}
		});
	},
	
	Carikaap64: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kaap64';
		RefJurnal.el_nama_account = 'naap64';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='1.3';//kode akun aset tetap
		RefJurnal.windowShow();
		
	},
	
	Carikabmp64: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kabmp64';
		RefJurnal.el_nama_account = 'nabmp64';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='5.2';//kode akun belanja modal
		RefJurnal.windowShow();	
	},
	
	Carikaapp64: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kaapp64';
		RefJurnal.el_nama_account = 'naapp64';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='1.3.7';
		RefJurnal.windowShow();	
	},	
	Carikabpp64: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kabpp64';
		RefJurnal.el_nama_account = 'nabpp64';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='5.1.2';
		RefJurnal.windowShow();	
	},	
	Carikabpp64j: function(){
		var me = this;		
		RefJurnal.el_kode_account = 'kabpp64j';
		RefJurnal.el_nama_account = 'nabpp64j';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='5.1';
		RefJurnal.windowShow();	
	},
	Carikrbmp21: function(){
		var me = this;	
		
		RefRekening.el_kode_rekening = 'krbmp21';
		RefRekening.el_nama_rekening = 'nrbmp21';
		RefRekening.windowSaveAfter= function(){};
		RefRekening.filterAkun='3';
		RefRekening.windowShow();
		
	},
	Carikrbpp21: function(){
		var me = this;	
		
		RefRekening.el_kode_rekening = 'krbpp21';
		RefRekening.el_nama_rekening = 'nrbpp21';
		RefRekening.windowSaveAfter= function(){};
		RefRekening.filterAkun='2';
		RefRekening.windowShow();
		
	},
	cariRekeningSewa: function(){
		var me = this;	
		
		RefRekening.el_kode_rekening = 'kodeRekeningSewa';
		RefRekening.el_nama_rekening = 'namaRekeningSewa';
		RefRekening.windowSaveAfter= function(){};
		RefRekening.filterAkun='2';
		RefRekening.windowShow();
		
	},
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					document.getElementById(cover).innerHTML = resp.content;	
					me.loading();					
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});
	},
		
	windowClose: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_cover');
	},
	
	windowSave: function(){
		var me= this;
		//alert('save');
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			//alert(box.value);
			this.idpilih = box.value;
			
			
			var cover = 'refprogram_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=refbarang_v2&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						if(document.getElementById(me.el_IDBARANG)) document.getElementById(me.el_IDBARANG).value= resp.content.IDBARANG;
						if(document.getElementById(me.el_NMBARANG)) document.getElementById(me.el_NMBARANG).value= resp.content.NMBARANG;
						if(document.getElementById(me.el_kode_account)) document.getElementById(me.el_kode_account).value= resp.content.kode_account;
						if(document.getElementById(me.el_nama_account)) document.getElementById(me.el_nama_account).value= resp.content.nama_account;
						if(document.getElementById(me.el_tahun_account)) document.getElementById(me.el_tahun_account).value= resp.content.tahun_account;
						if(document.getElementById(me.el_kode_rekening)) document.getElementById(me.el_kode_rekening).value= resp.content.kode_rekening;
						if(document.getElementById(me.el_nama_rekening)) document.getElementById(me.el_nama_rekening).value= resp.content.nama_rekening;
						me.windowClose();
						me.windowSaveAfter();
					}else{
						alert(resp.err)	
					}
			  	}
			});		
		}else{
			alert(errmsg);
		}
	},
	windowSaveAfter: function(){
		//alert('tes');
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
					me.refreshList(true);
					me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	}
});
