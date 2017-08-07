var ref_kode_gaji = new DaftarObj2({
	prefix : 'ref_kode_gaji',
	url : 'pages.php?Pg=ref_kode_gaji', 
	formName : 'ref_kode_gajiForm',
	el_IDBARANG : '',
	el_NMBARANG: '',
	el_kode_account : '',
	el_nama_account : '',
	el_tahun_account : '',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
	},
	
	Cari: function(){
		var me = this;
	
		ref_rekening.windowShow();	
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
	
	CariJurnalAT: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kode_account_at';
		RefJurnal.el_nama_account = 'nama_account_at';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='5.1';//kode akun aset tetap
		RefJurnal.windowShow();	
	},
	
	CariJurnalBM: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kode_account_bm';
		RefJurnal.el_nama_account = 'nama_account_bm';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='9.1';//kode akun belanja modal
		RefJurnal.windowShow();	
	},	
	
	CariJurnalAP: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kode_account_ap';
		RefJurnal.el_nama_account = 'nama_account_ap';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.filterAkun='1.3.7';//kode akun akum penyusutan
		RefJurnal.windowShow();	
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
				url: 'pages.php?Pg=refbarang&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						if(document.getElementById(me.el_IDBARANG)) document.getElementById(me.el_IDBARANG).value= resp.content.IDBARANG;
						if(document.getElementById(me.el_NMBARANG)) document.getElementById(me.el_NMBARANG).value= resp.content.NMBARANG;
						if(document.getElementById(me.el_kode_account)) document.getElementById(me.el_kode_account).value= resp.content.kode_account;
						if(document.getElementById(me.el_nama_account)) document.getElementById(me.el_nama_account).value= resp.content.nama_account;
						if(document.getElementById(me.el_tahun_account)) document.getElementById(me.el_tahun_account).value= resp.content.tahun_account;
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
