var tgr_ketetapanSkpd = new SkpdCls({
	prefix : 'tgr_ketetapanSkpd', formName:'TGRKetetapanForm'
});

var tgr_ketetapan = new DaftarObj2({
	prefix : 'tgr_ketetapan',	
	url : 'pages.php?Pg=tgr_ketetapan',
	formName : 'TGRKetetapanForm',
	
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
	CariGantiRugi: function(){
		var me = this;
		var err = "";
		var subunit = document.getElementById('fmSKPDUnit_form').value;
		var seksi = document.getElementById('fmSKPDSubUnit_form').value;
		
		if(err=='' && (subunit=='' || subunit=='00') ) err='Dipergunakan untuk unit belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='000') ) err='Dipergunakan untuk sub unit belum dipilih!';	
		
		if(err==''){	
			TGR.fmSKPD = document.getElementById('c').value;
			TGR.fmUNIT = document.getElementById('d').value;
			TGR.fmSUBUNIT = document.getElementById('fmSKPDUnit_form').value;
			TGR.fmSEKSI = document.getElementById('fmSKPDSubUnit_form').value;
			TGR.el_idtgr = 'idtgr';
			TGR.el_kd_barang = 'fmIDBARANG2';
			TGR.el_nm_barang = 'fmNMBARANG2';
			TGR.el_kd_account = 'kode_account';
			TGR.el_nm_account = 'nama_account';
			TGR.el_thn_akun = 'tahun_account';
			TGR.windowSaveAfter= function(resp){
			me.Filldetailtgr(resp.content.idtgr);
			};
			TGR.windowShow();
		}else{
			alert(err);
		}	
	},
	
	formSetDetailJenisEntry : function(){
		
		var jnstgr = document.getElementById('cr_pengganti').value;
		document.getElementById('det_jenis_pengganti_uang').style.display = 'none';
		document.getElementById('det_jenis_pengganti_brg').style.display = 'none';
		switch(jnstgr){
			case '0': {
				document.getElementById('det_jenis_pengganti_uang').style.display = 'block';
				break;
			}
			case '1': {
				document.getElementById('det_jenis_pengganti_brg').style.display = 'block';
				document.getElementById('hrg_pengganti').value = '';
				break;
			}
			
		}	
		
		
	},
	
	caribarang1: function(){
		var me = this;	
				
		RefBarang.el_IDBARANG = 'fmIDBARANG';
		RefBarang.el_NMBARANG = 'fmNMBARANG';
		//RefBarang.el_kode_account = 'kode_account2';
		//RefBarang.el_nama_account = 'nama_account2';
		//RefBarang.el_tahun_account = 'tahun_account2';		
		RefBarang.windowSaveAfter= function(){ tgr_ketetapan.formSetDetailBarangBaru();};
		RefBarang.windowShow();	
	},
	
	formSetDetailBarangBaru : function(){
		var me = this;
		var IDBarang =  document.getElementById('fmIDBARANG').value;
		errmsg = '';
		
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#CariBarangBaru_Form').serialize(),
				url: this.url +'&tipe=PilihBarangBaru&IDBarang='+IDBarang,
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						document.getElementById('kode_account2').value = resp.content.plhkode_account2;
						document.getElementById('nama_account2').value = resp.content.plhnama_account2;
						document.getElementById('tahun_account2').value = resp.content.plhtahun_account2;
						document.getElementById('DetailKIB').innerHTML = resp.content.plhdetailKIB;	
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
				
	},
	
	Filldetailtgr:function(idtgr){ //pilih cari
		var me = this;
		err = '';		
		
		if(err ==''){	
			$.ajax({
				type:'POST', 
				data:$('#'+this.prefix+'_form').serialize(),
				url: this.url +'&tipe=Filldetailtgr&id_tgr='+idtgr,
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						
						document.getElementById('idbi').value = resp.content.plhidbi;
						document.getElementById('idbi_awal').value = resp.content.idbi_awal;
						document.getElementById('no_reg').value = resp.content.plhnoreg;
						document.getElementById('thn_perolehan').value = resp.content.plhthn_perolehan;
						document.getElementById('spek_alamat').value = resp.content.plhspesifikasi;
						document.getElementById('hrg_perolehan').value = resp.content.plhharga_perolehan;
						document.getElementById('hrg_buku').value = resp.content.plhharga_buku;						
						
					}else{
						alert(resp.err);
						me.CariRencana();
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	 
	Baru2: function(){	
		var me = this;
		var err='';
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();	
		var cover = this.prefix+'_formcover';var skpd = document.getElementById('fmSKPDBidang').value; 
		var unit = document.getElementById('fmSKPDskpd').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
				
		if(err==''){
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru2&sw='+sw+'&sh='+sh,
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
	
	Edit2: function(){	
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
				url: this.url+'&tipe=formEdit2&sw='+sw+'&sh='+sh,
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
	
	
	
	Simpan2: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan2',
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
	
	sertifikat_onchange:function(){
		var thiscbx = document.getElementById('bersertifikat');
		var tgl = document.getElementById('fmTGLSERTIFIKAT_KIB_A_tgl');
		var bln = document.getElementById('fmTGLSERTIFIKAT_KIB_A_bln');
		var thn = document.getElementById('fmTGLSERTIFIKAT_KIB_A_thn');
		var clear = document.getElementById('fmTGLSERTIFIKAT_KIB_A_btClear');
		var nosert = document.getElementById('fmNOSERTIFIKAT_KIB_A');
		
		//alert (thiscbx.value);			//var set = thiscbx.value==1? 'true' : 'false';
		if ( thiscbx.value!=1){
			//clear.onclick();
			tgl.setAttribute('disabled','true');
			bln.setAttribute('disabled','true');
			thn.setAttribute('disabled','true');
			clear.setAttribute('disabled','true');	
			nosert.setAttribute('disabled','true');	
		}else{
			tgl.removeAttribute('disabled');
			bln.removeAttribute('disabled');
			thn.removeAttribute('disabled');
			clear.removeAttribute('disabled');
			nosert.removeAttribute('disabled');	
		}
	},
	getNoRegAkhir : function(){
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=getNoRegAkhir',
		  	success: function(data) {
				var resp = eval('(' + data + ')');					
				document.getElementById('no_reg2').value = resp.content.noreg;
		  	}
		});	
	},
	
	formNoDialog_show: function(){
		
	},
	
	
});