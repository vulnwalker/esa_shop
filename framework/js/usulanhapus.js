
var UsulanHapusSkpd = new SkpdCls({
	prefix : 'UsulanHapusSkpd', 
	formName: 'UsulanHapus_listForm',// 'adminForm'
	pilihBidangAfter:function(){
		UsulanHapus.refreshList(true);
	},
	pilihUnitAfter:function(){
		UsulanHapus.refreshList(true);
	},
	pilihSubUnitAfter:function(){
		UsulanHapus.refreshList(true);
	},
	pilihSeksiAfter:function(){
		UsulanHapus.refreshList(true);
	}
	
});


var UsulanHapus = new DaftarObj2({
	prefix : 'UsulanHapus',
	url : 'pages.php?Pg=usulanhapus', 
	formName : 'UsulanHapus_listForm',// 'adminForm',// 'ruang_form',
	
	//daftarPilih: new Array(),
	
	detail: function(){
		//alert('detail');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			//UserAktivitasDet.genDetail();			
			
		}else{
			alert(errmsg);
		}
		
	},
	
	AfterFormBaru:function() //after form usulan
	{
		//alert("tes");
		document.getElementById('div_detail').innerHTML = 
			"<div id='UsulanHapusdetail_cont_title' style='position:relative'></div>"+
			"<div id='UsulanHapusdetail_cont_opsi' style='position:relative'></div>"+
			"<div id='UsulanHapusdetail_cont_daftar' style='position:relative'></div>"+
			"<div id='UsulanHapusdetail_cont_hal' style='position:relative'></div>";
		//generate data
		UsulanHapusdetail.loading();
	},
	
	AfterFormEdit:function()
	{
		//alert("tes");
		this.AfterFormBaru()
	},
	/**
	AfterFormBA:function(){		
		//alert("tes");
		document.getElementById('div_detail').innerHTML = 
			"<input type='hidden' id='daftar_mode' name='daftar_mode' value='1' >"+
			"<div id='UsulanHapusdetail_cont_title' style='position:relative'></div>"+
			"<div id='UsulanHapusdetail_cont_opsi' style='position:relative'></div>"+
			"<div id='UsulanHapusdetail_cont_daftar' style='position:relative'></div>"+
			"<div id='UsulanHapusdetail_cont_hal' style='position:relative'></div>";
		//generate data
		UsulanHapusdetail.loading();
	},
	**/
	
	Baru: function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	/**
	BaruBA: function(){	
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEditBA',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormBA(resp);
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
	**/
	tterima: function(){	
		//alert('dudi')
	
		var me = this;
		errmsg = this.CekCheckbox();
			
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
						
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formTterima',
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

	pilihPejabatPengadaan: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	pilihKuasaBarang: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'id_kuasaP';
		PegawaiPilih.el_nip= 'nip_pejabat_kuasaP';
		PegawaiPilih.el_nama= 'nama_pejabat_kuasaP';
		PegawaiPilih.el_jabat= 'jbt_pejabat_kuasaP';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	petugastterima: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = "04"// old document.getElementById('c').value;
		PegawaiPilih.fmUNIT = "05"//old document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = "00"//old document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = "000";
		PegawaiPilih.el_idpegawai = 'id_kuasaT';
		PegawaiPilih.el_nip= 'nip_pejabat_kuasaT';
		PegawaiPilih.el_nama= 'nama_pejabat_kuasaT';
		PegawaiPilih.el_jabat= 'jbt_pejabat_kuasaT';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	/**
	simpanBA:function()
	{
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpanBA',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert("sukses")
					me.Close();
					me.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	
	},
	**/
	tes: function(){
			
	},
	SimpanTterima:function()
	{
		//alert('tes')
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpantterima';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=SimpanTterima',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert("Sukses")
					me.Close();
					me.Cetak_tterima();
					me.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	
	},
	
	Cari:function()
	{
		//alert("tes");
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();
		
		var idusul = document.getElementById('UsulanHapus_idplh').value;
		var sesiCari = document.getElementById('sesi').value;
		var c = document.getElementById('c').value;
		var d = document.getElementById('d').value;
		var e = document.getElementById('e').value;
		var e1 = document.getElementById('e1').value;
		
		var cover = this.prefix+'_formcovercari';
		addCoverPage2(cover,999,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formCari&sw='+sw+'&sh='+sh,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if (resp.err ==''){		
					document.getElementById(cover).innerHTML = resp.content;
					document.getElementById('div_detailcari').innerHTML = 
					"<input type='hidden' id='formcaribi' name='formcaribi' value='1'>"+
					"<input type='hidden' id='fmSKPD' name='fmSKPD' value='"+c+"'>"+
					"<input type='hidden' id='fmUNIT' name='fmUNIT' value='"+d+"'>"+
					"<input type='hidden' id='fmSUBUNIT' name='fmSUBUNIT' value='"+e+"'>"+
					"<input type='hidden' id='fmSEKSI' name='fmSEKSI' value='"+e1+"'>"+
					 
					"<input type='hidden' id='idusul' name='idusul' value='"+idusul+"'>"+
					"<input type='hidden' id='sesicari' name='sesicari' value='"+sesiCari+"'>"+					 
					"<input type='hidden' id='boxchecked' name='boxchecked' value='2'>"+
					"<input type='hidden' id='GetSPg' name='GetSPg' value='03'>"+
					"<div id='penatausaha_cont_opt'></div>"+
					"<div id='penatausaha_cont_list'></div>"+
					"<div id='penatausaha_cont_hal'><input type='hidden' value='1' id=HalDefault></div>"+
					"";
					//generate data
					Penatausaha.getDaftarOpsi();
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
		alert();
		
	},
	
	Closecari:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);	
		Penatausaha.resetPilih();		
		//document.body.style.overflow='auto';					
	},
	
	Pilih:function(){ //03 April
		var me = this;
		//document.getElementById(this.Prefix+'_idc').value=a
		
		errmsg = '';			
		
		//if((errmsg=='') && (adminForm.boxchecked.value == 0 )){
		if((errmsg=='') && (Penatausaha.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: 'pages.php?Pg=usulanhapusdetail'+'&tipe=simpanPilih',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						UsulanHapusdetail.refreshList(true);
						//document.body.style.overflow='hidden';
						Penatausaha.resetPilih();
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	Cetak_tterima:function(){	
	
		var me = this;
		errmsg = this.CekCheckbox();
			
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
		
		//document.getElementById('fr').style.display='block';
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=genCetak_tterima';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		//aForm.target='fr';
		aForm.submit();	
		aForm.target='';
		}else{
			alert(errmsg);
		}
	},
	
	Cetak_usulan:function(){	
	   	var me = this;
		errmsg = this.CekCheckbox();
			
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=genCetak_usulan';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		}else{
			alert(errmsg);
		}
	},
	
	
	Batalttr: function(){	
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
					
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=batalttr',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						
						alert("Sukses")
						me.Close();
						me.refreshList(true)
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
		if(document.getElementById('UsulanHapus_cont_daftar')){
			this.refreshList(false);
		}else if(document.getElementById('Sensus_cont_daftar')){
			//Sensus.refreshList(false);
		}
				
	},
	
	/**
	PilihPemeriksa:function()
	{
		//alert('tes');
		var me=this
		var cover = this.prefix+'_formcoverpanitia';
			addCoverPage2(cover,999,true,false);
		$.ajax({
				type:'POST', 
				data:$('#UsulanHapus_form').serialize(),
				url: this.url+'&tipe=formPilihPemeriksa',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err =='')
						{		
						 document.getElementById(cover).innerHTML = resp.content;	//alert("tes");
						document.getElementById('div_detailpanitia').innerHTML = 
					"<form name='PanitiaPemeriksafm' id='PanitiaPemeriksafm' method='post' action=''>"+
						"<input type='hidden' value='' id='"+me.prefix+"_idUsul' name='"+me.prefix+"_idUsul' >"+
						"<div id='PanitiaPemeriksa_cont_title' style='position:relative'></div>"+
						"<div id='PanitiaPemeriksa_cont_opsi' style='position:relative'></div>"+
						"<div id='PanitiaPemeriksa_cont_daftar' style='position:relative'></div>"+
						"<div id='PanitiaPemeriksa_cont_hal' style='position:relative'></div>"+
						"</form>";
						
						document.getElementById(me.prefix+'_idUsul').value= document.getElementById('UsulanHapus_idplh').value 
						//alert(document.getElementById('UsulanHapus_idplh').value)
						PanitiaPemeriksa.loading();
						
						}
						else
						{
						alert(resp.err);
						//delElem(cover);
						document.body.style.overflow='auto';
						}
			  	}
			});
		
	},
	**/
	/**
	Closepanitiafm:function(){//alert(this.elCover);
		$.ajax({
			type:'POST', 
			data:$('#PanitiaPemeriksafm').serialize(),				
			url: this.url+'&tipe=hitungPanitia',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					document.getElementById('hitung2').innerHTML=resp.content
					
				}else{
					alert(resp.err);
				}
		  	}
		 });
		
		var cover = this.prefix+'_formcoverpanitia';
		
		//if(document.getElementById(cover)) delElem(cover);			
	//	document.body.style.overflow='auto';	
						
	},
	**/
	
});
