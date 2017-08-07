var TGRSkpd = new SkpdCls({
	prefix : 'TGRSkpd', 
	formName: 'TGRForm',
});

var TGR = new DaftarObj2({
	prefix : 'TGR',
	url : 'pages.php?Pg=tgr', 
	formName : 'TGRForm',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	fmSEKSI:'',
	el_idtgr:'',
	el_kd_barang:'',
	el_nm_barang:'',
	el_kd_account:'',
	el_nm_account:'',
	el_thn_akun:'',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	},
	
	topBarRender: function(){
		var me=this;
		//var jns=document.getElementById('jns').value;
		//render subtitle
		$.ajax({
		  url: this.url+'&tipe=subtitle',
		 
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_title').innerHTML = resp.content;
		  }
		});
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
	
	BidangAfter: function(){
		var me = this;
		document.getElementById('btTampil').disabled = true;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=BidangAfter',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				document.getElementById('fmSKPDskpd').innerHTML=resp.content;
				document.getElementById('btTampil').disabled = false;	
		  }
		});
	},
	SKPDAfter: function(){
		var me = this;
		document.getElementById('btTampil').disabled = true;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=SKPDAfter',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
			document.getElementById('btTampil').disabled = false;	
				
		  }
		});
	},
	
	Baru: function(){
		var me = this;
		var err='';
		var cover = this.prefix+'_formcover';
		var skpd = document.getElementById('fmSKPDBidang').value;
		var unit = document.getElementById('fmSKPDskpd').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		
		if(err==''){
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
		}else{
			alert(err);
		}
		
	},	
	
	
	
	UnitPengguna: function(){	
		
		var me = this;
		var err='';
		var bidang = document.getElementById('c').value;
		var skpd = document.getElementById('d').value; 
		var unit = document.getElementById('e').value;
		var subunit = document.getElementById('e1').value;

			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:"bidang="+bidang+"&skpd="+skpd+"&unit="+unit+"&subunit="+subunit,
			  	url: this.url+'&tipe=UnitPengguna',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('div_e1').innerHTML = resp.content;
				}
			});
	},
	
	Skpd: function(){	
		
		var me = this;
		var err='';
		var bidang = document.getElementById('bidang').value;

			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:"bidang="+bidang,
			  	url: this.url+'&tipe=Skpd',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('div_skpd').innerHTML = resp.content;

				}
			});
	},
		
	TambahBarang:function(){		
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();
		var err = "";						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		var c = document.getElementById('c').value;
		var d = document.getElementById('d').value;
		var e = document.getElementById('e').value;
		var e1 = document.getElementById('e1').value;
		var tgl_gantirugi = document.getElementById('tgl_gantirugi').value;
		
		if(err=='' && (e=='' || e=='00') ) err='Dipergunakan untuk unit belum dipilih!';
		if(err=='' && (e1=='' || e1=='000') ) err='Dipergunakan untuk sub unit belum dipilih!';
		if(err=='' && (tgl_gantirugi=='') ) err='Tanggal Buku belum Lengkap!';
				
		if(err==''){
			var cover = this.prefix+'_formcovercari';
			addCoverPage2(cover,999,true,false);
			$.ajax({
				type:'POST', 
				data:$('#'+this.prefix+'_form').serialize(),
				url: this.url+'&tipe=formCari&sw='+sw+'&sh='+sh,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						//var c = ''; var d=''; var e='';
						document.getElementById('div_detailtambahbarang').innerHTML = 
						'<input type=\"hidden\" id=\"formcaribi\" name=\"formcaribi\" value=\"tgr\">'+
						'<input type=\"hidden\" id=\"multiSelectNo\" name=\"multiSelectNo\" value=\"1\">'+
						'<input type=\"hidden\" id=\"fmSKPD\" name=\"fmSKPD\" value=\"'+c+'\">'+
						'<input type=\"hidden\" id=\"fmUNIT\" name=\"fmUNIT\" value=\"'+d+'\">'+
						'<input type=\"hidden\" id=\"fmSUBUNIT\" name=\"fmSUBUNIT\" value=\"'+e+'\">'+
						'<input type=\"hidden\" id=\"fmSEKSI\" name=\"fmSEKSI\" value=\"'+e1+'\">'+
						'<input type=\"hidden\" id=\"idref\" name=\"idref\" value=\"'+idref+'\">'+
										 
						'<input type=\"hidden\" id=\"boxchecked\" name=\"boxchecked\" value=\"2\">'+
						
						'<div id=\"penatausaha_cont_opt\"></div>'+
						'<div id=\"penatausaha_cont_list\"></div>'+
						'<div id=\"penatausaha_cont_hal\"><input type=\"hidden\" value=\"1\" id=HalDefault></div>'+
						'';
						//generate data
						Penatausaha.getDaftarOpsi();
						Penatausaha.resetPilih();
						Penatausaha.refreshList(true);
						document.body.style.overflow='hidden';
						//barcodeCariBarang.loading();
					}else{
						alert(resp.err);
						//delElem(cover);
						document.body.style.overflow='auto';
					}
			  	}
			});	
		}else{
			alert(err);
		}
				
								
	} ,	
	
	CloseCariBarang:function(){//alert(this.elCover);
		Penatausaha.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},	

	PilihBarang:function(){ //pilih cari
		var me = this;
		errmsg = '';
		var tgl_gantirugi = document.getElementById('tgl_gantirugi').value;		
		if((errmsg=='') && (Penatausaha.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		if((errmsg=='') && (Penatausaha.daftarPilih.length > 1 )){
			errmsg= 'Pilih 1 data!';
		}
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url +'&tipe=simpanPilihBarang&tgl_gantirugi='+tgl_gantirugi,
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.CloseCariBarang();
						document.getElementById('idbi').value = resp.content.idbi;
						document.getElementById('idbi_awal').value = resp.content.idbi_awal;
						document.getElementById('kode_barang').value = resp.content.kode_barang;
						document.getElementById('nama_barang').value = resp.content.nama_barang;
						document.getElementById('kode_akun').value = resp.content.kode_akun;
						document.getElementById('nama_akun').value = resp.content.nama_akun;
						document.getElementById('thn_akun').value = resp.content.thn_akun;
						document.getElementById('noreg').value = resp.content.noreg;
						document.getElementById('thn_perolehan').value = resp.content.thn_perolehan;	
						document.getElementById('merk_barang').value = resp.content.merk_barang;
						document.getElementById('harga_perolehan').value = resp.content.harga_perolehan;
						document.getElementById('harga_buku').value = resp.content.harga_buku;	
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
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
					me.refreshList(true);
					//me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	/*CariGantiRugi: function(){
		var me = this;	
		TGR.fmSKPD = '02';//document.getElementById(this.prefix+'fmSKPD').value;
		TGR.fmUNIT = '01';//document.getElementById(this.prefix+'fmUNIT').value;
		TGR.fmSUBUNIT = '02';//document.getElementById(this.prefix+'fmSUBUNIT').value;
		TGR.fmSEKSI = '01';//document.getElementById(this.prefix+'fmSEKSI').value;
		TGR.el_idtgr4 = 'idtgr';
		TGR.el_kd_barang = 'kd_barang';
		TGR.el_nm_barang = 'nm_barang';
		TGR.el_kd_account = 'kd_account';
		TGR.el_nm_account = 'nm_account';
		TGR.el_thn_akun = 'thn_akun';
		TGR.windowSaveAfter= function(resp){
		//me.Filldetailrencana(resp.content.idtgr);
		};
		TGR.windowShow();	
	},*/
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&fmSKPD='+me.fmSKPD+'&fmUNIT='+me.fmUNIT+'&fmSUBUNIT='+me.fmSUBUNIT+'&fmSEKSI='+me.fmSEKSI,
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
			
			
			var cover = 'TGR_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=tgr&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						if(document.getElementById(me.el_idtgr)) document.getElementById(me.el_idtgr).value= resp.content.idtgr;
						if(document.getElementById(me.el_kd_barang)) document.getElementById(me.el_kd_barang).value= resp.content.kd_barang;
						if(document.getElementById(me.el_nm_barang)) document.getElementById(me.el_nm_barang).value= resp.content.nm_barang;
						if(document.getElementById(me.el_kd_account)) document.getElementById(me.el_kd_account).value= resp.content.kd_account;
						if(document.getElementById(me.el_nm_account)) document.getElementById(me.el_nm_account).value= resp.content.nm_account;
						if(document.getElementById(me.el_thn_akun)) document.getElementById(me.el_thn_akun).value= resp.content.thn_akun;
						me.windowClose();
						me.windowSaveAfter(resp);
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
});
