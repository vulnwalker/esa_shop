var Penilaian_koreksiSkpd = new SkpdCls({
	prefix : 'Penilaian_koreksiSkpd', formName:'adminForm'
});

var Penilaian_koreksi = new DaftarObj2({
	prefix : 'Penilaian_koreksi',	
	url : 'pages.php?Pg=Penilaian_koreksi',
	formName : 'Penilaian_koreksiForm',
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
	UnitAfter: function(){
		var me = this;
		//document.getElementById('btTampil').disabled = true;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=UnitAfter',
		  type : 'POST',
		  data:$('#'+this.prefix+'_form').serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
			//document.getElementById('btTampil').disabled = false;	
			document.getElementById('SubUnit_formdiv').innerHTML = resp.content;	
		  }
		});
	},
	UnitRefresh: function(){
		var me = this;
		//document.getElementById('btTampil').disabled = true;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=UnitRefresh',
		  type : 'POST',
		  data:$('#'+this.prefix+'_form').serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
			//document.getElementById('btTampil').disabled = false;	
			document.getElementById('Unit_formdiv').innerHTML = resp.content;	
		  }
		});
	},
	cariBI: function(){
		var me = this;	
		var err = '';	
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		var skpd = document.getElementById('c').value; 
		var unit = document.getElementById('d').value;
		var subunit = document.getElementById('fmSKPDUnit_form').value;
		var seksi = document.getElementById('fmSKPDSubUnit_form').value;
		
		if(err=='' && (subunit=='' || subunit=='00') ) err='Dipergunakan untuk unit belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='000') ) err='Dipergunakan untuk sub unit belum dipilih!';
		
		if(err==''){		
			var cover = this.prefix+'_formcovercari';
			addCoverPage2(cover,1,true,false);
			$.ajax({
				type:'POST', 
				data:$('#'+this.prefix+'_form').serialize(),
				url: this.url+'&tipe=formCariBI&sw='+sw+'&sh='+sh,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						document.getElementById('div_detailcaribi').innerHTML = 
						"<input type='hidden' id='formcaribi' name='formcaribi' value='penilaian'>"+
						"<input type='hidden' id='fmSKPD' name='fmSKPD' value='"+skpd+"'>"+
						"<input type='hidden' id='fmUNIT' name='fmUNIT' value='"+unit+"'>"+
						"<input type='hidden' id='fmSUBUNIT' name='fmSUBUNIT' value='"+subunit+"'>"+
						"<input type='hidden' id='fmSEKSI' name='fmSEKSI' value='"+seksi+"'>"+
						
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
		}else{
			alert(err);
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
		var tgl_buku = document.getElementById('tgl_buku').value;			
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
				url: this.url +'&tipe=pilihcaribi&tgl_buku='+tgl_buku,
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						document.getElementById('fmIDBARANG').value = resp.content.plhIDBARANG;
						document.getElementById('fmNMBARANG').value = resp.content.plhNMBARANG;
						document.getElementById('ref_idbi').value = resp.content.plhid_buku_induk;
						document.getElementById('ref_idbi_awal').value = resp.content.plhidbi_awal;
						document.getElementById('fmnoreg').value = resp.content.plhnoreg;
						document.getElementById('fmtahunperolehan').value = resp.content.plhtahun;
						document.getElementById('staset').value = resp.content.plhstaset;
						document.getElementById('kode_account').value = resp.content.plhkode_akun;
						document.getElementById('nama_account').value = resp.content.plhnama_akun;
						document.getElementById('tahun_account').value = resp.content.plhthn_akun;
						document.getElementById('fmspesifikasi').value = resp.content.plhspesifikasi;
						document.getElementById('fmharga_perolehan').value = resp.content.plhharga_perolehan;
						document.getElementById('fmharga_buku').value = resp.content.plhharga_buku;
						
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	Baru: function(){	
		var me = this;
		var err='';
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();	
		var cover = this.prefix+'_formcover';var skpd = document.getElementById('fmSKPDBidang').value; 
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
	
	Edit: function(){	
		var me = this;
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();
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
	
	cekkoreksi:function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formkoreksi';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=cekkoreksi',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
					
				if (resp.err ==''){
						document.getElementById('fmharga_koreksi').value = resp.content.plhharga_koreksi;
						delElem(cover);	
				}else{
						alert(resp.err);
				}
						
				
		  	}
		});
	}	,
	
	Simpan: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);
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
		
	},	
	
	
});