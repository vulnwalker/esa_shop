var rphbmdSkpd = new SkpdCls({
	prefix : 'rphbmdSkpd', 
	formName: 'rphbmdForm',// 'adminForm'
	pilihBidangAfter:function(){
		//rphbmd.refreshList(true);
	},
	pilihUnitAfter:function(){
		//rphbmd.refreshList(true);
	},
	pilihSubUnitAfter:function(){
		//rphbmd.refreshList(true);
	},
	pilihSeksiAfter:function(){
		//rphbmd.refreshList(true);
	}
	
});

var rphbmd = new DaftarObj2({
	prefix : 'rphbmd',
	url : 'pages.php?Pg=rphbmd', 
	formName : 'rphbmdForm',
	
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
		var err='';
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();	
		var cover = this.prefix+'_formcover';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUBUNIT belum dipilih!';
		if(err==''){
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru&sw='+sw+'&sh='+sh,
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
	Edit1 : function(){
		var me = this;
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();	
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
				url: this.url+'&tipe=formEdit&sw='+sw+'&sh='+sh,
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
	hapus : function(){
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
		
	},
	Closecari:function(){//alert(this.elCover);
		Penatausaha.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},
	Pilih:function(){ //pilih cari
		var me = this;
		errmsg = '';		
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
				url: this.url +'&tipe=pilihcaribi',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						document.getElementById('kode_account').value = resp.content.plhkode_account;
						document.getElementById('nama_account').value = resp.content.plhnama_account;
						document.getElementById('tahun_account').value = resp.content.plhtahun_account;
						document.getElementById('fmIDBARANG').value = resp.content.plhIDBARANG;
						document.getElementById('fmNMBARANG').value = resp.content.plhNMBARANG;
						document.getElementById('fmid_buku_induk').value = resp.content.plhid_buku_induk;
						document.getElementById('fmidbi_awal').value = resp.content.plhidbi_awal;
						document.getElementById('fmnoreg').value = resp.content.plhnoreg;
						document.getElementById('fmthn_perolehan').value = resp.content.plhthn_perolehan;
						document.getElementById('fmharga_perolehan').value = resp.content.plhharga_perolehan;
						document.getElementById('valharga_perolehan').value = resp.content.plhvalharga_perolehan;
						document.getElementById('fmasalusul').value = resp.content.plhasalusul;
						document.getElementById('fmkondisi').value = resp.content.plhkondisi;
						document.getElementById('valasalusul').value = resp.content.plhvalasalusul;
						document.getElementById('valkondisi').value = resp.content.plhvalkondisi;
						document.getElementById('fmluasa').value = resp.content.plhluas;
						document.getElementById('fmluasc').value = resp.content.plhluas;
						document.getElementById('fmluasd').value = resp.content.plhluas;
						document.getElementById('fmluasf').value = resp.content.plhluas;
						document.getElementById('fmalamata').value = resp.content.plhalamat;
						document.getElementById('fmalamat_kela').value = resp.content.plhkel;
						document.getElementById('fmalamat_keca').value = resp.content.plhkec;
						document.getElementById('fmalamat_kotaa').value = resp.content.plhkota;
						document.getElementById('fmalamatc').value = resp.content.plhalamat;
						document.getElementById('fmalamat_kelc').value = resp.content.plhkel;
						document.getElementById('fmalamat_kecc').value = resp.content.plhkec;
						document.getElementById('fmalamat_kotac').value = resp.content.plhkota;
						document.getElementById('fmalamatd').value = resp.content.plhalamat;
						document.getElementById('fmalamat_keld').value = resp.content.plhkel;
						document.getElementById('fmalamat_kecd').value = resp.content.plhkec;
						document.getElementById('fmalamat_kotad').value = resp.content.plhkota;
						document.getElementById('fmalamatf').value = resp.content.plhalamat;
						document.getElementById('fmalamat_kelf').value = resp.content.plhkel;
						document.getElementById('fmalamat_kecf').value = resp.content.plhkec;
						document.getElementById('fmalamat_kotaf').value = resp.content.plhkota;
						document.getElementById('fmmerk').value = resp.content.plhmerk;
						document.getElementById('fmukuranb').value = resp.content.plhukuran;
						document.getElementById('fmbahanb').value = resp.content.plhbahan;						
						document.getElementById('fmukurane').value = resp.content.plhukuran;
						document.getElementById('fmbahane').value = resp.content.plhbahan;
						document.getElementById('fmkonstruksic').value = resp.content.plhkonstruksi;
						document.getElementById('fmkonstruksid').value = resp.content.plhkonstruksi;
						document.getElementById('fmkonstruksif').value = resp.content.plhkonstruksi;
						document.getElementById('fmtingkatc').value = resp.content.plhtingkat;
						document.getElementById('fmbetonc').value = resp.content.plhbeton;
						document.getElementById('fmtingkatf').value = resp.content.plhtingkat;
						document.getElementById('fmbetonf').value = resp.content.plhbeton;
						document.getElementById('fmpanjang').value = resp.content.plhpanjang;
						document.getElementById('fmlebar').value = resp.content.plhlebar;
						document.getElementById('fmjudul').value = resp.content.plhjudul;
						document.getElementById('fmspesifikasi').value = resp.content.plhspesifikasi;
						document.getElementById('fmasal').value = resp.content.plhasal;
						document.getElementById('fmpencipta').value = resp.content.plhpencipta;
						document.getElementById('fmjenis').value = resp.content.plhjenis;
						me.formSetDetailEntry();
						
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	formSetDetailEntry : function(){
		//set entry sesuai jenis barang 
		//alert('tes');
		var kdbrg = document.getElementsByName('fmIDBARANG')[0].value;
		var jnsbrg = kdbrg.substring(0,2);
		//if( jnsbrg != '' && jnsbrg.length == 2){
		document.getElementById('rphbmd_formkiba').style.display = 'none';
		document.getElementById('rphbmd_formkibb').style.display = 'none';
		document.getElementById('rphbmd_formkibc').style.display = 'none';
		document.getElementById('rphbmd_formkibd').style.display = 'none';
		document.getElementById('rphbmd_formkibe').style.display = 'none';
		document.getElementById('rphbmd_formkibf').style.display = 'none';
		switch(jnsbrg){
			case '01': {
				document.getElementById('rphbmd_formkiba').style.display = 'block';
				break;
			}
			case '02': {
				document.getElementById('rphbmd_formkibb').style.display = 'block';
				break;
			}
			case '03':  {
				document.getElementById('rphbmd_formkibc').style.display = 'block';
				break;
			}
			case '04':{
				document.getElementById('rphbmd_formkibd').style.display = 'block';
				break;
			}
			case '05': {
				document.getElementById('rphbmd_formkibe').style.display = 'block';
				break;
			}
			case '06': {
				document.getElementById('rphbmd_formkibf').style.display = 'block';
				break;
			}
		}	
		
	},
	caribarang1:function(){		
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
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
					document.getElementById('div_detailcaribi').innerHTML = 
					//"<input type='hidden' id='formcaribi' name='formcaribi' value='1'>"+
					"<input type='hidden' id='fmSKPD' name='fmSKPD' value='"+skpd+"'>"+
					"<input type='hidden' id='fmUNIT' name='fmUNIT' value='"+unit+"'>"+
					"<input type='hidden' id='fmSUBUNIT' name='fmSUBUNIT' value='"+subunit+"'>"+
					"<input type='hidden' id='fmSEKSI' name='fmSEKSI' value='"+seksi+"'>"+
					
					"<input type='hidden' id='formcaribi' name='formcaribi' value='rphbmd'>"+
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
	} ,
	CariJurnal: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kode_account';
		RefJurnal.el_nama_account = 'nama_account';
		RefJurnal.el_tahun_account = 'tahun_account';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.windowShow();	
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
