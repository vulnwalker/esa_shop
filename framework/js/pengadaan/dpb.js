var dpbSkpd = new SkpdCls({
	prefix : 'dpbSkpd', formName:'adminForm'
});

var dpb = new DaftarObj2({
	prefix : 'dpb',	
	url : 'pages.php?Pg=dpb&ajx=1',
	formName : 'adminForm',
	
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
	pilihPembuatKomitmen: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idkomitmen';
		PegawaiPilih.el_nip= 'nip_pembuat_komitmen';
		PegawaiPilih.el_nama= 'nama_pembuat_komitmen';
		PegawaiPilih.el_jabat= 'jbt_pembuat_komitmen';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
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
	caribarang1: function(){
		var me = this;	
		
		
		RefBarang.el_IDBARANG = 'fmIDBARANG';
		RefBarang.el_NMBARANG = 'fmNMBARANG';
		RefBarang.el_kode_account = 'kode_account';
		RefBarang.el_nama_account = 'nama_account';
		RefBarang.el_tahun_account = 'tahun_account';		
		RefBarang.windowSaveAfter= function(){document.getElementById('ref_iddkb').value = ''; dpb.UnitRefresh();};
		RefBarang.windowShow();	
	},
	CariDKB: function(){
		var me = this;
			
		dkb.fmSKPD = document.getElementById('c').value;
		dkb.fmUNIT = document.getElementById('d').value;
		dkb.el_iddkb = 'ref_iddkb';
		dkb.el_kd_barang = 'fmIDBARANG';
		dkb.el_nm_barang = 'fmNMBARANG';
		dkb.el_e = 'fmSKPDUnit_form';
		dkb.el_e1 = 'fmSKPDSubUnit_form';
		dkb.el_kd_account = 'kode_account';
		dkb.el_nm_account = 'nama_account';
		
		//dkb.windowSaveAfter= function(resp){ alert(resp.content.e+' '+ resp.content.e1); };
		dkb.windowSaveAfter= function(resp){ dpb.UnitRefresh(); dpb.UnitAfter(); };
		dkb.windowShow();
			
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
	
	showRKB: function(){
		var me = this;
		var aForm = document.getElementById(this.formName);	
		aForm.action=this.url+'&tipe=formDKB';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
						
		//window.open(this.url+'&tipe=formDKB');
		/*$.post(this.url+'&tipe=formDKB', $("#"+this.formName).serialize(), function(result) {
			window.open(result,'_blank');
		});*/
		
	},
	
	setDKB: function(){
		//this.showRKB();
		var me = this;	
		var cover = this.prefix+'_formcover';
		if(document.getElementById(this.elJmlCek)){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		errmsg = this.CekCheckbox();
		if(errmsg ==''){
			var box = this.GetCbxChecked();
			//alert(box.stat);
			//alert(box.getAttribute("stat"));
			var stat = box.getAttribute("stat");
			if(stat==0){
				this.showRKB();
			}else{
				alert('DKB sudah ada!');
			}
			/*addCoverPage2(cover,1,true,false);
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				//cek sudah rkb -------------------
			  	url: this.url+'&tipe=getsat',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					delElem(cover);
					if(resp.content.stat=='1'){
						alert('DKB sudah ada!');						
					}else{
						me.showRKB();
					}					
			  	}
			});	*/
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
	
	SimpanDKB: function(){
		var err ='';
		if(err == ''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=simpanDKB',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						alert('Sukses Simpan Data');
						window.close();		
					}else{
						alert(resp.err);
					}					
			  	}
			});
			
		}else{
			alert(err);
		}
		
	},
	
	formNoDialog_show: function(){
		
	},
	
	hitungSisa: function(){
		var jmldkb = document.getElementById('jml_dkb').value;
		var jml = document.getElementById('jml_barang').value;
		var jmlada = document.getElementById('jml_ada').value;
		document.getElementById('jml_sisa').value = parseInt(jmldkb) - ( parseInt(jmlada) + parseInt(jml) );
		
	},
	/*
	cekJmlBrgBI: function(){
		//alert('tess');
		var err = '';
		var tahun = document.getElementById('fmTAHUN').value;
		var kdbarang = document.getElementsByName('fmIDBARANG').value;
		if (err=='' && tahun =='') err= "Tahun Anggaran belum diisi!";
		if (err=='' && kdbarang =='') err= "Kode Barang belum diisi!";
		
		if(err==''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=getJmlBrgBI',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						document.getElementById('jmlbi').value = resp.content.jmlbi;
						document.getElementById('standar').value = resp.content.jmlstandar;
						//alert('Sukses Simpan Data');
						//window.close();		
					}else{
						//alert(resp.err);
					}
					
			  	}
			});
		}else{
			alert(err);
		}
	}
	*/
});