var UsulanHapusbaSkpd = new SkpdCls({
	prefix : 'UsulanHapusbaSkpd',
	 formName:'adminForm',
	 pilihBidangAfter:function(){
		UsulanHapusba.refreshList(true);
	},
	pilihUnitAfter:function(){
		UsulanHapusba.refreshList(true);
	},
	pilihSubUnitAfter:function(){
		UsulanHapusba.refreshList(true);
	},
	pilihSeksiAfter:function(){
		UsulanHapusba.refreshList(true);
	}
	
});

var UsulanHapusba = new DaftarObj2({
	prefix : 'UsulanHapusba',
	url : 'pages.php?Pg=usulanhapusba', 
	formName : 'adminForm',// 'ruang_form',
	
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
	AfterFormBA:function(){		
		//alert("tes");
		document.getElementById('div_detail').innerHTML = 
			"<input type='hidden' id='daftar_mode' name='daftar_mode' value='1' >"+
			"<div id='UsulanHapusbadetail_cont_title' style='position:relative'></div>"+
			"<div id='UsulanHapusbadetail_cont_opsi' style='position:relative'></div>"+
			"<div id='UsulanHapusbadetail_cont_daftar' style='position:relative'></div>"+
			"<div id='UsulanHapusbadetail_cont_hal' style='position:relative'></div>";
		//generate data
		UsulanHapusbadetail.loading();
	},
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
	
	simpanBA:function()	{
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpanBA',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);						
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
	
	PilihPemeriksa:function(){
		
	
		//alert('tes');
		var me=this
		var cover = this.prefix+'_formcoverpanitia';
			addCoverPage2(cover,999,true,false);
		$.ajax({
				type:'POST', 
				data:$('#UsulanHapusba_form').serialize(),
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
						
						document.getElementById(me.prefix+'_idUsul').value= document.getElementById('UsulanHapusba_idplh').value 
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
	
	BaruBA_: function(){	
		
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
	pilihUsulan: function(){
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
	
	cetakKKerja:function(){
		
		var me = this;
		errmsg = this.CekCheckbox();
			
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=gencetakKKerja';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		}else{
			alert(errmsg);
		}
	},
	
	
	cetakBA:function(){
		
		var me = this;
		errmsg = this.CekCheckbox();
			
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=gencetakBA';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		}else{
			alert(errmsg);
		}
	},
	
	
	Batal: function(){	
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
				url: this.url+'&tipe=batal',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						//document.getElementById(cover).innerHTML = resp.content;
						//me.AfterFormEdit(resp);
						alert("sukses")
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
						
						alert("sukses")
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
	}	
});
