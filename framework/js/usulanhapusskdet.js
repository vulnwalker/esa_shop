var UsulanHapusskdetSkpd = new SkpdCls({
	prefix : 'UsulanHapusskdetSkpd', formName:'adminForm'
});

var UsulanHapusskdet = new DaftarObj2({
	prefix : 'UsulanHapusskdet',
	url : 'pages.php?Pg=usulanhapusskdet', 
	formName : 'UsulanHapussk_form',// 'ruang_form',
	
	detail: function(){
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
	
	gotoHalaman:function(nothing, Hal){
		document.getElementById(this.prefix+'_hal').value = Hal;
		this.loading();
	},
	
	BaruBA: function(){	
		
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
			addCoverPage2(cover,1,true,false);	
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
	}	
});
